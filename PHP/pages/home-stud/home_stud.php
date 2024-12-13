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

// Fetch user details including profile picture
$sql_user = "SELECT fname, lname, profile_pic FROM tbl_student WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
if ($stmt_user) {
    $stmt_user->bind_param("i", $userId);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    if ($result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc();
        $userName = htmlspecialchars($user['fname']) . " " . htmlspecialchars($user['lname']);
        $profilePic = $user['profile_pic'] ? base64_encode($user['profile_pic']) : null;
    } else {
        $userName = "Unknown User";
        $profilePic = null;
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

<body class="font-poppins bg-gray-50">

    <div class="flex flex-col lg:flex-row h-full justify-between p-5 space-y-6 lg:space-y-0">

        <!-- Courses Section -->
        <div class="w-full sm:w-8/12 p-3 bg-white rounded-xl shadow-md">
            <h1 class="text-3xl font-semibold mb-5 text-gray-800">Available Courses</h1>

            <?php if (count($courses) > 0): ?>
                <?php foreach ($courses as $course): ?>
                    <div class="w-full bg-[#F8F8F8] p-6 rounded-lg flex justify-between items-center shadow-lg hover:shadow-xl hover:shadow-[#8A70D6] transition-all duration-200 mb-6">
                        <span class="font-semibold text-gray-700"><?php echo htmlspecialchars($course['course']); ?></span>
                        <button class="navigate-to-classes w-10 h-10 bg-indigo-600 rounded-full flex justify-center items-center text-white hover:bg-indigo-700"
                            data-course-id="<?php echo $course['id']; ?>" data-course-name="<?php echo htmlspecialchars($course['course']); ?>">
                            <img src="../../assets/icons/Path.svg" alt="Action Icon" class="w-4 h-4">
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500">No courses found.</p>
            <?php endif; ?>
        </div>

        <!-- Profile Section -->
        <div class="w-full sm:w-1/3 lg:w-1/4 mt-5 lg:mt-0 flex flex-col items-center" id="profile-area">
            <h1 class="text-xl font-semibold text-gray-800 mb-4">Profile</h1>
            <div class="w-full bg-[#F8F8F8] p-6 rounded-xl shadow-lg flex flex-col items-center">

                <!-- Edit Button -->
                <button class="bg-white rounded-full w-8 h-8 flex justify-center items-center mb-5 shadow-md hover:shadow-lg transition">
                    <img src="../../assets/icons/edit.svg" alt="Edit" class="w-4 h-4">
                </button>

                <!-- Profile Picture -->
                <?php if ($profilePic): ?>
                    <img class="rounded-full w-24 h-24 object-cover mb-3" src="data:image/jpeg;base64,<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Picture">
                <?php else: ?>
                    <img src="../../assets/images/no-dp.jpg" alt="Default Profile Picture" class="rounded-full w-20 h-20 object-cover mb-3">
                <?php endif; ?>

                <span class="font-semibold text-gray-800"><?php echo $userName; ?></span>
                <span class="text-sm text-gray-600">Student</span>
            </div>
        </div>

    </div>

    <script>
    document.querySelectorAll('.navigate-to-classes').forEach(button => {
        button.addEventListener('click', function() {
            // Get the course name from the button's data attribute
            var courseName = this.getAttribute('data-course-name');

            // Log the course name to check if it is being correctly passed
            console.log(courseName);

            // Redirect to panel_stud.php with page parameter and course name
            window.location.href = "panel_stud.php?page=classes_stud&course=" + encodeURIComponent(courseName);
        });
    });
</script>


    <script src="https://cdn.tailwindcss.com"></script>

</body>