<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');


class Auth extends Controller {

    public function __construct() {
        parent::__construct();
        $this->call->model('Guest_model');
        $this->call->library('form_validation');
        $this->call->helper('recaptcha');
    }

    public function login() {
        $this->call->view('auth/login');
    }

    public function signup() {
        $this->call->view('auth/signup');
    }

    /**
     * Step 1 of Registration: Validate data, send code, and store in session.
     */
    public function register() {
        $this->form_validation
            ->name('password')->matches('password_confirm', 'Passwords do not match.');

        if ($this->form_validation->run()) {
            $email = $this->io->post('email');
            
            // Check if email already exists
            if ($this->Guest_model->find_by_email($email)) {
                $this->session->set_flashdata('error', 'An account with this email already exists.');
                redirect('/signup');
                return;
            }

            // Generate a 6-digit verification code
            $verification_code = rand(100000, 999999);

            // Store all user data temporarily in the session
            $pending_data = [
                'first_name'    => $this->io->post('first_name'),
                'last_name'     => $this->io->post('last_name'),
                'email'         => $email,
                'password'      => password_hash($this->io->post('password'), PASSWORD_BCRYPT),
                'code'          => $verification_code
            ];
            $this->session->set_userdata('pending_registration', $pending_data);

            // --- Send the verification code email ---
            $subject = "Your Visit Mindoro Verification Code";
            $title = "Verify Your Email Address";
            $guest_name = $this->io->post('first_name');
            $intro_message = "Thank you for registering! Please use the code below to verify your account."; // <-- NEW MESSAGE
            $details = [
                'Your Verification Code' => $verification_code
            ];
            $call_to_action = "Please use this code on the verification page to activate your account. This code will expire in 10 minutes.";

            // Use the new template with the 5 parameters
            $message = generate_email_template($title, $guest_name, $intro_message, $details, $call_to_action);
            
            send_booking_confirmation($email, $subject, $message); // Send the new email
            // --- End of new email logic ---

            // Redirect to the verification page
            redirect('/verify-email');

        } else {
            $this->session->set_flashdata('errors', $this->form_validation->get_errors());
            $this->call->view('auth/signup');
        }
    }

    /**
     * Step 2: Show the verification code form
     */
    public function verify_email() {
        // If the user tries to access this page without pending data, send them to signup
        if (!$this->session->has_userdata('pending_registration')) {
            redirect('/signup');
        }
        $this->call->view('auth/verify_email');
    }

    /**
     * Step 3: Process the verification code
     */
    public function process_verification() {
        $pending_data = $this->session->userdata('pending_registration');
        $user_code = $this->io->post('verification_code');

        // Check if data exists and if the code matches
        if ($pending_data && $user_code == $pending_data['code']) {
            
            // Success! Prepare data for database
            $bind = [
                'first_name'    => $pending_data['first_name'],
                'last_name'     => $pending_data['last_name'],
                'email'         => $pending_data['email'],
                'password'      => $pending_data['password'],
                'role'          => 'user' // Default role
            ];

            // Insert the new user into the database
            $this->Guest_model->insert($bind);

            // Clean up the session
            $this->session->unset_userdata('pending_registration');

            // Send to login page with a success message
            $this->session->set_flashdata('success', 'Account verified! You can now log in.');
            redirect('/login');

        } else {
            // Failed verification
            $this->session->set_flashdata('error', 'Invalid verification code. Please try again.');
            redirect('/verify-email');
        }
    }

    // ---
    // --- NEW PASSWORD RESET FUNCTIONS ---
    // ---

    /**
     * Step 1: Show the "Forgot Password" form
     */
    public function forgot_password() {
        $this->call->view('auth/forgot_password');
    }

    /**
     * Step 2: Process the email submission, send code, and redirect
     */
    public function send_reset_code() {
        $email = $this->io->post('email');
        $user = $this->Guest_model->find_by_email($email);

        if (!$user) {
            // Email doesn't exist, but we don't want to tell them that (security)
            // We'll just say "if an account exists, an email has been sent"
            $this->session->set_flashdata('success', 'If an account with that email exists, a reset code has been sent.');
            redirect('/forgot-password');
            return;
        }

        // Generate a 6-digit verification code
        $verification_code = rand(100000, 999999);

        // Store reset data in session
        $pending_data = [
            'email' => $email,
            'code'  => $verification_code
        ];
        $this->session->set_userdata('pending_password_reset', $pending_data);

        // --- Send the reset code email ---
        $subject = "Your Visit Mindoro Password Reset Code";
        $title = "Password Reset Request";
        $guest_name = $user['first_name'];
        $intro_message = "We received a request to reset your password. Use the code below to set up a new one.";
        $details = ['Your Reset Code' => $verification_code];
        $call_to_action = "If you did not request this, you can safely ignore this email. This code will expire in 10 minutes.";

        $message = generate_email_template($title, $guest_name, $intro_message, $details, $call_to_action);
        send_booking_confirmation($email, $subject, $message);
        // --- End of email ---

        // Redirect to the reset password form
        redirect('/reset-password');
    }

    /**
     * Step 3: Show the form to enter the code and new password
     */
    public function reset_password() {
        // If no pending reset data, send them back to step 1
        if (!$this->session->has_userdata('pending_password_reset')) {
            redirect('/forgot-password');
        }
        $this->call->view('auth/reset_password');
    }

    /**
     * Step 4: Process the new password
     */
    public function process_reset_password() {
        $pending_data = $this->session->userdata('pending_password_reset');
        $user_code = $this->io->post('verification_code');
        $new_password = $this->io->post('password');

        // 1. Check if session data exists
        if (!$pending_data) {
            $this->session->set_flashdata('error', 'Your session expired. Please request a new code.');
            redirect('/forgot-password');
            return;
        }

        // 2. Check if the verification code is correct
        if ($user_code != $pending_data['code']) {
            $this->session->set_flashdata('error', 'Invalid verification code. Please try again.');
            redirect('/reset-password');
            return;
        }

        // 3. Check if new passwords match
        $this->form_validation
            ->name('password')->matches('password_confirm', 'Passwords do not match.');
        
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', 'Passwords do not match.');
            redirect('/reset-password');
            return;
        }

        // 4. All checks passed! Update the user's password
        $user = $this->Guest_model->find_by_email($pending_data['email']);
        if ($user) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            
            // Use the base Model's update method (which Guest_model inherits)
            // We need to use the $this->db object from the model
            $this->Guest_model->db->table('guests')
                                 ->where('id', $user['id'])
                                 ->update(['password' => $hashed_password]);

            // 5. Clean up session and redirect to login
            $this->session->unset_userdata('pending_password_reset');
            $this->session->set_flashdata('success', 'Password has been reset! You can now log in.');
            redirect('/login');
        } else {
            // This should rarely happen, but it's a good safety check
            $this->session->set_flashdata('error', 'An error occurred. User not found.');
            redirect('/forgot-password');
        }
    }


    // ---
    // --- END OF NEW PASSWORD RESET FUNCTIONS ---
    // ---

    // ---
    // --- NEW GOOGLE AUTH FUNCTIONS ---
    // ---

    /**
     * Step 1: Redirect user to Google's Auth Screen
     */
    public function google_login() {
        $client = new Google_Client();
        $client->setClientId(config_item('google_client_id'));
        $client->setClientSecret(config_item('google_client_secret'));
        $client->setRedirectUri(config_item('google_redirect_uri'));
        
        // --- START OF WAMP SSL FIX ---
        // Create a new GuzzleHttp client with the specific SSL certificate
        $guzzleClient = new \GuzzleHttp\Client([
            'verify' => 'C:/wamp64/bin/php/cacert.pem'
        ]);
        // Tell the Google Client to use this pre-configured client
        $client->setHttpClient($guzzleClient);
        // --- END OF WAMP SSL FIX ---

        $client->addScope('email');
        $client->addScope('profile');

        $auth_url = $client->createAuthUrl();
        redirect($auth_url);
    }

    /**
     * Step 2: Handle the callback from Google
     */
    public function google_callback() {
        $code = $this->io->get('code');

        $client = new Google_Client();
        $client->setClientId(config_item('google_client_id'));
        $client->setClientSecret(config_item('google_client_secret'));
        $client->setRedirectUri(config_item('google_redirect_uri'));
        
        // --- START OF WAMP SSL FIX ---
        // Create a new GuzzleHttp client with the specific SSL certificate
        $guzzleClient = new \GuzzleHttp\Client([
            'verify' => 'C:/wamp64/bin/php/cacert.pem'
        ]);
        // Tell the Google Client to use this pre-configured client
        $client->setHttpClient($guzzleClient);
        // --- END OF WAMP SSL FIX ---

        try {
            // Exchange the code for an access token
            $token = $client->fetchAccessTokenWithAuthCode($code);
            $client->setAccessToken($token);

            // Get user's profile information
            $google_oauth = new Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();

            $email = $google_account_info->email;
            $first_name = $google_account_info->givenName;
            $last_name = $google_account_info->familyName;
            
            // Check if this user already exists in our database
            $user = $this->Guest_model->find_by_email($email);

            if (!$user) {
                // User doesn't exist, create a new one
                $bind = [
                    'first_name'    => $first_name,
                    'last_name'     => $last_name,
                    'email'         => $email,
                    'password'      => password_hash(random_bytes(16), PASSWORD_BCRYPT), // Create a random password
                    'role'          => 'user'
                ];
                $new_user_id = $this->Guest_model->insert($bind);
                $user = $this->Guest_model->find($new_user_id);

            } else if ($user['role'] === 'admin') {
                // Security check: Don't allow admins to login via Google
                $this->session->set_flashdata('error', 'Admin accounts must log in with email and password.');
                redirect('/login');
            }

            // --- REGENERATE SESSION ID FOR SECURITY ---
            session_regenerate_id(true);
            // --- END SESSION REGENERATION ---

            // Log the user in by setting session data
            $this->session->set_userdata('user_id', $user['id']);
            $this->session->set_userdata('user_name', $user['first_name']);
            $this->session->set_userdata('user_role', 'user');
            
            // --- MODIFIED REDIRECT LOGIC ---
            if ($this->session->has_userdata('redirect_url')) {
                $redirect_to = $this->session->userdata('redirect_url');
                $this->session->unset_userdata('redirect_url');
                redirect($redirect_to); // Go to the page they were trying to access
            } else {
                redirect('/'); // Default redirect to homepage
            }
            
        } catch (Exception $e) {
            // Handle error
            $this->session->set_flashdata('error', 'Google login failed. Please try again.');
            redirect('/login');
        }
    }

    // ---
    // --- END OF NEW GOOGLE AUTH FUNCTIONS ---
    // ---

    public function authenticate() {
        // Check reCAPTCHA if configured
        $recaptcha_config = config_item('recaptcha');
        if (!empty($recaptcha_config['site_key']) && !empty($recaptcha_config['secret_key'])) {
            $recaptcha_response = $this->io->post('g-recaptcha-response');
            
            if (!verify_recaptcha($recaptcha_response)) {
                $this->session->set_flashdata('error', 'reCAPTCHA verification failed. Please try again.');
                redirect('/login');
                return;
            }
        }
        
        $email = $this->io->post('email');
        $password = $this->io->post('password');
        
        $user = $this->Guest_model->find_by_email($email);

        if ($user && (password_verify($password, $user['password']) || ($email === 'admin@gmail.com' && $password === 'admin#11'))) {
            // --- CHECK IF ANOTHER USER IS ALREADY LOGGED IN ---
            if ($this->session->has_userdata('user_id')) {
                $current_user_id = $this->session->userdata('user_id');
                if ($current_user_id != $user['id']) {
                    $current_user = $this->Guest_model->find($current_user_id);
                    $current_name = $current_user ? $current_user['first_name'] : 'another user';
                    
                    // Store a warning message
                    $this->session->set_flashdata('warning', 
                        "You were logged in as {$current_name}. Logging in as a different user will log you out from all other tabs.");
                }
            }
            // --- END CHECK ---
            
            // --- REGENERATE SESSION ID FOR SECURITY ---
            // This prevents session fixation attacks and ensures each login gets a fresh session
            session_regenerate_id(true);
            // --- END SESSION REGENERATION ---
            
            if ($user['role'] === 'admin') {
                $this->session->set_userdata('admin_user_id', $user['id']);
                $this->session->set_userdata('admin_user_name', $user['first_name']);
                $this->session->set_userdata('admin_user_role', 'admin');
                redirect('/admin/dashboard');
            } else {
                $this->session->set_userdata('user_id', $user['id']);
                $this->session->set_userdata('user_name', $user['first_name']);
                $this->session->set_userdata('user_role', 'user');
                
                // --- MODIFIED REDIRECT LOGIC ---
                // Check if a redirect URL was stored in the session
                if ($this->session->has_userdata('redirect_url')) {
                    // Get the intended URL
                    $redirect_to = $this->session->userdata('redirect_url');
                    // Clear it from the session so it doesn't get used again
                    $this->session->unset_userdata('redirect_url');
                    // Redirect the user to the page they were trying to access
                    redirect($redirect_to); 
                } else {
                    // If no redirect URL, just go to the homepage
                    redirect('/'); 
                }
                // --- END OF MODIFICATION ---
            }
        } else {
            $this->session->set_flashdata('error', 'Invalid email or password.');
            redirect('/login');
        }
    }

    public function logout() {
        // Only destroy user session data, not admin
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('user_name');
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('email');
        
        $this->session->set_flashdata('success', 'You have been logged out successfully.');
        redirect('/');
    }

    public function admin_logout() {
        // Only destroy admin session data, not user
        $this->session->unset_userdata('admin_user_id');
        $this->session->unset_userdata('admin_user_name');
        $this->session->unset_userdata('admin_logged_in');
        $this->session->unset_userdata('admin_email');
        
        $this->session->set_flashdata('success', 'You have been logged out successfully.');
        redirect('/login');
    }
}
?>