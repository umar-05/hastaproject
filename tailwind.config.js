import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // --- ADD THESE CUSTOM COLORS ---
            colors: {
                hasta: {
                    red: '#c13939',      // The main deep red color
                    redHover: '#a83232', // A slightly darker red for hovers
                    yellow: '#f5a623',   // The accent yellow color
                }
            }
            // -------------------------------
        },
    },

    plugins: [forms],
};