const passwordToggle = document.querySelector('[data-password-toggle]');
const passwordInput = document.querySelector('#login-password');

if (passwordToggle instanceof HTMLButtonElement && passwordInput instanceof HTMLInputElement) {
    passwordToggle.addEventListener('click', () => {
        const isVisible = passwordInput.type === 'text';

        passwordInput.type = isVisible ? 'password' : 'text';
        passwordToggle.setAttribute('aria-pressed', String(!isVisible));
        passwordToggle.setAttribute(
            'aria-label',
            isVisible ? 'Tampilkan password' : 'Sembunyikan password',
        );
    });
}

// Quick demo account auto-fill helper
document.querySelectorAll('[data-demo-user]').forEach((button) => {
    button.addEventListener('click', () => {
        const usernameInput = document.querySelector('input[name="username"]');
        const passwordInput = document.querySelector('input[name="password"]');
        const username = button.getAttribute('data-demo-user');
        const password = button.getAttribute('data-demo-pass');

        if (usernameInput instanceof HTMLInputElement) {
            usernameInput.value = username || '';
            usernameInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
        if (passwordInput instanceof HTMLInputElement) {
            passwordInput.value = password || '';
            passwordInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
    });
});

// Close user menu dropdown when clicking outside
document.addEventListener('click', (event) => {
    const userDropdown = document.querySelector('.user-menu-dropdown[open]');
    if (userDropdown && !userDropdown.contains(event.target)) {
        userDropdown.removeAttribute('open');
    }
});

