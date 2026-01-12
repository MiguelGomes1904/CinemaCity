// Products Page JavaScript - Cinema City
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
