<?php
require_once '../../PHP/services/config/db_connection.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login/loginv2.php");
    exit;
}

$student_fname = "Student";
$student_lname = "Name";
$student_profile_pic = null;
$student_email = "Email";

try {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT fname, lname, profile_pic, email FROM tbl_student WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $student_fname = $row['fname'] ?? "Student";
            $student_lname = $row['lname'] ?? "Name";
            $student_email = $row['email'] ?? "Email";
            $student_profile_pic = $row['profile_pic'] ? base64_encode($row['profile_pic']) : null;
        } else {
            error_log("No result found for user_id: " . $user_id);
        }
    } else {
        error_log("Query execution failed: " . $stmt->error);
    }
} catch (Exception $e) {
    error_log("Error fetching data: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Handle profile picture upload
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['profile_pic']['tmp_name'];
            $file_type = mime_content_type($file_tmp);
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

            if (in_array($file_type, $allowed_types)) {
                $file_content = file_get_contents($file_tmp);

                $stmt = $conn->prepare("UPDATE tbl_student SET profile_pic = ? WHERE id = ?");
                $stmt->bind_param("bi", $file_content, $user_id);
                $stmt->send_long_data(0, $file_content);

                if (!$stmt->execute()) {
                    throw new Exception("Failed to update profile picture: " . $stmt->error);
                }
            } else {
                header("Location: settings_stud.php?error=invalid_file");
                exit;
            }
        }

        // Handle name updates
        $fname = $_POST['fname'] ?? null;
        $lname = $_POST['lname'] ?? null;

        $updates = [];
        $params = [];

        // Update first name if provided
        if (!empty($fname)) {
            $updates[] = "fname = ?";
            $params[] = $fname;
        }

        // Update last name if provided
        if (!empty($lname)) {
            $updates[] = "lname = ?";
            $params[] = $lname;
        }

        // Only execute the update if there is something to update
        if (!empty($updates)) {
            $stmt = $conn->prepare("UPDATE tbl_student SET " . implode(", ", $updates) . " WHERE id = ?");
            $params[] = $user_id; // Add user ID as the last parameter
            $stmt->bind_param(str_repeat("s", count($params) - 1) . "i", ...$params); // Bind the params dynamically

            if (!$stmt->execute()) {
                throw new Exception("Failed to update name: " . $stmt->error);
            }
        }

        // After successful update, return the updated profile data as JSON
        $response = [
            'success' => true,
            'name' => $student_fname . ' ' . $student_lname,
            'email' => $student_email,
            'profile_pic' => $student_profile_pic ? base64_encode($student_profile_pic) : null
        ];
        echo json_encode($response); // Return the updated data as JSON
        exit;
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo json_encode(['success' => false]); // Return failure response
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/output.css">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet" />
    <title>Learning MS</title>
</head>

<body>
    <div class="font-poppins">
        <div class="flex flex-col h-screen">
            <div class="m-5">
                <span class="font-bold text-4xl text-[#424040]">Settings</span>
            </div>
            <div class="m-5 p-10 bg-white rounded-lg shadow-lg mb-20 h-full">
                <?php if (isset($_GET['success'])): ?>
                    <div class="text-green-600">Profile updated successfully!</div>
                <?php elseif (isset($_GET['error'])): ?>
                    <div class="text-red-600">Failed to update profile. Please try again.</div>
                <?php endif; ?>
                <span class="font-bold text-2xl text-[#424040]">Profile Management</span>
                <div class="mt-20 flex">
                    <div class="flex flex-col gap-5 mr-28">
                        <img class="profile-picture w-32 h-32 rounded-full" src="<?php echo $student_profile_pic ? 'data:image/jpeg;base64,' . htmlspecialchars($student_profile_pic) : 'img/default-profile.jpg'; ?>" alt="Profile Picture">

                        <div class="flex flex-col">
                            <span class="profile-name text-3xl truncate"><?php echo htmlspecialchars($student_fname . " " . $student_lname); ?></span>
                            <span class="profile-email text-1lg truncate"><?php echo htmlspecialchars($student_email); ?></span>
                        </div>
                        <a class="text-left self-start bg-white p-3 rounded-md shadow-lg" href="../services/logout/logout.php">Logout</a>
                    </div>

                    <div class="bg-[#E9E3FF] px-10 py-5 pt-10 rounded-md shadow-lg flex flex-col w-full">
                        <form id="profile-form" enctype="multipart/form-data">
                            <div class="gap-5 mb-20 flex flex-wrap">
                                <span class="text-2xl">First Name:</span>
                                <input type="text" name="fname" placeholder="<?php echo htmlspecialchars($student_fname); ?>" class="text-lg bg-white py-1 px-5 rounded-md w-48 shadow-lg">
                                <span class="text-2xl">Last Name:</span>
                                <input type="text" name="lname" placeholder="<?php echo htmlspecialchars($student_lname); ?>" class="text-lg bg-white py-1 px-5 rounded-md w-48 shadow-lg">
                            </div>
                            <div class="flex">
                                <div class="flex flex-col gap-4">
                                    <span class="text-2xl truncate font-bold">Change Avatar</span>
                                    <input type="file" name="profile_pic" accept="image/*" class="self-center">
                                </div>
                                <button type="submit" class="bg-white p-3 rounded-md shadow-lg ml-5">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        document.querySelector("#profile-form").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent the default form submission

            const formData = new FormData(this); // Collect form data

            fetch("settings_stud.php", {
                    method: "POST",
                    body: formData,
                })
                .then(response => response.json()) // Parse the JSON response
                .then(data => {
                    if (data.success) {
                        // Profile update was successful, reload the page
                        window.location.reload(); // This reloads the current page to show updated data
                    } else {
                        // Handle error in the response
                        alert("Error updating profile");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                });
        });
    </script>
</body>

</html>
