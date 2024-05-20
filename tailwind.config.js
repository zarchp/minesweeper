/** @type {import('tailwindcss').Config} */
export default {
  content: [
    // You will probably also need these lines
    './resources/**/**/*.blade.php',
    './resources/**/**/*.js',
    './app/View/Components/**/**/*.php',
    './app/Livewire/**/**/*.php',

    // Add mary
    './vendor/robsontenorio/mary/src/View/Components/**/*.php',
  ],
  theme: {
    extend: {
      colors: {
        // primary: '#3498db',
        // secondary: '#f1c40f',
      },
      spacing: {
        18: '4.5rem',
      },
      keyframes: {
        'pop-out': {
          '0%': { transform: 'scale(0)' },
          '50%': { transform: 'scale(1.2)' },
          '100%': { transform: 'scale(1)' },
        },
        'scale-up': {
          '0%': { transform: 'scale(0)' },
          '100%': { transform: 'scale(1)' },
        },
        'slide-up': {
          '0%': { transform: 'translateY(0%)' },
          '50%': { transform: 'translateY(-50%)' },
          '100%': { transform: 'translateY(-100%)' },
        },
        'slide-down': {
          '0%': { transform: 'translateY(0%)' },
          '50%': { transform: 'translateY(50%)' },
          '100%': { transform: 'translateY(100%)' },
        },
        'slide-left': {
          '0%': { transform: 'translateX(0%)' },
          '50%': { transform: 'translateX(-50%)' },
          '100%': { transform: 'translateX(-100%)' },
        },
        'slide-right': {
          '0%': { transform: 'translateX(0%)' },
          '50%': { transform: 'translateX(50%)' },
          '100%': { transform: 'translateX(100%)' },
        },
      },
      animation: {
        'pop-out': 'pop-out .2s ease-in-out',
        'scale-up': 'scale-up .2s ease-in-out',
        'slide-up': 'slide-up .2s linear',
        'slide-down': 'slide-down .2s linear',
        'slide-left': 'slide-left .2s linear',
        'slide-right': 'slide-right .2s linear',
      },
    },
  },

  // Add daisyUI
  plugins: [require('daisyui')],

  daisyui: {
    themes: true,
  },
};
