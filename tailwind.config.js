const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
    purge: {
        content: [
            "./resources/views/**/*.blade.php",
            "./resources/js/**/*.vue"
        ],
        options: {
            whitelistPatternsChildren: [/tippy/, /nprogress/]
        }
    },
    theme: {
        extend: {
            fontFamily: {
                sans: ["Inter var", ...defaultTheme.fontFamily.sans]
            }
        }
    },
    variants: {
        visibility: ["responsive", "group-hover"],
        opacity: ["disabled"],
        cursor: ["disabled"]
    },
    plugins: []
};
