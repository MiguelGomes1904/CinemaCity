// Products Page JavaScript - Cinema City
// Dynamic bar items, cart and order submission
const locationData = {
  alfragide: {
    name: 'Cinema Alegro Alfragide',
    address: 'Avenida dos Cavaleiros 60, Portela de Carnaxide - 2790-045 Carnaxide',
    extra: 'Junto ao Parque Alfragide'
  },
  alvalade: {
    name: 'Cinema City Alvalade',
    address: 'Avenida de Roma 144, 1700-312 Lisboa',
    extra: 'Metro Alvalade (Linha Verde)'
  },
  'campo-pequeno': {
    name: 'Cinema City Campo Pequeno',
    address: 'Praça do Campo Pequeno 50B, 1000-081 Lisboa',
    extra: 'Entrada lateral norte'
  },
  leiria: {
    name: 'Cinema City Leiria',
    address: 'Rua Comissão de Iniciativa 1, 2410-098 Leiria',
    extra: 'Centro Comercial'
  },
  setubal: {
    name: 'Cinema City Setúbal',
    address: 'Avenida Luísa Todi 123, 2900-461 Setúbal',
    extra: 'Zona ribeirinha'
  },
  default: {
    name: 'Cinema City',
    address: 'Escolhe uma localização para ver os artigos do bar.',
    extra: ''
  }
};

let productsCache = [];
let cart = [];
let selectedLocation = '';

document.addEventListener('DOMContentLoaded', () => {
  loadProducts();
  bindCartActions();
});

function openProductDrawer() {
  const overlay = document.getElementById('drawerOverlay');
  const locationDrawer = document.getElementById('productDrawer');
  const barDrawer = document.getElementById('barDrawer');
  if (overlay && locationDrawer && barDrawer) {
    barDrawer.style.display = 'none';
    locationDrawer.style.display = 'block';
    overlay.style.display = 'block';
    document.body.style.overflow = 'hidden';
  }
}

function openBarDrawer(locationId) {
  selectedLocation = locationId || '';
  const overlay = document.getElementById('drawerOverlay');
  const locationDrawer = document.getElementById('productDrawer');
  const barDrawer = document.getElementById('barDrawer');
  const info = locationData[locationId] || locationData.default;

  const nameEl = document.getElementById('bar-location-name');
  const addressEl = document.getElementById('bar-location-address');
  const extraEl = document.getElementById('bar-location-extra');

  if (nameEl) nameEl.textContent = info.name;
  if (addressEl) addressEl.textContent = info.address;
  if (extraEl) extraEl.textContent = info.extra || '';

  if (overlay && locationDrawer && barDrawer) {
    locationDrawer.style.display = 'none';
    barDrawer.style.display = 'flex';
    overlay.style.display = 'block';
    document.body.style.overflow = 'hidden';
  }
}

function closeAllDrawers() {
  const overlay = document.getElementById('drawerOverlay');
  const locationDrawer = document.getElementById('productDrawer');
  const barDrawer = document.getElementById('barDrawer');
  if (overlay) overlay.style.display = 'none';
  if (locationDrawer) locationDrawer.style.display = 'none';
  if (barDrawer) barDrawer.style.display = 'none';
  document.body.style.overflow = '';
}

// Expose globally
window.openProductDrawer = openProductDrawer;
window.closeAllDrawers = closeAllDrawers;
window.selectLocation = function(id) {
  openBarDrawer(id);
};

// ESC para fechar
document.addEventListener('keydown', function(event) {
  if (event.key === 'Escape') {
    closeAllDrawers();
  }
});

// --- Produtos e carrinho ---
function bindCartActions() {
  const checkoutBtn = document.getElementById('checkout-products');
  if (checkoutBtn) {
    checkoutBtn.addEventListener('click', submitOrder);
  }
}

async function loadProducts() {
  try {
    const res = await fetch('/CinemaCity/api/list-products.php');
    const data = await res.json();
    if (!data.success) throw new Error(data.message || 'Erro ao carregar produtos');
    productsCache = data.products || [];
    renderProducts(productsCache);
  } catch (err) {
    console.error('Produtos:', err);
    setMessage('Erro ao carregar artigos de bar.', true);
  }
}

function renderProducts(list) {
  const container = document.getElementById('bar-products-container');
  if (!container) return;
  container.innerHTML = '';

  const byCat = list.reduce((acc, p) => {
    const cat = p.category || 'Outros';
    if (!acc[cat]) acc[cat] = [];
    acc[cat].push(p);
    return acc;
  }, {});

  Object.keys(byCat).forEach(cat => {
    const section = document.createElement('div');
    section.innerHTML = `
      <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:6px;">
        <p style="margin:0; color:#0a2e63; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">${cat}</p>
      </div>
      <div class="bar-products-grid" style="display:flex; gap:14px; flex-wrap:wrap;"></div>
    `;

    const grid = section.querySelector('.bar-products-grid');
    byCat[cat].forEach(prod => {
      const card = document.createElement('div');
      card.style.cssText = 'width: 180px; background:#f7f7f7; border-radius:12px; padding:12px; box-shadow:0 10px 18px rgba(0,0,0,0.08); display:flex; flex-direction:column; gap:6px;';
      card.innerHTML = `
        <div style="width:100%; height:120px; background:#fff; border-radius:10px; display:flex; align-items:center; justify-content:center; overflow:hidden;">
          <img src="${prod.image_url || 'https://via.placeholder.com/150'}" alt="${prod.name}" style="max-width:100%; max-height:100%; object-fit:contain;">
        </div>
        <div style="font-weight:700; color:#0a2e63; font-size:0.95rem; min-height:38px;">${prod.name}</div>
        <div style="color:#333; font-size:0.9rem;">${(parseFloat(prod.price)||0).toFixed(2).replace('.', ',')}€</div>
        <div style="font-size:0.85rem; color:${prod.stock>0 ? '#0a2e63':'#c21633'};">Stock: ${prod.stock ?? 0}</div>
        <div style="display:flex; gap:8px; align-items:center;">
          <input type="number" min="1" max="${prod.stock ?? 0}" value="1" style="width:60px; padding:6px; border-radius:6px; border:1px solid #ccc;" ${prod.stock>0 ? '' : 'disabled'}>
          <button ${prod.stock>0 ? '' : 'disabled'} style="flex:1; padding:8px; background:#ffb703; color:#0a2e63; border:none; border-radius:8px; font-weight:700; cursor:pointer;">Adicionar</button>
        </div>
      `;
      const qtyInput = card.querySelector('input');
      const addBtn = card.querySelector('button');
      addBtn.addEventListener('click', () => addToCart(prod, parseInt(qtyInput.value,10)||1));
      grid.appendChild(card);
    });

    container.appendChild(section);
  });
}

function addToCart(prod, qty) {
  if (!prod || qty <= 0) return;
  const existing = cart.find(item => item.id === prod.id);
  const stock = Number(prod.stock ?? 0);
  const newQty = (existing ? existing.qty : 0) + qty;
  if (stock > 0 && newQty > stock) {
    setMessage(`Stock insuficiente para ${prod.name}.`, true);
    return;
  }
  if (existing) {
    existing.qty = newQty;
  } else {
    cart.push({ id: prod.id, name: prod.name, price: Number(prod.price), qty });
  }
  renderCart();
  setMessage(`${prod.name} adicionado ao carrinho.`);
}

function renderCart() {
  const list = document.getElementById('cart-items');
  const totalEl = document.getElementById('cart-total');
  if (!list || !totalEl) return;
  list.innerHTML = '';
  let total = 0;
  cart.forEach(item => {
    const line = document.createElement('div');
    line.style.display = 'flex';
    line.style.justifyContent = 'space-between';
    line.style.alignItems = 'center';
    const lineTotal = item.price * item.qty;
    total += lineTotal;
    line.innerHTML = `
      <span>${item.qty}x ${item.name}</span>
      <span>${lineTotal.toFixed(2).replace('.', ',')}€</span>
    `;
    list.appendChild(line);
  });
  if (cart.length === 0) {
    list.innerHTML = '<span style="opacity:0.8;">Carrinho vazio</span>';
  }
  totalEl.textContent = total.toFixed(2).replace('.', ',') + '€';
}

function setMessage(msg, isError = false) {
  const box = document.getElementById('cart-message');
  if (!box) return;
  box.textContent = msg || '';
  box.style.color = isError ? '#ffdcdc' : '#d9ffd9';
}

async function submitOrder() {
  if (cart.length === 0) {
    setMessage('Adicione artigos ao carrinho primeiro.', true);
    return;
  }
  const name = document.getElementById('order-name')?.value.trim() || '';
  const email = document.getElementById('order-email')?.value.trim() || '';
  const phone = document.getElementById('order-phone')?.value.trim() || '';
  const payment = document.getElementById('order-payment')?.value || '';
  if (!name || !email || !payment) {
    setMessage('Preencha nome, email e método de pagamento.', true);
    return;
  }

  const payload = {
    items: cart.map(c => ({ product_id: c.id, quantity: c.qty })),
    customer_name: name,
    customer_email: email,
    customer_phone: phone,
    payment_method: payment,
    pickup_location: selectedLocation
  };

  try {
    const res = await fetch('/CinemaCity/api/create-product-order.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const data = await res.json();
    if (!data.success) throw new Error(data.message || 'Erro ao registar compra');
    setMessage('Compra registada com sucesso!');
    cart = [];
    renderCart();
  } catch (err) {
    console.error('Order:', err);
    setMessage(err.message, true);
  }
}
