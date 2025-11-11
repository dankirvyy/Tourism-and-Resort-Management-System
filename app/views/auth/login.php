<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>Login - Visit Mindoro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        .toggle-password .fa-eye-slash { display: block; }
        .toggle-password .fa-eye { display: none; }
        .toggle-password.toggled .fa-eye-slash { display: none; }
        .toggle-password.toggled .fa-eye { display: block; }
    </style>
</head>
<body class="h-full">
    <div class="relative flex min-h-full items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-800">
        <img class="absolute inset-0 h-full w-full object-cover" 
             src="https://images.unsplash.com/photo-1506929562872-bb421503ef21?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" 
             alt="Mindoro Beach">
        <div class="absolute inset-0 bg-gray-900/60 mix-blend-multiply" aria-hidden="true"></div>

        <div class="relative z-10 w-full max-w-md space-y-8 bg-white/90 backdrop-blur-sm shadow-2xl rounded-2xl p-8 sm:p-10 animate-fade-in">
            <div>
                <a href="<?= site_url('/') ?>" class="flex items-center justify-center gap-3 text-3xl font-bold tracking-tight text-orange-600 hover:text-orange-700">
                    <img class="h-10 w-auto" src="<?= site_url('public/uploads/images/logo.png') ?>" alt="Visit Mindoro Logo">
                    <span>Visit Mindoro</span>
                </a>
                <h2 class="mt-6 text-2xl font-bold tracking-tight text-gray-900">Sign in to your account</h2>
                
            </div>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="rounded-md bg-red-50 p-4">
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
            
            <?php if ($this->session->flashdata('success')): ?>
                <div class="rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800"><?= $this->session->flashdata('success'); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <form class="space-y-6" action="<?= site_url('auth/authenticate') ?>" method="POST">
                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                    <div class="mt-2">
                        <input id="email-address" name="email" type="email" autocomplete="email" required class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6" placeholder="you@example.com">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                        <div class="text-sm">
                            <a href="<?= site_url('forgot-password') ?>" class="font-medium text-orange-600 hover:text-orange-500">Forgot password?</a>
                        </div>
                    </div>
                    <div class="mt-2 relative">
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6" placeholder="Your Password">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                            <span class="toggle-password cursor-pointer text-gray-500 hover:text-gray-700">
                                <i class="fa-solid fa-eye-slash h-5 w-5"></i>
                                <i class="fa-solid fa-eye h-5 w-5"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- reCAPTCHA Widget -->
                <?php 
                $recaptcha_config = config_item('recaptcha');
                if (!empty($recaptcha_config['site_key'])): 
                ?>
                <div class="flex justify-center">
                    <div class="g-recaptcha" data-sitekey="<?= html_escape($recaptcha_config['site_key']) ?>"></div>
                </div>
                <?php endif; ?>

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-orange-600 py-2.5 px-3 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">Sign in</button>
                </div>
            </form>
            
            <div class="relative">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-gray-400"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="bg-white/90 px-2 text-gray-500">Or continue with</span>
                </div>
            </div>

            <div>
                <a href="<?= site_url('google-login') ?>" class="flex w-full items-center justify-center rounded-md bg-white py-2.5 px-4 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="48px" height="48px"><path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/><path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/><path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/><path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.574l6.19,5.238C39.902,36.098,44,30.638,44,24C44,22.659,43.862,21.35,43.611,20.083z"/></svg>
                    <span class="ml-3">Sign in with Google</span>
                </a>
            </div>
            <p class="mt-2 text-sm text-gray-600">
                No account yet?
                <a href="<?= site_url('signup') ?>" class="font-medium text-orange-600 hover:text-orange-500">Sign up here</a>
            </p>
            <p class="mt-8 text-center text-sm text-gray-700">
                <a href="<?= site_url('/') ?>" class="font-medium text-orange-600 hover:text-orange-500">&larr; Back to Home</a>
            </p>
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(item => {
            item.addEventListener('click', function (e) {
                const iconWrapper = e.currentTarget;
                const input = iconWrapper.closest('.relative').querySelector('input');
                const isPassword = input.getAttribute('type') === 'password';
                
                input.setAttribute('type', isPassword ? 'text' : 'password');
                iconWrapper.classList.toggle('toggled');
            });
        });
    </script>
</body>
</html>