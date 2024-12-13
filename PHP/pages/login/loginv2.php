<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../Tailwind/css/tailwind.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Sign Up</title>
</head>

<body class="font-poppins">
    <div class="flex flex-col h-screen w-screen bg-[#F3F3F3] p-5 sm:flex-row">
        <div class="flex flex-col w-full sm:w-2/5">

            <!-- Dito ang header natin hahahahahha-->
            <div class="flex w-full justify-between items-center">
                <div class="flex justify-center items-center">
                    <img src="../../../assets/logo/logoq.svg" alt="">
                    <span class="text-3xl text-[#424040] font-[500] ml-4">LMS</span>
                </div>
                <a href="../register/register.php">Create an Account</a>
            </div>

            <div class="flex flex-col h-full justify-center items-center p-5">
                <!--input the user's email and password hahahahaha-->
                <span class="font-extrabold text-4xl text-[#424040]">
                    SIGN IN
                </span>
                <span class="text-sm text-[#424040] mb-3">
                    Enter you LMS account details.
                </span>

                <!--Here dito yung form na ah HAHAHAHAHA-->
                <form action="../../services/login_credentials/login_credentials.php" method="post" class="w-full flex flex-col gap-3">
                    <input type="text" name="email" placeholder="Email" class="p-4 bg-white rounded">
                    <input type="password" name="password" placeholder="Password" class="p-4 bg-white rounded">
                    <button type="submit" class="text-sm bg-[#424040] text-[#FBFBFB] w-full rounded p-4">Sign in</button>
                </form>
                <?php if (isset($_GET['error'])) : ?>
                    <p class="text-red-500 mt-5"><?php echo htmlspecialchars($_GET['error']); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="">
            <img src="../../../assets/images/LMS.png" alt="LMS" class="w-full h-full">
        </div>
    </div>
</body>

</html>