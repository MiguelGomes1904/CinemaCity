<?php
// Run this script once (via browser or CLI) to create movies and sessions tables and seed sample data.
require_once __DIR__ . '/..//api/db.inc';

// Create movies table
$sql = "CREATE TABLE IF NOT EXISTS movies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  original_title VARCHAR(255),
  poster VARCHAR(255),
  synopsis TEXT,
  duration VARCHAR(50),
  rating VARCHAR(20),
  director VARCHAR(255),
  country VARCHAR(255),
  release_date DATE
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if ($conn->query($sql) === TRUE) {
    echo "Created/checked table `movies`.\n";
} else {
    die("Error creating movies table: " . $conn->error);
}

// Create sessions table
$sql = "CREATE TABLE IF NOT EXISTS sessions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  movie_id INT NOT NULL,
  cinema VARCHAR(255) NOT NULL,
  show_time TIME NOT NULL,
  show_date DATE NOT NULL,
  FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if ($conn->query($sql) === TRUE) {
    echo "Created/checked table `sessions`.\n";
} else {
    die("Error creating sessions table: " . $conn->error);
}

// Insert sample movies if none exist
$res = $conn->query("SELECT COUNT(*) as cnt FROM movies");
$row = $res->fetch_assoc();
if ($row['cnt'] == 0) {
    $movies = [
        [ 'title' => 'Depois da Caçada', 'original' => 'After The Hunt', 'poster' => 'assets/capa1.jpg', 'syn' => 'AFTER THE HUNT é um intenso drama psicológico...', 'duration'=>'139 min', 'rating'=>'M14', 'director'=>'Luca Guadagnino', 'country'=>'Estados Unidos', 'release' => '2025-01-01'],
        [ 'title' => 'Gato Fantasma Anzu', 'original' => '', 'poster' => 'assets/capa 2.jpg', 'syn' => '', 'duration'=>'97 min', 'rating'=>'M12', 'director'=>'', 'country'=>'', 'release' => '2025-01-01'],
        [ 'title' => 'Um Ladrão no Telhado', 'original' => '', 'poster' => 'assets/capa 3.jpg', 'syn' => '', 'duration'=>'97 min', 'rating'=>'M12', 'director'=>'', 'country'=>'', 'release' => '2025-01-01'],
        [ 'title' => 'Partir, Um Dia', 'original' => '', 'poster' => 'assets/capa 4.jpg', 'syn' => '', 'duration'=>'97 min', 'rating'=>'M12', 'director'=>'', 'country'=>'', 'release' => '2025-01-01'],
        [ 'title' => 'Snow Princesa', 'original' => '', 'poster' => 'assets/capa 5.jpg', 'syn' => '', 'duration'=>'97 min', 'rating'=>'M12', 'director'=>'', 'country'=>'', 'release' => '2025-01-01'],
        [ 'title' => 'O Telefone Negro 2', 'original' => '', 'poster' => 'assets/capa 6.jpg', 'syn' => '', 'duration'=>'97 min', 'rating'=>'M12', 'director'=>'', 'country'=>'', 'release' => '2025-01-01'],
        [ 'title' => 'Kantara', 'original' => '', 'poster' => 'assets/capa 7.jpg', 'syn' => '', 'duration'=>'97 min', 'rating'=>'M12', 'director'=>'', 'country'=>'', 'release' => '2025-01-01'],
        [ 'title' => 'Mr. Burton', 'original' => '', 'poster' => 'assets/capa 8.jpg', 'syn' => '', 'duration'=>'97 min', 'rating'=>'M12', 'director'=>'', 'country'=>'', 'release' => '2025-01-01'],
        [ 'title' => 'Filme Extra 1', 'original' => '', 'poster' => 'assets/capa 9.jpg', 'syn' => '', 'duration'=>'97 min', 'rating'=>'M12', 'director'=>'', 'country'=>'', 'release' => '2025-01-01'],
        [ 'title' => 'Filme Extra 2', 'original' => '', 'poster' => 'assets/capa 10.jpg', 'syn' => '', 'duration'=>'97 min', 'rating'=>'M12', 'director'=>'', 'country'=>'', 'release' => '2025-01-01'],
        [ 'title' => 'Filme Extra 3', 'original' => '', 'poster' => 'assets/capa 12.jpg', 'syn' => '', 'duration'=>'97 min', 'rating'=>'M12', 'director'=>'', 'country'=>'', 'release' => '2025-01-01'],
        [ 'title' => 'Filme Extra 4', 'original' => '', 'poster' => 'assets/capa 13.jpg', 'syn' => '', 'duration'=>'97 min', 'rating'=>'M12', 'director'=>'', 'country'=>'', 'release' => '2025-01-01']
    ];

    $stmt = $conn->prepare("INSERT INTO movies (title, original_title, poster, synopsis, duration, rating, director, country, release_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($movies as $m) {
        $stmt->bind_param('sssssssss', $m['title'], $m['original'], $m['poster'], $m['syn'], $m['duration'], $m['rating'], $m['director'], $m['country'], $m['release']);
        $stmt->execute();
    }
    echo "Inserted sample movies.\n";
} else {
    echo "Movies table already has data (skipping seeding).\n";
}

// Optionally seed some sessions for movie id 1
$res = $conn->query("SELECT COUNT(*) as cnt FROM sessions");
$row = $res->fetch_assoc();
if ($row['cnt'] == 0) {
    $stmt = $conn->prepare("INSERT INTO sessions (movie_id, cinema, show_time, show_date) VALUES (?, ?, ?, ?)");
    $date = date('Y-m-d');
    $times = ['11:30:00','23:00:00','15:45:00'];
    for ($i=1;$i<=8;$i++) {
        foreach ($times as $t) {
            $cinema = 'Cinema ' . ($i);
            $stmt->bind_param('isss', $i, $cinema, $t, $date);
            $stmt->execute();
        }
    }
    echo "Inserted sample sessions.\n";
} else {
    echo "Sessions table already has data (skipping).\n";
}

echo "Setup complete.\n";

?>
