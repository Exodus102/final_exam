<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: ../PHP/login/loginv2.php");
    exit;
}
include 'C:/xampp/htdocs/final_exam-main/PHP/services/config/db_connection.php';
$userId = $_SESSION['user_id'];

// Fetch the courses assigned to the current instructor
$sql_courses = "SELECT course_name FROM instructor_courses WHERE prof_id = ?";
$stmt_courses = $conn->prepare($sql_courses);
if ($stmt_courses) {
    $stmt_courses->bind_param("i", $userId);
    $stmt_courses->execute();
    $result_courses = $stmt_courses->get_result();
    $courses = [];
    while ($row = $result_courses->fetch_assoc()) {
        $courses[] = htmlspecialchars($row['course_name']);
    }
    $stmt_courses->close();
} else {
    die("Error preparing SQL statement for courses: " . $conn->error);
}

$conn->close();
?>

<div class="flex h-full p-5">
    <div class="w-full sm:w-8/12 p-3">
        <div class="flex flex-col gap-5">
            <h1 class="text-2xl font-bold mb-3">Courses</h1>

            <div class="flex flex-wrap gap-5">
                <?php
                $colors = ['#C2B5E8', '#FFEAA4', '#FFC3E5']; // Array with your desired colors
                if (count($courses) > 0) {
                    foreach ($courses as $course) {
                        // Select a random color from the array
                        $selectedColor = $colors[array_rand($colors)];
                        echo "
                        <div class='bg-[$selectedColor] text-[#424040] w-60 h-60 flex flex-col justify-center items-center rounded-lg 
                        shadow-[0_4px_6px_rgba(0,0,0,0.2)] transition transform hover:shadow-violet-500 hover:scale-105'>
                            <span class='flex justify-center items-center'>
                                <img src='../../assets/icons/papers.svg' alt='' class='w-20 h-20'>
                            </span><br>
                            <span class='font-[500]'>$course</span>
                            <button class='navigate-to-classes' data-course-name='$course'>
                                <span class='w-10 h-10 bg-[#8A70D6] rounded-xl flex justify-center items-center mt-2'>
                                    <img src='../../assets/icons/Path.svg' alt=''>
                                </span>
                            </button>
                        </div>";
                    }
                } else {
                    echo "<p>No courses assigned to you yet.</p>";
                }
                ?>
            </div>

        </div>
    </div>
</div>

<script>
    // Attach event listener to all course navigation buttons
    document.querySelectorAll('.navigate-to-classes').forEach(button => {
        button.addEventListener('click', function() {
            // Get the course name from the button's data attribute
            var courseName = this.getAttribute('data-course-name');
            // Redirect to classes.php and pass the course name via query string
            window.location.href = "../../PHP/pages/classes-prof/classes.php?course=" + encodeURIComponent(courseName);
        });
    });
</script>