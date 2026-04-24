// Utility for Toast Notifications
function showToast(title, message) {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerHTML = `
        <i class="ph-fill ph-check-circle"></i>
        <div class="toast-content">
            <h4>${title}</h4>
            <p>${message}</p>
        </div>
    `;
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3500);
}

// Update Header Cart Badge
async function updateCartBadge() {
    try {
        const res = await fetch('api/cart_actions.php?action=get');
        const json = await res.json();
        if(json.status === 'success') {
            document.getElementById('cart-count').innerText = json.data.count;
        }
    } catch(err) {
        console.error("Error fetching cart count", err);
    }
}

// Storefront logic (Index)
async function loadProducts() {
    const container = document.getElementById('products-container');
    if(!container) return; // not on index

    try {
        const res = await fetch('api/products.php');
        const json = await res.json();
        
        if(json.status === 'success') {
            renderProducts(json.data);
        }
    } catch(err) {
        container.innerHTML = `<p style="grid-column: 1/-1; text-align: center; color: red;">Error cargando productos. Verifica el servidor.</p>`;
    }
}

function renderProducts(products) {
    const container = document.getElementById('products-container');
    container.innerHTML = ''; // clear skeletons

    products.forEach(p => {
        const priceParts = p.price.toFixed(2).split('.');
        const primeHtml = p.prime ? `<div class="prime-logo"><i class="ph-fill ph-check"></i> prime</div>` : '';
        
        // Generate stars
        let starsHtml = '';
        for(let i=1; i<=5; i++) {
            if(i <= Math.floor(p.rating)) {
                starsHtml += `<i class="ph-fill ph-star"></i>`;
            } else if(i - p.rating < 1) {
                starsHtml += `<i class="ph-fill ph-star-half"></i>`;
            } else {
                starsHtml += `<i class="ph ph-star"></i>`;
            }
        }

        const card = document.createElement('div');
        card.className = 'product-card scroll-anim';
        card.innerHTML = `
            <div class="product-img-wrapper">
                <a href="product.php?id=${p.id}"><img src="${p.image}" alt="${p.title}" loading="lazy"></a>
            </div>
            <a href="product.php?id=${p.id}"><h4 class="product-title">${p.title}</h4></a>
            <div class="product-rating">
                ${starsHtml} <span class="count">(${p.reviews})</span>
            </div>
            <div class="product-price">
                <span class="currency">$</span>${priceParts[0]}<span class="fraction">${priceParts[1]}</span>
            </div>
            ${primeHtml}
            <div class="delivery-info">Entrega <span>${p.delivery}</span></div>
            
            <button class="btn btn-primary add-to-cart" data-id="${p.id}">Añadir al carrito</button>
        `;
        container.appendChild(card);
    });

    // Attach listeners
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            const btnEl = e.target;
            const originalText = btnEl.innerText;
            btnEl.innerText = 'Añadiendo...';
            btnEl.disabled = true;

            const id = btnEl.getAttribute('data-id');
            await addToCart(id);
            
            btnEl.innerText = originalText;
            btnEl.disabled = false;
        });
    });

    // Observe newly generated cards for scroll animations
    if (window.scrollObserver) {
        document.querySelectorAll('.scroll-anim').forEach(el => window.scrollObserver.observe(el));
    }
}

async function addToCart(id) {
    try {
        const res = await fetch('api/cart_actions.php?action=add', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id})
        });
        const json = await res.json();
        if(json.status === 'success') {
            document.getElementById('cart-count').innerText = json.count;
            showToast('Añadido al carrito', 'El producto se agregó exitosamente a tu orden.');
        }
    } catch(err) {
        console.error(err);
        showToast('Error', 'No se pudo añadir al carrito.');
    }
}

// Cart Page Logic
async function loadCartItems() {
    const container = document.getElementById('cart-items-container');
    if(!container) return; // not on cart page

    try {
        const res = await fetch('api/cart_actions.php?action=get');
        const json = await res.json();
        
        if(json.status === 'success') {
            renderCart(json.data);
            // Also update top badge since cart is loaded
            document.getElementById('cart-count').innerText = json.data.count;
        }
    } catch(err) {
        container.innerHTML = `<p>Error cargando tu carrito.</p>`;
    }
}

function renderCart(cartData) {
    const container = document.getElementById('cart-items-container');
    container.innerHTML = '';

    if(cartData.items.length === 0) {
        container.innerHTML = `<div style="padding: 40px 20px;"><h3>Tu carrito de NexStore está vacío.</h3><a href="index.php">Comienza a explorar productos</a></div>`;
        updateCartTotals(0, 0);
        return;
    }

    cartData.items.forEach(item => {
        const itemEl = document.createElement('div');
        itemEl.className = 'cart-item';
        
        // Generate Qty options
        let qtyOptions = '';
        for(let i=1; i<=10; i++) {
            qtyOptions += `<option value="${i}" ${item.cart_qty === i ? 'selected' : ''}>${i}</option>`;
        }

        itemEl.innerHTML = `
            <input type="checkbox" checked class="cart-item-checkbox">
            <img src="${item.image}" class="cart-item-img">
            <div class="cart-item-details">
                <h3 class="cart-item-title">${item.title}</h3>
                <div class="cart-item-in-stock">Disponible</div>
                ${item.prime ? `<div class="prime-logo" style="margin-bottom:10px;"><i class="ph-fill ph-check"></i> prime</div>` : ''}
                
                <div class="cart-item-actions">
                    <select class="cart-qty-select" data-id="${item.id}">
                        ${qtyOptions}
                    </select>
                    <a href="#" class="remove-item" data-id="${item.id}">Eliminar</a>
                    <a href="#">Guardar para más tarde</a>
                </div>
            </div>
            <div class="cart-item-price">$${item.price.toFixed(2)}</div>
        `;
        container.appendChild(itemEl);
    });

    updateCartTotals(cartData.count, cartData.subtotal);

    // Attach listeners
    document.querySelectorAll('.cart-qty-select').forEach(sel => {
        sel.addEventListener('change', async (e) => {
            const id = e.target.getAttribute('data-id');
            const qty = e.target.value;
            await updateCartItem(id, qty);
        });
    });

    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const id = e.target.getAttribute('data-id');
            await removeCartItem(id);
        });
    });
}

function updateCartTotals(count, subtotal) {
    const subFormat = `$${subtotal.toFixed(2)}`;
    
    const countB = document.getElementById('cart-item-count-bottom');
    const countS = document.getElementById('cart-item-count-side');
    if(countB) countB.innerText = count;
    if(countS) countS.innerText = count;

    const subB = document.getElementById('cart-subtotal-bottom');
    const subS = document.getElementById('cart-subtotal-side');
    if(subB) subB.innerText = subFormat;
    if(subS) subS.innerText = subFormat;
}

async function updateCartItem(id, qty) {
    try {
        const res = await fetch('api/cart_actions.php?action=update', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id, qty})
        });
        const json = await res.json();
        if(json.status === 'success') {
            renderCart(json.data);
            document.getElementById('cart-count').innerText = json.data.count;
        }
    } catch(err) {
        console.error(err);
    }
}

async function removeCartItem(id) {
    try {
        const res = await fetch('api/cart_actions.php?action=remove', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id})
        });
        const json = await res.json();
        if(json.status === 'success') {
            renderCart(json.data);
            document.getElementById('cart-count').innerText = json.data.count;
            showToast('Eliminado', 'El producto fue eliminado del carrito.');
        }
    } catch(err) {
        console.error(err);
    }
}

// Init
document.addEventListener('DOMContentLoaded', () => {
    updateCartBadge();
    loadProducts();
    initScrollAnimations();
    initSpectacularEffects();
});

function initScrollAnimations() {
    // Hide mouse wheel indicator on scroll down
    const indicator = document.getElementById('scrollIndicator');
    if (indicator) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 80) {
                indicator.classList.add('hidden-scroll');
            } else {
                indicator.classList.remove('hidden-scroll');
            }
        });
    }

    // Intersection Observer for scroll-anim elements
    window.scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                // Optional: stop observing once animated in
                // window.scrollObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: "0px 0px -50px 0px" });

    // Observe existing static elements
    document.querySelectorAll('.scroll-anim').forEach(el => window.scrollObserver.observe(el));
}

window.logoutUser = function(e) {
    if(e) e.preventDefault();
    fetch('api/auth.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({action: 'logout'})
    }).then(() => location.reload());
};

// ---------------- SPECTACULAR 2026 EFFECTS ----------------
function initSpectacularEffects() {
    // 1. Glowing Trailing Cursor
    const glow = document.createElement('div');
    glow.className = 'cursor-glow';
    document.body.appendChild(glow);

    // Track mouse with slight delay for the trail effect
    let mouseX = window.innerWidth / 2;
    let mouseY = window.innerHeight / 2;
    let glowX = mouseX;
    let glowY = mouseY;

    window.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    function animateCursor() {
        // Linear interpolation (lerp) for smooth trailing
        glowX += (mouseX - glowX) * 0.15;
        glowY += (mouseY - glowY) * 0.15;
        glow.style.transform = `translate(calc(${glowX}px - 50%), calc(${glowY}px - 50%))`;
        requestAnimationFrame(animateCursor);
    }
    animateCursor();

    // Attach hover state to interactive elements (using event delegation for dynamic ones)
    document.body.addEventListener('mouseover', (e) => {
        if (e.target.closest('a, button, input, select, .product-card')) {
            glow.classList.add('cursor-hover');
        }
    });
    document.body.addEventListener('mouseout', (e) => {
        if (e.target.closest('a, button, input, select, .product-card')) {
            glow.classList.remove('cursor-hover');
        }
    });

    // 2. 3D Parallax Tilt Effect for Cards
    function attachTilt(elements) {
        elements.forEach(card => {
            if (card.classList.contains('tilt-attached')) return;
            card.classList.add('tilt-attached', 'tilt-card');
            
            card.addEventListener('mousemove', e => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                // Calculate rotation (max 8 degrees)
                const rotateX = ((y - centerY) / centerY) * -8;
                const rotateY = ((x - centerX) / centerX) * 8;
                
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)`;
            });
        });
    }

    // Attach to existing static cards
    attachTilt(document.querySelectorAll('.glass-card, .product-gallery img'));

    // Observe DB-loaded product cards
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.addedNodes.length) {
                attachTilt(document.querySelectorAll('.product-card'));
            }
        });
    });
    const pCont = document.getElementById('products-container');
    if (pCont) observer.observe(pCont, { childList: true });
}
