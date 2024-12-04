<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: ../PHP/login/login.php");
    exit;
}
require_once '../../PHP/services/config/db_connection.php';
$total_classes = 0;
$professor_fname = "Professor";
$professor_lname = "Name";

try {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT COUNT(*) AS total_classes FROM class_prof WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total_classes = $row['total_classes'] ?? 0;
    } else {
        error_log("Query execution failed for total classes: " . $stmt->error);
    }
    $stmt = $conn->prepare("SELECT fname, lname FROM tbl_prof WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $professor_fname = $row['fname'] ?? "Professor";
            $professor_lname = $row['lname'] ?? "Name";
        } else {
            error_log("No result found for user_id: " . $user_id);
        }
    } else {
        error_log("Query execution failed for professor's name: " . $stmt->error);
    }
} catch (Exception $e) {
    error_log("Error fetching data: " . $e->getMessage());
}
error_log("User ID: " . $user_id);
error_log("Total Classes: " . $total_classes);
error_log("Professor First Name: " . $professor_fname);
error_log("Professor Last Name: " . $professor_lname);
?>



<div class="flex">

    <div class="mt-5 mb-10 ml-5 w-full sm:w-8/12" id="content-area">
        <div class="flex flex-col">
            <h1 class="font-bold text-4xl text-[#424040]">
                DASHBOARD
            </h1>
            <h1 class="font-[500] text-2xl text-[#424040] mb-10">
                Welcome, <?php echo htmlspecialchars($professor_fname); ?>!
            </h1>
            <div class="flex justify-between">
                <div class="bg-[#C2B5E8] text-[#424040] w-60 h-60 flex flex-col justify-center
                        items-center rounded-lg shadow-[0_4px_6px_rgba(0,0,0,0.2)]">
                    <span class="flex justify-center items-center">
                        <img src="../../assets/icons/papers.svg" alt="" srcset="" class="w-20 h-20">
                        <span class="text-5xl font-[500]"><?php echo htmlspecialchars($total_classes); ?></span>
                    </span><br>
                    <span class="font-[500]">Total Courses</span>
                    <button id="navigate-to-classes">
                        <span class="w-10 h-10 bg-[#8A70D6] rounded-xl flex justify-center items-center mt-2">
                            <img src="../../assets/icons/Path.svg" alt="" srcset="">
                        </span>
                    </button>
                </div>
                <div class="bg-[#FFEAA4] text-[#424040] w-60 h-60 flex flex-col justify-center
                        items-center rounded-lg shadow-[0_4px_6px_rgba(0,0,0,0.2)] ml-5">
                    <span class="flex justify-center items-center">
                        <img src="../../assets/icons/Profile.svg" alt="" srcset="" class="w-20 h-20">
                        <span class="text-5xl font-[500]">0</span>
                    </span><br>
                    <span class="font-[500]">Total Students</span>
                    <button>
                        <span class="w-10 h-10 bg-[#8A70D6] rounded-xl flex justify-center items-center mt-2">
                            <img src="../../assets/icons/Path.svg" alt="" srcset="">
                        </span>
                    </button>
                </div>
                <div class="bg-[#FFC3E5] text-[#424040] w-60 h-60 flex flex-col justify-center
                        items-center rounded-lg shadow-[0_4px_6px_rgba(0,0,0,0.2)] ml-5">
                    <span class="flex justify-center items-center">
                        <img src="../../assets/icons/Document.svg" alt="" srcset="" class="w-20 h-20">
                        <span class="text-5xl font-[500]">0</span>
                    </span><br>
                    <span class="font-[500]">Archived Classes</span>
                    <button>
                        <span class="w-10 h-10 bg-[#8A70D6] rounded-xl flex justify-center items-center mt-2">
                            <img src="../../assets/icons/Path.svg" alt="" srcset="">
                        </span>
                    </button>
                </div>
            </div>
            <div class="flex flex-col">
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

    <div class="mt-5 w-full ml-5 sm:w-1/3 flex flex-col" id="profile-area">
        <h1 class="font-bold">
            Profile
        </h1>
        <div class="w-full h-1/2 bg-[#EFEFEF] flex flex-col items-center shadow-lg rounded-xl mb-3">
            <button class="bg-white rounded-xl w-8 h-8 flex justify-center items-center mb-5 shadow-xl mt-4 ml-auto mr-5">
                <img src="../../assets/icons/edit.svg" alt="" srcset="" class="w-4 h-4">
            </button>
            <img src="../../assets/images/icon.png" alt="" class="w-16 h-16 mb-4">
            <span class="font-poppins font-bold">
                <?php echo htmlspecialchars($professor_fname . ' ' . $professor_lname); ?>
            </span>
            <span class="text-sm">
                Instructor
            </span>
        </div>
        <div class="w-full h-1/2 bg-[#EFEFEF] rounded-xl shadow-lg flex flex-col items-center">
            <span class="mt-10 font-[500]">
                Audit Trail
            </span>
        </div>
    </div>
</div>