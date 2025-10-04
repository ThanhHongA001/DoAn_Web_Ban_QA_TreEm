// scripts.js
document.addEventListener('DOMContentLoaded', () => {
    // Initialize cart count
    fetchCartCount();
    // Load discount codes
    fetchDiscountCodes();
    // Load featured products
    fetchFeaturedProducts();
    // Load best-selling products
    fetchBestSellingProducts();
    // Initialize chat
    initChat();
    // Initialize search history
    renderHistory();
});

// ================== LỊCH SỬ TÌM KIẾM (localStorage) ==================
const HISTORY_KEY = 'searchHistory';
function getHistory() {
    try { return JSON.parse(localStorage.getItem(HISTORY_KEY)) || []; } catch { return []; }
}
function saveHistory(term) {
    if (!term) return;
    let hist = getHistory().filter(x => x.toLowerCase() !== term.toLowerCase());
    hist.unshift(term);
    if (hist.length > 8) hist = hist.slice(0, 8);
    localStorage.setItem(HISTORY_KEY, JSON.stringify(hist));
    renderHistory();
}
function clearSearchHistory() {
    localStorage.removeItem(HISTORY_KEY);
    renderHistory();
}
function renderHistory() {
    const wrap = document.getElementById('search-history');
    if (!wrap) return;
    const hist = getHistory();
    wrap.innerHTML = hist.map(h => `
        <button class="px-3 py-1 rounded-full border text-sm hover:bg-blue-50"
            onclick="document.getElementById('search-input').value='${h.replace(/'/g,"&#39;")}'; searchProducts();">
            ${h}
        </button>
    `).join('');
}

// ================== GỢI Ý TỪ KHÓA (backend/search_suggestions.php) ==================
let suggestAbort;
async function fetchSearchSuggestions() {
    const box = document.getElementById('search-input');
    const panel = document.getElementById('search-suggestions');
    const q = box.value.trim();
    if (!panel) return;

    if (q.length < 2) { panel.classList.add('hidden'); panel.innerHTML=''; return; }

    try {
        if (suggestAbort) suggestAbort.abort();
        suggestAbort = new AbortController();

        const res = await fetch(`api/category/search_suggestions.php?q=${encodeURIComponent(q)}`, {signal: suggestAbort.signal});
        const items = await res.json();

        const hist = getHistory().filter(h => h.toLowerCase().includes(q.toLowerCase())).slice(0,3)
            .map(h => ({ code: null, name: h, _isHistory: true }));

        const merged = [...hist, ...items];
        if (!merged.length) { panel.classList.add('hidden'); panel.innerHTML=''; return; }

        panel.innerHTML = merged.map(it => `
            <div class="px-3 py-2 hover:bg-blue-50 cursor-pointer flex items-center justify-between"
                onclick="onSelectSuggestion('${(it.name||'').replace(/'/g,"&#39;")}', '${it.code ?? ''}')">
                <span>${it._isHistory ? '🕘 ' : '🔎 '}${it.name}</span>
                ${it.code ? `<span class="text-xs text-gray-500">#${it.code}</span>` : ''}
            </div>
        `).join('');
        panel.classList.remove('hidden');
    } catch(e) {
        panel.classList.add('hidden'); panel.innerHTML='';
    }
}
function onSelectSuggestion(name, code) {
    const box = document.getElementById('search-input');
    box.value = name;
    document.getElementById('search-suggestions').classList.add('hidden');
    if (code) {
        // Nếu gợi ý là sản phẩm cụ thể -> sang trang chi tiết
        window.location.href = `product-details.html?code=${code}`;
    } else {
        searchProducts();
    }
}
document.addEventListener('click', (e) => {
    const panel = document.getElementById('search-suggestions');
    const box = document.getElementById('search-input');
    if (!panel || !box) return;
    if (!panel.contains(e.target) && e.target !== box) {
        panel.classList.add('hidden');
    }
});

// ================== TÌM KIẾM SẢN PHẨM ==================
function searchProducts() {
    const query = document.getElementById('search-input').value.trim();
    if (query) {
        saveHistory(query);
        window.location.href = `category.html?q=${encodeURIComponent(query)}`;
    }
}

// Fetch cart count
function fetchCartCount() {
    fetch('api/cart.php?action=count')
        .then(response => response.json())
        .then(data => {
            document.getElementById('cart-count').textContent = data.count || 0;
        })
        .catch(error => console.error('Error fetching cart count:', error));
}

// Fetch discount codes
function fetchDiscountCodes() {
    fetch('api/vouchers.php')
        .then(response => response.json())
        .then(data => {
            const discountDiv = document.getElementById('discount-codes');
            discountDiv.innerHTML = '';
            data.forEach(voucher => {
                const div = document.createElement('div');
                div.className = 'bg-white p-6 rounded-lg shadow-md text-center';
                div.innerHTML = `
                    <h3 class="text-xl font-semibold text-pink-600">${voucher.code}</h3>
                    <p class="text-gray-600">${voucher.description}</p>
                    <p class="text-blue-600 font-bold">${voucher.discount_percent}% OFF</p>
                    <p class="text-gray-500 text-sm">Hết hạn: ${voucher.expiry_date}</p>
                `;
                discountDiv.appendChild(div);
            });
        })
        .catch(error => console.error('Error fetching discount codes:', error));
}

// Fetch featured products
function fetchFeaturedProducts() {
    fetch('api/products.php?type=featured')
        .then(response => response.json())
        .then(data => {
            const productsDiv = document.getElementById('featured-products');
            productsDiv.innerHTML = '';
            data.forEach(product => {
                let imagePath = '';
                try {
                    const images = JSON.parse(product.image);
                    imagePath = images.length > 0 ? images[0] : 'assets/images/no-image.png';
                } catch (e) {
                    imagePath = product.image || 'assets/images/no-image.png';
                }

                const div = document.createElement('div');
                div.className = 'product-card bg-white p-6 rounded-lg shadow-md text-center';
                div.innerHTML = `
                    <img src="${imagePath}" alt="${product.name}" class="h-40 mx-auto mb-4 rounded">
                    <h3 class="text-lg font-semibold text-blue-600">${product.name}</h3>
                    <div class="text-gray-600">
                        <span class="line-through">${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(product.original_price)}</span>
                        <span class="text-pink-600 font-bold ml-2">${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(product.discounted_price)}</span>
                    </div>
                    <a href="product-details.html?code=${product.code}" class="mt-2 inline-block bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600">Xem chi tiết</a>
                `;
                productsDiv.appendChild(div);
            });
        })
        .catch(error => console.error('Error fetching featured products:', error));
}

// Fetch best-selling products
function fetchBestSellingProducts() {
    fetch('api/products.php?type=best-selling')
        .then(response => response.json())
        .then(data => {
            const productsDiv = document.getElementById('best-selling-products');
            productsDiv.innerHTML = '';
            data.forEach(product => {
                let imagePath = '';
                try {
                    const images = JSON.parse(product.image);
                    imagePath = images.length > 0 ? images[0] : 'assets/images/no-image.png';
                } catch (e) {
                    imagePath = product.image || 'assets/images/no-image.png';
                }

                const div = document.createElement('div');
                div.className = 'product-card bg-white p-6 rounded-lg shadow-md text-center';
                div.innerHTML = `
                    <img src="${imagePath}" alt="${product.name}" class="h-40 mx-auto mb-4 rounded">
                    <h3 class="text-lg font-semibold text-blue-600">${product.name}</h3>
                    <div class="text-gray-600">
                        <span class="line-through">${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(product.original_price)}</span>
                        <span class="text-pink-600 font-bold ml-2">${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(product.discounted_price)}</span>
                    </div>
                    <a href="product-details.html?code=${product.code}" class="mt-2 inline-block bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600">Xem chi tiết</a>
                `;
                productsDiv.appendChild(div);
            });
        })
        .catch(error => console.error('Error fetching best-selling products:', error));
}
