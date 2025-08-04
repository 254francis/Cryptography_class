// Login form submission
const loginForm = document.getElementById('loginForm');
if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(loginForm);

        const response = await fetch('login.php', {
            method: 'POST',
            body: formData
        });

        const text = await response.text();
        document.getElementById('loginMessage').textContent = text;

        if (text.includes('success')) {
            localStorage.setItem('loggedIn', 'true');
            window.location.href = 'dashboard.html';
        }
    });
}

// Card form submission
const cardForm = document.getElementById('cardForm');
if (cardForm) {
    cardForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(cardForm);

        const response = await fetch('insert_card.php', {
            method: 'POST',
            body: formData
        });

        const text = await response.text();
        document.getElementById('cardMessage').textContent = text;
        cardForm.reset();
    });
}

// Logout button logic
const logoutBtn = document.getElementById('logoutBtn');
if (logoutBtn) {
    logoutBtn.addEventListener('click', () => {
        localStorage.removeItem('loggedIn');
        window.location.href = 'login.html';
    });
}
