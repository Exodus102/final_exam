<?php
include '../../services/config/db_connection.php';

$errorMessage = ""; // Initialize error message variable
$successMessage = ""; // Initialize success message variable

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = strtolower(trim($_POST['email']));
    $password = trim($_POST['password']);

    // Validate the email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid Email";
    } else {
        try {
            // Check if the email already exists in the student and professor tables
            $checkEmailStudent = $conn->prepare('SELECT COUNT(*) FROM tbl_student WHERE email = ?');
            $checkEmailStudent->bind_param('s', $email);
            $checkEmailStudent->execute();
            $checkEmailStudent->bind_result($studentCount);
            $checkEmailStudent->fetch();
            $checkEmailStudent->close();

            $checkEmailProf = $conn->prepare('SELECT COUNT(*) FROM tbl_prof WHERE email = ?');
            $checkEmailProf->bind_param('s', $email);
            $checkEmailProf->execute();
            $checkEmailProf->bind_result($profCount);
            $checkEmailProf->fetch();
            $checkEmailProf->close();

            if ($studentCount > 0 || $profCount > 0) {
                $errorMessage = "Email already exists. You cannot register again with this email.";
            } else {
                // Validate the password if the email does not exist
                if (!preg_match('/^(?=.*\d)(?=.*[a-zA-Z]).{8,}$/', $password)) {
                    $errorMessage = "Password needs to be at least 8 characters with letters and numbers.";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                    $conn->begin_transaction();
                    // Default to adding the user to the student table
                    $statement = $conn->prepare('INSERT INTO tbl_student (email, password, fname, lname) VALUES (?, ?, ?, ?)');
                    $statement->bind_param('ssss', $email, $hashed_password, $fname, $lname);
                    $statement->execute();
                    $conn->commit();

                    $successMessage = "Sign Up Successful! You can now log in."; // Success message
                    // Do not redirect immediately here, handle it with JavaScript after the page loads
                }
            }
        } catch (mysqli_sql_exception $e) {
            $conn->rollback();
            if ($e->getCode() === 23000) {
                $errorMessage = "Email already exists.";
            } else {
                error_log($e->getMessage(), 3, 'error_log.txt');
                $errorMessage = "An error occurred. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../Tailwind/css/tailwind.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Register</title>
    <style>
        #submitBtn:disabled {
            cursor: not-allowed;
            /* Changes the cursor to 'not-allowed' */
        }
    </style>
</head>

<body class="font-poppins">
    <div class="lg:h-screen flex flex-col-reverse lg:flex-row p-5 gap-4">
        <div class="w-full lg:w-2/3 flex flex-col justify-center items-start">
            <h1 class="font-semibold text-5xl lg:text-7xl mb-5">
                Register
            </h1>
            <form method="post" class="w-full">
                <input type="text" name="fname" placeholder="First Name" class="w-full p-3 bg-gray-300 rounded placeholder-gray-500 mb-5" value="<?php echo isset($fname) ? htmlspecialchars($fname) : ''; ?>"><br>
                <input type="text" name="lname" placeholder="Last Name" class="w-full p-3 bg-gray-300 rounded placeholder-gray-500 mb-5" value="<?php echo isset($lname) ? htmlspecialchars($lname) : ''; ?>"><br>
                <input type="text" name="email" placeholder="email" class="w-full p-3 bg-gray-300 rounded placeholder-gray-500" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"><br>
                <input type="password" name="password" placeholder="password" class="mt-5 w-full p-3 mb-5 bg-gray-300 rounded placeholder-gray-500"><br>
                <div class="w-full flex justify-center items-center bg-[#D4FF00] rounded-lg p-3">
                    <button type="submit" id="submitBtn" class="w-full" <?php echo !empty($successMessage) ? 'disabled' : ''; ?>>
                        Sign Up
                    </button>
                </div>
                <?php if (!empty($errorMessage)) : ?>
                    <p class="text-red-500 mt-3 text-center"><?php echo htmlspecialchars($errorMessage); ?></p>
                <?php elseif (!empty($successMessage)) : ?>
                    <p class="text-green-500 mt-3 text-center"><?php echo htmlspecialchars($successMessage); ?></p>
                <?php endif; ?>
            </form>
        </div>
        <div class="w-full lg:w-2/3 bg-[#D4FF00] flex justify-center lg:justify-end items-center rounded p-5">
            <h1 class="text-3xl lg:text-7xl font-semibold lg:text-right md:text-6xl">
                Register to start<br> the unstoppable <br>learning.
            </h1>
        </div>
    </div>

    <script>
        // If the sign-up is successful, wait for 3 seconds and then redirect to login page
        <?php if (!empty($successMessage)) : ?>
            setTimeout(function() {
                window.location.href = '../login/login.php';
            }, 3000); // 3000 ms = 3 seconds delay
        <?php endif; ?>
    </script>
</body>

</html>