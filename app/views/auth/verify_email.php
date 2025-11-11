<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF8">
    <title>Verify Your Account - Visit Mindoro</title>
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
                <h2 class="mt-6 text-2xl font-bold tracking-tight text-gray-900">
                    Check Your Email
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    We've sent a 6-digit verification code to your email address.
                </p>
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

            <form class="mt-8 space-y-6" action="<?= site_url('auth/process-verification') ?>" method="POST">
                <div>
                    <label for="verification_code" class="block text-sm font-medium leading-6 text-gray-900">Verification Code</label>
                    <div class="mt-2">
                        <input id="verification_code" name="verification_code" type="text" inputmode="numeric" required class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6" placeholder="Enter 6-digit code">
                    </div>
                </div>

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-orange-600 py-2.5 px-3 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">
                        Verify Account
                    </button>
                </div>
            </form>
            <p class="mt-10 text-center text-sm text-gray-700">
                Didn't get a code? <a href="<?= site_url('signup') ?>" class="font-medium text-orange-600 hover:text-orange-500">Go back and try again</a>
            </p>
        </div>
    </div>
</body>
</html>