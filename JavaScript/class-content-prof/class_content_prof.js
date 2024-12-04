const toggleUpload = document.getElementById('toggle-upload');
const uploadScreen = document.getElementById('upload-a-lesson');

// Show the upload modal when toggle button is clicked
toggleUpload.addEventListener('click', () => {
    uploadScreen.classList.remove('invisible');
});
