/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: ["../*.php", "../PHP/pages/login/*.php", "../PHP/pages/register/*.php", "../PHP/pages/dashboard-prof/*.php", "../PHP/panel-prof/*.php", "../PHP/pages/classes-prof/*.php",
    "../PHP/panel-admin/*.php", "../PHP/pages/add-account-admin/*.php", "../PHP/pages/add-prof-to-course/*.php", "../PHP/pages/home_stud/*.php", "../PHP/pages/classes-stud/*.php"
  ],
  theme: {
    extend: {
      fontFamily: {
        poppins: ['Poppins', 'sans-serif'],
      },
      colors: {
        'neon-blue': '#00FFFF', // Custom neon blue color for buttons
        'light-yellow': '#D4FF00', // Custom light yellow color
        'dark-background': '#121212', // Custom dark background color
      },
    },
  },
  variants: {
    extend: {
        // Extend variants for dark mode support if needed
        backgroundColor: ['dark'],
        textColor: ['dark'],
    },
  },
  plugins: [],
}

