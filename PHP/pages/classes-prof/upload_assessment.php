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

// Include the database connection
include 'C:/xampp/htdocs/final_exam-main/PHP/services/config/db_connection.php';

$message = ''; // To store the message to display

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseName = $_POST['course'] ?? '';
    $lesson = $_POST['lesson'] ?? '';
    $questions = $_POST['questions'] ?? [];
    $answers = $_POST['answers'] ?? [];
    $profId = $_SESSION['user_id'];

    if (empty($courseName) || empty($lesson) || empty($questions) || empty($answers)) {
        $message = '<div class="bg-red-500 text-white p-4 rounded-md shadow-md text-center">
                        <strong>Error:</strong> All fields are required.
                    </div>';
    } else {
        $query = "SELECT id AS instructor_courses_id, course_name FROM instructor_courses WHERE course_name = ? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $courseName);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $message = '<div class="bg-red-500 text-white p-4 rounded-md shadow-md text-center">
                            <strong>Error:</strong> Invalid course name.
                        </div>';
        } else {
            $courseData = $result->fetch_assoc();
            $instructorCoursesId = $courseData['instructor_courses_id'];

            // Insert the main quiz course information once
            $query = "INSERT INTO quiz_courses (instructor_course_id, course_name, prof_id, lesson)
                      VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("isss", $instructorCoursesId, $courseName, $profId, $lesson);

            if (!$stmt->execute()) {
                $message = '<div class="bg-red-500 text-white p-4 rounded-md shadow-md text-center">
                                <strong>Error:</strong> Failed to upload assessment.
                            </div>';
            } else {
                // Get the inserted quiz_course_id
                $quizCourseId = $stmt->insert_id;
                $stmt->close();

                // Now, insert each question and answer into separate rows in the same quiz_courses table
                $query = "INSERT INTO quiz_courses (instructor_course_id, course_name, prof_id, lesson, questions, answers)
                          VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);

                foreach ($questions as $index => $question) {
                    $answer = isset($answers[$index]) ? $answers[$index] : '';
                    $stmt->bind_param("isssss", $instructorCoursesId, $courseName, $profId, $lesson, $question, $answer);
                    if (!$stmt->execute()) {
                        $message = '<div class="bg-red-500 text-white p-4 rounded-md shadow-md text-center">
                                        <strong>Error:</strong> Failed to upload question and answer.
                                    </div>';
                        break;
                    }
                }

                // If all goes well, show success message
                if (!$message) {
                    $message = '<div class="bg-green-500 text-white p-4 rounded-md shadow-md text-center">
                                    <strong>Success:</strong> Assessment uploaded successfully!
                                </div>';
                }
            }
        }
    }
    $stmt->close();
    $conn->close();
} else {
    $message = '<div class="bg-yellow-500 text-white p-4 rounded-md shadow-md text-center">
                    <strong>Warning:</strong> Invalid request method.
                </div>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Upload</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-poppins">
    <div class="flex justify-center items-center h-screen p-5">
        <div class="w-full sm:w-8/12 p-3">
            <div class="flex flex-col gap-5">
                <!-- Display the message here -->
                <?php echo $message; ?>
            </div>
        </div>
    </div>
</body>

</html>