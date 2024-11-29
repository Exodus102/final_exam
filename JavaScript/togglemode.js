console.log("Script Loaded!");
const toggleButton = document.getElementById('dark-mode-toggle');
const modeIcon = document.getElementById('mode-icon');
const buttons = document.querySelectorAll('.btn-neon');
const content = document.querySelector('.content');
const savedTheme = localStorage.getItem('theme');
if (savedTheme === 'dark') {
    document.querySelector('.image1').classList.add('shadow-lg', 'shadow-cyan-500/50');
    document.querySelector('.image2').classList.add('shadow-lg', 'shadow-blue-500/50');
    document.body.classList.add('dark-mode');
    modeIcon.textContent = '🌜'; 
    content.classList.remove('text-black'); 
    content.classList.add('text-white'); 
    buttons.forEach(button => {
        button.style.backgroundColor = '#00FFFF'; 
        button.style.color = 'black'; 
    });
} else {
    document.querySelector('.image1').classList.remove('shadow-lg', 'shadow-cyan-500/50');
    document.querySelector('.image2').classList.remove('shadow-lg', 'shadow-blue-500/50');
    document.body.classList.add('light-mode');
    modeIcon.textContent = '🌞'; 
    content.classList.remove('text-white');
    content.classList.add('text-black');
    buttons.forEach(button => {
        button.style.backgroundColor = '#D4FF00'; 
        button.style.color = 'black';
    });
}
toggleButton.addEventListener('click', () => {
    const isDarkMode = document.body.classList.contains('dark-mode');
    document.body.classList.toggle('dark-mode');
    buttons.forEach(button => {
        if (isDarkMode) {
            localStorage.setItem('theme', 'light');
            button.style.backgroundColor = '#D4FF00'; 
            button.style.color = 'black'; 
            content.classList.remove('text-white'); 
            content.classList.add('text-black'); 
            modeIcon.textContent = '🌞';
            document.querySelector('.image1').classList.remove('shadow-lg', 'shadow-cyan-500/50');
            document.querySelector('.image2').classList.remove('shadow-lg', 'shadow-blue-500/50');
        } else {
            localStorage.setItem('theme', 'dark');
            button.style.backgroundColor = '#00FFFF'; 
            button.style.color = 'black'; 
            content.classList.remove('text-black'); 
            content.classList.add('text-white'); 
            modeIcon.textContent = '🌜';
            document.querySelector('.image1').classList.add('shadow-lg', 'shadow-cyan-500/50');
            document.querySelector('.image2').classList.add('shadow-lg', 'shadow-blue-500/50');
        }
    });
});
