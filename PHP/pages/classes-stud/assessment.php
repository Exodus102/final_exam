<?php
include 'C:/xampp/htdocs/final_exam-main/PHP/services/config/db_connection.php';

$courseName = isset($_GET['course_name']) ? htmlspecialchars($_GET['course_name']) : '';

$query = "SELECT DISTINCT lesson FROM quiz_courses WHERE course_name = ? ORDER BY lesson ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $courseName);
$stmt->execute();
$result = $stmt->get_result();
$lessons = [];

while ($row = $result->fetch_assoc()) {
    $lessons[] = $row['lesson'];
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Assessments</title>
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white w-full sm:w-3/4 lg:w-1/2 p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-semibold text-center text-indigo-600 mb-6">
                Available Assessments for <?php echo $courseName; ?>
            </h1>

            <?php if (!empty($lessons)): ?>
                <ul class="space-y-4">
                    <?php foreach ($lessons as $lesson): ?>
                        <li>
                            <a href="quiz.php?course_name=<?php echo urlencode($courseName); ?>&lesson=<?php echo urlencode($lesson); ?>"
                               class="block p-4 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded-lg shadow">
                                Quiz for Lesson: <?php echo htmlspecialchars($lesson); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-center text-gray-600">No lessons available for this course.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>