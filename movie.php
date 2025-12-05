<?php
// Dynamic movie detail page: loads movie by id from DB
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit();
}
$id = (int) $_GET['id'];
require_once __DIR__ . '/api/db.inc';

if (!isset($conn) || !($conn instanceof mysqli)) {
  echo "<p>Erro na conexão à base de dados. Verifica `api/db.inc`.</p>";
  exit();
}

$stmt = $conn->prepare('SELECT * FROM movies WHERE id = ? LIMIT 1');
if ($stmt === false) {
  // Show a helpful message instead of a fatal error
  echo "<h3>Erro na query</h3>";
  echo "<p>Detalhes: " . htmlspecialchars($conn->error) . "</p>";
  echo "<p>Verifica se a tabela `movies` existe e se a base de dados está correctamente configurada.</p>";
  echo "<p>Se quiser, executa <code>scripts/setup_movies.php</code> para criar as tabelas de exemplo.</p>";
  exit();
}
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    // movie not found
    http_response_code(404);
    echo "<p>Filme não encontrado. <a href='index.php'>Voltar</a></p>";
    exit();
}
$movie = $result->fetch_assoc();
// Add a small HTML comment with debug info so you can check which record was loaded
echo "<!-- DEBUG: requested id={$id} | loaded movie_id=" . htmlspecialchars($movie['id']) . " | title=" . htmlspecialchars($movie['title']) . " -->\n";
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($movie['title']); ?> — CinemaCity</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="assets/css/movie.css">
  <style> /* small inline adjustments */
    .navbar-brand{font-weight:700;color:#0b2f4b}
  </style>
</head>
<body>

  <?php include 'header.php'; ?>

  <header class="mc-hero" style="background-image: linear-gradient(90deg, rgba(10,41,71,0.85) 0%, rgba(62,100,135,0.5) 60%), url('assets/FUNDO.jpg');">
    <div class="container">
      <div class="row">
        <div class="col-md-3">
          <div class="mc-poster">
            <img src="<?php echo htmlspecialchars($movie['poster']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
          </div>
        </div>
        <div class="col-md-7 offset-md-1 text-white" style="margin-top:80px">
          <div class="mc-meta"><?php echo htmlspecialchars($movie['rating'] . ' • ' . $movie['duration']); ?></div>
          <h1 class="mc-hero-title"><?php echo htmlspecialchars($movie['title']); ?></h1>
          <div class="mc-hero-sub"><?php echo htmlspecialchars($movie['original_title']); ?></div>
          <p class="mt-3" style="max-width:680px;opacity:0.95"><?php echo nl2br(htmlspecialchars($movie['synopsis'])); ?></p>
          <a class="btn btn-outline-light mt-3" href="#sessoes">Ver Sessões →</a>
        </div>
      </div>
    </div>
  </header>

  <section class="mc-synopsis">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h4>Sinopse:</h4>
          <p><?php echo nl2br(htmlspecialchars($movie['synopsis'])); ?></p>
        </div>
        <div class="col-md-1 d-none d-md-block">
          <div class="divider-vertical"></div>
        </div>
        <div class="col-md-5">
          <h4>Detalhes</h4>
          <p><strong>Realizador:</strong> <?php echo htmlspecialchars($movie['director']); ?></p>
          <p><strong>País:</strong> <?php echo htmlspecialchars($movie['country']); ?></p>
          <p><strong>Estreia:</strong> <?php echo htmlspecialchars($movie['release_date']); ?></p>
        </div>
      </div>
    </div>
  </section>

  <section id="sessoes" class="schedule-tabs">
    <div class="container d-flex align-items-center">
      <div class="me-4 text-danger">Hoje</div>
      <div class="me-4">Amanhã</div>
      <div>Selecione a data</div>
    </div>
  </section>

  <section class="cinema-list">
    <div class="container">
      <div class="row">
        <div class="col-md-3">
          <div class="filters">
            <button class="btn btn-outline-primary">Todos</button>
          </div>
        </div>
        <div class="col-md-9">
          <?php
          // load sessions from DB
          $sstmt = $conn->prepare('SELECT cinema, show_time FROM sessions WHERE movie_id = ? AND show_date = CURDATE()');
          $sstmt->bind_param('i', $id);
          $sstmt->execute();
          $sres = $sstmt->get_result();
          while ($s = $sres->fetch_assoc()): ?>
            <div class="cinema-card">
              <div class="d-flex justify-content-between align-items-center">
                <div style="font-size:20px;font-weight:700"><?php echo htmlspecialchars($s['cinema']); ?></div>
                <div class="times"><div class="time-pill"><?php echo substr($s['show_time'],0,5); ?></div></div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
  </section>

  <?php include 'footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
