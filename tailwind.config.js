/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/views/**/*.blade.php',  // Isso vai garantir que todas as views sejam monitoradas
    './resources/js/**/*.js', 
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php'
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

