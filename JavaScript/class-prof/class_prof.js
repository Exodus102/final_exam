console.log("Script Loaded");

const showCreate = document.getElementById('create-class-prof');
const toggleButton = document.getElementById('create-new-class');
const toggleCancel = document.getElementById('cancel-button');


toggleButton.addEventListener('click', function() {
    showCreate.classList.remove('invisible');
});

toggleCancel.addEventListener('click', function() {
    showCreate.classList.add('invisible');
});


document.getElementById('add-button').addEventListener('click', function() {
    const subjectCodeElement = document.getElementById('subject-code');
    const subjectNameElement = document.getElementById('subject-name');
    const sectionElement = document.getElementById('section');

    const subjectCode = subjectCodeElement.value; 
    const subjectName = subjectNameElement.value;
    const section = sectionElement.value;

    const classCode = generateClassCode();  
    
    if (subjectCode && subjectName) {
        const formData = {
            subject_code: subjectCode,
            subject_name: subjectName,
            section: section,
            class_code: classCode 
        };
        
        checkClassCodeUniqueness(classCode, formData);
    } else {
        alert("Subject Code and Subject Name are required.");
    }
});


function generateClassCode() {
    return Math.floor(10000000 + Math.random() * 90000000);  
}


function checkClassCodeUniqueness(classCode, formData) {
    fetch('http://localhost/final_exam-main/PHP/services/add-class-prof/check-class-code.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ class_code: classCode }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
           
            console.log('Class code already exists. Generating new class code...');
            const newClassCode = generateClassCode();
            checkClassCodeUniqueness(newClassCode, formData);
        } else {
          
            insertClass(formData);
        }
    })
    .catch(error => {
        console.error('Error checking class code:', error);
    });
}


function insertClass(formData) {
    fetch('http://localhost/final_exam-main/PHP/services/add-class-prof/add_class_prof.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData),
    })
    .then(response => response.text())  
    .then(data => {
        try {
         
            const jsonData = JSON.parse(data);
            console.log('Class added:', jsonData);

          
            document.getElementById('subject-code').value = '';
            document.getElementById('subject-name').value = '';
            document.getElementById('section').value = '';
        } catch (error) {
            console.error('Error parsing JSON:', error);
            console.log('Raw response:', data);  
        }
    })
    .catch(error => {
        console.error('Error adding class:', error);
    });
}

