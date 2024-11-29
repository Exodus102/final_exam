<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);  // Enable full error reporting

include 'db_connection.php';
$conn = OpenCon();

header("Content-Type: application/json");

// Clear any previous output before sending JSON response
ob_clean();

// Get the incoming JSON data
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    error_log("Failed to decode JSON input");
}

$classCode = $data['classCode'] ?? '';
error_log("Received class code: " . $classCode);  // Log received class code

if (empty($classCode)) {
    echo json_encode(['success' => false, 'message' => 'Class code is required.']);
    exit;
}

// Query to check if the class exists
$sql = "SELECT prof, subject, profile_pic FROM classes WHERE class_code = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Query preparation failed']);
    exit;
}

$stmt->bind_param("i", $classCode);  // Ensure class_code is bound as an integer
$stmt->execute();
$result = $stmt->get_result();

// Check if any results were returned
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Convert the BLOB image data to a base64-encoded string
    $imageData = base64_encode($row['profile_pic']);
    $imageSrc = "data:image/jpeg;base64," . $imageData;  // Adjust MIME type if necessary

    $response = [
        'success' => true,
        'title' => $row['subject'],
        'subtitle' => $row['prof'],
        'image' => $imageSrc,  // Send base64 encoded image
    ];
} else {
    $response = ['success' => false, 'message' => 'Invalid class code.'];
}

// Log the response to verify what is being returned
error_log('Response: ' . json_encode($response));  // Log full JSON response

// Send the JSON response
echo json_encode($response);

$stmt->close();
CloseCon($conn);
?>
