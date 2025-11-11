<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

if (!function_exists('send_booking_confirmation')) {
    /**
     * Sends a booking confirmation email using SendGrid's HTTP API (cURL).
     * This bypasses all Composer library loading issues.
     *
     * @param string $recipient_email The guest's email address.
     * @param string $subject The email subject.
     * @param string $html_content The HTML content of the email.
     * @return bool True on success, False on failure.
     */
    function send_booking_confirmation($recipient_email, $subject, $html_content) {
        
        $apiKey = config_item('sendgrid_api_key');
        $senderEmail = config_item('sender_email');
        $senderName = config_item('sender_name');

        if (empty($apiKey) || empty($senderEmail)) {
            error_log("SendGrid cURL Error: API Key or Sender Email is not configured.");
            return false;
        }

        // 1. Prepare the JSON data payload
        $payload = [
            'personalizations' => [
                [
                    'to' => [
                        ['email' => $recipient_email]
                    ]
                ]
            ],
            'from' => [
                'email' => $senderEmail,
                'name' => $senderName
            ],
            'subject' => $subject,
            'content' => [
                [
                    'type' => 'text/html',
                    'value' => $html_content
                ]
            ]
        ];
        $jsonData = json_encode($payload);

        // 2. Prepare cURL request
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ]);
        
        // 3. (THE FIX) Set the SSL certificate path directly in cURL
        // This forces it to use the correct file and bypasses php.ini issues.
        $cacert_path = "C:/wamp64/bin/php/cacert.pem";
        if (file_exists($cacert_path)) {
            curl_setopt($ch, CURLOPT_CAINFO, $cacert_path);
        } else {
            error_log("SendGrid cURL Error: cacert.pem file not found at " . $cacert_path);
            return false;
        }


        // 4. Execute and get response
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        // 5. Handle response
        if ($httpcode >= 200 && $httpcode < 300) {
            // Success! (SendGrid returns 202 Accepted)
            return true;
        } else {
            // Log the failure
            error_log("SendGrid cURL Error: Failed to send. HTTP Code: " . $httpcode . " | Response: " . $response . " | cURL Error: " . $curl_error);
            return false;
        }
    }
}

if (!function_exists('generate_email_template')) {
    /**
     * Generates a beautiful HTML email template.
     *
     * @param string $title The main title of the email.
     * @param string $guest_name The first name of the guest.
     * @param string $intro_message The main paragraph of text (e.g., "Thank you for booking...")
     * @param array $details An associative array of booking details.
     * @param string $call_to_action A concluding paragraph.
     * @return string The full HTML email string.
     */
    function generate_email_template($title, $guest_name, $intro_message, $details, $call_to_action) {
        $details_html = '';
        foreach ($details as $key => $value) {
            $details_html .= '<tr>
                                <td style="padding: 10px 0; border-bottom: 1px solid #eeeeee; font-size: 14px; color: #555555;">' . html_escape($key) . '</td>
                                <td style="padding: 10px 0; border-bottom: 1px solid #eeeeee; font-size: 14px; color: #111111; text-align: right;"><strong>' . html_escape($value) . '</strong></td>
                              </tr>';
        }

        return '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . html_escape($title) . '</title>
            <style>
                body { margin: 0; padding: 0; font-family: Arial, sans-serif; }
                .container { width: 100%; max-width: 600px; margin: 0 auto; }
                .content { padding: 30px; }
                .header { background: #f8f8f8; padding: 20px; text-align: center; }
                .footer { background: #f8f8f8; padding: 20px; text-align: center; font-size: 12px; color: #999999; }
                h1 { color: #dd4814; font-size: 24px; }
                p { font-size: 16px; line-height: 1.5; color: #333333; }
                .details-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            </style>
        </head>
        <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
            <table class="container" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 600px; margin: 0 auto;">
                <tr>
                    <td align="center">
                        <table class="main-wrapper" cellpadding="0" cellspacing="0" style="width: 100%; background: #ffffff; border: 1px solid #dddddd;">
                            <tr>
                                <td class="header" style="background: #f8f8f8; padding: 20px; text-align: center;">
                                    <h1 style="color: #dd4814; font-size: 24px; margin: 0;">Visit Mindoro</h1>
                                </td>
                            </tr>
                            <tr>
                                <td class="content" style="padding: 30px;">
                                    <h2 style="font-size: 20px; color: #333333;">' . html_escape($title) . '</h2>
                                    <p style="font-size: 16px; line-height: 1.5; color: #333333;">Hi ' . html_escape($guest_name) . ',</p>
                                    <p style="font-size: 16px; line-height: 1.5; color: #333333;">' . $intro_message . '</p>
                                    
                                    <table class="details-table" style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                                        ' . $details_html . '
                                    </table>
                                    
                                    <p style="font-size: 16px; line-height: 1.5; color: #333333;">' . html_escape($call_to_action) . '</p>
                                    <p style="font-size: 16px; line-height: 1.5; color: #333333;">Thank you,<br>The Visit Mindoro Team</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="footer" style="background: #f8f8f8; padding: 20px; text-align: center; font-size: 12px; color: #999999;">
                                    &copy; ' . date('Y') . ' Visit Mindoro. All rights reserved.
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>';
    }
}
?>
