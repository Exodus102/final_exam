<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../Tailwind/css/tailwind.css">
    <title>Sign In</title>
</head>

<body class="ml-5 mr-5 font-poppins">
    <div class="h-screen flex pt-5 pb-5">
        <div class="w-2/3 bg-[#D4FF00] content-center pl-5 rounded-lg">
            <h1 class="text-6xl font-semibold">
                Empowering the future of <span><br>Education.</span>
            </h1>
            <p class="leading-none text-2xl font-light">
                Deepen the understanding of technology is a must.
            </p>
        </div>

        <div class="w-full pl-5 flex flex-col justify-center items-start">
            <h1 class="mb-3 text-5xl font-bold">
                Sign in
            </h1>
            <form action="../../services/login_credentials/login_credentials.php" method="post" class="w-full">
                <input type="text" name="email" placeholder="email" class="w-full p-3 bg-gray-300 rounded placeholder-gray-500"><br>
                <input type="password" name="password" placeholder="password" class="mt-5 w-full p-3 mb-5 bg-gray-300 rounded placeholder-gray-500">
                <div class="flex justify-between items-center">
                    <button class="text-1xl bg-[#D4FF00] w-80 h-10 rounded">Sign in</button>
                    <a href="../register/register.php" class="text-1xl bg-[#D4FF00] w-80 h-10 rounded flex justify-center items-center">
                        Register
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script src="../../../JavaScript/login_script/login_togglemode.js"></script>
</body>

</html>