// Checkout page script
(function() {
  'use strict';

  function formatPrice(n) {
    return '₱' + Number(n).toLocaleString();
  }

  function normalizeProductId(item) {
    const id = item?.product_id ?? item?.productId ?? item?.id;
    const n = Number(id);
    return Number.isFinite(n) && n > 0 ? n : null;
  }

  function normalizeVariantId(item) {
    const id = item?.product_variant_id ?? item?.productVariantId ?? item?.variant_id ?? item?.variantId ?? item?.variant?.id;
    if (id === null || id === undefined || id === '') return null;
    const n = Number(id);
    return Number.isFinite(n) && n > 0 ? n : null;
  }

  function normalizeQuantity(item) {
    const raw = item?.qty ?? item?.quantity;
    const n = Math.trunc(Number(raw));
    return Number.isFinite(n) && n > 0 ? n : 1;
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
    // Check if server-side cart items are available (for logged-in users)
    if (window.serverCartItems && Array.isArray(window.serverCartItems)) {
      return window.serverCartItems;
    }
    
    // Fallback to localStorage for guests
    try {
      return (window.Cart && window.Cart._read) ? window.Cart._read() : JSON.parse(localStorage.getItem('corefivegadgets_cart_v1') || '[]');
    } catch {
      return [];
    }
  }

  function writeCart(cart) {
    // For logged-in users, cart updates via API only
    if (window.serverCartItems) {
      return;
    }
    // For guests, use localStorage
    localStorage.setItem('corefivegadgets_cart_v1', JSON.stringify(cart));
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

      const stockState = stockStateFor(item);
      const stockText = stockTextFor(item);

      const row = document.createElement('div');
      row.className = 'list-group-item';
      row.innerHTML = `
        <div class="item-row">
          <img src="${item.image || 'https://via.placeholder.com/160'}" alt="${item.title || 'Product'}" class="item-img">
          <div class="item-body">
            <div class="d-flex w-100 justify-content-between align-items-start">
              <h6 class="mb-1">${item.title || 'Product'}</h6>
              <small class="text-muted">${formatPrice(item.price || 0)}</small>
            </div>
            <p class="mb-1 muted">${item.description || ''}</p>
              <div class="mb-2"><span class="stock-pill stock-${stockState}">${stockText}</span></div>
            <div class="d-flex align-items-center gap-2">
              <label class="mb-0">Qty</label>
              <input data-idx="${idx}" type="number" class="form-control form-control-sm qty-input" value="${qty}" min="1">
              <button data-idx="${idx}" class="btn btn-sm btn-outline-danger ms-2 btn-remove">Remove</button>
            </div>
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
        if (cart[i]) {
          cart[i].qty = val;
          writeCart(cart);
          render();
        }
      });
    });

    itemsEl.querySelectorAll('.btn-remove').forEach(btn => {
      btn.addEventListener('click', () => {
        const i = Number(btn.getAttribute('data-idx'));
        const cart = readCart();
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
      if (window.Toast && window.Toast.show) {
        window.Toast.show('Your cart is empty.');
      }
      return;
    }

    // Capture latest qty values even if the user didn't blur the input.
    document.querySelectorAll('#checkoutItems .qty-input').forEach((input) => {
      const idx = Number(input.getAttribute('data-idx'));
      if (!Number.isFinite(idx) || idx < 0 || idx >= cart.length) return;
      const val = Math.max(1, Math.trunc(Number(input.value) || 1));
      cart[idx].qty = val;
      cart[idx].quantity = val;
    });

    if (!(window.serverCartItems && Array.isArray(window.serverCartItems))) {
      writeCart(cart);
    }

    const orderError = document.getElementById('orderError');
    if (orderError) {
      orderError.classList.add('d-none');
      orderError.textContent = '';
    }

    const addressInput = document.getElementById('shippingAddress');
    const address = addressInput ? addressInput.value.trim() : '';

    const showAddressError = (msg) => {
      if (orderError) {
        orderError.textContent = msg;
        orderError.classList.remove('d-none');
      }
    };

    const markInvalid = (el) => {
      if (!el) return;
      el.classList.add('is-invalid');
    };

    const clearInvalid = (el) => {
      if (!el) return;
      el.classList.remove('is-invalid');
    };
    
    // Validate shipping address
    if (!address) {
      markInvalid(addressInput);
      addressInput?.focus();
      showAddressError('Please select your shipping address to continue.');
      return;
    }

    // Reject placeholder/unfinished builder text
    if (/\bSelect\b/i.test(address)) {
      markInvalid(addressInput);
      addressInput?.focus();
      showAddressError('Please select your full shipping address (region, province, city, barangay).');
      return;
    }

    // If the PH address builder is being used (visible), require selections.
    const builder = document.getElementById('checkoutPhAddressBuilder');
    const builderVisible = builder && builder.style.display !== 'none';
    if (builderVisible) {
      const street = document.getElementById('checkoutStreet');
      const region = document.getElementById('checkoutRegion');
      const province = document.getElementById('checkoutProvince');
      const city = document.getElementById('checkoutCity');
      const barangay = document.getElementById('checkoutBarangay');

      [street, region, province, city, barangay, addressInput].forEach(clearInvalid);

      const streetVal = street ? String(street.value || '').trim() : '';
      if (!streetVal) {
        markInvalid(street);
        street?.focus();
        showAddressError('Please enter your street / building / unit.');
        return;
      }
      if (!region || !region.value) {
        markInvalid(region);
        region?.focus();
        showAddressError('Please select a region.');
        return;
      }
      if (!province || !province.value) {
        markInvalid(province);
        province?.focus();
        showAddressError('Please select a province.');
        return;
      }
      if (!city || !city.value) {
        markInvalid(city);
        city?.focus();
        showAddressError('Please select a city/municipality.');
        return;
      }
      if (!barangay || !barangay.value) {
        markInvalid(barangay);
        barangay?.focus();
        showAddressError('Please select a barangay.');
        return;
      }
    }
    
    // Remove invalid class if address is provided
    clearInvalid(addressInput);

    const shippingOption = document.getElementById('shippingOption');
    const shippingMethod = shippingOption ? shippingOption.value : 'standard';
    const shippingFee = shippingOption ? Number(shippingOption.options[shippingOption.selectedIndex].dataset.fee || 0) : 0;
    const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked')?.value || 'card';
    const notes = document.getElementById('orderNotes').value || '';

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
        if (window.serverCartItems && Array.isArray(window.serverCartItems)) {
          window.serverCartItems = [];
        } else {
          writeCart([]);
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
    // Ensure render is called after serverCartItems is available
    if (window.serverCartItems) {
      console.log('Using server cart items:', window.serverCartItems);
    }
    render();

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
