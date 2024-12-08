<?php
require_once '../../PHP/services/config/db_connection.php';
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to access this page.";
    header("Location: ../PHP/login/login.php");
    exit;
}

//select *
$query = "select * from tbl_student;";
$result = mysqli_query($conn, $query);
$students = mysqli_fetch_all($result, MYSQLI_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Tailwind/css/tailwind.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Leanring MS</title>
    <title>Document</title>
</head>

<body class="font-poppins">
    <div class="flex flex-col items-center mt-20">
        <div class="flex flex-col">
            <span class="text-4xl text-center mb-10">Users</span>
            <div class="flex justify-between mb-5">
                <span id="userType" class="text-2xl">Students</span>
                <input id="searchInput" type="text" placeholder="Search" class="pl-4 border rounded-lg border-2">
            </div>

            <div class="overflow-x-auto border-2 border-[#000000] rounded-lg overflow-y-auto h-3/4">
                <table id="userTable" class="min-w-full rounded-lg text-center border-collapse ">
                    <thead class="bg-[#E9E3FF]">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Password</th>
                            <th scope="col" class="px-6 py-3">First Name</th>
                            <th scope="col" class="px-6 py-3">Last Name</th>
                            <th scope="col" class="px-6 py-3 ">Profile Pic</th>
                            <th scope="col" class="px-6 py-3 ">Edit</th>
                            <th scope="col" class="px-6 py-3 ">Delete</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php foreach ($students as $student) {
                            // if students get clicked
                        ?>
                            <tr class="hover:bg-gray-100">
                                <td><?php echo $student['id']; ?></td>
                                <td><?php echo $student['email']; ?></td>
                                <td><?php echo substr($student['password'], 0, 5) . str_repeat('*', 5); ?></td>
                                <td><?php echo $student['fname']; ?></td>
                                <td><?php echo $student['lname']; ?></td>
                                <td class="pl-10">
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($student['profile_pic']); ?>"
                                        alt="Profile Picture" class="w-10 h-10 rounded-full">
                                </td>
                                <td>
                                    <button class="editBtn"
                                        data-id="<?php echo $student['id']; ?>"
                                        data-email="<?php echo $student['email']; ?>"
                                        data-fname="<?php echo $student['fname']; ?>"
                                        data-lname="<?php echo $student['lname']; ?>">
                                        Edit
                                    </button>
                                </td>
                                <td>
                                    <button class="deleteBtn"
                                        data-id="<?php echo $student['id']; ?>">
                                        <span class="text-rose-900">Delete</span> 
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
                <div class="bg-white p-5 rounded-lg w-1/3">
                    <h2 class="text-2xl mb-4">Edit User</h2>
                    <form id="editForm">
                        <input type="hidden" id="editId">
                        <div class="mb-4">
                            <label for="editEmail" class="block">Email:</label>
                            <input type="email" id="editEmail" class="w-full border px-4 py-2 rounded">
                        </div>
                        <div class="mb-4">
                            <label for="editFname" class="block">First Name:</label>
                            <input type="text" id="editFname" class="w-full border px-4 py-2 rounded">
                        </div>
                        <div class="mb-4">
                            <label for="editLname" class="block">Last Name:</label>
                            <input type="text" id="editLname" class="w-full border px-4 py-2 rounded">
                        </div>
                        <div class="flex gap-4">
                            <button type="button" id="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Attach event listeners after DOM is fully loaded
        document.addEventListener('DOMContentLoaded', () => {
            // Edit button functionality
            document.querySelectorAll('.editBtn').forEach(button => {
                button.addEventListener('click', function() {
                    const modal = document.getElementById('editModal');
                    modal.classList.remove('hidden');

                    // Populate modal fields with current data
                    document.getElementById('editId').value = this.dataset.id;
                    document.getElementById('editEmail').value = this.dataset.email;
                    document.getElementById('editFname').value = this.dataset.fname;
                    document.getElementById('editLname').value = this.dataset.lname;
                });
            });

            // Close the modal
            document.getElementById('closeModal').addEventListener('click', () => {
                document.getElementById('editModal').classList.add('hidden');
            });

            // Delete button functionality
            document.querySelectorAll('.deleteBtn').forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.dataset.id;

                    if (confirm('Are you sure you want to delete this user?')) {
                        fetch("delete_student.php", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    id: userId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('User deleted successfully!');
                                    location.reload(); // Refresh the page
                                } else {
                                    alert('Failed to delete user!');
                                }
                            });
                    }
                });
            });
        });

        document.getElementById('editForm').addEventListener('submit', function(event) {
            event.preventDefault();

            // Collect form data
            const id = document.getElementById('editId').value;
            const email = document.getElementById('editEmail').value;
            const fname = document.getElementById('editFname').value;
            const lname = document.getElementById('editLname').value;

            // Send the update request
            fetch('update_student.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id,
                        email,
                        fname,
                        lname
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('User updated successfully!');
                        location.reload(); // Refresh the page to reflect changes
                    } else {
                        alert(`Failed to update user: ${data.message || 'Unknown error'}`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the user.');
                });
        });


        // Search Functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#userTable tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>

</html>