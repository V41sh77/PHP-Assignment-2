<?php
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/header.php';
only_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo "Method Not Allowed"; require __DIR__ . '/includes/footer.php'; exit; }
$uid = (int)($_POST['id'] ?? 0);
$u = q("SELECT * FROM users WHERE id=:i", [':i'=>$uid])->fetch();
if (!$u){ http_response_code(404); echo "User not found"; require __DIR__ . '/includes/footer.php'; exit; }


if (!empty($u['avatar_path'])) {
    $p = __DIR__ . '/' . $u['avatar_path'];
    if (is_file($p)) @unlink($p);
}

$its = q("SELECT image_path FROM items WHERE user_id=:u", [':u'=>$uid])->fetchAll();
foreach($its as $it){
    if (!empty($it['image_path'])) {
        $pp = __DIR__ . '/' . $it['image_path'];
        if (is_file($pp)) @unlink($pp);
    }
}


q("DELETE FROM items WHERE user_id=:u", [':u'=>$uid]);
q("DELETE FROM users WHERE id=:i", [':i'=>$uid]);

header("Location: /dashboard.php");
exit;
