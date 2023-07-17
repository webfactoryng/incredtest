/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./public/**/*.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  theme: {
    extend: {
      colors: {
        incredidark: '#222429',
        incrediorgange: '#ee7c32',
      },
      textColor: {
        'incrediorgange': 'var(--color-incrediorgange)',
      },
    },
  },
  variants: {},
  plugins: [],
}

