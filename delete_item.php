<?php
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/header.php';
need_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo "Method Not Allowed"; require __DIR__ . '/includes/footer.php'; exit; }
$id = (int)($_POST['id'] ?? 0);
$itm = q("SELECT * FROM items WHERE id=:i", [':i'=>$id])->fetch();
if (!$itm){ http_response_code(404); echo "Not found"; require __DIR__ . '/includes/footer.php'; exit; }
if (!is_admin() && me()['id'] != $itm['user_id']) { http_response_code(403); echo "Forbidden"; require __DIR__ . '/includes/footer.php'; exit; }

if (!empty($itm['image_path'])) {
    $old = __DIR__ . '/' . $itm['image_path'];
    if (is_file($old)) @unlink($old);
}
q("DELETE FROM items WHERE id=:i", [':i'=>$id]);

header("Location: /index.php");
exit;
