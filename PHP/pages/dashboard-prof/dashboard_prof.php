<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../Tailwind/css/tailwind.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Leanring MS</title>
</head>

<body class="font-poppins p-5">
    <div class="flex h-screen">

        <div class="flex flex-col w-1/5 border-[3px] border-[#E9E3FF] rounded-xl mb-10 shadow-xl
            pl-5 pr-5">
            <div class="flex mb-10 mt-10 justify-start items-center">
                <img src="../../../assets/logo/logoq.svg" alt="" width="60" height="60">
                <h1 class="font-[500] ml-2 text-2xl">
                    LMS
                </h1>
            </div>

            <div class="flex flex-col text-[#424040]">
                <a href="#" class="flex items-center m-2 space-x-5 p-1 rounded-lg hover:bg-gray-100 active:bg-[#E9E3FF] focus:bg-[#E9E3FF]">
                    <img src="../../../assets/icons/dashboard.svg" alt="dashboard" class="w-5 h-5">
                    <span class="text-left">Dashboard</span>
                </a>
                <a href="#" id="classes-link" class="flex items-center m-2 space-x-5 p-1 rounded-lg hover:bg-gray-100 focus:bg-[#E9E3FF]">
                    <img src="../../../assets/icons/classes.svg" alt="classes" class="w-5 h-5">
                    <span class="text-left">Classes</span>
                </a>
                <a href="#" class="flex items-center m-2 space-x-5 p-1 rounded-lg hover:bg-gray-100 focus:bg-[#E9E3FF]">
                    <img src="../../../assets/icons/notifications.svg" alt="notifications" class="w-5 h-5">
                    <span class="text-left">Notifications</span>
                </a>
                <a href="#" class="flex items-center m-2 space-x-5 p-1 rounded-lg hover:bg-gray-100 focus:bg-[#E9E3FF]">
                    <img src="../../../assets/icons/archived.svg" alt="archived classes" class="w-5 h-5">
                    <span class="text-left">Archived Classes</span>
                </a>
                <a href="#" class="flex items-center m-2 space-x-5 p-1 rounded-lg hover:bg-gray-100 focus:bg-[#E9E3FF]">
                    <img src="../../../assets/icons/reports.svg" alt="reports and certificates" class="w-5 h-5">
                    <span class="text-left">Reports & Certificate</span>
                </a>
                <a href="#" class="flex items-center m-2 space-x-5 p-1 rounded-lg hover:bg-gray-100 focus:bg-[#E9E3FF]">
                    <img src="../../../assets/icons/audit.svg" alt="audit trail" class="w-5 h-5">
                    <span class="text-left">Audit Trail</span>
                </a>
            </div>

            <div class="mt-auto mb-5">
                <a href="#" id="settings-link" class="flex items-center m-2 space-x-5 p-1 rounded-lg hover:bg-gray-100 focus:bg-[#E9E3FF]">
                    <img src="../../../assets/icons/settings.svg" alt="audit trail" class="w-5 h-5">
                    <span class="text-left">Settings</span>
                </a>
            </div>
        </div>

        <div class="mt-5 mb-10 ml-10 w-3/5" id="content-area">
            <div class="flex flex-col">
                <h1 class="font-bold text-4xl text-[#424040]">
                    DASHBOARD
                </h1>
                <h1 class="font-[500] text-2xl text-[#424040] mb-10">
                    Welcome, Professor!
                </h1>
                <div class="flex">
                    <div class="bg-[#C2B5E8] text-[#424040] w-60 h-60 flex flex-col justify-center
                        items-center rounded-lg shadow-[0_4px_6px_rgba(0,0,0,0.2)]">
                        <span class="flex justify-center items-center">
                            <img src="../../../assets/icons/papers.svg" alt="" srcset="" class="w-20 h-20">
                            <span class="text-5xl font-[500]">6</span>
                        </span><br>
                        <span class="font-[500]">Total Classes</span>
                        <button>
                            <span class="w-10 h-10 bg-[#8A70D6] rounded-xl flex justify-center items-center mt-2">
                                <img src="../../../assets/icons/Path.svg" alt="" srcset="">
                            </span>
                        </button>
                    </div>
                    <div class="bg-[#FFEAA4] text-[#424040] w-60 h-60 flex flex-col justify-center
                        items-center rounded-lg shadow-[0_4px_6px_rgba(0,0,0,0.2)] ml-5">
                        <span class="flex justify-center items-center">
                            <img src="../../../assets/icons/Profile.svg" alt="" srcset="" class="w-20 h-20">
                            <span class="text-5xl font-[500]">169</span>
                        </span><br>
                        <span class="font-[500]">Total Students</span>
                        <button>
                            <span class="w-10 h-10 bg-[#8A70D6] rounded-xl flex justify-center items-center mt-2">
                                <img src="../../../assets/icons/Path.svg" alt="" srcset="">
                            </span>
                        </button>
                    </div>
                    <div class="bg-[#FFC3E5] text-[#424040] w-60 h-60 flex flex-col justify-center
                        items-center rounded-lg shadow-[0_4px_6px_rgba(0,0,0,0.2)] ml-5">
                        <span class="flex justify-center items-center">
                            <img src="../../../assets/icons/Document.svg" alt="" srcset="" class="w-20 h-20">
                            <span class="text-5xl font-[500]">2</span>
                        </span><br>
                        <span class="font-[500]">Archived Classes</span>
                        <button>
                            <span class="w-10 h-10 bg-[#8A70D6] rounded-xl flex justify-center items-center mt-2">
                                <img src="../../../assets/icons/Path.svg" alt="" srcset="">
                            </span>
                        </button>
                    </div>
                </div>
                <div class="flex flex-col mr-14">
                    <span class="w-full h-28 bg-[#E9E3FF] rounded-lg mt-5 shadow-[0_4px_6px_rgba(0,0,0,0.2)]
                        p-5">
                        <span class="font-[500]">
                            Recent Activities
                        </span>
                    </span>
                    <span class="w-full h-28 bg-[#E9E3FF] rounded-lg mt-4 shadow-[0_4px_6px_rgba(0,0,0,0.2)]
                        p-5">
                        <span class="font-[500]">
                            Upcoming Events
                        </span>
                    </span>
                    <span class="w-full h-28 bg-[#E9E3FF] rounded-lg mt-4 shadow-[0_4px_6px_rgba(0,0,0,0.2)]
                        p-5">
                        <span class="font-[500]">
                            Class Performance Overview
                        </span>
                    </span>
                </div>
            </div>
        </div>

        <div class="mt-5 w-1/4 flex flex-col" id="profile-area">
            <h1 class="font-bold">
                Profile
            </h1>
            <div class="w-full h-1/2 bg-[#EFEFEF] flex flex-col items-center shadow-lg rounded-xl mb-3">
                <button class="bg-white rounded-xl w-8 h-8 flex justify-center items-center mb-5 shadow-xl mt-4 ml-auto mr-5">
                    <img src="../../../assets/icons/edit.svg" alt="" srcset="" class="w-4 h-4">
                </button>
                <img src="../../../assets/images/icon.png" alt="" class="w-16 h-16 mb-4">
                <span>
                    Jayson A. Daluyon
                </span>
                <span class="text-sm">
                    Professor
                </span>
            </div>
            <div class="w-full h-1/2 bg-[#EFEFEF] rounded-xl shadow-lg flex flex-col items-center">
                <span class="mt-10 font-[500]">
                    Audit Trail
                </span>
            </div>
        </div>
    </div>
</body>

</html>