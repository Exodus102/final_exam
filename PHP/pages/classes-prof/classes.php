<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session to check if user is logged in
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: ../PHP/login/loginv2.php");
    exit;
}

if (isset($_GET['course'])) {
    // Retrieve the course name from the URL
    $courseName = htmlspecialchars($_GET['course']);
} else {
    $courseName = "No course selected.";
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

<body class="p-5 h-screen">
    <div class="flex h-full">
        <div class="flex flex-col h-full gap-20">
            <form id="uploadLessonForm" action="upload_lesson.php" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
                <span class="text-2xl font-bold">Upload a Lesson for <?php echo $courseName; ?></span>
                <input type="hidden" name="course" value="<?php echo htmlspecialchars($courseName); ?>">
                <input type="text" name="lesson" placeholder="Lesson" class="p-3 rounded-lg focus:outline-none shadow-lg" required>
                <input type="text" name="description" placeholder="Description" class="p-3 rounded-lg focus:outline-none shadow-lg" required>
                <input type="file" name="fileUpload" id="uploadfile" class="hidden" required>
                <label for="uploadfile" class="bg-violet-500 text-white text-center rounded p-3 transition transform
                hover:shadow-violet-500 hover:scale-105 hover:bg-violet-600">Upload a File</label>
                <button type="submit" class="bg-teal-500 text-white text-center rounded p-3 transition transform
                hover:shadow-violet-500 hover:scale-105 hover:bg-teal-700">Upload Lesson</button>
                <div id="statusMessage" class="text-lg font-semibold mt-3"></div>
            </form>

            <form action="" class="flex flex-col gap-5">
                <span class="text-2xl font-bold">Create an assessment for <?php echo $courseName; ?></span>
                <input type="text" placeholder="Lesson" class="p-3 rounded-lg focus:outline-none shadow-lg" required>
                <input type="text" placeholder="Description" class="p-3 rounded-lg focus:outline-none shadow-lg" required>
                <button type="submit" class="bg-teal-500 text-white text-center rounded p-3 transition transform
                hover:shadow-violet-500 hover:scale-105 hover:bg-teal-700">Upload Assessmet</button>
            </form>
        </div>
        <!--This is for the content-->
        <div>
        </div>
    </div>
    <script>
        const form = document.getElementById('uploadLessonForm');
        const statusMessage = document.getElementById('statusMessage');

        form.addEventListener('submit', async (e) => {
            e.preventDefault(); // Prevent default form submission

            const formData = new FormData(form);

            try {
                const response = await fetch('upload_lesson.php', {
                    method: 'POST',
                    body: formData,
                });

                const result = await response.json();

                // Display the status message
                if (result.status === 'success') {
                    statusMessage.textContent = result.message;
                    statusMessage.classList.add('text-teal-700');
                    statusMessage.classList.remove('text-red-700');
                } else {
                    statusMessage.textContent = result.message;
                    statusMessage.classList.add('text-red-700');
                    statusMessage.classList.remove('text-teal-700');
                }
            } catch (error) {
                statusMessage.textContent = 'An error occurred. Please try again.';
                statusMessage.classList.add('text-red-700');
                statusMessage.classList.remove('text-teal-700');
                console.error(error);
            }
        });
    </script>

</body>

</html>