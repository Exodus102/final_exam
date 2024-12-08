<?php
// Include database connection
include 'C:/xampp/htdocs/final_exam-main/PHP/services/config/db_connection.php';

// Retrieve the course name and lesson from the URL
$courseName = isset($_GET['course_name']) ? htmlspecialchars($_GET['course_name']) : '';
$lesson = isset($_GET['lesson']) ? htmlspecialchars($_GET['lesson']) : '';

// Fetch quiz questions for the selected course and lesson
$query = "SELECT id, questions FROM quiz_courses WHERE course_name = ? AND lesson = ? ORDER BY id ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $courseName, $lesson);
$stmt->execute();
$result = $stmt->get_result();
$quizData = [];

while ($row = $result->fetch_assoc()) {
    $quizData[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz for Lesson <?php echo $lesson; ?> - <?php echo $courseName; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-poppins">

    <div class="flex justify-center items-center min-h-screen p-5">
        <div class="bg-white w-full sm:w-3/4 lg:w-1/2 p-6 rounded-lg shadow-xl">

            <!-- Quiz Header -->
            <h1 class="text-3xl font-semibold text-center mb-6 text-indigo-600 font-poppins">
                Assessment for Lesson <?php echo htmlspecialchars($lesson); ?> - <?php echo $courseName; ?>
            </h1>

            <form action="submit_answer.php" method="POST">
                <!-- Hidden inputs for course name and lesson -->
                <input type="hidden" name="course_name" value="<?php echo htmlspecialchars($courseName); ?>">
                <input type="hidden" name="lesson" value="<?php echo htmlspecialchars($lesson); ?>">

                <!-- Quiz Questions -->
                <div class="space-y-6">
                    <?php foreach ($quizData as $quiz): ?>
                        <div class="question bg-blue-50 p-4 rounded-md shadow-sm">
                            <p class="text-lg font-medium text-gray-800"><?php echo htmlspecialchars($quiz['questions']); ?></p>
                            <input type="text" name="answers[<?php echo $quiz['id']; ?>]" placeholder="Your answer" required
                                class="mt-2 w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Submit Button -->
                <div class="mt-6 text-center">
                    <button type="submit" class="bg-indigo-600 text-white p-3 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Submit Answers
                    </button>
                </div>
            </form>

        </div>
    </div>

</body>

</html>