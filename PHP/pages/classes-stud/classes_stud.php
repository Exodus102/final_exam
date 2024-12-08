<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: ../PHP/login/loginv2.php");
    exit;
}

include 'C:/xampp/htdocs/final_exam-main/PHP/services/config/db_connection.php';

// Get the course from the query parameter
if (isset($_GET['course'])) {
    $course = $_GET['course'];
} else {
    die("Course not specified.");
}

// Fetch professors for the specific course
$sql_professors = "SELECT DISTINCT prof_id, prof_fname, prof_lname, prof_email 
                   FROM instructor_courses 
                   WHERE course_name = ?";
$stmt = $conn->prepare($sql_professors);
$stmt->bind_param("s", $course); // "s" stands for string type
$stmt->execute();
$result_professors = $stmt->get_result();
$professors = [];

if ($result_professors && $result_professors->num_rows > 0) {
    while ($row = $result_professors->fetch_assoc()) {
        $professors[] = [
            'prof_id' => $row['prof_id'],
            'prof_fname' => htmlspecialchars($row['prof_fname']),
            'prof_lname' => htmlspecialchars($row['prof_lname']),
            'prof_email' => htmlspecialchars($row['prof_email']),
        ];
    }
} else {
    $professors = []; // No professors found
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professors for <?php echo htmlspecialchars($course); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-poppins">
    <div class="flex items-center justify-center h-screen p-5 bg-gray-100">
        <div class="w-full sm:w-8/12 p-3">
            <div class="flex flex-col gap-5 items-center">
                <h1 class="text-2xl font-bold mb-3 text-center">Instructors for <?php echo htmlspecialchars($course); ?></h1>
                <div class="flex flex-wrap gap-5 justify-center">
                    <?php
                    if (count($professors) > 0) {
                        foreach ($professors as $prof) {
                            echo "
                            <div class='bg-[#E8F1FF] text-[#424040] w-60 h-60 flex flex-col justify-center items-center rounded-lg 
                            shadow-[0_4px_6px_rgba(0,0,0,0.2)] transition transform hover:shadow-blue-500 hover:scale-105'>
                                <span class='font-bold text-xl'>{$prof['prof_fname']} {$prof['prof_lname']}</span>
                                <span class='text-sm text-gray-600'>{$prof['prof_email']}</span>
                                <button class='navigate-to-professor mt-3' data-prof-id='{$prof['prof_id']}'>
                                    <span class='w-10 h-10 bg-[#4285F4] text-white rounded-full flex justify-center items-center'>→</span>
                                </button>
                            </div>";
                        }
                    } else {
                        // Display "No professors found" message with enhanced design
                        echo "
                        <div class='w-full bg-yellow-200 p-6 rounded-lg shadow-lg flex justify-center items-center'>
                            <p class='text-xl font-bold text-yellow-800 text-center'>No professors found for this course.</p>
                        </div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.navigate-to-professor').forEach(button => {
            button.addEventListener('click', function() {
                var profId = this.getAttribute('data-prof-id');
                var courseName = "<?php echo $course; ?>"; // Pass the current course
                window.location.href = "../../pages/classes-stud/professor-details.php?prof_id=" + encodeURIComponent(profId) + "&course_name=" + encodeURIComponent(courseName);
            });
        });
    </script>
</body>

</html>