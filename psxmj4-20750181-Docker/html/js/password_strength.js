// html/cw/js/password_strength.js
document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('new_password');
    const feedbackText = document.getElementById('strength-text');
    const feedbackBar = document.getElementById('strength-bar-fill');

    if (passwordInput && feedbackText && feedbackBar) {
        passwordInput.addEventListener('input', function () {
            const val = passwordInput.value;
            let score = 0;

            // Simple rules
            if (val.length >= 8) score++;
            if (val.match(/[a-z]/)) score++;
            if (val.match(/[A-Z]/)) score++;
            if (val.match(/[0-9]/)) score++;
            if (val.match(/[^a-zA-Z0-9]/)) score++;

            // Visual Logic
            let color = "#e0e0e0";
            let width = "0%";
            let text = "";

            if (val.length > 0) {
                if (score < 3) {
                    color = "#f44336"; // Red
                    width = "30%";
                    text = "Weak";
                } else if (score < 5) {
                    color = "#ff9800"; // Orange
                    width = "60%";
                    text = "Medium";
                } else {
                    color = "#4CAF50"; // Green
                    width = "100%";
                    text = "Strong";
                }
            }

            feedbackText.textContent = text;
            feedbackText.style.color = color;
            feedbackBar.style.width = width;
            feedbackBar.style.backgroundColor = color;
        });

        const form = document.querySelector('form.styled-form');
        if (form) {
            form.addEventListener('submit', function (e) {
                const val = passwordInput.value;
                let score = 0;
                if (val.length >= 8) score++;
                if (val.match(/[a-z]/)) score++;
                if (val.match(/[A-Z]/)) score++;
                if (val.match(/[0-9]/)) score++;
                if (val.match(/[^a-zA-Z0-9]/)) score++;

                if (score < 3) {
                    e.preventDefault();
                    alert("Password is too weak. Please choose a stronger password.");
                }
            });
        }
    }
});