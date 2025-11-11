<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>Signup - Visit Mindoro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* A simple fade-in animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        /* Styles to manage password icon visibility */
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
                <h2 class="mt-6 text-2xl font-bold tracking-tight text-gray-900">Create a new account</h2>
                
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
            <?php if ($this->session->flashdata('errors')): ?>
                <div class="rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul role="list" class="list-disc space-y-1 pl-5">
                                    <?php foreach($this->session->flashdata('errors') as $error): ?>
                                        <li><?= $error; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <form class="space-y-4" action="<?= site_url('auth/register') ?>" method="POST">
                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div>
                        <label for="first_name" class="block text-sm font-medium leading-6 text-gray-900">First Name</label>
                        <div class="mt-2">
                            <input id="first_name" name="first_name" type="text" required class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium leading-6 text-gray-900">Last Name</label>
                        <div class="mt-2">
                            <input id="last_name" name="last_name" type="text" required class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" autocomplete="email" required class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6" placeholder="you@example.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                    <div class="mt-2 relative">
                        <input id="password" name="password" type="password" required class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                            <span class="toggle-password cursor-pointer text-gray-500 hover:text-gray-700">
                                <i class="fa-solid fa-eye-slash h-5 w-5"></i>
                                <i class="fa-solid fa-eye h-5 w-5"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="password_confirm" class="block text-sm font-medium leading-6 text-gray-900">Confirm Password</label>
                    <div class="mt-2 relative">
                        <input id="password_confirm" name="password_confirm" type="password" required class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                            <span class="toggle-password cursor-pointer text-gray-500 hover:text-gray-700">
                                <i class="fa-solid fa-eye-slash h-5 w-5"></i>
                                <i class="fa-solid fa-eye h-5 w-5"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-orange-600 py-2.5 px-3 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">Create Account</button>
                </div>
            </form>
            <p class="mt-2 text-sm text-gray-600">
                Already have an account?
                <a href="<?= site_url('login') ?>" class="font-medium text-orange-600 hover:text-orange-500">Sign in here</a>
            </p>
            
            <p class="mt-10 text-center text-sm text-gray-700">
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