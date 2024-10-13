<?php
session_start();
include "koneksi.php";

// Fungsi untuk menghapus pemain
function deletePlayer($kon, $playerID) {
    $deleteQuery = "DELETE FROM tbplayer WHERE id = $playerID";
    $deleteResult = mysqli_query($kon, $deleteQuery);

    if ($deleteResult) {
        return true; // Berhasil menghapus pemain
    } else {
        return false; // Gagal menghapus pemain
    }
}

// Fungsi untuk mengedit nama pemain
function editPlayerName($kon, $playerID, $newName) {
    $editQuery = "UPDATE tbplayer SET username = '$newName' WHERE id = $playerID";
    $editResult = mysqli_query($kon, $editQuery);

    if ($editResult) {
        return true; // Berhasil mengedit nama pemain
    } else {
        return false; // Gagal mengedit nama pemain
    }
}

// Ambil data semua pemain
$allPlayersResult = mysqli_query($kon, "SELECT * FROM tbplayer");

if ($allPlayersResult) {
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Developer Dashboard</title>
        <style>
            body {
                font-family: 'Arial', sans-serif;
                margin: 20px;
            }

            h2 {
                color: #333;
                text-align: center;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            th, td {
                border: 1px solid #ddd;
                padding: 10px;
                text-align: center;
            }

            th {
                background-color: #4CAF50;
                color: white;
            }

            form {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            input[type="text"] {
                padding: 5px;
            }

            button {
                padding: 5px 10px;
                background-color: #4CAF50;
                color: white;
                border: none;
                cursor: pointer;
            }

            button:hover {
                background-color: #45a049;
            }

            p {
                margin: 10px 0;
            }

            .success {
                color: green;
            }

            .error {
                color: red;
            }

            .delete-link {
                color: #fff; /* Warna teks link */
                background-color: #e74c3c; /* Warna latar belakang link */
                padding: 5px 10px; /* Padding agar tampilan lebih baik */
                text-decoration: none; /* Hapus garis bawah pada link */
                border-radius: 3px; /* Sudut bulat untuk tampilan yang lebih lembut */
            }

            .delete-link:hover {
                background-color: #c0392b; /* Warna latar belakang saat dihover */
            }

            .logout-link {
                display: block;
                text-align: center;
                margin-top: 20px;
                padding: 10px;
                background-color: #e74c3c;
                color: #fff;
                text-decoration: none;
                border-radius: 3px;
            }

            .logout-link:hover {
                background-color: #c0392b;
            }

        </style>
    </head>
    <body>
        <h2>Data Semua Pemain</h2>

            <table border='1'>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Password</th>
                <th>Player Score</th>
                <th>Action</th>
            </tr>

            <?php
            while ($playerRow = mysqli_fetch_assoc($allPlayersResult)) {
                $playerID = $playerRow['id'];
                $playerUsername = $playerRow['username'];
                $playerPassword = $playerRow['password']; // Ditambahkan kolom password
                $playerScore = $playerRow['player_score'];

                echo "<tr>";
                echo "<td>$playerID</td>";
                echo "<td>";
                echo "<form method='post' action='?action=edit&id=$playerID'>";
                echo "<input type='text' name='newName' value='$playerUsername'>";
                echo "<button type='submit'>Edit</button>";
                echo "</form>";
                echo "</td>";
                echo "<td>$playerPassword</td>"; // Menampilkan password
                echo "<td>$playerScore</td>";
                echo "<td><a class='delete-link' href='?action=delete&id=$playerID'>Delete</a></td>";
                echo "</tr>";
            }
            ?>
        </table>

        <!-- Proses penghapusan pemain -->
        <?php
        if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
            $playerIDToDelete = $_GET['id'];
            $deleteSuccess = deletePlayer($kon, $playerIDToDelete);

            if ($deleteSuccess) {
                echo "<p class='success'>Data pemain berhasil dihapus!</p>";
            } else {
                echo "<p class='error'>Gagal menghapus data pemain! Error: " . mysqli_error($kon) . "</p>";
            }
        }

        // Proses pengeditan nama pemain
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["action"]) && $_GET["action"] == "edit" && isset($_GET["id"])) {
            $playerIDToEdit = $_GET["id"];
            $newName = mysqli_real_escape_string($kon, $_POST["newName"]);
            $editSuccess = editPlayerName($kon, $playerIDToEdit, $newName);

            if ($editSuccess) {
                echo "<p class='success'>Nama pemain berhasil diedit!</p>";
            } else {
                echo "<p class='error'>Gagal mengedit nama pemain! Error: " . mysqli_error($kon) . "</p>";
            }
        }
        ?>

        <a class="logout-link" href="index.php">Logout</a>


    </body>
    </html>

    <?php
} else {
    echo "<p style='color: red;'>Gagal mengambil data pemain dari database! Error: " . mysqli_error($kon) . "</p>";
}
?>
