// js/signup.js

document.addEventListener('DOMContentLoaded', () => {
    const signupForm = document.getElementById('signup-form');
    const signupMessage = document.getElementById('signup-message');
    const registerBtn = document.getElementById('register-btn');

    if (signupForm) {
        signupForm.addEventListener('submit', async (event) => {
            event.preventDefault(); // Stop the form from submitting normally

            signupMessage.textContent = '';
            signupMessage.classList.add('hidden');
            registerBtn.disabled = true; // Disable button during submission
            registerBtn.textContent = 'Registering...'; // Change button text

            const username = signupForm.username.value;
            const email = signupForm.email.value;
            const password = signupForm.password.value;
            const confirm_password = signupForm.confirm_password.value;

            try {
                const response = await fetch('api/register.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, email, password, confirm_password })
                });

                const data = await response.json();

                signupMessage.classList.remove('hidden');
                if (data.success) {
                    signupMessage.textContent = data.message;
                    signupMessage.classList.remove('text-red-600');
                    signupMessage.classList.add('text-green-600');
                    signupForm.reset(); // Clear the form
                    // Optional: Redirect to login page after successful registration
                    setTimeout(() => {
                        window.location.href = 'login.html';
                    }, 2000); // Redirect after 2 seconds
                } else {
                    signupMessage.textContent = data.message;
                    signupMessage.classList.remove('text-green-600');
                    signupMessage.classList.add('text-red-600');
                }
            } catch (error) {
                console.error('Registration error:', error);
                signupMessage.classList.remove('hidden');
                signupMessage.classList.remove('text-green-600');
                signupMessage.classList.add('text-red-600');
                signupMessage.textContent = 'An error occurred during registration. Please try again.';
            } finally {
                registerBtn.disabled = false; // Re-enable button
                registerBtn.textContent = 'Register'; // Restore button text
            }
        });
    }
});