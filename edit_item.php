<?php
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/header.php';
need_login();

$id = (int)($_GET['id'] ?? 0);
$itm = q("SELECT * FROM items WHERE id=:i", [':i'=>$id])->fetch();
if (!$itm){ http_response_code(404); echo "Not found"; require __DIR__ . '/includes/footer.php'; exit; }

if (!is_admin() && me()['id'] != $itm['user_id']) {
    http_response_code(403); echo "Forbidden"; require __DIR__ . '/includes/footer.php'; exit;
}

$title = trim($_POST['title'] ?? $itm['title']);
$category = trim($_POST['category'] ?? $itm['category']);
$price = trim($_POST['price'] ?? $itm['price']);
$details = trim($_POST['details'] ?? $itm['details']);
$errs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($title === '') $errs[] = "Item name is required.";
    if ($price === '' || !is_numeric($price) || (float)$price < 0) $errs[] = "Price must be a valid non-negative number.";
    [$img, $uerr] = save_pic($_FILES['image'] ?? null);
    if ($uerr) $errs[] = $uerr;

    if (!$errs) {
        
        if ($img) {
            if (!empty($itm['image_path'])) {
                $old = __DIR__ . '/' . $itm['image_path'];
                if (is_file($old)) @unlink($old);
            }
            $itm['image_path'] = $img;
        }
        q("UPDATE items SET title=:t, category=:c, price=:p, details=:d, image_path=:i WHERE id=:id", [
            ':t'=>$title, ':c'=>$category, ':p'=>number_format((float)$price,2,'.',''), ':d'=>$details, ':i'=>$itm['image_path'], ':id'=>$id
        ]);
        header("Location: /index.php");
        exit;
    }
}
?>
<h1 class="h1">Edit Item</h1>
<?php if ($errs): ?><div class="err"><ul><?php foreach($errs as $e){ echo "<li>".esc($e)."</li>"; } ?></ul></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="form">
  <div class="field">
    <label for="title">Item Name</label>
    <input id="title" name="title" type="text" maxlength="150" required value="<?=esc($title)?>">
  </div>
  <div class="field">
    <label for="category">Category</label>
    <input id="category" name="category" type="text" maxlength="60" value="<?=esc($category)?>">
  </div>
  <div class="field">
    <label for="price">Price</label>
    <input id="price" name="price" type="number" min="0" step="0.01" required value="<?=esc($price)?>">
  </div>
  <div class="field">
    <label for="details">Description</label>
    <textarea id="details" name="details" rows="4" maxlength="4000"><?=esc($details)?></textarea>
  </div>
  <div class="field">
    <label for="image">Replace Image</label>
    <input id="image" name="image" type="file" accept="image/*">
  </div>
  <button class="btn primary" type="submit">Save Changes</button>
</form>
<?php require __DIR__ . '/includes/footer.php'; ?>
