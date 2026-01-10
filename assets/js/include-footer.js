(function(){
  const hostPaths = [
    'templates/partials/footer.mustache',
    '../templates/partials/footer.mustache',
    '../../templates/partials/footer.mustache',
    '../../../templates/partials/footer.mustache'
  ];

  function inject(html){
    const container = document.getElementById('site-footer');
    if(!container){
      console.warn('No #site-footer element found to inject footer.');
      return;
    }
    container.innerHTML = html;
  }

  function tryLoad(i){
    if(i>=hostPaths.length){
      console.warn('Footer fragment not found via fetch; injecting fallback footer.');
      return;
    }
    fetch(hostPaths[i])
      .then(r=>{
        if(r.ok) return r.text();
        return Promise.reject();
      })
      .then(html=>{
        inject(html);
      })
      .catch(()=>tryLoad(i+1));
  }

  tryLoad(0);
})();
