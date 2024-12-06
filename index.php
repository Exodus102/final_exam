<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="Tailwind/css/tailwind.css" rel="stylesheet">
    <link rel="stylesheet" href="Tailwind/animations/animations.css">
    <link rel="stylesheet" href="Tailwind/togglemode/togglemode.css">
    <title>Learning MS</title>
</head>

<body class="light-mode">
    <div class="flex flex-col-reverse lg:flex-row h-screen font-poppins dark:bg-dark-background lg:gap-60">
        <div class="flex flex-col justify-center items-center lg:items-start p-10 fade-in content">
            <h1 class="font-semibold text-[70px] mb-4 leading-[40px] dark:text-white">Learning</h1>
            <h1 class="font-semibold text-[70px] mb-0 dark:text-white text-center lg:text-start">Module System</h1>
            <p class="text-lg mb-6 leading-6 sm:leading-3 dark:text-white text-center sm:text-start">A website for online education to pursue one's dream.</p>
            <div class="flex space-x-4">
                <a href="PHP/pages/login/loginv2.php">
                    <button id="get-started" class="btn-neon bg-light-yellow text-black font-light py-2 px-4 
                        rounded transition duration-300 shadow-md hover:shadow-lg hover:outline-none dark:bg-neon-blue dark:text-black
                        ">
                        <span class="hidden sm:inline">Get Started with Learning Module System</span>
                        <span class="inline sm:hidden">Get Started</span>
                    </button>
                </a>
                <button id="dark-mode-toggle" class="btn-neon bg-light-yellow text-black font-bold py-2 px-4 
                    rounded transition duration-300 shadow-md hover:shadow-lg hover:outline-none dark:bg-neon-blue dark:text-black
                    ">
                    <span id="mode-icon">🌞</span>
                </button>
            </div>
        </div>
    </div>

    <script src="JavaScript/togglemode.js"></script>
</body>

</html>