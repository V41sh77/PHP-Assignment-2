<?php
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/header.php';

// Search + list grocery items
$needle = trim($_GET['q'] ?? '');
$sql = "SELECT g.*, u.name AS who FROM items g JOIN users u ON u.id = g.user_id";
$args = [];
if ($needle !== '') {
    $sql .= " WHERE g.title LIKE :q OR g.details LIKE :q OR g.category LIKE :q";
    $args[':q'] = "%{$needle}%";
}
$sql .= " ORDER BY g.created_at DESC";
$rows = q($sql, $args)->fetchAll();
?>
<h1 class="h1">Fresh Items</h1>
<form method="get" class="row" action="/index.php" style="margin-bottom:12px">
  <input type="text" name="q" placeholder="Search fruits, dairy, snacks..." value="<?=esc($needle)?>">
  <button class="btn" type="submit">Search</button>
</form>

<div class="grid">
<?php foreach ($rows as $r): ?>
  <div class="card">
    <div class="pic">
      <?php if (!empty($r['image_path'])): ?>
        <img src="/<?=esc($r['image_path'])?>" alt="<?=esc($r['title'])?>">
      <?php else: ?>
        <div class="small">No image</div>
      <?php endif; ?>
    </div>
    <div class="card-body">
      <div><strong><?=esc($r['title'])?></strong></div>
      <div class="small"><?=esc($r['category'])?> · $<?=number_format((float)$r['price'], 2)?></div>
      <div class="small">by <?=esc($r['who'])?></div>
      <div class="actions">
        <?php $mine = me() && (is_admin() || me()['id'] == $r['user_id']); ?>
        <?php if ($mine): ?>
          <a class="btn small" href="/edit_item.php?id=<?= (int)$r['id'] ?>">Edit</a>
          <form method="post" action="/delete_item.php" onsubmit="return confirm('Delete this item?');">
            <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
            <button class="btn small danger" type="submit">Delete</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
