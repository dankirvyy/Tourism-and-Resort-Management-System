<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful - Visit Mindoro</title>
    <link href="<?= site_url('public/css/output.css') ?>" rel="stylesheet">
    <meta http-equiv="refresh" content="3;url=<?= site_url('my-profile') ?>">
</head>
<body class="bg-gray-50">
    <div class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
        <svg class="w-24 h-24 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <h2 class="mt-4 text-3xl font-extrabold text-gray-900 sm:text-4xl">
            <span class="block">Payment Successful!</span>
        </h2>
        <p class="mt-4 text-lg leading-6 text-gray-500">Your booking is confirmed! We've sent a confirmation email to your address.</p>
        <p class="mt-2 text-sm text-gray-500">You will be redirected to your profile shortly.</p>
        <a href="<?= site_url('my-profile') ?>" class="mt-8 w-full inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 sm:w-auto">
            Go to My Profile
        </a>
    </div>
</body>
</html>