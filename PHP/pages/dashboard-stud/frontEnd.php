<?php
include '../../services/config/db_connection.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

$student_fname = "Student";
$student_lname = "Name";
$student_profile_pic = null;

try {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT fname, lname, profile_pic FROM tbl_student WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $student_fname = $row['fname'] ?? "Student";
            $student_lname = $row['lname'] ?? "Name";
            $student_profile_pic = $row['profile_pic']  ? base64_encode($row['profile_pic']) : null;
        } else {
            error_log("No result found for user_id: " . $user_id);
        }
    } else {
        error_log("Query execution failed for student's name: " . $stmt->error);
    }
} catch (Exception $e) {
    error_log("Error fetching data: " . $e->getMessage());
}
error_log("User ID: " . $user_id);
error_log("Student First Name: " . $student_fname);
error_log("Student Last Name: " . $student_lname);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/output.css">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap"
        rel="stylesheet" />
    <title>Learning MS</title>
</head>

<body class="font-poppins p-5">
    <div class="flex h-screen">

        <div class="flex flex-col w-1/6 border-[3px] border-[#E9E3FF] rounded-xl mb-10 shadow-xl
            pl-5 pr-5">
            <div class="flex mb-10 mt-10">
                <h1 class="font-bold">
                    LMS
                </h1>
            </div>

            <div class="flex flex-col text-[#424040]">
                <a href="#" class="flex items-center m-2 space-x-5 p-1 rounded-lg hover:bg-gray-100 focus:bg-[#E9E3FF]">
                    <img src="icons/classes.svg" alt="classes" class="w-5 h-5">
                    <span class="text-left">Courses</span>
                </a>
            </div>

            <div class="mt-auto mb-5 ">
                <a href="settings.php" class="flex items-center m-2 space-x-5">
                    <img src="icons/settings.svg" alt="audit trail" class="w-5 h-5">
                    <span class="text-left">Settings</span>
                </a>
            </div>
        </div>

        <div class="m-5 w-3/5">
            <div class="flex flex-col">
                <h1 class="font-bold text-4xl text-[#424040]">
                    COURSES
                </h1>
                <div class="flex mb-10 justify-between">
                    <h1 class="font-[500] text-2xl text-[#424040] items-center">
                        Welcome!
                    </h1>
                    <div class="flex bg-[#FFFFFF] rounded-md justify-evenly shadow-lg">
                        <button class="flex p-2 items-center" id="add-class">
                            <img src="img/add.png" alt="" class="w-7 h-7">
                            <h1 class="text-xs">
                                Add New Course
                            </h1>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 ml-7" id="module-container">
                </div>
                <div class="" id="no-module">
                </div>
            </div>
        </div>

        <div class="mt-5 w-1/4">
            <h1 class="font-bold">
                Profile
            </h1>
            <div class="w-full h-1/2 bg-[#EFEFEF] flex flex-col items-center shadow-xl rounded-xl">
                <a class="bg-white rounded-xl w-8 h-8 flex justify-center items-center mb-5 shadow-xl mt-4 ml-auto mr-5" href="settings.php">
                    <img src="icons/edit.svg" alt="" srcset="" class="w-4 h-4">
                </a>
                <img src="<?php echo $student_profile_pic ? 'data:image/jpeg;base64,' . htmlspecialchars($student_profile_pic) : 'img/default-profile.jpg'; ?>" alt="" class="w-16 h-16 mb-4 rounded-full">
                <span>
                    <?php echo htmlspecialchars($student_fname . " " . $student_lname) ?>
                </span>
                <span class="text-sm">
                    <?php echo "Student" ?>
                </span>
            </div>
        </div>
    </div>

    <div id="modal_container" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 invisible">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
            <form action="frontEnd.php" method="POST" id="joinForm">
                <p class="text-xl font-bold text-center mb-4">Join Class</p>
                <div class="mb-4 border-b pb-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">Enter Class Code</p>
                    <input
                        type="text"
                        placeholder="Class Code"
                        id="join_field"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    <span
                        id="error_message"
                        class="text-red-500 text-sm mt-2 hidden"></span>
                </div>
                <div class="flex justify-end gap-4">
                    <button
                        type="button"
                        id="cancel"
                        class="px-4 py-2 text-sm font-medium text-gray-700 border rounded-lg hover:bg-gray-100 transition">
                        Cancel
                    </button>
                    <input
                        type="submit"
                        value="Join"
                        id="join"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition cursor-pointer" />
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.tailwindcss.com">
    </script>

    <script>
        const addClassButton = document.getElementById("add-class"); // Add class button
        const modalContainer = document.getElementById("modal_container"); // Modal container
        const cancelButton = document.getElementById("cancel"); // Cancel button in the modal
        const joinField = document.getElementById("join_field"); // Input field for class code
        const errorMessage = document.getElementById("error_message"); // Error message element
        const joinForm = document.getElementById("joinForm"); // The form itself

        // Array to track added class codes
        const addedClassCodes = [];

        // Handle modal opening
        addClassButton.addEventListener("click", () => {
            modalContainer.classList.remove("invisible"); // Make modal visible
            joinField.value = ""; // Clear the input field
            errorMessage.style.display = "none"; // Hide any error messages
            errorMessage.textContent = ""; // Clear error message text
        });

        // Handle modal closing
        cancelButton.addEventListener("click", (event) => {
            event.preventDefault(); // Prevent form submission
            modalContainer.classList.add("invisible"); // Hide the modal
            errorMessage.style.display = "none"; // Hide any error messages
            joinField.value = ""; // Clear the input field
        });

        // Handle form submission
        joinForm.addEventListener("submit", (event) => {
            event.preventDefault(); // Prevent form default submission

            const classCode = joinField.value.trim(); // Get the entered class code

            if (!classCode) {
                errorMessage.textContent = "Please enter a class code.";
                errorMessage.style.display = "block";
                return;
            }

            // Check if class has already been added
            if (addedClassCodes.includes(classCode)) {
                errorMessage.textContent = "This class is already added.";
                errorMessage.style.display = "block";
                return;
            }

            // Send class code to the server for validation and enrollment
            fetch("checks.php", {
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
                        addedClassCodes.push(classCode); // Add the class code to the list of added courses

                        // Dynamically create the new course card
                        const moduleContainer = document.querySelector(".grid-cols-3");
                        const newCourseCard = document.createElement("div");

                        newCourseCard.classList.add("flex");
                        newCourseCard.innerHTML = `
                <div class="bg-[#C2B5E8] text-[#424040] w-72 grid grid-col justify-center rounded-lg shadow-xl p-5 gap-2" id="${data.classCode}">
                    <span class="flex flex-col">
                        <img src="img/Digital_technology.jpg" alt="Course Image" class="rounded-md h-25 w-60 self-center">
                    </span>
                    <div class="flex flex-col w-72 p-4">
                        <span class="font-bold text-left text-3xl truncate leading-8">${data.subjectName}</span>
                        <span>${data.professorName}</span>
                    </div>
                    <div class="flex justify-end">
                        <a href="courses.php?classCode=${data.classCode}">
                            <button class="self-end mr-5">
                                <span class="w-10 h-10 bg-[#8A70D6] rounded-xl flex justify-center items-center mt-2">
                                    <img src="icons/Path.svg" alt="Course Link">
                                </span>
                            </button>
                        </a>
                    </div>
                </div>
            `;

                        moduleContainer.appendChild(newCourseCard); // Append the new course card
                        modalContainer.classList.add("invisible"); // Close the modal
                    } else {
                        // Display server-side error message
                        errorMessage.textContent = data.message;
                        errorMessage.style.display = "block";
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    errorMessage.textContent = "An unexpected error occurred. Please try again.";
                    errorMessage.style.display = "block";
                });
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const moduleContainer = document.getElementById("module-container");

            // Fetch enrolled courses from the server
            fetch("fetch_enrolled_courses.php")
                .then(response => response.json())
                .then(data => {
                    if (Array.isArray(data)) {
                        data.forEach(course => {
                            const courseCard = document.createElement("div");
                            courseCard.classList.add("flex");
                            courseCard.innerHTML = `
                        <div class="bg-[#C2B5E8] text-[#424040] w-72 grid grid-col justify-center rounded-lg shadow-xl p-5 gap-2" id="${course.classCode}">
                            <span class="flex flex-col">
                                <img src="img/Digital_technology.jpg" alt="Course Image" class="rounded-md h-25 w-60 self-center">
                            </span>
                            <div class="flex flex-col w-72 p-4">
                                <span class="font-bold text-left text-3xl truncate leading-8">${course.subjectName}</span>
                                <span>${course.professorName}</span>
                            </div>
                            <div class="flex justify-end">
                            
                                
                                <a href="courses.php?classCode=${course.classCode}">
                                    <button class="self-end mr-5">
                                        <span class="w-10 h-10 bg-[#8A70D6] rounded-xl flex justify-center items-center mt-2">
                                            <img src="icons/Path.svg" alt="Course Link">
                                        </span>
                                    </button>
                                </a>
                            </div>
                        </div>
                    `;
                            moduleContainer.appendChild(courseCard);
                        });
                    }
                })
                .catch(error => {
                    console.error("Error fetching courses:", error);
                });
        });

    </script>
    

</body>

</html>