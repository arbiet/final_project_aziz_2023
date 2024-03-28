/** @type {import('tailwindcss').Config} */
module.exports = {
  mode: 'jit',
  content: ["./public/**/*.{html,js,php}"],
  theme: {
    extend: {
      fontFamily: {
        inter: ['Inter'],
      },
      colors: {
      },
    },
    plugins: [],
  }
}