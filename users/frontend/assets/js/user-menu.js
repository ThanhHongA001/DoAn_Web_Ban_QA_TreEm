document.addEventListener('DOMContentLoaded', () => {
    const userMenuBtn = document.getElementById('user-menu-btn');
    const userMenu = document.getElementById('user-menu');
    const guestMenu = document.getElementById('guest-menu');
    const userSettingsMenu = document.getElementById('user-settings-menu');
    const logoutBtn = document.getElementById('logout-btn');

    if (!userMenuBtn || !userMenu || !guestMenu || !userSettingsMenu || !logoutBtn) {
        console.error('User menu elements not found');
        return;
    }

    // Toggle user menu visibility
    userMenuBtn.addEventListener('click', () => {
        userMenu.classList.toggle('hidden');
    });

    // Close menu when clicking outside
    document.addEventListener('click', (event) => {
        if (!userMenu.contains(event.target) && !userMenuBtn.contains(event.target)) {
            userMenu.classList.add('hidden');
        }
    });

    // Check login status and update menu
    function updateUserMenu() {
        fetch('api/check_session.php')
            .then(response => response.json())
            .then(data => {
                if (data.isLoggedIn) {
                    guestMenu.classList.add('hidden');
                    userSettingsMenu.classList.remove('hidden');
                    // Display masked user name or email in the button
                    const displayName = maskName(data.user.name || data.user.email || 'Người dùng');
                    userMenuBtn.innerHTML = `
                        <span class="text-blue-600 font-semibold">${displayName}</span>
                        <svg class="w-6 h-6 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    `;
                } else {
                    guestMenu.classList.remove('hidden');
                    userSettingsMenu.classList.add('hidden');
                    userMenuBtn.innerHTML = `
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    `;
                }
            })
            .catch(error => {
                console.error('Error checking session:', error);
                guestMenu.classList.remove('hidden');
                userSettingsMenu.classList.add('hidden');
            });
    }

    // Mask name or email for display
    function maskName(input) {
        if (!input) return 'Người dùng';
        if (input.includes('@')) {
            const [local, domain] = input.split('@');
            return local.length <= 3 ? `${local}****@${domain}` : `${local.slice(0, 3)}****@${domain}`;
        }
        return input.length <= 3 ? input : `${input.slice(0, 3)}****${input.slice(-1)}`;
    }

    // Handle logout
    logoutBtn.addEventListener('click', (event) => {
        event.preventDefault();
        fetch('api/logout.php', { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = 'login.html';
                } else {
                    alert(data.message || 'Lỗi khi đăng xuất');
                }
            })
            .catch(error => {
                console.error('Error logging out:', error);
                alert('Lỗi khi đăng xuất');
            });
    });

    // Initial menu update
    updateUserMenu();
});