<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Cancelled - Visit Mindoro</title>
    <link href="<?= site_url('public/css/output.css') ?>" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
        <svg class="w-24 h-24 text-red-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h2 class="mt-4 text-3xl font-extrabold text-gray-900 sm:text-4xl">
            <span class="block">Payment Cancelled</span>
        </h2>
        <p class="mt-4 text-lg leading-6 text-gray-500">Your payment was not completed, and your booking has not been made.</p>
        
        <?php $return_url = ($type == 'room') ? site_url('rooms') : site_url('tours'); ?>
        <?php $return_text = ($type == 'room') ? 'Back to Rooms' : 'Back to Tours'; ?>
        
        <a href="<?= $return_url ?>" class="mt-8 w-full inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 sm:w-auto">
            <?= $return_text ?>
        </a>
    </div>
</body>
</html>