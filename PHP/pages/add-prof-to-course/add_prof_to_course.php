<?php
include 'C:/xampp/htdocs/final_exam-main/PHP/services/config/db_connection.php';

$message = ""; // Initialize message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $course_name = trim($_POST['course']);

    // Fetch course details
    $sql_course = "SELECT id, course FROM tbl_courses WHERE course = ?";
    $stmt_course = $conn->prepare($sql_course);
    if ($stmt_course) {
        $stmt_course->bind_param("s", $course_name);
        $stmt_course->execute();
        $result_course = $stmt_course->get_result();

        if ($result_course->num_rows > 0) {
            $course = $result_course->fetch_assoc();
            $course_id = $course['id'];
        } else {
            $message = "Course not found.";
        }
        $stmt_course->close();
    } else {
        $message = "Error preparing course query: " . $conn->error;
    }

    // Fetch professor details by email
    if (empty($message)) {
        $sql_prof = "SELECT id, fname, lname, email FROM tbl_prof WHERE email = ?";
        $stmt_prof = $conn->prepare($sql_prof);
        if ($stmt_prof) {
            $stmt_prof->bind_param("s", $email);
            $stmt_prof->execute();
            $result_prof = $stmt_prof->get_result();

            if ($result_prof->num_rows > 0) {
                $prof = $result_prof->fetch_assoc();
                $prof_id = $prof['id'];
                $prof_fname = $prof['fname'];
                $prof_lname = $prof['lname'];
                $prof_email = $prof['email'];
            } else {
                $message = "Professor not found.";
            }
            $stmt_prof->close();
        } else {
            $message = "Error preparing professor query: " . $conn->error;
        }
    }

    // Check if the professor is already assigned to the course
    if (empty($message)) {
        $sql_check_existing = "SELECT * FROM instructor_courses WHERE course_id = ? AND prof_id = ?";
        $stmt_check = $conn->prepare($sql_check_existing);
        if ($stmt_check) {
            $stmt_check->bind_param("ii", $course_id, $prof_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                $message = "Instructor is already on class.";
            } else {
                // Insert into instructor_courses if not already assigned
                $sql_insert = "INSERT INTO instructor_courses (course_id, course_name, prof_id, prof_fname, prof_lname, prof_email) 
                               VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);
                if ($stmt_insert) {
                    $stmt_insert->bind_param("isisss", $course_id, $course_name, $prof_id, $prof_fname, $prof_lname, $prof_email);
                    $stmt_insert->execute();

                    if ($stmt_insert->affected_rows > 0) {
                        $message = "Instructor assigned to the course successfully.";
                    } else {
                        $message = "Failed to assign instructor to the course.";
                    }
                    $stmt_insert->close();
                } else {
                    $message = "Error preparing insert query: " . $conn->error;
                }
            }
            $stmt_check->close();
        } else {
            $message = "Error preparing check query: " . $conn->error;
        }
    }

    $conn->close();
}
?>

<div class="p-5 h-full font-poppins">
    <div class="flex flex-col justify-center items-center h-full">
        <span class="text-3xl">
            Deploy Instructor
        </span>
        <form action="" method="POST" class="flex flex-col gap-5 w-full justify-center items-center">
            <input type="text" name="email" placeholder="Email" class="p-3 w-1/2 rounded shadow-lg" required>
            <input type="text" name="course" placeholder="Course" class="p-3 w-1/2 rounded shadow-lg" required>
            <button type="submit" class="bg-violet-500 hover:bg-violet-600 rounded p-3 text-white w-1/2">
                Add to Course
            </button>
        </form>

        <?php if (!empty($message)): ?>
            <div class="mt-3 text-center text-lg font-semibold <?php echo ($message === 'Instructor assigned to the course successfully.') ? 'text-green-500' : ($message === 'Instructor is already on class.' ? 'text-yellow-500' : 'text-red-500'); ?>">
                <?php
                echo htmlspecialchars($message);
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>