/** @type {import('tailwindcss').Config} */
const colors = require('tailwindcss/colors');
export default {
  content: ["./index.html", "./src/**/*.{vue,js,ts,jsx,tsx}"],
  theme: {
    extend: {
      fontFamily: {
        sans: ["Roboto", "sans-serif"],
        audiowide: ["Audiowide", "sans-serif"],
      },
    },
    colors: {
      primaryGreen: "#17ae9f",
      primaryPink: "#ff4281",
      darkPink: "#C33363",
      ...colors,
    },
    boxShadow: {
      'pinkBox': '0px 0px 15px #ff4281',
      'pinkText': '0px 4px 15px #ff0f5f, 0px 2px 11px #535353',
      'greenShadow': '0px 4px 15px #17ae9f, 0px 2px 11px #535353',
    },
   
  },
  plugins: [],
};
