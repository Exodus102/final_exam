<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../Tailwind/css/tailwind.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Register</title>
</head>

<body class="font-poppins">
    <div class="h-screen flex p-5">
        <div class="w-2/3 flex flex-col justify-center items-start">
            <h1 class="font-semibold text-7xl mb-5">
                Register
            </h1>
            <form action="../../services/register_credentials/register_credentials.php" method="post" class="w-full">
                <input type="text" name="fname" placeholder="First Name" class="w-full p-3 bg-gray-300 rounded placeholder-gray-500 mb-5"><br>
                <input type="text" name="lname" placeholder="Last Name" class="w-full p-3 bg-gray-300 rounded placeholder-gray-500 mb-5"><br>
                <input type="text" name="course" placeholder="Course" class="w-full p-3 bg-gray-300 rounded placeholder-gray-500 mb-5"><br>
                <input type="text" name="email" placeholder="email" class="w-full p-3 bg-gray-300 rounded placeholder-gray-500"><br>
                <input type="password" name="password" placeholder="password" class="mt-5 w-full p-3 mb-5 bg-gray-300 rounded placeholder-gray-500"><br>
                <button type="submit" >
                    Sign Up
                </button>
                <select name="role">
                    <option value="student">Student</option>
                    <option value="prof">Professor</option>
                </select>
            </form>
        </div>
        <div class="w-2/3 bg-[#D4FF00] flex justify-end items-center rounded">
            <h1 class="text-7xl font-semibold text-right">
                Register to start<br> the unstoppable <br>learning.
            </h1>
        </div>
    </div>
</body>

</html>