<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session to access the current user
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../PHP/login/loginv2.php");
    exit;
}

include 'C:/xampp/htdocs/final_exam-main/PHP/services/config/db_connection.php';

// Check database connection
if (!$conn) {
    die('<div class="bg-red-500 text-white p-4 rounded-md text-center">
             <strong>Error:</strong> Database connection failed.
         </div>');
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseName = trim($_POST['course'] ?? '');
    $lesson = trim($_POST['lesson'] ?? '');
    $questions = $_POST['questions'] ?? [];
    $answers = $_POST['answers'] ?? [];
    $profId = $_SESSION['user_id'];

    // Input validation
    if (empty($courseName) || empty($lesson) || empty($questions) || empty($answers) || count($questions) !== count($answers)) {
        $message = '<div class="bg-red-500 text-white p-4 rounded-md text-center">
                        <strong>Error:</strong> All fields are required, and each question must have an answer.
                    </div>';
    } else {
        // Fetch the course ID
        $query = "SELECT id FROM instructor_courses WHERE course_name = ? AND prof_id = ? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $courseName, $profId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $message = '<div class="bg-red-500 text-white p-4 rounded-md text-center">
                            <strong>Error:</strong> Invalid course name or unauthorized access.
                        </div>';
        } else {
            $courseData = $result->fetch_assoc();
            $instructorCoursesId = $courseData['id'];

            // Insert questions and answers
            $query = "INSERT INTO quiz_courses (instructor_course_id, course_name, prof_id, lesson, questions, answers)
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);

            $errors = [];
            foreach ($questions as $index => $question) {
                $question = trim($question);
                $answer = trim($answers[$index]);

                if (empty($question) || empty($answer)) {
                    $errors[] = "Question or answer cannot be empty.";
                    continue;
                }

                $stmt->bind_param("isssss", $instructorCoursesId, $courseName, $profId, $lesson, $question, $answer);
                if (!$stmt->execute()) {
                    $errors[] = "Failed to upload question: " . htmlspecialchars($question);
                }
            }

            if (empty($errors)) {
                $message = '<div class="bg-green-500 text-white p-4 rounded-md text-center">
                                <strong>Success:</strong> Assessment uploaded successfully!
                            </div>';
            } else {
                $message = '<div class="bg-red-500 text-white p-4 rounded-md text-center">
                                <strong>Error:</strong><br>' . implode('<br>', $errors) . '
                            </div>';
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Assessment</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-poppins">
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white w-full sm:w-3/4 lg:w-1/2 p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-semibold text-center text-indigo-600 mb-6">Upload Assessment</h1>

            <?php echo $message; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label for="course" class="block text-gray-700">Course Name</label>
                    <input type="text" name="course" id="course" class="w-full p-3 border rounded-lg" required>
                </div>

                <div>
                    <label for="lesson" class="block text-gray-700">Lesson</label>
                    <input type="text" name="lesson" id="lesson" class="w-full p-3 border rounded-lg" required>
                </div>

                <div id="questions-container">
                    <div class="question-answer-pair space-y-2">
                        <label for="question-1" class="block text-gray-700">Question</label>
                        <input type="text" name="questions[]" id="question-1" class="w-full p-3 border rounded-lg" required>

                        <label for="answer-1" class="block text-gray-700">Answer</label>
                        <input type="text" name="answers[]" id="answer-1" class="w-full p-3 border rounded-lg" required>
                    </div>
                </div>

                <button type="button" id="add-question" class="block bg-blue-500 text-white px-4 py-2 rounded-lg mt-3">
                    Add Another Question
                </button>

                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg">
                    Upload Assessment
                </button>
            </form>
        </div>
    </div>

    <script>
        const questionsContainer = document.getElementById('questions-container');
        const addQuestionButton = document.getElementById('add-question');

        addQuestionButton.addEventListener('click', () => {
            const questionNumber = questionsContainer.children.length + 1;
            const questionAnswerPair = `
                <div class="question-answer-pair space-y-2">
                    <label for="question-${questionNumber}" class="block text-gray-700">Question</label>
                    <input type="text" name="questions[]" id="question-${questionNumber}" class="w-full p-3 border rounded-lg" required>

                    <label for="answer-${questionNumber}" class="block text-gray-700">Answer</label>
                    <input type="text" name="answers[]" id="answer-${questionNumber}" class="w-full p-3 border rounded-lg" required>

                    <button type="button" onclick="this.parentElement.remove()" class="bg-red-500 text-white px-4 py-2 rounded-lg mt-3">
                        Remove Question
                    </button>
                </div>
            `;
            questionsContainer.insertAdjacentHTML('beforeend', questionAnswerPair);
        });
    </script>
</body>

</html>
