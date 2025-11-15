<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Payment extends Controller {

    private $paypal;

    public function __construct() {
        parent::__construct();
        // Load all necessary models
        $this->call->model('Room_type_model');
        $this->call->model('Room_model');
        $this->call->model('Guest_model');
        $this->call->model('Booking_model');
        $this->call->model('Tour_model');
        $this->call->model('Tour_booking_model');
        $this->call->model('Invoice_model');
        $this->call->model('Invoice_item_model');

        try {
            // Load and initialize our PayPal service
            $this->call->library('PayPalService');
            $this->paypal = new PayPalService();
            // Load and initialize our PayMongo service
            $this->call->library('PaymongoService');
            $this->paymongo = new PaymongoService();
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Payment Gateway Error: ' . $e->getMessage());
            redirect('/booking/confirm');
        }
    }

    /**
     * Get PayPal OAuth2 access token
     * @return string access token
     * @throws Exception on failure
     */
    private function paypal_get_access_token() {
        $cfg = config_item('payment');
        if (!isset($cfg['paypal'])) {
            throw new Exception('PayPal configuration missing');
        }
        $paypal = $cfg['paypal'];
        $mode = isset($paypal['mode']) && $paypal['mode'] === 'live' ? 'live' : 'sandbox';
        $url = $mode === 'live' ? 'https://api.paypal.com/v1/oauth2/token' : 'https://api.sandbox.paypal.com/v1/oauth2/token';

        if (empty($paypal['client_id']) || empty($paypal['secret'])) {
            throw new Exception('PayPal credentials are not configured');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, $paypal['client_id'] . ':' . $paypal['secret']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Accept-Language: en_US']);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new Exception('PayPal token request failed: ' . $err);
        }
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode !== 200) {
            throw new Exception('PayPal token request returned HTTP ' . $httpcode . ': ' . $result);
        }

        $json = json_decode($result, true);
        if (!isset($json['access_token'])) {
            throw new Exception('Invalid PayPal token response: ' . $result);
        }
        return $json['access_token'];
    }

    /**
     * Retrieve PayPal order details
     */
    private function paypal_get_order($accessToken, $orderId) {
        $cfg = config_item('payment');
        $paypal = isset($cfg['paypal']) ? $cfg['paypal'] : null;
        $mode = isset($paypal['mode']) && $paypal['mode'] === 'live' ? 'live' : 'sandbox';
        $url = $mode === 'live' ? "https://api.paypal.com/v2/checkout/orders/{$orderId}" : "https://api.sandbox.paypal.com/v2/checkout/orders/{$orderId}";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ]);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new Exception('PayPal order request failed: ' . $err);
        }
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode !== 200) {
            throw new Exception('PayPal order request returned HTTP ' . $httpcode . ': ' . $result);
        }

        return json_decode($result, true);
    }

    /**
     * Step 1 (Room): Create a PayMongo "Source"
     */
    public function create_room_source() {
        $booking = $this->session->userdata('pending_booking');
        if (!$booking) {
            redirect('/rooms');
        }

        // Get payment type (full or downpayment) and amount
        $payment_type = $this->io->post('payment_type') ?: 'full';
        $amount_to_pay = $this->io->post('amount_to_pay');
        
        // Calculate payment amount based on type
        if (!$amount_to_pay) {
            $amount_to_pay = $payment_type === 'downpayment' 
                ? $booking['booking_data']['total_price'] * 0.5 
                : $booking['booking_data']['total_price'];
        }
        
        // Store payment info in session
        $this->session->set_userdata('payment_type', $payment_type);
        $this->session->set_userdata('amount_to_pay', $amount_to_pay);

        // Check if this is a PayPal payment
        $payment_method = $this->io->post('payment_method') ?: 'gcash'; // Default to gcash if not set
        if ($payment_method === 'paypal') {
            $paypalOrderId = $this->io->post('paypal_order_id');
            if (!$paypalOrderId) {
                $this->session->set_flashdata('error', 'PayPal order ID missing.');
                redirect('/booking/confirm');
                return;
            }

            try {
                $accessToken = $this->paypal_get_access_token();
                $order = $this->paypal_get_order($accessToken, $paypalOrderId);

                // Validate order status
                if (!isset($order['status']) || strtoupper($order['status']) !== 'COMPLETED') {
                    throw new Exception('PayPal order not completed. Status: ' . ($order['status'] ?? 'unknown'));
                }

                // Verify amount
                $purchase_unit = $order['purchase_units'][0] ?? null;
                if (!$purchase_unit || !isset($purchase_unit['amount']['value'])) {
                    throw new Exception('Invalid PayPal order amount data');
                }

                $orderAmount = $purchase_unit['amount']['value'];
                $orderCurrency = $purchase_unit['amount']['currency_code'];
                $expectedUsd = number_format(($amount_to_pay / 55), 2, '.', '');

                if ($orderCurrency !== 'USD' || (string)$orderAmount !== (string)$expectedUsd) {
                    throw new Exception('PayPal amount mismatch. Expected ' . $expectedUsd . ' USD, got ' . $orderAmount . ' ' . $orderCurrency);
                }

                // Payment verified — finalize booking
                return $this->finalize_room_booking($booking, $payment_type, $amount_to_pay);
            } catch (Exception $e) {
                $this->session->set_flashdata('error', 'PayPal verification failed: ' . $e->getMessage());
                redirect('/booking/confirm');
                return;
            }
        }

        // Get payment configuration
        $payment_config = config_item('payment');
        if (!$payment_config || $payment_config['gateway'] !== 'paymongo') {
            $this->session->set_flashdata('error', 'Payment gateway is not properly configured');
            redirect('/booking/confirm');
        }

        $amount = (int) ($amount_to_pay * 100);

        try {
            $source = $this->paymongo->createSource([
                'type' => 'gcash',
                'amount' => $amount,
                'currency' => 'PHP',
                'redirect' => [
                    'success' => site_url('payment/success/room') . '?source_id={id}',
                    'failed' => site_url('payment/cancel/room') . '?source_id={id}'
                ],
                'billing' => [
                    'name' => $booking['guest_data']['first_name'] . ' ' . $booking['guest_data']['last_name'],
                    'email' => $booking['guest_data']['email'],
                    'phone' => $booking['guest_data']['phone_number']
                ]
            ]);

            $this->session->set_userdata('paymongo_source_id', $source['id']);
            redirect($source['attributes']['redirect']['checkout_url']);

        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Payment gateway error: ' . $e->getMessage());
            redirect('/booking/confirm');
        }
    }

    /**
     * Step 1 (Tour): Create a PayMongo "Source"
     */
    public function create_tour_source() {
        $tour_booking = $this->session->userdata('pending_tour_booking');
        if (!$tour_booking) {
            redirect('/tours');
        }

        // Get payment type (full or downpayment) and amount
        $payment_type = $this->io->get('payment_type') ?: 'full';
        $amount_to_pay = $this->io->get('amount_to_pay');
        
        // Calculate payment amount based on type
        if (!$amount_to_pay) {
            $amount_to_pay = $payment_type === 'downpayment' 
                ? $tour_booking['booking_data']['total_price'] * 0.5 
                : $tour_booking['booking_data']['total_price'];
        }
        
        // Store payment info in session
        $this->session->set_userdata('payment_type', $payment_type);
        $this->session->set_userdata('amount_to_pay', $amount_to_pay);

        // Check if this is a PayPal payment
        // Note: The tour booking form uses GET method, so we read from GET parameters
        $payment_method = $this->io->get('payment_method') ?: 'gcash'; // Default to gcash if not set
        if ($payment_method === 'paypal') {
            $paypalOrderId = $this->io->get('paypal_order_id');
            if (!$paypalOrderId) {
                $this->session->set_flashdata('error', 'PayPal order ID missing.');
                redirect('/booking/confirm-tour');
                return;
            }

            try {
                $accessToken = $this->paypal_get_access_token();
                $order = $this->paypal_get_order($accessToken, $paypalOrderId);

                // Validate order status
                if (!isset($order['status']) || strtoupper($order['status']) !== 'COMPLETED') {
                    throw new Exception('PayPal order not completed. Status: ' . ($order['status'] ?? 'unknown'));
                }

                // Verify amount
                $purchase_unit = $order['purchase_units'][0] ?? null;
                if (!$purchase_unit || !isset($purchase_unit['amount']['value'])) {
                    throw new Exception('Invalid PayPal order amount data');
                }

                $orderAmount = $purchase_unit['amount']['value'];
                $orderCurrency = $purchase_unit['amount']['currency_code'];
                $expectedUsd = number_format(($amount_to_pay / 55), 2, '.', '');

                if ($orderCurrency !== 'USD' || (string)$orderAmount !== (string)$expectedUsd) {
                    throw new Exception('PayPal amount mismatch. Expected ' . $expectedUsd . ' USD, got ' . $orderAmount . ' ' . $orderCurrency);
                }

                // Payment verified — finalize tour booking
                return $this->finalize_tour_booking($tour_booking, $payment_type, $amount_to_pay);
            } catch (Exception $e) {
                $this->session->set_flashdata('error', 'PayPal verification failed: ' . $e->getMessage());
                redirect('/booking/confirm-tour');
                return;
            }
        }

        // Get payment configuration
        $payment_config = config_item('payment');
        if (!$payment_config || $payment_config['gateway'] !== 'paymongo') {
            $this->session->set_flashdata('error', 'Payment gateway is not properly configured');
            redirect('/booking/confirm-tour');
        }

        $amount = (int) ($amount_to_pay * 100);

        try {
            $source = $this->paymongo->createSource([
                'type' => 'gcash',
                'amount' => $amount,
                'currency' => 'PHP',
                'redirect' => [
                    'success' => site_url('payment/success/tour') . '?source_id={id}',
                    'failed' => site_url('payment/cancel/tour') . '?source_id={id}'
                ],
                'billing' => [
                    'name' => $tour_booking['guest_data']['first_name'] . ' ' . $tour_booking['guest_data']['last_name'],
                    'email' => $tour_booking['guest_data']['email'],
                    'phone' => $tour_booking['guest_data']['phone_number']
                ]
            ]);

            $this->session->set_userdata('paymongo_source_id', $source['id']);
            redirect($source['attributes']['redirect']['checkout_url']);

        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Payment gateway error: ' . $e->getMessage());
            redirect('/booking/confirm-tour');
        }
    }

    /**
     * Step 2 (Room): Handle the "success" redirect from PayMongo
     */
    public function handle_room_payment_success() {
        $booking = $this->session->userdata('pending_booking');

        // Try multiple ways to get the source ID
        $source_id = null;

        // 1. Try from URL query parameters
        if (isset($_GET['source_id'])) {
            $source_id = $_GET['source_id'];
            error_log("PayMongo - Found source_id in GET parameters: " . $source_id);
        }

        // 2. Try from PayMongo's callback data
        if (!$source_id && isset($_GET['source'])) {
            $source_id = $_GET['source'];
            error_log("PayMongo - Found source in GET parameters: " . $source_id);
        }

        // 3. Try from session as fallback
        if (!$source_id) {
            $source_id = $this->session->userdata('paymongo_source_id');
            error_log("PayMongo - Found source_id in session: " . $source_id);
        }

        // Handle literal placeholder if PayMongo failed to substitute
        if ($source_id === '{id}' || $source_id === '%7Bid%7D' || strpos($source_id, '{id}') !== false) {
            error_log("PayMongo Warning - Room callback contained placeholder instead of actual source ID: " . $source_id);
            $fallback = $this->session->userdata('paymongo_source_id');
            if ($fallback) {
                error_log("PayMongo - Falling back to session-stored source_id for room: " . $fallback);
                $source_id = $fallback;
            } else {
                $source_id = null;
            }
        }

        if (!$booking || !$source_id) {
            redirect('/booking/confirm');
        }

        try {
            $source = $this->paymongo->retrieveSource($source_id);

            if ($source['attributes']['status'] === 'chargeable') {
                // Ensure we use the exact chargeable amount (PayMongo expects the same amount as the source)
                $chargeAmount = (int) $source['attributes']['amount'];
                error_log("PayMongo - Using charge amount for room payment: " . $chargeAmount);

                $payment = $this->paymongo->createPayment([
                    'source' => [
                        'id' => $source['id'],
                        'type' => 'source'
                    ],
                    'amount' => $chargeAmount,
                    'currency' => 'PHP',
                    'description' => 'Visit Mindoro Room Booking'
                ]);

                // NOW we finalize the booking
                $payment_type = $this->session->userdata('payment_type') ?: 'full';
                $amount_to_pay = $this->session->userdata('amount_to_pay') ?: $booking['booking_data']['total_price'];
                return $this->finalize_room_booking($booking, $payment_type, $amount_to_pay);
            } else {
                $this->session->set_flashdata('error', 'Payment verification failed. Please try again.');
                redirect('/booking/confirm');
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Payment processing error: ' . $e->getMessage());
            redirect('/booking/confirm');
        }
    }

    /**
     * Step 3 (Room): Save to DB and send email (Moved from Home.php)
     */
    private function finalize_room_booking($booking, $payment_type = 'full', $amount_paid = null) {
        $selected_room_id = $booking['booking_data']['room_id'];
        $check_in = $booking['booking_data']['check_in_date'];
        $check_out = $booking['booking_data']['check_out_date'];
        
        // Default amount paid to full price if not provided
        if ($amount_paid === null) {
            $amount_paid = $booking['booking_data']['total_price'];
        }
        
        // --- FINAL CONFLICT CHECK (Prevents race conditions) ---
        if ($this->Booking_model->has_conflict($selected_room_id, $check_in, $check_out)) {
            $this->session->unset_userdata('pending_booking');
            $this->session->unset_userdata('paymongo_source_id');
            $this->session->unset_userdata('paypal_order_id');
            $this->session->unset_userdata('payment_type');
            $this->session->unset_userdata('amount_to_pay');
            $this->session->set_flashdata('error', 
                'Sorry, this room was just booked by another guest while you were completing payment. Your payment was successful and will be refunded. Please select another room or different dates.');
            redirect('/rooms');
            return;
        }
        // --- END FINAL CONFLICT CHECK ---
        
        $room_to_book = $this->Room_model->find($selected_room_id);

        if($room_to_book && $room_to_book['status'] === 'available') {
            $guest_data = $booking['guest_data'];
            $guest = $this->Guest_model->find_by_email($guest_data['email']);
            $guest_id = $guest ? $guest['id'] : $this->session->userdata('user_id');
            
            // Determine payment status based on payment type
            $payment_status = $payment_type === 'downpayment' ? 'partial' : 'paid';
            $balance_due = $booking['booking_data']['total_price'] - $amount_paid;

            $final_booking_data = [
                'guest_id' => $guest_id,
                'room_id' => $selected_room_id,
                'check_in_date' => $check_in,
                'check_out_date' => $check_out,
                'total_price' => $booking['booking_data']['total_price'],
                'amount_paid' => $amount_paid,
                'balance_due' => $balance_due,
                'payment_status' => $payment_status,
                'status' => 'confirmed' // Set to CONFIRMED
            ];

            $booking_id = $this->Booking_model->insert($final_booking_data);

            if ($booking_id) {
                $this->Room_model->update($selected_room_id, ['status' => 'occupied']);

                // --- Create Invoice ---
                $this->create_room_invoice($booking_id, $booking, $room_to_book, $final_booking_data);

                // --- Send Confirmation Email ---
                $subject = "Your Visit Mindoro Room Booking is Confirmed!";
                $title = "Room Booking Confirmed!";
                $guest_name = $guest_data['first_name'];
                
                if ($payment_type === 'downpayment') {
                    $intro_message = "Thank you! Your downpayment has been processed and your booking is confirmed. Here are your details:";
                } else {
                    $intro_message = "Thank you! Your payment has been processed and your booking is confirmed. Here are your details:";
                }
                
                $details = [
                    'Room Type' => $booking['room_type_data']['name'],
                    'Room Number' => $room_to_book['room_number'],
                    'Check-in' => date('F j, Y', strtotime($final_booking_data['check_in_date'])),
                    'Check-out' => date('F j, Y', strtotime($final_booking_data['check_out_date'])),
                    'Total Price' => 'PHP ' . number_format($final_booking_data['total_price'], 2),
                    'Amount Paid' => 'PHP ' . number_format($amount_paid, 2)
                ];
                
                if ($payment_type === 'downpayment') {
                    $details['Balance Due'] = 'PHP ' . number_format($balance_due, 2) . ' (Pay on arrival)';
                }
                
                $call_to_action = "We look forward to seeing you!";

                $message = generate_email_template($title, $guest_name, $intro_message, $details, $call_to_action);
                $email_sent = send_booking_confirmation($guest_data['email'], $subject, $message);
                error_log("Room booking email sent status: " . ($email_sent ? 'success' : 'failed') . " to " . $guest_data['email']);
                // --- End Email ---

                $this->session->unset_userdata('pending_booking');
                $this->session->unset_userdata('paypal_order_id');
                $this->session->unset_userdata('payment_type');
                $this->session->unset_userdata('amount_to_pay');

                $this->call->view('public/payment_success');
            }
        } else {
            // This is rare, but means the room was taken while they were paying
            $this->session->unset_userdata('pending_booking');
            $this->session->unset_userdata('paymongo_source_id');
            $this->session->set_flashdata('error', 'That room was just booked by someone else. Your payment was not processed. Please select another room.');
            redirect('/book/room/' . $booking['booking_data']['room_type_id']);
        }
    }


    /**
     * Step 2 (Tour): Handle the "success" redirect from PayMongo
     */
    public function handle_tour_payment_success() {
        $tour_booking = $this->session->userdata('pending_tour_booking');

        // Try multiple ways to get the source ID
        $source_id = null;

        // 1. Try from URL query parameters
        if (isset($_GET['source_id'])) {
            $source_id = $_GET['source_id'];
            error_log("PayMongo - Found source_id in GET parameters: " . $source_id);
        }

        // 2. Try from PayMongo's callback data
        if (!$source_id && isset($_GET['source'])) {
            $source_id = $_GET['source'];
            error_log("PayMongo - Found source in GET parameters: " . $source_id);
        }

        // 3. Try from session as fallback
        if (!$source_id) {
            $source_id = $this->session->userdata('paymongo_source_id');
            error_log("PayMongo - Found source_id in session: " . $source_id);
        }

        // Handle literal placeholder if PayMongo failed to substitute
        if ($source_id === '{id}' || $source_id === '%7Bid%7D' || strpos($source_id, '{id}') !== false) {
            error_log("PayMongo Warning - Tour callback contained placeholder instead of actual source ID: " . $source_id);
            $fallback = $this->session->userdata('paymongo_source_id');
            if ($fallback) {
                error_log("PayMongo - Falling back to session-stored source_id for tour: " . $fallback);
                $source_id = $fallback;
            } else {
                $source_id = null;
            }
        }

        if (!$tour_booking || !$source_id) {
            redirect('/tours');
        }

        try {
            $source = $this->paymongo->retrieveSource($source_id);

            if ($source['attributes']['status'] === 'chargeable') {
                // Ensure we use the exact chargeable amount (PayMongo expects the same amount as the source)
                $chargeAmount = (int) $source['attributes']['amount'];
                error_log("PayMongo - Using charge amount for tour payment: " . $chargeAmount);

                $payment = $this->paymongo->createPayment([
                    'source' => [
                        'id' => $source['id'],
                        'type' => 'source'
                    ],
                    'amount' => $chargeAmount,
                    'currency' => 'PHP',
                    'description' => 'Visit Mindoro Tour Booking'
                ]);

                // NOW we finalize the booking
                $payment_type = $this->session->userdata('payment_type') ?: 'full';
                $amount_to_pay = $this->session->userdata('amount_to_pay') ?: $tour_booking['booking_data']['total_price'];
                return $this->finalize_tour_booking($tour_booking, $payment_type, $amount_to_pay);
            } else {
                $this->session->set_flashdata('error', 'Payment verification failed. Please try again.');
                redirect('/booking/confirm-tour');
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Payment processing error: ' . $e->getMessage());
            redirect('/booking/confirm-tour');
        }
    }

    /**
     * Step 3 (Tour): Save to DB and send email (Moved from Home.php)
     */
    private function finalize_tour_booking($tour_booking, $payment_type = 'full', $amount_paid = null) {
        $tour_id = $tour_booking['booking_data']['tour_id'];
        $booking_date = $tour_booking['booking_data']['booking_date'];
        $num_pax = $tour_booking['booking_data']['number_of_pax'];
        
        // Default amount paid to full price if not provided
        if ($amount_paid === null) {
            $amount_paid = $tour_booking['booking_data']['total_price'];
        }
        
        // --- FINAL CAPACITY CHECK (Prevents race conditions) ---
        $availability = $this->Tour_booking_model->check_availability($tour_id, $booking_date, $num_pax);
        
        if ($availability['has_conflict']) {
            $this->session->unset_userdata('pending_tour_booking');
            $this->session->unset_userdata('paymongo_source_id');
            $this->session->unset_userdata('paypal_order_id');
            $this->session->unset_userdata('payment_type');
            $this->session->unset_userdata('amount_to_pay');
            
            $error_msg = 'Sorry, this tour was just fully booked by other guests while you were completing payment. Your payment was successful and will be refunded.';
            if (isset($availability['available_slots']) && $availability['available_slots'] > 0) {
                $error_msg .= ' Only ' . $availability['available_slots'] . ' slot(s) now available.';
            }
            
            $this->session->set_flashdata('error', $error_msg);
            redirect('/tours');
            return;
        }
        // --- END FINAL CAPACITY CHECK ---
        
        $guest_data = $tour_booking['guest_data'];
        $guest = $this->Guest_model->find_by_email($guest_data['email']);
        $guest_id = $guest ? $guest['id'] : $this->session->userdata('user_id');
        
        // Determine payment status based on payment type
        $payment_status = $payment_type === 'downpayment' ? 'partial' : 'paid';
        $balance_due = $tour_booking['booking_data']['total_price'] - $amount_paid;

        $final_tour_booking_data = [
            'guest_id' => $guest_id,
            'tour_id' => $tour_id,
            'booking_date' => $booking_date,
            'number_of_pax' => $num_pax,
            'total_price' => $tour_booking['booking_data']['total_price'],
            'amount_paid' => $amount_paid,
            'balance_due' => $balance_due,
            'payment_status' => $payment_status,
            'status' => 'confirmed' // Set to CONFIRMED
        ];

        $tour_booking_id = $this->db->table('tour_bookings')->insert($final_tour_booking_data);

        if ($tour_booking_id) {
            // --- Create Invoice ---
            $this->create_tour_invoice($tour_booking_id, $tour_booking, $final_tour_booking_data);

            // --- Send Confirmation Email ---
            $subject = "Your Visit Mindoro Tour Booking is Confirmed!";
            $title = "Tour Booking Confirmed!";
            $guest_name = $guest_data['first_name'];
            
            if ($payment_type === 'downpayment') {
                $intro_message = "Thank you! Your downpayment has been processed and your tour booking is confirmed. Here are your details:";
            } else {
                $intro_message = "Thank you! Your payment has been processed and your tour booking is confirmed. Here are your details:";
            }
            
            $details = [
                'Tour Name' => $tour_booking['tour_data']['name'],
                'Tour Date' => date('F j, Y', strtotime($final_tour_booking_data['booking_date'])),
                'Number of Guests' => $final_tour_booking_data['number_of_pax'],
                'Total Price' => 'PHP ' . number_format($final_tour_booking_data['total_price'], 2),
                'Amount Paid' => 'PHP ' . number_format($amount_paid, 2)
            ];
            
            if ($payment_type === 'downpayment') {
                $details['Balance Due'] = 'PHP ' . number_format($balance_due, 2) . ' (Pay before tour)';
            }
            
            $call_to_action = "We look forward to seeing you!";

            $message = generate_email_template($title, $guest_name, $intro_message, $details, $call_to_action);
            $email_sent = send_booking_confirmation($guest_data['email'], $subject, $message);
            error_log("Tour booking email sent status: " . ($email_sent ? 'success' : 'failed') . " to " . $guest_data['email']);
            // --- End Email ---
            
            $this->session->unset_userdata('pending_tour_booking');
            $this->session->unset_userdata('paymongo_source_id');
            $this->session->unset_userdata('payment_type');
            $this->session->unset_userdata('amount_to_pay');
            $this->session->unset_userdata('paymongo_source_id');
            $this->call->view('public/payment_success');
        }
    }


    /**
     * This is where the user lands if they cancel the payment
     */
    public function cancel($type = 'room') {
        // Clear the pending data
        if ($type == 'room') {
            $this->session->unset_userdata('pending_booking');
            $this->session->unset_userdata('paypal_order_id');
        } else {
            $this->session->unset_userdata('pending_tour_booking');
            $this->session->unset_userdata('paymongo_source_id');
        }

        // Pass the type to the view so it can link back correctly
        $data['type'] = $type;
        $this->call->view('public/payment_cancel', $data);
    }

    /**
     * API endpoint to create a GCash payment source
     */
    public function api_create_gcash_source() {
        // Get the JSON body
        $json = json_decode(file_get_contents('php://input'), true);

        try {
            if (!$json || !isset($json['amount']) || !isset($json['booking_id']) || !isset($json['type'])) {
                throw new Exception('Invalid request data');
            }

            // Get user data for billing
            $userData = $this->session->userdata();
            if (!isset($userData['user_id'])) {
                throw new Exception('User not authenticated');
            }

            $user = $this->Guest_model->find($userData['user_id']);
            if (!$user) {
                throw new Exception('User not found');
            }

            // Prepare the source data
            $sourceData = [
                'type' => 'gcash',
                'amount' => intval($json['amount'] * 100), // Convert to cents
                'currency' => 'PHP',
                'redirect' => [
                    'success' => site_url("payment/success/{$json['type']}/{$json['booking_id']}"),
                    'failed' => site_url("payment/failed/{$json['type']}/{$json['booking_id']}")
                ],
                'billing' => [
                    'name' => $user['first_name'] . ' ' . $user['last_name'],
                    'email' => $user['email'],
                    'phone' => $user['phone_number']
                ]
            ];

            // Create the payment source
            $source = $this->paymongo->createSource($sourceData);

            // Return the source details
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => true,
                    'source_id' => $source['id'],
                    'checkout_url' => $source['attributes']['redirect']['checkout_url']
                ]));

        } catch (Exception $e) {
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]));
        }
    }

    /**
     * API endpoint to check GCash payment source status
     */
    public function api_check_gcash_status($sourceId) {
        try {
            $source = $this->paymongo->retrieveSource($sourceId);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => true,
                    'status' => $source['attributes']['status']
                ]));

        } catch (Exception $e) {
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]));
        }
    }

    /**
     * Create an invoice for a room booking
     */
    private function create_room_invoice($booking_id, $booking, $room, $final_booking_data) {
        // Create invoice record
        $invoice_data = [
            'booking_id' => $booking_id,
            'issue_date' => date('Y-m-d'),
            'due_date' => $final_booking_data['check_in_date'], // Due on check-in
            'total_amount' => $final_booking_data['total_price'],
            'status' => $final_booking_data['payment_status'] === 'paid' ? 'paid' : 'unpaid'
        ];
        
        $invoice_id = $this->Invoice_model->insert($invoice_data);
        
        if ($invoice_id) {
            // Calculate duration
            $checkin_date = new DateTime($final_booking_data['check_in_date']);
            $checkout_date = new DateTime($final_booking_data['check_out_date']);
            $nights = $checkout_date->diff($checkin_date)->format("%a");
            
            // Add invoice item for the room booking
            $item_data = [
                'invoice_id' => $invoice_id,
                'description' => $booking['room_type_data']['name'] . ' (RM ' . $room['room_number'] . ') - ' . 
                               date('Y-m-d', strtotime($final_booking_data['check_in_date'])) . ' to ' . 
                               date('Y-m-d', strtotime($final_booking_data['check_out_date'])),
                'quantity' => max(1, $nights), // Number of nights
                'unit_price' => $booking['room_type_data']['base_price'],
                'total_price' => $final_booking_data['total_price']
            ];
            
            $this->Invoice_item_model->insert($item_data);
        }
    }

    /**
     * Create an invoice for a tour booking
     */
    private function create_tour_invoice($tour_booking_id, $tour_booking, $final_tour_booking_data) {
        // Create invoice record
        $invoice_data = [
            'booking_id' => null, // Tour bookings don't use the bookings table
            'tour_booking_id' => $tour_booking_id, // Store tour booking ID separately
            'issue_date' => date('Y-m-d'),
            'due_date' => $final_tour_booking_data['booking_date'], // Due on tour date
            'total_amount' => $final_tour_booking_data['total_price'],
            'status' => $final_tour_booking_data['payment_status'] === 'paid' ? 'paid' : 'unpaid'
        ];
        
        $invoice_id = $this->Invoice_model->insert($invoice_data);
        
        if ($invoice_id) {
            // Calculate unit price (price per person)
            $unit_price = $final_tour_booking_data['number_of_pax'] > 0 
                ? $final_tour_booking_data['total_price'] / $final_tour_booking_data['number_of_pax']
                : $final_tour_booking_data['total_price'];
            
            // Add invoice item for the tour booking
            $item_data = [
                'invoice_id' => $invoice_id,
                'description' => 'Tour: ' . $tour_booking['tour_data']['name'] . ' (' . 
                               date('Y-m-d', strtotime($final_tour_booking_data['booking_date'])) . ') - ' . 
                               $final_tour_booking_data['number_of_pax'] . ' pax',
                'quantity' => $final_tour_booking_data['number_of_pax'],
                'unit_price' => $unit_price,
                'total_price' => $final_tour_booking_data['total_price']
            ];
            
            $this->Invoice_item_model->insert($item_data);
        }
    }

}
?>