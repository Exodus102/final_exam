<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session to check if the user is logged in
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: ../PHP/login/loginv2.php");
    exit;
}

// Retrieve course name from the URL
$courseName = isset($_GET['course']) ? htmlspecialchars($_GET['course']) : "No course selected.";

// Include the database connection
include 'C:/xampp/htdocs/final_exam-main/PHP/services/config/db_connection.php';

// Fetch lessons dynamically
$lessons = [];
if ($courseName !== "No course selected.") {
    $query = "SELECT lesson, contents, file_name FROM course_content WHERE course_name = ? ORDER BY lesson ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $courseName);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $lessons[] = $row;
    }
    $stmt->close();
}

// Fetch assessments dynamically
$assessments = [];
if ($courseName !== "No course selected.") {
    $query = "SELECT lesson FROM quiz_courses WHERE course_name = ? ORDER BY lesson ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $courseName);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $assessments[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../Tailwind/css/tailwind.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Portal</title>
</head>

<body class="p-5 h-screen bg-[#F3F3F3]">
    <div class="flex h-full">
        <!-- Upload Lesson Section -->
        <div class="flex flex-col h-full gap-20">
            <form id="uploadLessonForm" action="upload_lesson.php" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
                <span class="text-2xl font-bold">Upload a Lesson for <?php echo $courseName; ?></span>
                <input type="hidden" name="course" value="<?php echo htmlspecialchars($courseName); ?>">
                <input type="text" name="lesson" placeholder="Lesson Title" class="p-3 rounded-lg focus:outline-none shadow-lg" required>
                <textarea name="description" placeholder="Lesson Description" class="p-3 rounded-lg focus:outline-none shadow-lg" required></textarea>
                <input type="file" name="fileUpload" id="uploadfile" class="hidden" required>
                <label for="uploadfile" class="bg-violet-500 text-white text-center rounded p-3 transition transform hover:shadow-violet-500 hover:scale-105 hover:bg-violet-600">Upload a File</label>
                <button type="submit" class="bg-teal-500 text-white text-center rounded p-3 transition transform hover:shadow-violet-500 hover:scale-105 hover:bg-teal-700">Upload Lesson</button>
                <div class="text-center">
                    <div id="statusMessage" class="text-lg font-semibold mt-3"></div>
                </div>
            </form>

            <!-- Assessment Form -->
            <form id="createAssessmentForm" action="upload_assessment.php" method="POST" class="flex flex-col gap-5">
                <span class="text-2xl font-bold">Create an Assessment for <?php echo $courseName; ?></span>
                <input type="hidden" name="course" value="<?php echo htmlspecialchars($courseName); ?>">
                <input type="text" name="lesson" placeholder="Assessment for Lesson" class="p-3 rounded-lg focus:outline-none shadow-lg" required>
                <!-- Container for dynamically added questions -->
                <div id="questionsContainer" class="flex flex-col gap-5">
                    <div class="flex flex-col gap-3">
                        <input type="text" name="questions[]" placeholder="Question" class="p-3 rounded-lg focus:outline-none shadow-lg" required>
                        <input type="text" name="answers[]" placeholder="Answer" class="p-3 rounded-lg focus:outline-none shadow-lg" required>
                    </div>
                </div>

                <!-- Button to add more questions -->
                <button type="button" id="addQuestionBtn" class="w-full bg-blue-500 text-white text-center rounded p-3 transition transform hover:shadow-blue-500 hover:scale-105 hover:bg-blue-700">
                    Add Question
                </button>

                <button type="submit" class="bg-teal-500 text-white text-center rounded p-3 transition transform hover:shadow-violet-500 hover:scale-105 hover:bg-teal-700">
                    Upload Assessment
                </button>
            </form>
        </div>

        <!-- Display Lessons -->
        <div class="p-5 flex flex-col w-full gap-5">
            <span class="text-3xl font-bold">Lessons for <?php echo $courseName; ?></span>
            <?php if (!empty($lessons)): ?>
                <?php foreach ($lessons as $lesson): ?>
                    <div class="flex w-full rounded-lg p-5 bg-white shadow-lg gap-5 justify-between items-center">
                        <div class="flex justify-center items-center gap-5">
                            <img src="../../../assets/icons/Lesson.svg" alt="Lesson Icon">
                            <div class="flex flex-col">
                                <span class="text-3xl font-[500]"><?php echo htmlspecialchars($lesson['lesson']); ?></span>
                                <span><?php echo htmlspecialchars($lesson['contents']); ?></span>
                            </div>
                        </div>
                        <a href="view_file.php?file=<?php echo urlencode($lesson['file_name']); ?>" target="_blank" class="bg-teal-500 text-white px-4 py-2 rounded-lg">View File</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-lg text-gray-600">No lessons available for this course yet.</p>
            <?php endif; ?>

            <!-- Display Assessments -->
            <div class="h-full">
                <span class="text-3xl font-bold">Assessments</span>
                <?php if (!empty($assessments)): ?>
                    <?php foreach ($assessments as $assessment): ?>
                        <div class="flex w-full rounded-lg p-5 bg-white shadow-lg justify-between items-center mt-5">
                            <div class="flex flex-col">
                                <span class="text-xl font-[500]">Assessment for <?php echo htmlspecialchars($assessment['lesson']); ?></span>
                            </div>
                            <a href="view_assessment.php?lesson=<?php echo urlencode($assessment['lesson']); ?>" class="bg-blue-500 text-white px-4 py-2 rounded-lg">View Assessment</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-lg text-gray-600">No assessments available for this course yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('uploadLessonForm');
        const statusMessage = document.getElementById('statusMessage');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            try {
                const response = await fetch('upload_lesson.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.status === 'success') {
                    statusMessage.textContent = result.message;
                    statusMessage.classList.add('text-teal-700');
                    statusMessage.classList.remove('text-red-700');
                    form.reset();
                } else {
                    statusMessage.textContent = result.message;
                    statusMessage.classList.add('text-red-700');
                    statusMessage.classList.remove('text-teal-700');
                }
            } catch (error) {
                statusMessage.textContent = 'An error occurred. Please try again.';
                statusMessage.classList.add('text-red-700');
                statusMessage.classList.remove('text-teal-700');
            }
        });

        document.getElementById('addQuestionBtn').addEventListener('click', function() {
            const container = document.getElementById('questionsContainer');

            const questionGroup = document.createElement('div');
            questionGroup.classList.add('flex', 'flex-col', 'gap-2');

            const questionInput = document.createElement('input');
            questionInput.type = 'text';
            questionInput.name = 'questions[]';
            questionInput.placeholder = 'Question';
            questionInput.classList.add('p-3', 'rounded-lg', 'focus:outline-none', 'shadow-lg');
            questionInput.required = true;

            const answerInput = document.createElement('input');
            answerInput.type = 'text';
            answerInput.name = 'answers[]';
            answerInput.placeholder = 'Answer';
            answerInput.classList.add('p-3', 'rounded-lg', 'focus:outline-none', 'shadow-lg');
            answerInput.required = true;

            questionGroup.appendChild(questionInput);
            questionGroup.appendChild(answerInput);

            container.appendChild(questionGroup);
        });
    </script>
</body>

</html>