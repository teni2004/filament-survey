import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
        './vendor/filament/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                'xxl': '1.5rem',
                '3xl': '3rem',
                '4xl': '4rem',
            },
            colors: {
                'orange': '#F35D22',
            },
            padding: {
                'bottom-0.3': '0 0 0.3rem 0', 
            }
        },
    },

    plugins: [forms],
};
