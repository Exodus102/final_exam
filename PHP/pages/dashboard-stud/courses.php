<?php
include '../../services/config/db_connection.php';
include 'retrieve.php';


// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

// Initialize variables for student details
$student_fname = "Student";
$student_lname = "Name";
$student_profile_pic = 'img/default-profile.jpg'; // Default profile picture

try {
    // Get user ID from session
    $user_id = $_SESSION['user_id'];

    // Fetch student details
    $stmt = $conn->prepare("SELECT fname, lname, profile_pic FROM tbl_student WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $student_fname = $row['fname'] ?? "Student";
            $student_lname = $row['lname'] ?? "Name";
            $student_profile_pic = $row['profile_pic']
                ? 'data:image/jpeg;base64,' . base64_encode($row['profile_pic'])
                : 'img/default-profile.jpg'; // Use default if profile_pic is null
        }
    } else {
        error_log("Failed to fetch student details: " . $stmt->error);
    }
} catch (Exception $e) {
    error_log("Error fetching student details: " . $e->getMessage());
}

// Fetch posts for the classes the student is enrolled in
// Fetch posts for the current class, ensuring they are from the correct professor
$posts = [];
try {
    // Get the class_code from the URL
    $class_code = $_GET['classCode'] ?? null;
    if (!$class_code) {
        throw new Exception("Class code parameter is missing.");
    }

    $stmt = $conn->prepare("
        SELECT 
            p.id AS post_id,
            p.content,
            p.file_url,
            p.date_added,
            pr.fname AS prof_fname,
            pr.lname AS prof_lname,
            pr.profile_pic AS prof_profile_pic,
            cp.subject_name,
            cp.subject_code
        FROM tbl_posts p
        INNER JOIN tbl_prof pr ON p.prof_id = pr.id
        INNER JOIN class_prof cp ON cp.class_id = p.class_id AND cp.class_code = ?
        WHERE cp.class_id IN (
            SELECT class_id 
            FROM tbl_enrolled_students 
            WHERE student_id = ?
        )
        ORDER BY p.date_added DESC
    ");
    $stmt->bind_param("si", $class_code, $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $posts[] = [
                'post_id' => $row['post_id'],
                'content' => $row['content'],
                'file_url' => $row['file_url'] ?? null, // Handle null file_url
                'date_added' => date("F j, Y, g:i a", strtotime($row['date_added'])),
                'prof_name' => "{$row['prof_fname']} {$row['prof_lname']}",
                'prof_profile_pic' => $row['prof_profile_pic']
                    ? 'data:image/jpeg;base64,' . base64_encode($row['prof_profile_pic'])
                    : 'img/default-profile.jpg',
                'subject_name' => $row['subject_name'],
                'subject_code' => $row['subject_code'],
            ];
        }
    } else {
        error_log("Failed to fetch posts: " . $stmt->error);
    }
} catch (Exception $e) {
    error_log("Error fetching posts: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/output.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Learning MS</title>
</head>

<body class="font-Poppins p-5 bg-[#F3F3F3]">
    <div class="flex h-screen">

        <div class="flex flex-col w-1/6 border-[3px] border-[#E9E3FF] rounded-xl mb-10 shadow-xl
            pl-5 pr-5">
            <div class="flex mb-10 mt-10">
                <h1 class="font-bold">
                    LMS
                </h1>
            </div>
            <div class="flex flex-col text-[#424040]">
                <a href="frontEnd.php" class="flex items-center m-2 space-x-5 p-1 rounded-lg hover:bg-gray-100 focus:bg-[#E9E3FF]">
                    <img src="icons/classes.svg" alt="classes" class="w-5 h-5">
                    <span class="text-left">Courses</span>
                </a>
                <a href="#" class="flex items-center m-2 space-x-5 p-1 rounded-lg hover:bg-gray-100 focus:bg-[#E9E3FF]">
                    <img src="icons\archived.svg" alt="archived classes" class="w-5 h-5">
                    <span class="text-left">Archived Courses</span>
                </a>
            </div>

            <div class="mt-auto mb-5 ">
                <a href="" class="flex items-center m-2 space-x-5">
                    <img src="icons/settings.svg" alt="audit trail" class="w-5 h-5">
                    <span class="text-left">Settings</span>
                </a>
            </div>
        </div>

        <div class="m-5 w-full mr-40">
            <div class="flex justify-between mb-8">
                <div class="flex flex-col ">
                    <h1 class="text-4xl font-bold text-slate-[#424040]"><?php echo $subject ?></h1>
                    <h1 class="text-xl">BSIT 3-2</h1>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <div class="flex gap-10">
                    <div class="flex flex-col gap-5 w-3/6">
                        <div class="flex gap-2 mb-5 w-1/4">
                            <button class="bg-[#FFFFFF] py-2 px-5 rounded-xl text-sm shadow-md font-bold" id="stream-btn">
                                Stream
                            </button>
                        </div>
                        <div class="flex flex-col bg-[#E9E3FF] px-4 py-5 rounded-md border-2 border-[#CCC5C5] shadow-lg">
                            <div class="flex justify-center gap-4 mb-5">
                                <img src="<?php echo $profilePic ? 'data:image/jpeg;base64,' . htmlspecialchars($profilePic) : 'img/default-profile.jpg'; ?>" class="w-20 h-20 rounded-full shadow-md" alt="">
                                <div class="flex flex-col gap-0 leading-4 justify-center">
                                    <span class="font-bold m-0 p-0">Instructor:</span>
                                    <span class="m-0 p-0 text-sm"><?php echo $prof ?></span>
                                </div>
                            </div>
                            <div class="flex flex-col shadow-md mx-10 rounded-md py-4 bg-[#FFFFFF] h-max px-0">
                                <span class="self-center">Enrolled Students:</span>
                                <div class="flex flex-col overflow-auto px-4">
                                    <?php if (!empty($students)): ?>
                                        <ul>
                                            <?php foreach ($students as $student): ?>
                                                <li><?php echo htmlspecialchars($student); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p>No students are enrolled in this class.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col w-full gap-3 overflow-y-scroll h-[84vh]" id="courses-modules">
                        <span class="text-3xl font-bold">POSTS</span>
                        <?php foreach ($posts as $post): ?>
                            <div id="post-<?php echo $post['post_id']; ?>" class="space-y-4">
                                <!-- Render post content here -->
                                <div class="comments-container space-y-4">
                                    <!-- Existing comments for the post will go here -->
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php foreach ($posts as $post): ?>
                            <div id="post-list" class="space-y-4">

                                <!-- Posts will be dynamically injected here -->
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>



            </div>
        </div>

    </div>

    
    <script src="https://cdn.tailwindcss.com">
    </script>

    <script>
        
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const classCode = new URLSearchParams(window.location.search).get("classCode");
            const postContainer = document.getElementById("post-list");

            if (classCode) {
                fetch("get_posts.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            classCode
                        }),
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            postContainer.innerHTML = ""; // Clear existing posts
                            data.posts.forEach((post) => {
                                const postElement = document.createElement("div");
                                postElement.classList.add("bg-white", "p-4", "rounded-lg", "shadow-md");
                                postElement.innerHTML = `
                            <h3 class="font-semibold text-lg">${post.content}</h3>
                            <p class="text-sm text-gray-500">
                                Posted by ${post.prof_name} | ${new Date(post.date_added).toLocaleDateString()}
                            </p>
                            ${
                                post.file_url 
                                    ? `<a href="${post.file_url}" target="_blank" class="text-blue-500 underline">View Attachment</a>`
                                    : "<p>No attachments</p>"
                            }
                        `;
                                postContainer.appendChild(postElement);
                            });
                        } else {
                            postContainer.innerHTML = `<p class="text-red-500">${data.message}</p>`;
                        }
                    })
                    .catch((error) => {
                        console.error("Error fetching posts:", error);
                        postContainer.innerHTML = `<p class="text-red-500">An unexpected error occurred. Please try again later.</p>`;
                    });
            } else {
                postContainer.innerHTML = `<p class="text-red-500">Class code is missing from the URL.</p>`;
            }
        });
    </script>
</body>

</html>