/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./vendor/livewire/livewire/**/*.blade.php",
    ],
    plugins: [require("daisyui")],
    daisyui: {
        themes: ["light", "dark"],
    },
};
