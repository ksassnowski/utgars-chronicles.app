const defaultTheme = require("tailwindcss/defaultTheme");
const colors = require("tailwindcss/colors");

module.exports = {
    darkMode: 'class',
    important: true,

    content: [
        "./resources/views/**/*.blade.php",
        "./resources/scripts/**/*.vue",
    ],

    theme: {
        extend: {
            backgroundImage: {
                'gradient-radial': 'radial-gradient(var(--gradient-color-stops))',
            },

            screens: {
                "2xl": "1700px",
                "3xl": "2040px",
            },

            width: {
                "1/7": "14.2857143%",
                112: "28rem",
                128: "32rem",
            },

            fontFamily: {
                sans: ["Inter var", ...defaultTheme.fontFamily.sans],
            },

            colors: {
                orange: colors.orange,
            },
        },
    },

    plugins: [require("@tailwindcss/forms")],
};
