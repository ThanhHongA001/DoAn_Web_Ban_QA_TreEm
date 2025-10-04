async function checkAuth() {
    const response = await fetch('admin-api/auth/check-auth.php');
    const data = await response.json();
    if (data.status === 'error') {
        window.location.href = 'admin-login.html';
    }
}

document.addEventListener('DOMContentLoaded', checkAuth);