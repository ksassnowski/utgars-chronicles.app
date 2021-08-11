const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
    mode: "jit",

    important: true,

    purge: {
        content: [
            "./resources/views/**/*.blade.php",
            "./resources/js/**/*.vue",
        ],
        options: {
            whitelistPatternsChildren: [/nprogress/],
        },
    },
    theme: {
        extend: {
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
        },
    },
    plugins: [require("@tailwindcss/forms")],
};
