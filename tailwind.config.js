/** @type {import('tailwindcss').Config} */
export default {
  content: ["./index.html", "./src/**/*.{vue,js,ts,jsx,tsx}"],
  theme: {
    extend: {
      fontFamily: {
        sans: ["Roboto", "sans-serif"],
        audiowide: ["Audiowide", "sans-serif"],
      },
    },
  },
  plugins: [],
};
