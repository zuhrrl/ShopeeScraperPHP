module.exports = {
  purge: [
    './public/**/*.{vue,js,ts,jsx,tsx,php,html}',
    './home/**/*.{vue,js,ts,jsx,tsx,php,html}',
    './product/**/*.{vue,js,ts,jsx,tsx,php,html}',
    './admin/**/*.{vue,js,ts,jsx,tsx,php,html}'

  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {},
  },
  variants: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
    require('@tailwindcss/aspect-ratio'),
  ],
}
