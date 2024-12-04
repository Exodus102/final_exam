<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: ../PHP/login/login.php");
    exit;
}
require_once '../../PHP/services/config/db_connection.php';
$userId = $_SESSION['user_id'];
$sql_user = "SELECT fname, lname FROM tbl_prof WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
if ($stmt_user) {
    $stmt_user->bind_param("i", $userId);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    if ($result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc();
        $userName = htmlspecialchars($user['fname']) . " " . htmlspecialchars($user['lname']);
    } else {
        $userName = "Unknown User";
    }
    $stmt_user->close();
} else {
    die("Error preparing SQL statement for user: " . $conn->error);
}

// Fetch courses
$sql_courses = "SELECT id, course FROM tbl_courses";
$result_courses = $conn->query($sql_courses);

if (!$result_courses) {
    die("Error fetching courses: " . $conn->error);
}

// Store courses in an array
$courses = [];
while ($row = $result_courses->fetch_assoc()) {
    $courses[] = $row;
}

$conn->close();
?>
<div class="flex h-full">
    <div class="w-full sm:w-8/12 p-3">
        <div class="flex flex-col gap-5">
            <h1 class="text-2xl font-bold mb-3">Courses</h1>
            <?php if (count($courses) > 0): ?>
                <?php foreach ($courses as $course): ?>
                    <div class="w-full bg-[#EFEFEF] p-5 rounded flex justify-between items-center shadow-md transition transform hover:shadow-violet-500 hover:scale-105">
                        <span class="font-[500]"><?php echo htmlspecialchars($course['course']); ?></span>
                        <button onclick="navigateToClasses()">
                            <span class="w-10 h-10 bg-[#8A70D6] rounded-xl flex justify-center items-center">
                                <img src="../../assets/icons/Path.svg" alt="Action Icon">
                            </span>
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500">No courses found.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="invisible lg:visible mt-5 w-full ml-5 sm:w-1/3 flex flex-col" id="profile-area">
        <h1 class="font-bold">Profile</h1>
        <div class="w-full h-1/2 bg-[#EFEFEF] flex flex-col items-center shadow-lg rounded-xl mb-3">
            <button class="bg-white rounded-xl w-8 h-8 flex justify-center items-center mb-5 shadow-xl mt-4 ml-auto mr-5">
                <img src="../../assets/icons/edit.svg" alt="Edit" class="w-4 h-4">
            </button>
            <span class="font-bold">
                <?php echo $userName; ?>
            </span>
            <span class="text-sm">
                Instructor
            </span>
        </div>
        <div class="w-full h-1/2 bg-[#EFEFEF] rounded-xl shadow-lg flex flex-col items-center">
            <span class="mt-10 font-[500]">Audit Trail</span>
        </div>
    </div>
</div>
<script>
    function navigateToClasses() {
        window.location.href = "../../PHP/pages/classes-prof/classes.php";
    }
</script>