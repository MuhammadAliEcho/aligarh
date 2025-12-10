module.exports = {
  prefix: 'tw-',
  important: false,
  purge: {
    enabled: process.env.NODE_ENV === 'production',
    content: [
      './resources/views/**/*.blade.php',
      './resources/assets/js/**/*.vue',
      './resources/assets/js/**/*.js',
    ],
  },
  theme: {
    extend: {},
    fontSize: {
      'xs': '12px',
      'sm': '14px',
      'base': '16px',
      'lg': '18px',
      'xl': '20px',
      '2xl': '24px',
      '3xl': '30px',
      '4xl': '36px',
      '5xl': '48px',
      '6xl': '60px',
      '7xl': '72px',
      '8xl': '96px',
      '9xl': '128px',
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
  corePlugins: {
    preflight: false, // Disable Tailwind's base reset to avoid conflicts with Bootstrap 3
  },
}
