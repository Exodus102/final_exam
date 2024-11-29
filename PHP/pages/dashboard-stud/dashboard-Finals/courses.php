<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/output.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Learning MS</title>
</head>

<body class="font-poppins p-5">
    <div class="flex h-screen">

        <div class="flex flex-col w-1/6 border-[3px] border-[#E9E3FF] rounded-xl mb-10 shadow-xl
            pl-5 pr-5">
            <div class="flex mb-10 mt-10">
                <h1 class="font-bold">
                    LMS
                </h1>
            </div>

            <div class="flex flex-col text-[#424040]">
                <a href="#" class="flex items-center m-2 space-x-5 p-1 rounded-lg hover:bg-gray-100 focus:bg-[#E9E3FF]">
                    <img src="icons/classes.svg" alt="classes" class="w-5 h-5">
                    <span class="text-left">Courses</span>
                </a>
                <a href="#" class="flex items-center m-2 space-x-5 p-1 rounded-lg hover:bg-gray-100 focus:bg-[#E9E3FF]">
                    <img src="icons/notifications.svg" alt="notifications" class="w-5 h-5">
                    <span class="text-left">Notifications</span>
                </a>
                <a href="#" class="flex items-center m-2 space-x-5 p-1 rounded-lg hover:bg-gray-100 focus:bg-[#E9E3FF]">
                    <img src="icons\archived.svg" alt="archived classes" class="w-5 h-5">
                    <span class="text-left">Archived Classes</span>
                </a>
                <a href="#" class="flex items-center m-2 space-x-5 p-1 rounded-lg hover:bg-gray-100 focus:bg-[#E9E3FF]">
                    <img src="icons/reports.svg" alt="reports and certificates" class="w-5 h-5">
                    <span class="text-left">Reports and Certificate</span>
                </a>
            </div>

            <div class="mt-auto mb-5 ">
                <a href="" class="flex items-center m-2 space-x-5">
                    <img src="icons/settings.svg" alt="audit trail" class="w-5 h-5">
                    <span class="text-left">Settings</span>
                </a>
            </div>
        </div>

        <div class="m-5 w-3/5">
            <div class="flex flex-col gap-5">
                <h1 class="font-bold text-4xl text-[#424040]">
                    COURSES
                </h1>
                <div class="flex flex-col bg-[#F3F3F3] h-max rounded-md p-5" id="course-info">
                    <h1 class="text-center text-4xl">Class Code: 12345</h1>
                </div>
                <div class="flex">
                    <div class="flex flex-col bg-[#F3F3F3] h-max rounded-md p-5 gap-6 w-1/4">
                        <h1>Instructor:</h1>
                        <div class="flex justify-start gap-2">
                            <img src="img/nijika.jpg" alt="" class="h-10 w-10">
                            <h1 class="text-sky">Jayson Daluyon</h1>
                        </div>
                        <div class="flex flex-col">
                            <h1>Class Members:</h1>
                            <h1>Reanne C. Mendoza</h1>

                        </div>
                    </div>
                    <div class="flex flex-col gap-5">
                        <div class="flex p-5 ml-2 rounded-md gap-4 bg-[#F3F3F3]">
                            <textarea name="post" id="post" rows="3" cols="80" placeholder="Post Something..." class="resize-none h-40 p-3 border border-black rounded-sm"></textarea>
                            <button class=" bg-slate-900 p-10 rounded-md h-24">
                                <h1 class="text-white">Post</h1>
                            </button>
                        </div>
                        <div class="flex p-5 ml-2 rounded-md gap-4 bg-[#F3F3F3]">
                            <img src="icons/docs.svg" alt="">
                            <div class="flex flex-col">
                                <h1>Jayson Posted a new assignment: Finals Quiz One</h1>
                                <h1 class="font-thin">9:23am</h1>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="mt-5 w-1/4">
            <h1 class="font-bold">
                Profile
            </h1>
            <div class="w-full h-1/2 bg-[#EFEFEF] flex flex-col items-center shadow-xl rounded-xl">
                <button class="bg-white rounded-xl w-8 h-8 flex justify-center items-center mb-5 shadow-xl mt-4 ml-auto mr-5">
                    <img src="icons/edit.svg" alt="" srcset="" class="w-4 h-4">
                </button>
                <img src="img/nijika.jpg" alt="" class="w-16 h-16 mb-4 rounded-full">
                <span>
                    Reanne C. Mendoza
                </span>
                <span class="text-sm">
                    Student
                </span>
            </div>
        </div>
    </div>

    <div id="modal_container" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 invisible">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
            <form action="frontEnd.php" method="POST" id="joinForm">
                <p class="text-xl font-bold text-center mb-4">Join Class</p>
                <div class="mb-4 border-b pb-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">Enter Class Code</p>
                    <input
                        type="text"
                        placeholder="Class Code"
                        id="join_field"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    <span
                        id="error_message"
                        class="text-red-500 text-sm mt-2 hidden"></span>
                </div>
                <div class="flex justify-end gap-4">
                    <button
                        type="button"
                        id="cancel"
                        class="px-4 py-2 text-sm font-medium text-gray-700 border rounded-lg hover:bg-gray-100 transition">
                        Cancel
                    </button>
                    <input
                        type="submit"
                        value="Join"
                        id="join"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition cursor-pointer" />
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.tailwindcss.com">
    </script>





</body>

</html>