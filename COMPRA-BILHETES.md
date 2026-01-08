# 🎬 Cinema City - Sistema de Compra de Bilhetes

## Fluxo Completo de Compra

### 1. **Página Home (index.html)**
- Booking Bar com 4 selectores:
  - **Cinema**: Escolhe uma das 5 localidades Cinema City
  - **Filme**: Seleciona um filme (com busca/autocomplete)
  - **Data**: Escolhe a data da sessão
  - **Hora**: Seleciona a hora disponível
- Botão "Comprar bilhetes" redireciona para seleção de assentos

### 2. **Seleção de Assentos (checkout-seats.html)**
- URL: `checkout-seats.html?session={id}`
- **Design**: Inspirado na imagem fornecida
  - Lado esquerdo (azul): Informações do cinema e filme
  - Lado direito (cinzento): Mapa de assentos interativo
  - Layout responsivo

- **Funcionalidades**:
  - Carrega dados da sessão via API `get-session.php`
  - Exibe mapa de 80 assentos (8 linhas x 10 colunas)
  - Assentos com 3 estados:
    - ⚪ **Livre**: Clicável para seleção
    - 🔵 **Selecionado**: Em azul
    - 🔴 **Reservado**: Em vermelho (não selecionável)
  - Calcula preço dinâmico baseado em:
    - Número de assentos selecionados
    - Preço da sessão (vem da API)
  - Botão "Confirmar" ativo apenas com assentos selecionados

### 3. **Checkout (checkout.html)**
- URL: `checkout.html?session={id}&seats={A1,A2,B5}&totalPrice={15.00}`
- **Secções**:
  - **Sua Seleção**: Resumo do cinema, filme, data, hora, assentos
  - **Resumo de Preço**: Detalhamento do total
  - **Dados Pessoais**: 
    - Nome Completo
    - Email
    - Telemóvel
  - **Método de Pagamento**:
    - Cartão de Crédito/Débito (com campos: número, validade, CVC)
    - PayPal
    - Transferência Bancária
  - **Termos e Condições**: Checkbox obrigatório

- **Funcionalidades**:
  - Formatação automática de cartão (1234 5678 9012 3456)
  - Validação de campos
  - Processamento de pagamento com feedback visual
  - Redireção para home após sucesso

## APIs Utilizadas

### 1. `api/list-movies.php`
- **GET** - Lista todos os filmes
- Resposta: `{ success: true, movies: [...], count: 4 }`

### 2. `api/list-sessions.php`
- **GET** - Lista todas as sessões
- Resposta: `{ success: true, sessions: [...], count: 22 }`

### 3. `api/get-session.php?id={id}`
- **GET** - Detalhes de uma sessão específica
- Resposta: `{ success: true, session: { id, cinema_name, movie_id, ... } }`

### 4. `api/get-movie.php?id={id}`
- **GET** - Detalhes de um filme específico
- Resposta: `{ success: true, movie: { id, title, description, ... } }`

### 5. `api/create-order.php` (A Implementar)
- **POST** - Cria um pedido/ordem de compra
- Body: `{ session_id, seats, customer_name, customer_email, customer_phone, total_price, payment_method }`

## Estrutura de Ficheiros

```
CinemaCity/
├── index.html                          # Home com booking bar
├── checkout-seats.html                 # Seleção de assentos
├── checkout.html                       # Finalização de pagamento
├── api/
│   ├── db.inc                          # Conexão à base de dados
│   ├── list-movies.php                 # API de filmes
│   ├── list-sessions.php               # API de sessões
│   ├── get-session.php                 # ✅ Criada
│   ├── get-movie.php                   # API detalhes filme
│   └── create-order.php                # 🔄 A Implementar
├── assets/
│   ├── js/
│   │   ├── booking-bar.js              # Popula booking bar
│   │   └── main.js
│   └── css/
│       └── styles.css                  # Estilos gerais
├── templates/
│   ├── pages/
│   │   ├── index.mustache
│   │   └── checkout-seats.mustache     # ✅ Criada
│   ├── layouts/
│   │   └── layout.mustache
│   └── partials/
└── database/
    └── schema.sql                      # Schema com 10 tabelas
```

## Dados de Teste

### Utilizadores
- **Email**: user1@test.com
- **Senha**: password123

### Cinemas (5)
1. Alegro Alfragide - 10 salas
2. Alegro Setúbal - 8 salas
3. Alvalade - 12 salas
4. Campo Pequeno - 8 salas
5. Leiria - 6 salas

### Filmes (4)
- The Matrix (Ficção Científica)
- Oppenheimer (Drama)
- Killers of the Flower Moon (Thriller)
- Dune (Ficção Científica)

### Sessões (22)
- Distribuídas entre todos os cinemas
- Datas: Janeiro de 2026
- Preço padrão: 7,50€

## Próximos Passos

1. ✅ Criar `checkout-seats.html` com seleção de assentos
2. ✅ Criar `checkout.html` com dados de pagamento
3. ✅ Criar `api/get-session.php`
4. 🔄 Implementar `api/create-order.php`
5. 🔄 Integração com gateway de pagamento
6. 🔄 Sistema de QR codes para bilhetes
7. 🔄 Email de confirmação com bilhetes

## Como Testar

### Teste Completo:
1. Abrir `http://localhost/CinemaCity/index.html`
2. Selecionar Cinema, Filme, Data, Hora no booking bar
3. Clicar "Comprar bilhetes"
4. Selecionar assentos em `checkout-seats.html`
5. Clicar "Confirmar" para ir a `checkout.html`
6. Preencher dados pessoais e método de pagamento
7. Clicar "Pagar agora"

### Teste Direto de Página:
- Seleção de assentos: `http://localhost/CinemaCity/checkout-seats.html?session=1`
- Checkout: `http://localhost/CinemaCity/checkout.html?session=1&seats=A1,A2,B3&totalPrice=22.50`

## Design Responsivo

Todos as páginas são responsivas:
- ✅ Desktop (1200px+)
- ✅ Tablet (768px - 1199px)
- ✅ Mobile (< 768px)

Layout se adapta automaticamente:
- Desktop: Info panel + Seats panel lado a lado
- Mobile: Info panel acima, Seats panel abaixo
