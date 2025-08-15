<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


function esc($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function me(){ return $_SESSION['u'] ?? null; }
function is_admin(){ return !empty($_SESSION['u']) && !empty($_SESSION['u']['is_admin']); }
function need_login(){
    if (empty($_SESSION['u'])) {
        header("Location: /login.php?go=1");
        exit;
    }
}
function only_admin(){
    if (!is_admin()) {
        http_response_code(403);
        echo "Forbidden";
        exit;
    }
}

function save_pic($file){
    if (!$file || !isset($file['tmp_name']) || $file['error'] === UPLOAD_ERR_NO_FILE) return [null, null];
    if ($file['error'] !== UPLOAD_ERR_OK) return [null, "Upload error."];
    if ($file['size'] > 2*1024*1024) return [null, "Image too large (max 2MB)."];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $ok = ['image/jpeg'=>'jpg','image/png'=>'png','image/gif'=>'gif','image/webp'=>'webp'];
    if (!isset($ok[$mime])) return [null, "Unsupported image type."];
    $ext = $ok[$mime];
    $name = bin2hex(random_bytes(8)) . "." . $ext;
    $dest = __DIR__ . "/../uploads/" . $name;
    if (!move_uploaded_file($file['tmp_name'], $dest)) return [null, "Failed to store file."];
    return ["uploads/".$name, null];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Grocery Store CRUD with PHP">
  <title>GrocerEase</title>
  <link rel="stylesheet" href="/styles/style.css">
</head>
<body>
<header class="topbar">
  <div class="wrap">
    <div class="brand"><a href="/index.php">🛒 GrocerEase</a></div>
    <nav class="nav">
      <a href="/index.php">Home</a>
      <a href="/add_item.php">Add Item</a>
      <?php if (is_admin()): ?>
        <a href="/dashboard.php">Dashboard</a>
      <?php endif; ?>
    </nav>
    <div class="auth">
      <?php if (me()): ?>
        <span class="hello">Hello, <?=esc(me()['name'])?><?=is_admin()?' (admin)':''?></span>
        <a class="btn" href="/logout.php">Logout</a>
      <?php else: ?>
        <a class="btn" href="/login.php">Login</a>
        <a class="btn primary" href="/register.php">Register</a>
      <?php endif; ?>
    </div>
  </div>
</header>
<main class="wrap pad">
