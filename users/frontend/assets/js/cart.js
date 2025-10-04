function getLocalCart() {
    const cart = localStorage.getItem('cart');
    return cart ? JSON.parse(cart) : [];
}

function addToLocalCart(item) {
    let cart = getLocalCart();
    const existingItem = cart.find(
        i => i.product_code === item.product_code && i.variant_id === item.variant_id
    );
    if (existingItem) {
        existingItem.quantity += item.quantity;
    } else {
        cart.push(item);
    }
    localStorage.setItem('cart', JSON.stringify(cart));
}

function updateLocalCartQuantity(index, quantity) {
    let cart = getLocalCart();
    if (cart[index]) {
        cart[index].quantity = parseInt(quantity);
        localStorage.setItem('cart', JSON.stringify(cart));
    }
}

function removeFromLocalCart(index) {
    let cart = getLocalCart();
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
}

function clearLocalCart() {
    localStorage.removeItem('cart');
}

function updateCartCount() {
    fetch('api/check_session.php')
        .then(response => response.json())
        .then(data => {
            if (data.isLoggedIn) {
                fetch('api/cart.php?action=count')
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('cart-count').textContent = data.count || 0;
                    })
                    .catch(error => console.error('Error fetching cart count:', error));
            } else {
                const cart = getLocalCart();
                const total = cart.reduce((sum, item) => sum + item.quantity, 0);
                document.getElementById('cart-count').textContent = total;
            }
        });
}

function syncLocalCartWithServer() {
    const cart = getLocalCart();
    if (cart.length === 0) return;

    fetch('api/cart.php?action=sync', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ items: cart })
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                clearLocalCart();
                updateCartCount();
            } else {
                console.error('Error syncing cart:', data.message);
            }
        })
        .catch(error => console.error('Error syncing cart:', error));
}