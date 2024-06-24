import colors from "tailwindcss/colors";
import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                'gray' : {
                    ...colors.slate,
                },
        
                'primary': {
                    DEFAULT: '#50ADF8',
                    50: '#FFFFFF',
                    100: '#EDF7FE',
                    200: '#C6E4FD',
                    300: '#9ED2FB',
                    400: '#77BFFA',
                    500: '#50ADF8',
                    600: '#1A94F6',
                    700: '#0877D0',
                    800: '#06589A',
                    900: '#043964',
                    950: '#032A49'
                },
        
                'secondary': {
                    DEFAULT: '#F96335',
                    50: '#FEEDE7',
                    100: '#FEDDD3',
                    200: '#FDBFAC',
                    300: '#FBA084',
                    400: '#FA825D',
                    500: '#F96335',
                    600: '#EF3D07',
                    700: '#B82F05',
                    800: '#822104',
                    900: '#4B1302',
                    950: '#300C01'
                },
        
                accent: {
                    DEFAULT: '#F7F3F0',
                    50: '#FFFFFF',
                    100: '#FFFFFF',
                    200: '#FFFFFF',
                    300: '#FFFFFF',
                    400: '#FFFFFF',
                    500: '#F7F3F0',
                    600: '#E3D6CB',
                    700: '#D0B8A7',
                    800: '#BC9B82',
                    900: '#A97E5E',
                    950: '#997052'
                },
        
                success: {
                    ...colors.emerald,
                },
        
                warning: {
                    ...colors.amber,
                },
        
                danger: {
                    ...colors.rose,
                },
            },
        },
    },

    plugins: [forms],
};
