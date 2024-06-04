/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./resources/views/layouts/*.blade.php"],
  theme: {
    extend: {},
    colors: {
      'ciano': {
        100: '#5bb4ff',
        200: '#a5f3fc',
        300: '#67e8f9',
        400: '#22d3ee',
        500: '#06b6d4',
        600: '#0891b2',
        700: '#0e7490',
        800: '#155e75',
        900: '#164e63',
      },
    }
  },
  plugins: [],
}

