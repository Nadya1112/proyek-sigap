module.exports = {
  purge: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
      colors: {
        'sigap-yellow': '#FFD400',
      },
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
