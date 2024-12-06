<?php
include 'C:/xampp/htdocs/final_exam-main/PHP/services/config/db_connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_name = $_POST['course_name'];
    $course_name = $conn->real_escape_string($course_name);
    $sql = "INSERT INTO tbl_courses (course) VALUES ('$course_name')";
    if ($conn->query($sql) === TRUE) {
        $message = "Course added successfully!";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<div class="p-5 font-poppins flex flex-col justify-center h-full gap-5 items-center">
    <h1 class="text-3xl">Add Courses</h1>
    <form action="" method="POST" class="w-1/2 flex flex-col gap-3">
        <input type="text" name="course_name" placeholder="Courses" class="p-3 rounded-lg focus:outline-none shadow-lg w-full" required>
        <button type="submit" class="text-white p-3 rounded-lg shadow-lg bg-violet-500 hover:bg-violet-600 w-full">Submit</button>
    </form>
    <?php if (!empty($message)): ?>
        <p class="text-center mt-3 text-green-600 font-medium"><?php echo $message; ?></p>
    <?php endif; ?>
</div>