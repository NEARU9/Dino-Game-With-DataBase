<?php
session_start();
include "koneksi.php";

// Cek apakah pengguna adalah developer
$developerCheckQuery = "SELECT * FROM tbdeveloper WHERE username = '$username' AND password = '$password'";
$developerCheckResult = mysqli_query($kon, $developerCheckQuery);

if ($developerCheckResult) {
    $isDeveloper = mysqli_num_rows($developerCheckResult) > 0;

    if ($isDeveloper) {
        // Jika pengguna adalah developer, arahkan ke halaman developer
        header("Location: developer_dashboard.php");
        exit(); // Penting untuk menghentikan eksekusi skrip setelah mengarahkan header
    }
} else {
    echo "<p style='color: red;'>Gagal melakukan pemeriksaan developer! Error: " . mysqli_error($kon) . "</p>";
}

// Cek apakah pengguna telah login
if (!empty($_SESSION["useradm"]) && !empty($_SESSION["passadm"])) {
    $username = $_SESSION["useradm"];

    // Ambil skor dari database
    $result = mysqli_query($kon, "SELECT player_score FROM tbplayer WHERE username = '$username'");
    
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $currentScore = $row['player_score'];
        $_SESSION["score"] = $currentScore;

        // Ambil skor tertinggi dari pengguna tertentu
        $userHighScoreResult = mysqli_query($kon, "SELECT MAX(player_score) AS high_score FROM tbplayer WHERE username = '$username'");
        $userHighScoreRow = mysqli_fetch_assoc($userHighScoreResult);
        $userHighScore = $userHighScoreRow['high_score'];
    } else {
        echo "<p style='color: red;'>Gagal mengambil skor dari database! Error: " . mysqli_error($kon) . "</p>";
    }

    // Tambahkan logika pembaruan skor di sini (berdasarkan kondisi tertentu, misalnya, saat permainan berakhir)
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["score"])) {
        // Terima skor dari JavaScript
        $newScore = $_POST["score"];

        // Ambil skor tertinggi dari database
        $result = mysqli_query($kon, "SELECT player_score FROM tbplayer WHERE username = '$username'");

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $currentScore = $row['player_score'];

            // Tambahkan skor baru ke skor tertinggi jika skor baru lebih tinggi
            $newHighScore = max($currentScore, $newScore);

            // Perbarui skor di database
            $updateResult = mysqli_query($kon, "UPDATE tbplayer SET player_score = $newHighScore WHERE username = '$username'");

            if ($updateResult) {
                echo "Skor berhasil diperbarui di database!";
            } else {
                echo "Gagal memperbarui skor di database! Error: " . mysqli_error($kon);
            }
        } else {
            echo "Gagal mengambil skor dari database! Error: " . mysqli_error($kon);
        }

        // Tampilkan skor tertinggi setelah pembaruan
        $highScoreResult = mysqli_query($kon, "SELECT MAX(player_score) AS high_score FROM tbplayer");
        $highScoreRow = mysqli_fetch_assoc($highScoreResult);
        $highScore = $highScoreRow['high_score'];
        echo "<h3>Skor Tertinggi: $highScore</h3>";
    }
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dino Game</title>
        <link rel="stylesheet" href="">
        <script src="dino.js"></script>
        <style>
            /* Gaya CSS tambahan di sini */

            body {
                font-family:'Courier New', Courier, monospace;
                text-align: center;
                display: flex;
                flex-direction: row;
                justify-content: space-around;
            }

            #left {
                flex: 1;
                text-align: left;
                margin-left: 10px;
                margin-top: 8%;
            }

            #center {
                flex: 2;
            }

            #right {
                flex: 1;
                text-align: right;
                margin-right: 10px;
            }

            .mulai, .logout {
                display: inline-block;
                padding: 15px 30px;
                font-size: 18px;
                text-align: center;
                text-decoration: none;
                background-color: #4CAF50;
                color: white;
                border-radius: 5px;
                transition: background-color 0.3s ease;
                margin-right: 10px;
                margin-top: 10px;
            }

            .mulai:hover, .logout:hover{
                background-color: #45a049;
            }

            .username h3 p {
                color: #333;
                margin-bottom: 10px;
            }

            #board {
                background-color: lightgray;
                border-bottom: 1px solid black;
                position: relative;
                z-index: 0;
            }

            #gameOverImg {
                display: none; /* Initially hide the game over image */
                position: absolute;
                top: 50px; /* Adjust the top position as needed */
                left: 50px; /* Adjust the left position as needed */
                z-index: 1; /* Ensure the game over image is above the canvas */
            }

            /* Gaya CSS untuk peringkat top score */
            table {
                border-collapse: collapse;
                width: 100%;
                margin-top: 35px;
                margin-left: 10px;
            }

            th, td {
                border: 1px solid #ddd;
                padding: 10px;
                text-align: left;
            }

            th {
                background-color: #4CAF50;
                color: white;
            }

        /* Media queries untuk tampilan handphone */
        @media screen and (max-width: 600px) {
            #center::before {
            content: "FOR Windows";
            font-size: 24px;
            color: red; /* Atur warna teks sesuai keinginan Anda */
            display: block;
            margin-bottom: 10px;
        }
    }

        </style>
    </head>
    <body>
        <div id="left">
            <h3 style="color: blue;"> Space for Jump</h3>
            <a class="mulai" href="#" onclick="refreshPage()">Mulai</a>
            <a class="logout" href="?p=logout">Logout</a>
        </div>

        <div id="center">
            <div class="username">
                <h3>Username: <?php echo $username; ?></h3>
                <?php echo "<h3>Skor Tertinggi: $userHighScore</h3>"; ?>
            </div>
            <canvas id="board"></canvas>
            <img id="gameOverImg" src="./img/game-over.png" alt="Game Over">
        </div>

        <div id="right">
            <?php
            // Ambil peringkat top score dari database
            $topScoreResult = mysqli_query($kon, "SELECT username, player_score FROM tbplayer ORDER BY player_score DESC LIMIT 10");

            if ($topScoreResult) {
                echo "<h3>Top 10 Leaderboards:</h3>";
                echo "<table>";
                echo "<tr><th>Username</th><th>Skor</th></tr>";
                while ($topScoreRow = mysqli_fetch_assoc($topScoreResult)) {
                    $topUsername = $topScoreRow['username'];
                    $topScore = $topScoreRow['player_score'];
                    echo "<tr><td>$topUsername</td><td>$topScore</td></tr>";
                }
                echo "</table>";
            } else {
                echo "<p style='color: red;'>Gagal mengambil peringkat top score dari database! Error: " . mysqli_error($kon) . "</p>";
            }
            ?>
        </div>
        <div class="grid">
            <?php
            if ($_GET["p"] == "logout") {
                include "logout.php";
            }
            ?>
        </div>

        <?php
} else {
    // Tampilkan halaman login jika pengguna belum login
    include "login.php";
}
?>

<script>
function refreshPage() {
    location.reload();
}
</script>
</body>
</html>
