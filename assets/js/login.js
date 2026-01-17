// Login Form Handler
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            try {
                const response = await fetch('api/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        username: username,
                        password: password
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Store user session
                    localStorage.setItem('user', JSON.stringify(data.user));
                    
                    // Redirect to home page or dashboard
                    window.location.href = 'index.html';
                } else {
                    alert('Credenciais inválidas. Por favor, tente novamente.');
                }
            } catch (error) {
                console.error('Erro no login:', error);
                alert('Erro ao conectar com o servidor. Por favor, tente novamente.');
            }
        });
    }
    
    // Google Login Handler
    const googleBtn = document.querySelector('.btn-google');
    if (googleBtn) {
        googleBtn.addEventListener('click', function() {
            // Implement Google OAuth here
            alert('Funcionalidade de login com Google em desenvolvimento');
        });
    }
});
