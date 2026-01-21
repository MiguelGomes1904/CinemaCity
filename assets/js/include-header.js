(function(){
  const hostPaths = [
    'templates/partials/header.html',
    '../templates/partials/header.html',
    '../../templates/partials/header.html',
    '../../../templates/partials/header.html'
  ];

  function inject(html){
    const container = document.getElementById('site-header');
    if(!container){
      console.warn('No #site-header element found to inject header.');
      return;
    }
    container.innerHTML = html;
  }

  function tryLoad(i){
    if(i>=hostPaths.length){
      console.warn('Header fragment not found via fetch; injecting fallback header.');
      // Fallback: minimal header markup (keeps visual even when opened via file://)
      const fallback = `
        <header>
          <nav class="navbar">
            <div class="logo"><a href="index.html"><img src="assets/images/gallery/logo.png" alt="Cinema City Logo"></a></div>
            <ul class="nav-links">
              <li><a href="index.html">Home</a></li>
              <li><a href="cinemas.html">Cinemas</a></li>
              <li><a href="products.html">Produtos</a></li>
              <li><a href="destaques.html">Destaques</a></li>
            </ul>
            <div class="search-login">
              <input type="text" placeholder="Pesquise por filme, actores, realizadores">
              <a class="login-btn" href="login.html" title="Login">
                <img src="assets/images/gallery/login-icon.svg" alt="Login" class="login-icon">
              </a>
            </div>
          </nav>
        </header>
      `;
      inject(fallback);
      return;
    }
    fetch(hostPaths[i]).then(r=>{ if(r.ok) return r.text(); throw new Error('not found'); }).then(html=>{
      inject(html);
    }).catch(()=> tryLoad(i+1));
  }

  // Run on DOMContentLoaded to ensure #site-header exists
  if(document.readyState === 'loading'){
    document.addEventListener('DOMContentLoaded', ()=> tryLoad(0));
  } else {
    tryLoad(0);
  }
})();

