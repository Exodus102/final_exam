<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>Learning Module Syste</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }


        /*to make the sidebar flex for the user info*/
        .container {
            display: flex;
        }

        /*text styling*/

        p,
        li {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        li {
            font-size: 20px;
            padding-top: 20px;
            font-weight: 500;
        }

        /*module styling*/
        .module-title {
            font-size: 50px;
        }

        .module-subtitle {
            font-size: 20px;
        }

        /*sidebar styling*/
        .sidebar {

            display: flex;
            flex-direction: column;
            padding: 2%;
            background-color: #FFFFF7;
            box-shadow: 100;
            border-width: 1px;
            border-right-style: solid;
            width: 20%;
            gap: 30px;
            position: sticky;
            /*to make the sidebar follow*/
            height: fit-content;
            top: 0;
        }

        /*for the name and bio*/
        .user-section {
            display: flex;
            gap: 40px;
        }


        /*font size for username*/
        .username {
            font-size: 2em;
        }


        /*adjusting the name and bio*/
        .info {
            margin-top: 10px;
        }

        /*to make all images round*/
        img {
            border-radius: 50%;

        }

        /*for the img on the sidebar*/
        .pfp {
            height: 120px;
            width: 120px;
        }


        /*list on sidebar*/
        ul {
            list-style: none;
        }

        /*placeholder button*/
        .side-bar-button {
            margin-top: 98%;
            background-color: #FF1313;
            border-radius: 10px;
            border: 0px;
            padding: 20px;
            width: 50%;
            align-self: center;
            font-family: 'Poppins';
            font-size: 20px;
        }

        /*container of the whole modules*/
        .modules-container {
            display: flex;
            flex-direction: column;
            padding: 2%;
            width: 66%;
            height: 90%;
        }

        /*classes text sa loob ng module container*/
        .classes {
            font-size: 2rem;
            margin-bottom: 2%;
        }

        /*img for the module pfp*/
        .module-pfp {
            height: 150px;
            width: 150px;
            margin-left: 50px;
        }

        /*module styling*/
        .module {
            border-radius: 10px;
            padding: 10px;
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            height: 200px;
            border-style: solid;
            border-width: thin;
            box-shadow: 5px 5px 5px #aaaaaa;
            visibility: hidden;
            display: none;
        }

        /*container for the text if user is not enrolled onm any class*/
        .no-module {
            padding: 20px;
            margin-top: 20%;
            text-align: center;
            display: flex;
            flex-direction: column;
        }

        .no-module-title {
            font-size: 3rem;
            font-weight: 200;

        }



        /*module texts*/
        .module-info {
            margin-left: 20px;
        }

        .module-title,
        .module-subtitle {
            margin: 0;
            padding: 0;
        }

        /*style for the enroll button*/
        .add-button {
            padding: 10px;
            border-radius: 2px;
            border-width: 0;
            height: 50;
            width: 100px;
            font-family: 'Poppins';
        }

        /*modal container styling*/
        .modal-container {
            display: flex;
            pointer-events: none;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.3);
            opacity: 0;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            transition: opacity 0.3s ease;
        }

        /*show the modal when clicked, pang coditional lang*/
        .modal-container.show {
            pointer-events: auto;
            opacity: 1;
        }



        /*styling of the modal buttons*/
        .modal-buttons {
            padding: 10px 15px;
            border: 0;
            font-family: 'Poppins';
            border-radius: 5px;
        }

        /*styling of textfield on the add class modal*/
        .join-field {
            padding: 10px 15px;
            width: auto;
            height: 25px;
            font-size: 1rem;
        }

        #cancel {
            margin-left: 70%;
            margin-top: 20px;
        }

        /*container of the actual content of the modal*/
        .modal {
            width: 500px;
            background-color: white;
            padding: 50px;
            border-radius: 5px;
            max-width: 100%;
        }

        /*border that separates the textfield inside the modal content*/
        .border {
            border-style: solid;
            border-width: 1px;
            padding: 30px 30px 50px 30px;
            border-radius: 5px;
            margin-bottom: 20px;
            height: 80px;
            /* to adjust the 2 modal buttons below*/
        }

        .class-with-add {
            display: flex;
            justify-content: space-between;
        }

        @media (max-width: 768px) {

            /* Adjust sidebar styling */
            .sidebar {
                position: fixed;
                /* Make it fixed for easy toggling */
                top: 0;
                left: -100%;
                /* Hide it off-screen initially */
                width: 70%;
                /* Take up less space when shown */
                height: 100%;
                /* Full height of the screen */
                transition: left 0.3s ease;
                /* Smooth transition for showing/hiding */
                z-index: 1000;
                /* Ensure it stays on top */
            }

            /* When sidebar is active, slide it into view */
            .sidebar.active {
                left: 0;
            }

            .modules-container {
                margin-left: 0;
                /* Adjust modules container for smaller screens */
                width: 100%;
                /* Full width for the content area */
            }

            /* Toggle button for sidebar */
            .sidebar-toggle {
                display: block;
                /* Show toggle button on small screens */
                position: fixed;
                top: 10px;
                left: 10px;
                font-size: 2rem;
                cursor: pointer;
                z-index: 1100;
                /* Place above sidebar */
            }
        }
    </style>
</head>

<body>
    <div class="container">
    <i class="fa-solid fa-bars sidebar-toggle" id="sidebarToggle"></i>
        <div class="sidebar">
            <div class="user-section">
                <img src="img/nijika.jpg" alt="cute" class="pfp">
                <div class="info">
                    <p class="username">Nanaphilia</p>
                    <p>BSIT 3-2B</p>
                </div>
            </div>
            <div class="sidebar-content">
                <ul>
                    <li>Home</li>
                    <li>Profile Management</li>
                    <li>Verify your Email</li>
                    <li>Theme</li>
                    <li>Notifications</li>
                    <li>About</li>
                </ul>
            </div>
            <button class="side-bar-button">Logout</button>
        </div>
        <div class="modules-container">
            <div class="class-with-add">
                <p class="classes">Classes</p>
                <i class="fa-solid fa-plus fa-3x" id="add-class-icon"></i>
            </div>

            <div class="module">
                <img src="img/nijika.jpg" alt="cute" class="module-pfp">
                <div class="module-info">
                    <p class="module-title"></p>
                    <p class="module-subtitle"></p>
                </div>
            </div>

            <div class="no-module">
                <i class="fa-regular fa-face-sad-cry fa-10x"></i>
                <div class="module-info">
                    <p class="no-module-title">Looks like your not enrolled to a class</p>
                    <button class="add-button" id="add-class">Add Class</button>
                </div>
            </div>


        </div>
    </div>

    <div class="modal-container" id="modal_container">
        <div class="modal">
            <form action="index.php" method="POST" id="joinForm">
                <p>Join Class</p>
                <div class="border">
                    <p>Enter Class Code</p>
                    <input type="text" placeholder="Class Code" class="join-field" id="join_field">
                    <span id="error_message" style="color: red; font-size: 0.9em; display: none;"></span>
                </div>
                <button class="modal-buttons" id="cancel">Cancel</button>
                <input type="submit" value="Join" class="modal-buttons" id="join">
            </form>
        </div>
    </div>


    <script>
        // assigning elements to a variable
        const addclassIcon = document.getElementById('add-class-icon');
        const addclass = document.getElementById('add-class');
        const close = document.getElementById('cancel');
        const modalContainer = document.getElementById('modal_container');
        const joinField = document.getElementById('join_field');
        const joinButton = document.getElementById('join');
        const errorMessage = document.getElementById('error_message');
        const joinForm = document.getElementById('joinForm');

        addclass.addEventListener('click', () => { //open the modal
            modalContainer.classList.add('show');
            joinField.value = ''; // Clear the input field when opening
            errorMessage.style.display = 'none'; // Reset error message visibility
            errorMessage.textContent = ''; // Clear error message text
        });

        addclassIcon.addEventListener('click', () => { //open the modal
            modalContainer.classList.add('show');
            joinField.value = ''; // Clear the input field when opening
            errorMessage.style.display = 'none'; // Reset error message visibility
            errorMessage.textContent = ''; // Clear error message text
        });

        close.addEventListener('click', () => { // closing the modal
            event.preventDefault(); // Prevent form submission on "Cancel"
            modalContainer.classList.remove('show');
            errorMessage.style.display = 'none'; // Hide the error message
            errorMessage.textContent = ''; // Clear error message text
            joinField.value = ''; // Clear the input field when closing
        });






        // Array to mark the added classCodes
        const addedClassCodes = [];

        // Add a new class
        document.getElementById('joinForm').addEventListener('submit', (event) => {
            event.preventDefault(); // Prevent default form submission

            const classCode = joinField.value.trim();
            const errorMessage = document.getElementById("error_message");

            if (!classCode) {
                errorMessage.textContent = "Please enter a class code.";
                errorMessage.style.display = "block";
                return;
            }

            // Check if the class is already added
            if (addedClassCodes.includes(classCode)) {
                errorMessage.textContent = "This class is already added.";
                errorMessage.style.display = "block";
                return;
            }

            // Send the class code to the server
            fetch("checks.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        classCode
                    }),
                })
                .then(response => {
                    // Check if the response is not empty and is valid JSON
                    if (!response.ok) {
                        throw new Error('Server error');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Add the class code to the list of added classes
                        addedClassCodes.push(classCode);

                        // Create a new module
                        const newModule = document.createElement('div');
                        newModule.classList.add('module'); // Add the class module styles
                        newModule.style.visibility = "visible";
                        newModule.style.display = "flex";

                        // Add the module content
                        newModule.innerHTML = `
                    <img src="${data.image}" alt="Module Image" class="module-pfp">
                    <div class="module-info">
                        <p class="module-title">${data.title}</p>
                        <p class="module-subtitle">${data.subtitle}</p>
                    </div>
                `;

                        // Append the new module to the modules container
                        document.querySelector(".modules-container").appendChild(newModule);

                        // Hide the no-module div but keep it in the DOM
                        document.querySelector(".no-module").style.display = "none";

                        modalContainer.classList.remove("show"); // Close the modal
                    } else {
                        // Show error message
                        errorMessage.textContent = data.message;
                        errorMessage.style.display = "block";
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    errorMessage.textContent = "An unexpected error occurred. Please try again.";
                    errorMessage.style.display = "block";
                });
        });

        const sidebar = document.querySelector('.sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active'); // Show/hide sidebar
        });
    </script>
</body>

</html>

<?php
include 'db_connection.php';
?>