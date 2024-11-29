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
                <a href="PHP/pages/login/login.php">
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

        <div class="relative flex justify-center items-center fade-in flex-grow pt-10 md:pt-0 md:mt-0 ">
            <div class="image1 relative z-10 rounded-full">
                <img
                    src="assets/images/solo.JPG"
                    alt="Solo Image"
                    class="w-80 h-80 object-cover rounded-full left-3 hidden lg:block lg:w-60 lg:h-60
                    xl:w-80 xl:h-80">
            </div>
            <img
                src="assets/images/with_them.JPG"
                alt="With Them Image"
                class="image2 sm:w-80 sm:h-80 w-48 h-48 object-cover rounded-full z-0 absolute lg:top-4
                lg:w-80 lg:h-80 xl:w-80 xl:h-80">
            <img
                src="assets/images/sir.jpg"
                alt="prof"
                class="image3 w-80 h-80 object-cover rounded-full z-20 absolute bottom-0 md:bottom-4 md:left-7
                hidden lg:block lg:w-60 lg:h-60 xl:w-80 xl:h-80">
        </div>
    </div>

    <script src="JavaScript/togglemode.js"></script>
</body>

</html>