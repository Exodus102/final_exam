<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Increase memory limit
ini_set('memory_limit', '256M');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['prof_id']) || !isset($_GET['course_name'])) {
    die("Professor or course not specified.");
}

$profId = intval($_GET['prof_id']);
$courseName = $_GET['course_name']; // Get the course name from the URL
include 'C:/xampp/htdocs/final_exam-main/PHP/services/config/db_connection.php';

// Fetch professor details (name)
$sql_professor = "SELECT prof_fname, prof_lname FROM instructor_courses WHERE prof_id = ?";
$stmt_professor = $conn->prepare($sql_professor);
if ($stmt_professor) {
    $stmt_professor->bind_param("i", $profId);
    $stmt_professor->execute();
    $result_professor = $stmt_professor->get_result();
    $professor = $result_professor->fetch_assoc();
    $stmt_professor->close();
} else {
    die("Error preparing professor query: " . $conn->error);
}


// Pagination setup
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$page = max($page, 1); // Ensure the page is at least 1
$itemsPerPage = 10; // Show 10 lessons per page
$offset = ($page - 1) * $itemsPerPage;

// Fetch lessons with pagination
$sql_contents = "
    SELECT cc.id, cc.lesson, cc.contents, cc.files, cc.file_name, cc.file_type, cc.file_size, ic.course_name 
    FROM course_content cc
    LEFT JOIN instructor_courses ic ON cc.instructor_course_id = ic.id
    WHERE ic.prof_id = ? AND ic.course_name = ?
    ORDER BY cc.lesson
    LIMIT ? OFFSET ?";
$stmt_contents = $conn->prepare($sql_contents);

if ($stmt_contents) {

    // Bind and execute query
    $stmt_contents->bind_param("isii", $profId, $courseName, $itemsPerPage, $offset);
    if ($stmt_contents->execute()) {
        $result_contents = $stmt_contents->get_result();


        // Fetch results
        $contents = [];
        while ($row = $result_contents->fetch_assoc()) {
            $contents[] = [
                'id' => $row['id'],
                'lesson' => htmlspecialchars($row['lesson']),
                'contents' => htmlspecialchars($row['contents']),
                'files' => $row['files'],
                'file_name' => htmlspecialchars($row['file_name']),
                'file_type' => htmlspecialchars($row['file_type']),
                'file_size' => htmlspecialchars($row['file_size']),
                'course_name' => htmlspecialchars($row['course_name']),
            ];
        }
    } else {
        die("SQL Execution Error: " . $stmt_contents->error);
    }

    $stmt_contents->close();
} else {
    die("Error preparing contents query: " . $conn->error);
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../Tailwind/css/tailwind.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title></title>
</head>
<body>
    <!-- Container for the content -->
<div class="max-w-7xl mx-auto p-6">

<!-- Professor's Course Header -->
<div class="bg-white shadow-md rounded-lg p-6 mb-8">
    <h1 class="text-3xl font-semibold text-indigo-700 font-poppins">
        <?php echo "{$professor['prof_fname']} {$professor['prof_lname']}'s Courses for {$courseName}"; ?>
    </h1>
</div>

<!-- Lessons Section -->
<div class="space-y-6">
    <?php if (count($contents) > 0): ?>
        <?php foreach ($contents as $content): ?>
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                <h3 class="text-2xl font-semibold text-indigo-600 mb-2"><?php echo $content['lesson']; ?></h3>
                <p class="text-gray-700 mb-4"><?php echo $content['contents']; ?></p>

                <?php if ($content['files']): ?>
                    <div class="flex items-center space-x-2">
                        <a href="../../../final_exam-main/PHP/pages/classes-stud/download_file.php?id=<?php echo $content['id']; ?>"
                            class="text-blue-600 hover:underline">
                            <?php echo "{$content['file_name']} ({$content['file_type']}, {$content['file_size']} KB)"; ?>
                        </a>
                    </div>
                <?php endif; ?>

                <p class="text-sm text-gray-500 mt-4">Course: <?php echo $content['course_name']; ?></p>
            </div>
        <?php endforeach; ?>

        <!-- Pagination Section -->
        <div class="flex justify-between items-center mt-6">
            <div>
                <a href="../../../final_exam-main/PHP/pages/classes-stud/assessment.php?prof_id=<?php echo $profId; ?>&course_name=<?php echo $courseName; ?>"
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-300">
                    Go to Assessment
                </a>
            </div>
            <div class="flex space-x-4">
                <a href="?prof_id=<?php echo $profId; ?>&course_name=<?php echo $courseName; ?>&page=<?php echo max($page - 1, 1); ?>"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">
                    Previous
                </a>
                <a href="?prof_id=<?php echo $profId; ?>&course_name=<?php echo $courseName; ?>&page=<?php echo $page + 1; ?>"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">
                    Next
                </a>
            </div>
        </div>
    <?php else: ?>
        <p class="text-center text-gray-500">No lessons available for this course.</p>
    <?php endif; ?>
</div>
</div>

<script src="https://cdn.tailwindcss.com"></script>
</body>
</html>
