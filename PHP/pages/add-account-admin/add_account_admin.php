<?php
include 'C:/xampp/htdocs/final_exam-main/PHP/services/config/db_connection.php';

$validationMessage = "";
$validationMessageColor = "text-red-500";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = strtolower(trim($_POST['email']));
    $password = trim($_POST['password']);


    if (empty($fname) || empty($lname) || empty($email) || empty($password)) {
        $validationMessage = "All fields are required.";
    } elseif (strlen($password) < 8) {
        $validationMessage = "Password must be at least 8 characters long.";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $validationMessage = "Password must contain at least one number.";
    } elseif (!preg_match('/[\W_]/', $password)) {
        $validationMessage = "Password must contain at least one special character.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);


        $checkEmailQuery = "SELECT * FROM tbl_prof WHERE email = ?";
        $stmt = $conn->prepare($checkEmailQuery);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $validationMessage = "Email already exists. Please use a different email.";
        } else {

            $insertQuery = "INSERT INTO tbl_prof (email, password, fname, lname) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param('ssss', $email, $hashed_password, $fname, $lname);

            if ($stmt->execute()) {
                $validationMessage = "Instructor added successfully.";
                $validationMessageColor = "text-black";
            } else {
                $validationMessage = "Error: " . $stmt->error;
            }
        }

        $stmt->close();
    }
}

$conn->close();
?>

<div class="flex flex-col justify-center p-5 h-screen font-poppins gap-3">
    <div>
        <h1 class="text-5xl font-[500]">Add Instructor</h1>
    </div>
    <div class="w-full">
        <form action="" method="POST" class="flex flex-col gap-5" id="addInstructorForm">
            <input type="text" name="fname" placeholder="First Name" class="p-3 rounded-lg focus:outline-none shadow-lg" required>
            <input type="text" name="lname" placeholder="Last Name" class="p-3 rounded-lg focus:outline-none shadow-lg" required>
            <input type="email" name="email" placeholder="Email" class="p-3 rounded-lg focus:outline-none shadow-lg" required>
            <input type="password" name="password" id="password" placeholder="Password" class="p-3 rounded-lg focus:outline-none shadow-lg" required>
            <button type="submit" class="p-3 rounded-lg shadow-lg bg-violet-500 hover:bg-violet-600">Submit</button>
        </form>
    </div>
    <?php if ($validationMessage): ?>
        <div class="mt-2 <?php echo $validationMessageColor; ?>"><?php echo $validationMessage; ?></div>
    <?php endif; ?>
</div>

<script>
    function clearPasswordField() {
        document.getElementById('password').value = '';
    }

    <?php if (strpos($validationMessage, 'Password') !== false && $validationMessageColor === "text-red-500"): ?>
        clearPasswordField();
    <?php endif; ?>

    <?php if ($validationMessageColor == "text-black"): ?>
        document.getElementById('addInstructorForm').reset();
    <?php endif; ?>
</script>