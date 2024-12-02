<?php
include '../../services/config/db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);
$classCode = $data['classCode'];

if (isset($classCode)) {
    // Query to update the course to archived status
    $stmt = $conn->prepare("UPDATE class_prof SET archived = 1 WHERE class_code = ?");
    $stmt->bind_param("i", $classCode);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to archive course.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Class code is missing.']);
}


// Fetch archived courses
$query = "SELECT * FROM class_prof WHERE archived = 1";
$result = $conn->query($query);

$archived_courses = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $archived_courses[] = $row;
    }
}

// Output as JSON for front-end
echo json_encode($archived_courses);
?>


<script>
    document.addEventListener("DOMContentLoaded", () => {
    const archivedContainer = document.getElementById("archived-container");

    // Fetch archived courses from the server
    fetch("fetch_archived_courses.php")
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data)) {
                data.forEach(course => {
                    const archivedCourseCard = document.createElement("div");
                    archivedCourseCard.classList.add("flex");
                    archivedCourseCard.innerHTML = `
                        <div class="bg-[#C2B5E8] text-[#424040] w-72 grid grid-col justify-center rounded-lg shadow-xl p-5 gap-2" id="${course.classCode}">
                            <span class="flex flex-col">
                                <img src="img/Digital_technology.jpg" alt="Course Image" class="rounded-md h-25 w-60 self-center">
                            </span>
                            <div class="flex flex-col w-72 p-4">
                                <span class="font-bold text-left text-3xl truncate leading-8">${course.subjectName}</span>
                                <span>${course.professorName}</span>
                            </div>
                        </div>
                    `;
                    archivedContainer.appendChild(archivedCourseCard);
                });
            }
        })
        .catch(error => {
            console.error("Error fetching archived courses:", error);
        });
});
</script>