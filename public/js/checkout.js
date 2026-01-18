// Checkout page script
(function() {
  'use strict';

  function getCsrfToken() {
    const el = document.querySelector('meta[name="csrf-token"]');
    return el ? el.getAttribute('content') : '';
  }

  function formatPrice(n) {
    return '₱' + Number(n).toLocaleString();
  }

  function isServerCart() {
    return window.serverCartItems && Array.isArray(window.serverCartItems);
  }

  function normalizeProductId(item) {
    return item?.product_id ?? item?.productId ?? item?.id;
  }

  function normalizeVariantId(item) {
    const v = item?.product_variant_id ?? item?.productVariantId ?? item?.variant_id ?? item?.variantId;
    const n = (v === '' || v === undefined || v === null) ? null : Number(v);
    return Number.isFinite(n) && n > 0 ? n : null;
  }

  function normalizeQuantity(item) {
    const raw = item?.qty ?? item?.quantity;
    const n = Math.trunc(Number(raw));
    return Number.isFinite(n) && n > 0 ? n : 1;
  }

  function escapeHtml(value) {
    return String(value)
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  function truncateText(value, maxLen) {
    const s = String(value || '').trim();
    const limit = Number(maxLen) || 160;
    if (s.length <= limit) return s;
    return s.slice(0, Math.max(0, limit - 1)).trimEnd() + '…';
  }

  function readStock(item) {
    const unlimited = Boolean(item?.stock_unlimited ?? item?.stockUnlimited);
    const qty = Number(item?.stock ?? item?.max_stock ?? item?.maxStock ?? 0);
    return { unlimited, qty: Number.isFinite(qty) ? qty : 0 };
  }

  function stockStateFor(item) {
    const s = readStock(item);
    if (s.unlimited) return 'unlimited';
    if (s.qty <= 0) return 'out';
    if (s.qty <= 5) return 'low';
    return 'ok';
  }

  function stockTextFor(item) {
    const s = readStock(item);
    return s.unlimited ? 'Stock: ∞' : ('Stock: ' + String(Math.max(0, Math.trunc(s.qty))));
  }

  function readCart() {
    // Logged-in users: use server-provided cart (DB cart)
    if (isServerCart()) return window.serverCartItems;

    // Guests: fallback to legacy localStorage cart
    try {
      return (window.Cart && window.Cart._read)
        ? window.Cart._read()
        : JSON.parse(localStorage.getItem('corefivegadgets_cart_v1') || '[]');
    } catch (e) {
      return [];
    }
  }

  function writeCart(cart) {
    // Logged-in users: cart is stored in DB; do not write localStorage
    if (isServerCart()) return;
    localStorage.setItem('corefivegadgets_cart_v1', JSON.stringify(cart));
  }

  function updateCartCountBadge() {
    const badge = document.getElementById('cartCount');
    if (!badge) return;
    fetch('/cart/get', { headers: { 'Accept': 'application/json' } })
      .then(r => r.json())
      .then(data => {
        if (typeof data?.cart_count === 'number') {
          badge.textContent = String(data.cart_count);
          badge.style.display = data.cart_count > 0 ? 'inline-block' : 'none';
        }
      })
      .catch(() => {});
  }

  function syncQtyToServer(productId, variantId, quantity) {
    return fetch('/cart/update', {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken(),
        'Accept': 'application/json'
      },
      body: JSON.stringify({ product_id: productId, product_variant_id: variantId, quantity })
    }).then(r => r.json());
  }

  function removeFromServer(productId, variantId) {
    return fetch('/cart/remove', {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken(),
        'Accept': 'application/json'
      },
      body: JSON.stringify({ product_id: productId, product_variant_id: variantId })
    }).then(r => r.json());
  }

  function render() {
    const itemsEl = document.getElementById('checkoutItems');
    const summaryList = document.getElementById('summaryList');
    const subtotalEl = document.getElementById('subtotal');
    const totalEl = document.getElementById('total');
    const cardSummary = document.querySelector('.card-summary');

    if (!itemsEl || !summaryList || !subtotalEl || !totalEl) return;

    const cart = readCart();
    itemsEl.innerHTML = '';
    summaryList.innerHTML = '';

    if (!cart.length) {
      itemsEl.innerHTML = '<div class="list-group-item text-center py-4 muted">No products available in your cart.</div>';
      if (cardSummary) cardSummary.style.display = 'none';
      return;
    }

    if (cardSummary) cardSummary.style.display = 'block';

    let subtotal = 0;
    cart.forEach((item, idx) => {
      const qty = normalizeQuantity(item);
      const lineTotal = (item.price || 0) * qty;
      subtotal += lineTotal;

      const safeTitle = escapeHtml(item.title || 'Product');
      const safeVariant = escapeHtml(item.variant_name || item.variantName || '');
      const safeDescFull = escapeHtml(item.description || '');
      const safeDescShort = escapeHtml(truncateText(item.description || '', 180));
      const stockState = stockStateFor(item);
      const stockText = escapeHtml(stockTextFor(item));

      const row = document.createElement('div');
      row.className = 'list-group-item d-flex gap-3 py-3 align-items-center';
      row.innerHTML = `
        <img src="${item.image || 'https://via.placeholder.com/160'}" alt="${safeTitle}" class="item-img item-img-lg">
        <div class="d-flex flex-column flex-grow-1">
          <div class="d-flex w-100 justify-content-between">
            <h6 class="mb-1">${safeTitle}</h6>
            <small class="text-muted">${formatPrice(item.price || 0)}</small>
          </div>
          ${safeVariant ? `<div class="small text-muted" style="margin-top:-2px;">Variant: ${safeVariant}</div>` : ''}
          <p class="mb-1 muted checkout-desc" title="${safeDescFull}">${safeDescShort}</p>
          <div class="mb-2"><span class="stock-pill stock-${stockState}">${stockText}</span></div>
          <div class="d-flex align-items-center gap-2">
            <label class="mb-0">Qty</label>
            <input data-idx="${idx}" type="number" class="form-control form-control-sm qty-input" value="${qty}" min="1">
            <button data-idx="${idx}" class="btn btn-sm btn-outline-danger ms-2 btn-remove">Remove</button>
          </div>
        </div>
      `;
      itemsEl.appendChild(row);

      const li = document.createElement('li');
      li.className = 'd-flex justify-content-between';
      li.innerHTML = `<span>${item.title} ×${qty}</span><strong>${formatPrice(lineTotal)}</strong>`;
      summaryList.appendChild(li);
    });

    subtotalEl.textContent = formatPrice(subtotal);

    const shippingOption = document.getElementById('shippingOption');
    const shippingFee = shippingOption ? Number(shippingOption.options[shippingOption.selectedIndex].dataset.fee || 0) : 0;
    document.getElementById('shippingFee').textContent = formatPrice(shippingFee);
    totalEl.textContent = formatPrice(subtotal + shippingFee);

    // Attach handlers
    itemsEl.querySelectorAll('.qty-input').forEach(input => {
      input.addEventListener('change', () => {
        const i = Number(input.getAttribute('data-idx'));
        const val = Math.max(1, Number(input.value) || 1);
        const cart = readCart();
        const current = cart[i];
        if (!current) return;

        // Logged-in users: sync to DB cart
        if (isServerCart()) {
          const productId = normalizeProductId(current);
          const variantId = normalizeVariantId(current);
          if (!productId) return;
          syncQtyToServer(productId, variantId, val)
            .then(() => {
              current.qty = val;
              current.quantity = val;
              render();
              updateCartCountBadge();
            })
            .catch(() => {
              // revert UI
              input.value = String(normalizeQuantity(current));
            });
          return;
        }

        // Guests
        current.qty = val;
        current.quantity = val;
        writeCart(cart);
        render();
      });
    });

    itemsEl.querySelectorAll('.btn-remove').forEach(btn => {
      btn.addEventListener('click', () => {
        const i = Number(btn.getAttribute('data-idx'));
        const cart = readCart();
        const current = cart[i];
        if (!current) return;

        // Logged-in users: remove from DB cart
        if (isServerCart()) {
          const productId = normalizeProductId(current);
          const variantId = normalizeVariantId(current);
          if (!productId) return;
          removeFromServer(productId, variantId)
            .then(() => {
              window.serverCartItems.splice(i, 1);
              render();
              updateCartCountBadge();
            })
            .catch(() => {});
          return;
        }

        // Guests
        cart.splice(i, 1);
        writeCart(cart);
        render();
      });
    });

    if (shippingOption) {
      shippingOption.addEventListener('change', render);
    }
  }

  function placeOrder() {
    const cart = readCart();
    if (!cart.length) {
      return;
    }

    // Capture latest qty values even if the user didn't blur the input.
    // (The 'change' event only fires on blur, but users often type and click Place Order immediately.)
    document.querySelectorAll('#checkoutItems .qty-input').forEach((input) => {
      const idx = Number(input.getAttribute('data-idx'));
      if (!Number.isFinite(idx) || idx < 0 || idx >= cart.length) return;
      const val = Math.max(1, Math.trunc(Number(input.value) || 1));
      cart[idx].qty = val;
      cart[idx].quantity = val;
    });

    // Guests: persist latest qty to localStorage
    if (!isServerCart()) {
      writeCart(cart);
    }

    const orderError = document.getElementById('orderError');
    if (orderError) {
      orderError.classList.add('d-none');
      orderError.textContent = '';
    }

    const addressInput = document.getElementById('shippingAddress');
    const address = addressInput ? addressInput.value.trim() : '';
    
    // Validate shipping address
    if (!address) {
      if (addressInput) {
        addressInput.classList.add('is-invalid');
        addressInput.focus();
      }
      return;
    }
    
    // Remove invalid class if address is provided
    if (addressInput) {
      addressInput.classList.remove('is-invalid');
    }

    const shippingOption = document.getElementById('shippingOption');
    const shippingMethod = shippingOption ? shippingOption.value : 'standard';
    const shippingFee = shippingOption ? Number(shippingOption.options[shippingOption.selectedIndex].dataset.fee || 0) : 0;
    const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked')?.value || 'card';
    const notes = document.getElementById('orderNotes').value || '';

    const subtotal = cart.reduce((s, i) => s + (i.price || 0) * normalizeQuantity(i), 0);
    const total = subtotal + shippingFee;

    // Map cart items to match backend expectations
    const items = cart.map(item => ({
      product_id: normalizeProductId(item),
      product_variant_id: normalizeVariantId(item),
      quantity: normalizeQuantity(item),
    })).filter(i => i.product_id);

    const payload = {
      items: items,
      shipping_fee: shippingFee,
      shipping_method: shippingMethod,
      shipping_address: address,
      payment_method: paymentMethod,
      order_notes: notes,
    };

    const pickErrorMessage = (data) => {
      if (!data) return null;
      if (typeof data.message === 'string' && data.message.trim()) return data.message;
      const errors = data.errors;
      if (errors && typeof errors === 'object') {
        const firstKey = Object.keys(errors)[0];
        const firstArr = firstKey ? errors[firstKey] : null;
        if (Array.isArray(firstArr) && firstArr[0]) return String(firstArr[0]);
      }
      return null;
    };

    fetch('/orders', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
      },
      body: JSON.stringify(payload)
    })
      .then(async (res) => {
        // If middleware redirects (login/verify), fetch follows and returns HTML.
        // Treat that as an error instead of a successful order.
        if (res.redirected) {
          if (orderError) {
            orderError.textContent = 'Your session expired or your email is not verified. Please refresh and try again.';
            orderError.classList.remove('d-none');
          }
          return;
        }

        const contentType = (res.headers.get('content-type') || '').toLowerCase();
        const data = contentType.includes('application/json')
          ? await res.json().catch(() => ({}))
          : {};

        if (!res.ok) {
          const msg = pickErrorMessage(data) || 'Unable to place order. Please try again.';
          if (orderError) {
            orderError.textContent = msg;
            orderError.classList.remove('d-none');
          }
          return;
        }

        if (!data || typeof data.id !== 'number') {
          if (orderError) {
            orderError.textContent = 'Unable to place order (unexpected server response). Please refresh and try again.';
            orderError.classList.remove('d-none');
          }
          return;
        }

        // Guests: clear local storage cart; Logged-in: server clears DB cart
        if (!isServerCart()) {
          writeCart([]);
        } else {
          window.serverCartItems = [];
          updateCartCountBadge();
        }
        const orderId = data?.id;
        const msgEl = document.getElementById('orderSuccessMessage');
        const idEl = document.getElementById('orderSuccessOrderId');
        if (msgEl) msgEl.textContent = 'Thank you — your order has been placed.';
        if (idEl) idEl.textContent = orderId ? `Order ID: ${orderId}` : '';

        const modal = document.getElementById('orderSuccessModal');
        if (modal) {
          const bsModal = new bootstrap.Modal(modal);
          bsModal.show();
          setTimeout(() => {
            window.location.href = '/my-orders';
          }, 1800);
        } else {
          if (window.Toast && window.Toast.show) {
            window.Toast.show('Order placed successfully!');
          }
          setTimeout(() => {
            window.location.href = '/my-orders';
          }, 1200);
        }
      })
      .catch(err => {
        console.error('Order error:', err);
        if (orderError) {
          orderError.textContent = 'Network error placing order. Please try again.';
          orderError.classList.remove('d-none');
        }
      });
  }

  document.addEventListener('DOMContentLoaded', () => {
    render();
    updateCartCountBadge();

    const placeOrderBtn = document.getElementById('placeOrderBtn');
    if (placeOrderBtn) {
      placeOrderBtn.addEventListener('click', placeOrder);
    }

    // Remove validation error when user starts typing in shipping address
    const shippingAddress = document.getElementById('shippingAddress');
    if (shippingAddress) {
      shippingAddress.addEventListener('input', () => {
        shippingAddress.classList.remove('is-invalid');
      });
    }
  });
})();
