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
    <title>Sign Up</title>
</head>

<body class="font-poppins">
    <div class="flex flex-col h-screen w-screen bg-[#F3F3F3] p-5 sm:flex-row">
        <div class="flex flex-col w-full sm:w-2/5">
            <div class="flex w-full justify-between items-center mb-5">
                <div class="flex justify-center items-center">
                    <img src="../../../assets/logo/logoq.svg" alt="Logo">
                    <span class="text-3xl text-[#424040] font-medium ml-4">LMS</span>
                </div>
                <a href="../login/loginv2.php" class="text-[#424040]">Sign In</a>
            </div>

            <div class="flex flex-col h-full justify-center items-center p-5">
                <span class="font-extrabold text-4xl text-[#424040] mb-3">
                    SIGN UP
                </span>
                <span class="text-sm text-[#424040] mb-5">
                    Register your LMS account now.
                </span>

                <form method="post" class="w-full flex flex-col gap-3">
                    <input type="text" name="fname" placeholder="First Name" class="p-4 bg-white rounded" value="<?php echo isset($fname) ? htmlspecialchars($fname) : ''; ?>">
                    <input type="text" name="lname" placeholder="Last Name" class="p-4 bg-white rounded" value="<?php echo isset($lname) ? htmlspecialchars($lname) : ''; ?>">
                    <input type="text" name="email" placeholder="Email" class="p-4 bg-white rounded" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                    <input type="password" name="password" placeholder="Password" class="p-4 bg-white rounded">
                    <label for="password" class="text-slate-600 text-sm">Password should be<br>• Longer than 8 characters<br>• Contain a combination of lower and uppercases<br>• Contain special characters
                        

                    </label>
                    <button type="submit" class="text-sm bg-[#424040] text-[#FBFBFB] w-full rounded p-4">
                        Register
                    </button>
                </form>

                <?php if (!empty($errorMessage)) : ?>
                    <p class="text-red-500 mt-3"><?php echo htmlspecialchars($errorMessage); ?></p>
                <?php elseif (!empty($successMessage)) : ?>
                    <p class="text-green-500 mt-3"><?php echo htmlspecialchars($successMessage); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div>
            <img src="../../../assets/images/LMS.png" alt="LMS" class="w-full h-full">
        </div>
    </div>
    <script src="https://cdn.tailwindcss.com"></script>
</body>

</html>