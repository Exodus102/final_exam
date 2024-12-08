<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to access this page.";
    header("Location: ../pages/login/loginv2.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Tailwind/css/tailwind.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Leanring MS</title>
</head>

<body class="font-poppins">
    <div class="flex h-screen p-5">
        <div class="flex flex-col w-1/5 border-[3px] border-[#E9E3FF] rounded-xl shadow-xl
            pl-5 pr-5">
            <div class="flex mb-10 mt-10 justify-start items-center">
                <img src="../../assets/logo/logoq.svg" alt="" width="60" height="60">
                <h1 class="font-[500] ml-2 text-2xl invisible md:visible">
                    LMS
                </h1>
            </div>

            <div class="flex flex-col text-[#424040]">
                <a href="panel_prof.php?page=dashboard_prof_testing" class="flex items-center m-2 space-x-5 p-1 rounded-lg hover:bg-gray-100 active:bg-[#E9E3FF] focus:bg-[#E9E3FF]
                <?php echo (isset($_GET['page']) && $_GET['page'] === 'dashboard_prof_testing') ? 'bg-[#E9E3FF]' : ''; ?>">
                    <img src="../../assets/icons/dashboard.svg" alt="dashboard" class="w-5 h-5">
                    <span class="text-left invisible lg:visible">Dashboard</span>
                </a>
                <a href="panel_prof.php?page=classes_prof" class="flex items-center m-2 space-x-5 p-1 rounded-lg hover:bg-gray-100 focus:bg-[#E9E3FF]
                <?php echo (isset($_GET['page']) && $_GET['page'] === 'classes_prof') ? 'bg-[#E9E3FF]' : ''; ?>">
                    <img src="../../assets/icons/classes.svg" alt="classes" class="w-5 h-5">
                    <span class="text-left invisible lg:visible">Courses</span>
                </a>
            </div>

            <div class="mt-auto mb-5">
                <a href="panel_prof.php?page=settings_prof" id="settings-link" class="flex items-center m-2 space-x-5 p-1 rounded-lg hover:bg-gray-100 focus:bg-[#E9E3FF]
                <?php echo (isset($_GET['page']) && $_GET['page'] === 'settings_prof') ? 'bg-[#E9E3FF]' : ''; ?>">
                    <img src="../../assets/icons/settings.svg" alt="audit trail" class="w-5 h-5">
                    <span class="text-left invisible lg:visible">Settings</span>
                </a>
            </div>
        </div>

        <div class="w-full">
            <?php
            $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard_prof_testing';
            $allowedpages = [
                'dashboard_prof_testing' => "../pages/dashboard-prof/dashboard_prof_testing.php",
                'classes_prof' => "../pages/classes-prof/classes_prof.php",
                'settings_prof' => "settings_prof.php"
            ];
            if (array_key_exists($page, $allowedpages)) {
                include $allowedpages[$page]; // Include the correct page
            } else {
                include "settings_prof.php"; // Default to settings page
            }
            ?>
        </div>

        <script src="../../JavaScript/class-prof/class_prof.js"></script>
        <script src="../../JavaScript/dashboard-prof/dashboard-prof.js"></script>
</body>

</html>