<?php
header('Content-Type: application/json');

include '../../services/config/db_connection.php';

// Check if a file has been uploaded
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        // File details
        $fileName = $_FILES['file']['name'];
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileData = file_get_contents($fileTmpPath); // Read the content of the file

        // Insert file into the database
        $stmt = $conn->prepare("INSERT INTO class_content (file_name, file_data) VALUES (?, ?)");
        $stmt->bind_param("ss", $fileName, $fileData); // ss because we are using strings and binary data

        if ($stmt->execute()) {
            echo json_encode(["message" => "File uploaded successfully!"]);
        } else {
            echo json_encode(["message" => "Error uploading file.", "error" => $conn->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["message" => "Error: " . $_FILES['file']['error']]);
    }
}

$conn->close();
