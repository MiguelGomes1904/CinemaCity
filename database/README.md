# Cinema City - Guia de Instalação da Base de Dados

## Requisitos
- XAMPP instalado e a correr
- MySQL a funcionar no XAMPP
- PHP 7.4 ou superior

## Instalação Passo a Passo

### 1. Iniciar o XAMPP
1. Abra o painel de controlo do XAMPP
2. Inicie os serviços **Apache** e **MySQL**

### 2. Criar a Base de Dados

#### Opção A: Via phpMyAdmin (Interface Gráfica)
1. Abra o navegador e aceda a: `http://localhost/phpmyadmin`
2. Clique em **"New"** (Nova) no painel esquerdo
3. Nome da base de dados: `cinemacity`
4. Collation: `utf8mb4_general_ci`
5. Clique em **"Create"** (Criar)
6. Clique na base de dados `cinemacity` no painel esquerdo
7. Clique no separador **"Import"** (Importar)
8. Clique em **"Choose File"** e selecione: `C:\xampp\htdocs\CinemaCity\database\schema.sql`
9. Clique em **"Go"** no fundo da página
10. Aguarde a mensagem de sucesso

#### Opção B: Via Linha de Comandos
```bash
# Abra o PowerShell ou CMD na pasta do projeto
cd C:\xampp\htdocs\CinemaCity

# Execute o script SQL
C:\xampp\mysql\bin\mysql.exe -u root -p < database\schema.sql

# Quando pedida a password, prima Enter (password vazia por defeito)
```

### 3. Verificar a Instalação
1. Aceda a `http://localhost/phpmyadmin`
2. Clique na base de dados `cinemacity`
3. Verifique se existem as seguintes tabelas:
   - ✅ users
   - ✅ movies
   - ✅ cinemas
   - ✅ screens
   - ✅ sessions
   - ✅ products
   - ✅ tickets
   - ✅ orders
   - ✅ order_items
   - ✅ reviews

### 4. Dados de Teste
O script SQL já inclui dados de exemplo:
- **3 cinemas** (Porto, Lisboa, Coimbra)
- **4 filmes** de exemplo
- **8 sessões** de cinema
- **8 produtos** (pipocas, bebidas, combos)
- **2 utilizadores** de teste

## Credenciais de Teste

### Utilizadores de Teste
- **Email:** `joao@example.com`
- **Password:** `password123`

- **Email:** `maria@example.com`
- **Password:** `password123`

## APIs Disponíveis

### Autenticação
- **POST** `/api/register.php` - Registar novo utilizador
- **POST** `/api/login.php` - Login
- **GET** `/api/logout.php` - Logout

### Filmes
- **GET** `/api/list-movies.php` - Listar todos os filmes
  - Parâmetros opcionais: `?genre=Action&search=adventure`
- **GET** `/api/get-movie.php?id=1` - Detalhes de um filme específico

### Produtos
- **GET** `/api/list-products.php` - Listar todos os produtos
  - Parâmetros opcionais: `?category=Snacks`
- **GET** `/api/get-product.php?id=1` - Detalhes de um produto específico

### Bilhetes
- **POST** `/api/create-ticket.php` - Comprar bilhete
- **GET** `/api/list-tickets.php?user_id=1` - Listar bilhetes do utilizador
- **GET** `/api/get-ticket.php?id=1` - Detalhes de um bilhete
- **GET** `/api/get-ticket.php?qr_code=TICKET-xxx` - Validar bilhete por QR code

## Exemplos de Uso

### Registar Utilizador
```javascript
fetch('http://localhost/CinemaCity/api/register.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        name: 'Ana Silva',
        email: 'ana@example.com',
        password: 'password123',
        phone: '+351 910 000 000'
    })
})
.then(response => response.json())
.then(data => console.log(data));
```

### Login
```javascript
fetch('http://localhost/CinemaCity/api/login.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        email: 'joao@example.com',
        password: 'password123'
    })
})
.then(response => response.json())
.then(data => console.log(data));
```

### Listar Filmes
```javascript
fetch('http://localhost/CinemaCity/api/list-movies.php')
    .then(response => response.json())
    .then(data => console.log(data.movies));
```

### Comprar Bilhete
```javascript
fetch('http://localhost/CinemaCity/api/create-ticket.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        user_id: 1,
        session_id: 1,
        seat_number: 'A10',
        ticket_type: 'Standard'
    })
})
.then(response => response.json())
.then(data => console.log(data));
```

### Listar Produtos
```javascript
fetch('http://localhost/CinemaCity/api/list-products.php')
    .then(response => response.json())
    .then(data => console.log(data.products));
```

## Estrutura da Base de Dados

### Tabelas Principais

#### users
Utilizadores registados no sistema
- `id`, `name`, `email`, `password`, `phone`, `created_at`

#### movies
Filmes disponíveis
- `id`, `title`, `description`, `duration`, `genre`, `director`, `cast`
- `release_date`, `poster_url`, `rating`, `age_rating`, `is_active`

#### cinemas
Localizações dos cinemas
- `id`, `name`, `address`, `city`, `postal_code`, `phone`

#### screens
Salas de cinema
- `id`, `cinema_id`, `screen_number`, `total_seats`, `screen_type`

#### sessions
Sessões/horários dos filmes
- `id`, `movie_id`, `screen_id`, `session_date`, `session_time`
- `price`, `available_seats`, `is_active`

#### products
Produtos à venda (snacks, bebidas)
- `id`, `name`, `description`, `category`, `price`
- `image_url`, `is_available`, `stock`

#### tickets
Bilhetes comprados
- `id`, `user_id`, `session_id`, `seat_number`, `ticket_type`
- `price`, `status`, `qr_code`, `purchase_date`

#### orders
Encomendas de produtos
- `id`, `user_id`, `total_amount`, `payment_method`, `payment_status`

#### order_items
Itens de cada encomenda
- `id`, `order_id`, `product_id`, `quantity`, `unit_price`

#### reviews
Avaliações de filmes
- `id`, `user_id`, `movie_id`, `rating`, `comment`

## Resolução de Problemas

### Erro: "Database connection failed"
- Verifique se o MySQL está a correr no XAMPP
- Confirme as credenciais em `/api/db.inc`
- Password por defeito do root é vazia

### Erro: "Table doesn't exist"
- Execute novamente o ficheiro `schema.sql`
- Verifique se a base de dados `cinemacity` foi criada

### Erro: CORS
- Se estiver a testar de outro domínio, verifique os headers CORS nos ficheiros PHP
- Os headers já estão configurados para permitir acesso local

## Notas Importantes

1. **Segurança:** Esta configuração é para desenvolvimento local. Em produção, altere:
   - Password da base de dados
   - Desative CORS ou configure corretamente
   - Use HTTPS
   - Implemente proteção CSRF

2. **Passwords:** As passwords de teste estão hasheadas com bcrypt

3. **Charset:** A base de dados usa UTF-8 (utf8mb4) para suportar caracteres portugueses

4. **Timestamps:** Todas as datas e horas são geridas automaticamente pelo MySQL

## Próximos Passos

Agora pode:
1. ✅ Criar interfaces HTML para consumir as APIs
2. ✅ Implementar sistema de carrinho de compras
3. ✅ Adicionar sistema de pagamentos
4. ✅ Criar painel de administração
5. ✅ Implementar sistema de avaliações

## Suporte

Se tiver problemas, verifique:
1. Logs do Apache: `C:\xampp\apache\logs\error.log`
2. Logs do MySQL: `C:\xampp\mysql\data\mysql_error.log`
3. Console do navegador (F12) para erros JavaScript
