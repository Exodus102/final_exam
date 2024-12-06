<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Tailwind/css/tailwind.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Learning MS</title>
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
                <a href="panel_stud.php?page=home_panel_stud" class="flex items-center m-2 space-x-5 p-1 rounded-lg hover:bg-gray-100 active:bg-[#E9E3FF] focus:bg-[#E9E3FF]
                <?php echo (isset($_GET['page']) && $_GET['page'] === 'home_panel_stud') ? 'bg-[#E9E3FF]' : ''; ?>">
                    <img src="../../assets/icons/dashboard.svg" alt="dashboard" class="w-5 h-5">
                    <span class="text-left invisible lg:visible">Home</span>
                </a>
                <a href="panel_stud.php?page=classes_stud" class="flex items-center m-2 space-x-5 p-1 rounded-lg hover:bg-gray-100 focus:bg-[#E9E3FF]
                <?php echo (isset($_GET['page']) && $_GET['page'] === 'classes_stud') ? 'bg-[#E9E3FF]' : ''; ?>">
                    <img src="../../assets/icons/classes.svg" alt="classes" class="w-5 h-5">
                    <span class="text-left invisible lg:visible">Classes</span>
                </a>
            </div>

            <div class="mt-auto mb-5">
                <a href="panel_stud.php?page=settings_stud" id="settings-link" class="flex items-center m-2 space-x-5 p-1 rounded-lg hover:bg-gray-100 focus:bg-[#E9E3FF]
                <?php echo (isset($_GET['page']) && $_GET['page'] === 'settings_stud') ? 'bg-[#E9E3FF]' : ''; ?>">
                    <img src="../../assets/icons/settings.svg" alt="audit trail" class="w-5 h-5">
                    <span class="text-left invisible lg:visible">Settings</span>
                </a>
            </div>
        </div>

        <div class="w-full">
            <?php
            $page = isset($_GET['page']) ? $_GET['page'] : 'home_panel_stud';
            $allowedpages = [
                'home_panel_stud' => "../pages/home-stud/home_stud.php",
                'classes_stud' => "../pages/classes-stud/classes_stud.php",
                'settings_stud' => "../pages/settings/settings_stud.php"
            ];
            if (array_key_exists($page, $allowedpages)) {
                include $allowedpages[$page];
            } else {
                echo "<h1 class='text-2xl font-bold'>Page Not Found</h1>";
            }
            ?>
        </div>
    </div>

    <script src="../../JavaScript/class-prof/class_prof.js"></script>
    <script src="../../JavaScript/dashboard-prof/dashboard-prof.js"></script>
</body>

</html>

</html>