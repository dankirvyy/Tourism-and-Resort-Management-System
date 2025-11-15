<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Your Tour Booking - Visit Mindoro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://www.paypal.com/sdk/js?client-id=<?= html_escape(config_item('payment')['paypal']['client_id']) ?>&currency=USD"></script>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md sticky top-0 z-50">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex-shrink-0">
                <a href="<?= site_url('/') ?>" class="flex items-center gap-3 text-3xl font-bold text-orange-600">
                    <img class="h-10 w-auto" src="<?= site_url('public/uploads/images/logo.png') ?>" alt="Visit Mindoro Logo">
                    <span>Visit Mindoro</span>
                </a>
            </div>

            <div class="hidden md:flex md:items-center md:space-x-4">
                <a href="<?= site_url('/') ?>" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Home</a>
                <a href="<?= site_url('rooms') ?>" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Rooms</a>
                <a href="<?= site_url('tours') ?>" class="font-semibold text-orange-600 px-3 py-2 rounded-md text-sm">Tours</a>
                <a href="<?= site_url('contact') ?>" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Contact</a>
                <div class="border-l border-gray-300 h-6"></div>

                <?php if ($this->session->has_userdata('user_id')):
                    // Fetch avatar details if user is logged in
                    $guest_info = $this->Guest_model->find($this->session->userdata('user_id')); // Fetch guest info
                    $avatarUrl = ($guest_info && $guest_info['avatar_filename'])
                        ? base_url('public/uploads/avatars/' . $guest_info['avatar_filename'])
                        : 'https://via.placeholder.com/32/cccccc/888888?text=U'; // Placeholder if no avatar
                ?>
                    <div class="ml-4 flex items-center space-x-4">
                        <a href="<?= site_url('my-profile') ?>" class="flex items-center text-sm font-medium text-gray-700 hover:text-orange-600">
                            <img class="h-8 w-8 rounded-full mr-2" src="<?= $avatarUrl ?>" alt="User Avatar">
                            Welcome, <?= $this->session->userdata('user_name'); ?>!
                        </a>
                        <a href="<?= site_url('logout') ?>" class="text-gray-500 hover:text-gray-900 text-sm font-medium">Logout</a>
                    </div>
                <?php else: ?>
                    <div class="ml-4 flex items-center">
                        <a href="<?= site_url('login') ?>" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                        <a href="<?= site_url('signup') ?>" class="ml-2 inline-flex items-center justify-center rounded-md border border-transparent bg-orange-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-orange-700">Sign Up</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-orange-500" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"></path></svg>
                    <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
    </nav>

    <div class="md:hidden hidden" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="<?= site_url('/') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Home</a>
            <a href="<?= site_url('rooms') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Rooms</a>
            <a href="<?= site_url('tours') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Tours</a>
            <a href="<?= site_url('contact') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Contact</a>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="px-2 space-y-1">
                <?php if ($this->session->has_userdata('user_id')): ?>
                    <a href="<?= site_url('my-profile') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">My Profile</a>
                    <a href="<?= site_url('logout') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Logout</a>
                <?php else: ?>
                    <a href="<?= site_url('login') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Login</a>
                    <a href="<?= site_url('signup') ?>" class="text-gray-700 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

    <div class="bg-white py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Review Your Tour Reservation</h2>
                <p class="mt-4 text-lg text-gray-500">Please confirm the details below.</p>
            </div>
            
            <?php if ($this->session->flashdata('error')): ?>
                <div class="mt-8 rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800"><?= $this->session->flashdata('error'); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="mt-10 bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Tour Details</h3>
                            <dl class="mt-4 space-y-4">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Tour Name</dt>
                                    <dd class="text-sm font-medium text-gray-900"><?= html_escape($tour_booking['tour_data']['name']) ?></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Date</dt>
                                    <dd class="text-sm font-medium text-gray-900"><?= date('F j, Y', strtotime($tour_booking['booking_data']['booking_date'])) ?></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Number of People</dt>
                                    <dd class="text-sm font-medium text-gray-900"><?= html_escape($tour_booking['booking_data']['number_of_pax']) ?></dd>
                                </div>
                                <div class="flex justify-between border-t pt-4">
                                    <dt class="text-lg font-bold text-gray-900">Total Price</dt>
                                    <dd class="text-lg font-bold text-orange-600">₱<?= number_format($tour_booking['booking_data']['total_price'], 2) ?></dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Guest Information</h3>
                            <dl class="mt-4 space-y-4">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                    <dd class="text-sm font-medium text-gray-900"><?= html_escape($tour_booking['guest_data']['first_name'] . ' ' . $tour_booking['guest_data']['last_name']) ?></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="text-sm font-medium text-gray-900"><?= html_escape($tour_booking['guest_data']['email']) ?></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                    <dd class="text-sm font-medium text-gray-900"><?= html_escape($tour_booking['guest_data']['phone_number']) ?></dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t">
                    <form id="tour-payment-form" action="<?= site_url('checkout/tour') ?>" method="GET">
                        <input type="hidden" name="payment_method" id="tour_payment_method_input" value="">
                        <input type="hidden" name="paypal_order_id" id="tour_paypal_order_id" value="">
                        <input type="hidden" name="payment_type" id="tour_payment_type_input" value="full">
                        <input type="hidden" name="amount_to_pay" id="tour_amount_to_pay_input" value="<?= $tour_booking['booking_data']['total_price'] ?>">

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Option</label>
                            <div class="flex items-center gap-4">
                                <label class="cursor-pointer flex-1">
                                    <input type="radio" name="payment_type_choice" value="full" class="payment-type-input peer hidden" checked />
                                    <div class="flex flex-col p-4 rounded-lg border-2 border-gray-300 transition-all peer-checked:border-orange-600 peer-checked:ring-2 peer-checked:ring-orange-500 peer-checked:bg-orange-50">
                                        <span class="text-sm font-semibold text-gray-900">Full Payment</span>
                                        <span class="text-lg font-bold text-orange-600 mt-1">₱<?= number_format($tour_booking['booking_data']['total_price'], 2) ?></span>
                                        <span class="text-xs text-gray-500 mt-1">Pay the full amount now</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer flex-1">
                                    <input type="radio" name="payment_type_choice" value="downpayment" class="payment-type-input peer hidden" />
                                    <div class="flex flex-col p-4 rounded-lg border-2 border-gray-300 transition-all peer-checked:border-orange-600 peer-checked:ring-2 peer-checked:ring-orange-500 peer-checked:bg-orange-50">
                                        <span class="text-sm font-semibold text-gray-900">Downpayment (50%)</span>
                                        <span class="text-lg font-bold text-orange-600 mt-1">₱<?= number_format($tour_booking['booking_data']['total_price'] * 0.5, 2) ?></span>
                                        <span class="text-xs text-gray-500 mt-1">Pay remaining balance before tour</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                            <div class="flex items-center gap-4">
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_method_choice" value="gcash" class="payment-method-input peer hidden" />
                                    <span class="flex h-12 w-24 items-center justify-center rounded-lg border border-gray-300 text-sm font-medium transition-all peer-checked:border-orange-600 peer-checked:ring-2 peer-checked:ring-orange-500">
                                        <img class="h-6" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSZP_xjIqcvTS8MoWqso_WkLX3bG6zXGMJdDg&s" alt="GCash Logo">
                                    </span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_method_choice" value="paypal" class="payment-method-input peer hidden" />
                                    <span class="flex h-12 w-24 items-center justify-center rounded-lg border border-gray-300 text-sm font-medium transition-all peer-checked:border-orange-600 peer-checked:ring-2 peer-checked:ring-orange-500">
                                        <img class="h-6" src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal Logo">
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div id="tour-paypal-button-container" class="hidden mb-4"></div>
                        <div class="flex justify-end gap-x-4">
                            <a href="<?= site_url('book/tour/' . $tour_booking['tour_data']['id']) ?>" class="rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Go Back & Edit</a>
                            <button id="tour-pay-button" type="submit" disabled class="rounded-md bg-orange-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm opacity-50 cursor-not-allowed">Pay</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- GCash Payment Modal -->
    <div id="tour-gcash-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity hidden" aria-labelledby="payment-details-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-blue-100">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSZP_xjIqcvTS8MoWqso_WkLX3bG6zXGMJdDg&s" alt="GCash" class="h-8 w-8">
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-base font-semibold leading-6 text-gray-900" id="payment-details-title">Pay with GCash</h3>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500">Scan this QR code with your GCash app:</p>
                            <div class="mt-4 flex justify-center">
                                <div class="bg-white p-2 rounded-lg shadow-sm">
                                    <!-- Replace with actual QR code -->
                                    <div class="w-48 h-48 bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400">QR Code</span>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-4 text-sm text-gray-500">Or send payment to:</p>
                            <p class="font-medium text-gray-900">GCash: 09XX-XXX-XXXX</p>
                            <p class="mt-2 text-sm text-gray-500">Amount to pay: ₱<?= number_format($tour_booking['booking_data']['total_price'], 2) ?></p>
                        </div>
                    </div>

                    <div class="mt-5 sm:mt-6">
                        <button type="button" id="close-tour-gcash-modal" class="inline-flex w-full justify-center rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="bg-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-base text-gray-400">&copy; <?= date('Y') ?> Visit Mindoro. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
<script>
    (function(){
        const pmInputs = document.querySelectorAll('#tour-payment-form .payment-method-input');
        const ptInputs = document.querySelectorAll('.payment-type-input');
        const payBtn = document.getElementById('tour-pay-button');
        const paymentInput = document.getElementById('tour_payment_method_input');
        const paymentTypeInput = document.getElementById('tour_payment_type_input');
        const amountToPayInput = document.getElementById('tour_amount_to_pay_input');
        const gcashModal = document.getElementById('tour-gcash-modal');
        const closeGcashModal = document.getElementById('close-tour-gcash-modal');
        const paypalContainer = document.getElementById('tour-paypal-button-container');
        const paypalOrderIdInput = document.getElementById('tour_paypal_order_id');
        let selectedMethod = '';
        let selectedPaymentType = 'full';
        let paypalInitialized = false;
        const fullAmount = <?= $tour_booking['booking_data']['total_price'] ?>;
        const downpaymentAmount = fullAmount * 0.5;

        if(!pmInputs.length || !payBtn || !paymentInput) return;
        
        // Handle payment type selection
        ptInputs.forEach(function(inp){
            inp.addEventListener('change', function(e){
                selectedPaymentType = e.target.value;
                paymentTypeInput.value = selectedPaymentType;
                const amount = selectedPaymentType === 'full' ? fullAmount : downpaymentAmount;
                amountToPayInput.value = amount;
                
                // Reinitialize PayPal if needed
                if (selectedMethod === 'paypal' && paypalInitialized) {
                    document.getElementById('tour-paypal-button-container').innerHTML = '';
                    paypalInitialized = false;
                    initializePayPal();
                }
            });
        });
        
        // Initialize PayPal buttons with error handling
        function initializePayPal() {
            if (typeof paypal === 'undefined') return;
            
            // Always get the current amount based on selected payment type
            const currentAmount = selectedPaymentType === 'full' ? fullAmount : downpaymentAmount;
            const usdAmount = (currentAmount / 55).toFixed(2);
            
            console.log('Initializing PayPal with:', {
                paymentType: selectedPaymentType,
                phpAmount: currentAmount,
                usdAmount: usdAmount
            });
            
            try {
                paypal.Buttons({
                    createOrder: function(data, actions) {
                        return actions.order.create({
                            purchase_units: [{
                                amount: {
                                    value: usdAmount
                                }
                            }]
                        });
                    },
                    onApprove: function(data, actions) {
                        return actions.order.capture().then(function(details) {
                            paypalOrderIdInput.value = details.id;
                            document.getElementById('tour-payment-form').submit();
                        });
                    },
                    onError: function(err) {
                        console.error('PayPal error:', err);
                        alert('PayPal payment failed. Please try again or use another payment method.');
                    }
                }).render('#tour-paypal-button-container').then(function() {
                    paypalInitialized = true;
                }).catch(function(err) {
                    console.error('PayPal render error:', err);
                });
            } catch (error) {
                console.error('PayPal initialization error:', error);
            }
        }

        pmInputs.forEach(function(inp){
            inp.addEventListener('change', function(e){
                const val = e.target.value || '';
                selectedMethod = val;
                paymentInput.value = val;
                
                // Show/hide relevant payment UI
                if (val === 'paypal') {
                    if (typeof paypal === 'undefined') {
                        alert('PayPal is not configured. Please contact support or use GCash payment method.');
                        // Reset selection
                        e.target.checked = false;
                        selectedMethod = '';
                        paymentInput.value = '';
                        return;
                    }
                    
                    // Initialize PayPal only when user selects it
                    if (!paypalInitialized) {
                        initializePayPal();
                    }
                    
                    paypalContainer.classList.remove('hidden');
                    payBtn.classList.add('hidden');
                } else if (val === 'gcash') {
                    paypalContainer.classList.add('hidden');
                    payBtn.classList.remove('hidden');
                    payBtn.disabled = false;
                    payBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    paypalContainer.classList.add('hidden');
                    payBtn.classList.remove('hidden');
                    payBtn.disabled = true;
                    payBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            });
        });

        // Handle GCash Pay button click - submit form to server which will create PayMongo source and redirect
        payBtn.addEventListener('click', function(e) {
            if (selectedMethod === 'gcash') {
                // Ensure hidden payment method input is set
                paymentInput.value = 'gcash';
                // Let the form submit to server-side route (/checkout/tour) which will redirect to PayMongo checkout for testing
                return true;
            }
        });

        // Close GCash modal
        closeGcashModal.addEventListener('click', function() {
            gcashModal.classList.add('hidden');
        });

        // Close modal when clicking outside
        gcashModal.addEventListener('click', function(e) {
            if (e.target === gcashModal) {
                gcashModal.classList.add('hidden');
            }
        });
    })();
</script>