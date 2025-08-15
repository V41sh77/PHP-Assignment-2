<?php
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/header.php';


$title = trim($_POST['title'] ?? '');
$category = trim($_POST['category'] ?? '');
$price = trim($_POST['price'] ?? '');
$details = trim($_POST['details'] ?? '');
$errs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($title === '') $errs[] = "Item name is required.";
    if ($price === '' || !is_numeric($price) || (float)$price < 0) $errs[] = "Price must be a valid non-negative number.";
    [$img, $uerr] = save_pic($_FILES['image'] ?? null);
    if ($uerr) $errs[] = $uerr;

    if (!$errs) {
        $uid = me()['id'] ?? 1; // default admin owner
        q("INSERT INTO items (user_id, title, category, price, details, image_path) VALUES (:u,:t,:c,:p,:d,:i)", [
            ':u'=>$uid, ':t'=>$title, ':c'=>$category, ':p'=>number_format((float)$price,2,'.',''), ':d'=>$details, ':i'=>$img
        ]);
        header("Location: /index.php");
        exit;
    }
}
?>
<h1 class="h1">Add Grocery Item</h1>
<?php if ($errs): ?><div class="err"><ul><?php foreach($errs as $e){ echo "<li>".esc($e)."</li>"; } ?></ul></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="form">
  <div class="field">
    <label for="title">Item Name</label>
    <input id="title" name="title" type="text" maxlength="150" required value="<?=esc($title)?>">
  </div>
  <div class="field">
    <label for="category">Category</label>
    <input id="category" name="category" type="text" maxlength="60" placeholder="Produce, Bakery, Dairy..." value="<?=esc($category)?>">
  </div>
  <div class="field">
    <label for="price">Price</label>
    <input id="price" name="price" type="number" min="0" step="0.01" required value="<?=esc($price)?>">
  </div>
  <div class="field">
    <label for="details">Description</label>
    <textarea id="details" name="details" rows="4" maxlength="4000" placeholder="Optional details..."><?=esc($details)?></textarea>
  </div>
  <div class="field">
    <label for="image">Image (optional)</label>
    <input id="image" name="image" type="file" accept="image/*">
    <div class="small">Max 2MB. JPG/PNG/GIF/WEBP.</div>
  </div>
  <button class="btn primary" type="submit">Create</button>
</form>
<?php require __DIR__ . '/includes/footer.php'; ?>
