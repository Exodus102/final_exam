<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: ../PHP/login/login.php");
    exit;
}
require_once '../../PHP/services/config/db_connection.php';
$userId = $_SESSION['user_id'];
$sql_user = "SELECT fname, lname FROM tbl_prof WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
if ($stmt_user) {
    $stmt_user->bind_param("i", $userId);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    if ($result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc();
        $userName = htmlspecialchars($user['fname']) . " " . htmlspecialchars($user['lname']);
    } else {
        $userName = "Unknown User";
    }
    $stmt_user->close();
} else {
    die("Error preparing SQL statement for user: " . $conn->error);
}
$sql_classes = "SELECT subject_name, subject_code, section FROM class_prof WHERE user_id = ?";
$stmt_classes = $conn->prepare($sql_classes);
if ($stmt_classes) {
    $stmt_classes->bind_param("i", $userId);
    $stmt_classes->execute();
    $result_classes = $stmt_classes->get_result();
    if ($result_classes->num_rows > 0) {
        $classes = $result_classes->fetch_all(MYSQLI_ASSOC);
    } else {
        $classes = [];
    }
    $stmt_classes->close();
} else {
    die("Error preparing SQL statement for classes: " . $conn->error);
}
$conn->close();
?>
<div class="flex h-full">
    <div class="w-full sm:w-8/12">
        <div class="flex justify-between items-center mb-12">
            <div class="ml-10 flex flex-col mt-1">
                <span class="text-[#9D9D9D] text-xs ">
                    Dashboard > Classes
                </span>
                <span class="text-[#424040] text-3xl font-extrabold mt-3">
                    CLASSES
                </span>
                <span class="text-sm text-[#424040]">
                    Streamline class setup and communication.
                </span>
            </div>

            <div class="flex items-center w-60 h-12 rounded-lg bg-white shadow-[0_4px_6px_rgba(0,0,0,0.4)] mr-8 px-3">
                <img src="../../assets/icons/search.svg" alt="search icon" class="w-5 h-5 mr-3">
                <input
                    type="text"
                    placeholder="Search"
                    class="flex-1 h-full border-none focus:outline-none">
            </div>
        </div>

        <div class="flex justify-between">
            <span class="text-[#424040] ml-10">
                Classes
            </span>
            <span class="w-44 h-10 rounded-lg bg-white shadow-[0_4px_6px_rgba(0,0,0,0.4)] flex justify-center items-center text-sm mr-8
            ">
                <button id="create-new-class" class="">
                    <span class="font-bold text-2xl">+</span> Create New Class
                </button>
            </span>
        </div>

        <div id="create-class-prof" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 w-full invisible">
            <div class="w-1/2 h-3/4 bg-white rounded-xl flex flex-col justify-center items-center px-20 gap-5">
                <Span class="font-[500] mr-auto">
                    Create New Class
                </Span>
                <Label class="text-sm mr-auto">Subject Code(required)</Label>
                <input id="subject-code" type="text" placeholder="" class="w-full p-4 rounded-lg focus:outline-none shadow-lg">
                <Label class="text-sm mr-auto">Subject Name (required)</Label>
                <input id="subject-name" type="text" placeholder="" class="w-full p-4 rounded-lg focus:outline-none shadow-lg">
                <Label class="text-sm mr-auto">Section</Label>
                <input id="section" type="text" placeholder="" class="w-full p-4 rounded-lg focus:outline-none shadow-lg">
                <div class="flex ml-auto gap-5 mt-10">
                    <button id="cancel-button">Cancel</button>
                    <button id="add-button">Add</button>
                </div>
            </div>
        </div>

        <div class="flex m-5 flex-wrap gap-5">
            <?php if (!empty($classes)): ?>
                <?php foreach ($classes as $class): ?>
                    <div class="bg-[#C2B5E8] text-[#424040] w-60 h-60 flex flex-col justify-start
                        items-start rounded-lg shadow-[0_4px_6px_rgba(0,0,0,0.2)] px-3">
                        <div class="w-full bg-[#8A70D6] h-20 rounded-lg mt-3"></div>
                        <span class="font-[500] mt-2"><?= htmlspecialchars($class['subject_code']) ?></span>
                        <span class="font-[500] text-xs">
                            <?= htmlspecialchars($class['subject_name']) ?><br>
                            <?= htmlspecialchars($class['section']) ?>
                        </span>
                        <button class="mt-auto mb-5 ml-auto">
                            <span class="w-10 h-10 bg-[#8A70D6] rounded-xl flex justify-center items-center mt-2">
                                <img src="../../assets/icons/Path.svg" alt="View Details">
                            </span>
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-[#424040] ml-10">No classes found.</p>
            <?php endif; ?>
        </div>

    </div>

    <div class="mt-5 w-full ml-5 sm:w-1/3 flex flex-col" id="profile-area">
        <h1 class="font-bold">Profile</h1>
        <div class="w-full h-1/2 bg-[#EFEFEF] flex flex-col items-center shadow-lg rounded-xl mb-3">
            <button class="bg-white rounded-xl w-8 h-8 flex justify-center items-center mb-5 shadow-xl mt-4 ml-auto mr-5">
                <img src="../../assets/icons/edit.svg" alt="Edit" class="w-4 h-4">
            </button>
            <img src="../../assets/images/icon.png" alt="Profile Icon" class="w-16 h-16 mb-4">
            <span class="font-bold">
                <?php echo $userName; ?>
            </span>
            <span class="text-sm">
                Professor
            </span>
        </div>
        <div class="w-full h-1/2 bg-[#EFEFEF] rounded-xl shadow-lg flex flex-col items-center">
            <span class="mt-10 font-[500]">Audit Trail</span>
        </div>
    </div>

</div>