<?php
include '../../services/config/db_connection.php';

// Initialize array for file_names and content_ids
$file_names = [];
$content_ids = [];

// Retrieve all filenames and content IDs from the class_content table
$sql = "SELECT content_id, file_name FROM class_content";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $file_names[] = $row['file_name']; // Store file names
        $content_ids[] = $row['content_id']; // Store content ids
    }
} else {
    $file_names[] = 'No lessons uploaded yet.';
}

$subject_code = isset($_GET['subject_code']) ? htmlspecialchars($_GET['subject_code']) : '';
$subject_name = isset($_GET['subject_name']) ? htmlspecialchars($_GET['subject_name']) : '';
$section = isset($_GET['section']) ? htmlspecialchars($_GET['section']) : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../Tailwind/css/tailwind.css">
    <title>Classes</title>
</head>

<body class="font-poppins">
    <div class="w-screen h-screen">
        <div class="flex flex-col gap-5 p-10">
            <div class="flex flex-col">
                <span class="text-7xl font-[500]"><?= $subject_code; ?></span>
                <span class=""><?= $subject_name; ?></span>
                <span class=""><?= $section; ?></span>
            </div>
            <div class="flex">
                <button>
                    Stream
                </button>

            </div>

            <div class="flex flex-col">
                <button class="" id="toggle-upload">
                    <span>Upload a Lesson</span>
                </button>

                <!-- List of uploaded lessons -->
                <div>
                    <h3 class="text-xl font-semibold mt-5">Uploaded Lessons:</h3>
                    <?php if (count($file_names) > 0): ?>
                        <ul>
                            <?php foreach ($file_names as $index => $file_name): ?>
                                <li class="text-lg">
                                    <a href="uploads/<?= $file_name; ?>" download="<?= $file_name; ?>" class="text-blue-600"><?= $file_name; ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No lessons uploaded yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div id="upload-a-lesson" class="flex-col fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 w-full invisible">
                <form id="uploadForm" action="upload.php" method="POST" enctype="multipart/form-data" class="flex flex-col w-1/2 h-1/2 bg-[#E9E3FF] rounded-lg border-dashed border-2 border-[#8A70D6] justify-center items-center">
                    <input type="file" name="file" id="fileInput" multiple>
                    <img src="../../../assets/icons/upload.svg" alt="" class="w-16 h-16 mb-5">
                    <a href="#" id="triggerFileInput" class="text-1xl text-[#8A70D6]">Click here</a>
                    <p class="text-1xl">Browse File to Upload</p>
                    <span id="file-name" class="text-[#8A70D6] mt-3"></span>
                    <button type="submit" id="submit" class="mt-5 bg-[#8A70D6] text-white px-4 py-2 rounded">Upload</button>
                </form>
            </div>
        </div>
    </div>
    <script src="../../../JavaScript/class-content-prof/class_content_prof.js"></script>
</body>

</html>

<?php

$conn->close();
?>