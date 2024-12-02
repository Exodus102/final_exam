<?php
include 'retrieve.php';
include '../../services/config/db_connection.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

$student_fname = "Student";
$student_lname = "Name";
$student_profile_pic = null;
$student_email = "Email";

try {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT fname, lname, profile_pic, email FROM tbl_student WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $student_fname = $row['fname'] ?? "Student";
            $student_lname = $row['lname'] ?? "Name";
            $student_email = $row['email'] ?? "Email";
            $student_profile_pic = $row['profile_pic']  ? base64_encode($row['profile_pic']) : null;
        } else {
            error_log("No result found for user_id: " . $user_id);
        }
    } else {
        error_log("Query execution failed for student's name: " . $stmt->error);
    }
} catch (Exception $e) {
    error_log("Error fetching data: " . $e->getMessage());
}
error_log("User ID: " . $user_id);
error_log("Student First Name: " . $student_fname);
error_log("Student Last Name: " . $student_lname);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/output.css">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap"
        rel="stylesheet" />
    <title>Learning MS</title>
    <title>Document</title>
</head>

<body>
    <div class="font-poppins p-5 bg-[#F3F3F3]">
        <div class="flex h-screen">
            <div class="flex flex-col w-1/6 border-[3px] border-[#E9E3FF] rounded-xl mb-10 shadow-xl
            pl-5 pr-5 bg-white">
                <div class="flex mb-10 mt-10">
                    <h1 class="font-bold">
                        LMS
                    </h1>
                </div>

                <div class="flex flex-col text-[#424040]">
                    <a href="frontEnd.php" class="flex items-center m-2 space-x-5 p-1 rounded-lg hover:bg-gray-100 focus:bg-[#E9E3FF]">
                        <img src="icons/classes.svg" alt="classes" class="w-5 h-5">
                        <span class="text-left">Courses</span>
                    </a>
                </div>

                <div class="mt-auto mb-5 ">
                    <a href="settings.php" class="flex items-center m-2 space-x-5">
                        <img src="icons/settings.svg" alt="audit trail" class="w-5 h-5">
                        <span class="text-left">Settings</span>
                    </a>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="m-5">
                    <span class="font-bold text-4xl text-[#424040]">Settings</span>
                </div>

                <div class="m-5 p-10 bg-white rounded-lg shadow-lg mb-20 h-full w-full">
                    <?php if (isset($_GET['success'])): ?>
                        <div class="text-green-600">Profile updated successfully!</div>
                    <?php elseif (isset($_GET['error'])): ?>
                        <div class="text-red-600">Failed to update profile. Please try again.</div>
                    <?php endif; ?>
                    <span class="font-bold text-2xl text-[#424040]">Profile Management</span>
                    <div class="mt-20 flex" style="height: 80%;">
                        <div class="flex flex-col gap-5 mr-28">
                            <img src="<?php echo $student_profile_pic ? 'data:image/jpeg;base64,' . htmlspecialchars($student_profile_pic) : 'img/default-profile.jpg'; ?>" alt="" class="w-32 h-32 rounded-full">
                            <div class="flex flex-col leading-5">
                                <span class="text-3xl truncate"><?php echo $student_fname . " " . $student_lname ?></span>
                                <span class="text-1lg truncate"><?php echo $student_email ?></span>
                            </div>
                            <div class="flex flex-col leading-3">
                                <span class="text-lg truncate font-bold">Personal Info</span>
                                <span class="text-sm truncate font-thin">You can change you personal information settings here.</span>
                            </div>
                            <div class="flex-grow"></div>
                            <a class="text-left self-start bg-white p-3 rounded-md shadow-lg" href="logout.php">Logout</a>
                        </div>

                        <div class="bg-[#E9E3FF] px-10 py-5 pt-10 rounded-md shadow-lg flex flex-col w-full">
                            <form action="update_profile.php" method="POST" class="flex gap-5 flex-wrap">
                                <div class="gap-5 mb-20 flex flex-wrap">
                                    <span class="text-2xl">First Name: </span>
                                    <input type="text" id="fname" name="fname" placeholder="<?php echo htmlspecialchars($student_fname) ?>" class="text-lg bg-white py-1 px-5 rounded-md w-48 shadow-lg flex justify-between"></span>
                                    <span class="text-2xl">Last Name: </span>
                                    <input type="text" id="lname" name="lname" placeholder="<?php echo htmlspecialchars($student_lname) ?>" class="text-lg bg-white py-1 px-5 rounded-md w-48 shadow-lg flex justify-between"></span>
                                    <input type="submit" class="bg-white p-4 rounded-lg shadow-2xl cursor-pointer ml-5">
                                </div>
                            </form>
                            <form action="update_profile_pic.php" method="POST" enctype="multipart/form-data">
                                <div class="flex">
                                    <div class="flex flex-col gap-4">
                                        <span class="text-2xl truncate font-bold">Change Avatar</span>
                                        <img src="<?php echo $student_profile_pic ? 'data:image/jpeg;base64,' . htmlspecialchars($student_profile_pic) : 'img/default-profile.jpg'; ?>" alt="" class="w-32 h-32 rounded-full">
                                    </div>
                                    <input type="file" id="profile_pic" name="profile_pic" accept="image/*" class="self-center">
                                    <button type="submit" class="bg-white self-center p-3 rounded-md shadow-lg" >Upload File</button>
                                </div>
                            </form>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.tailwindcss.com"></script>
</body>

</html>