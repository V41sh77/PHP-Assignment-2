<?php
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/header.php';
only_admin();

$list = q("SELECT id,name,email,avatar_path,created_at FROM users WHERE avatar_path IS NOT NULL ORDER BY created_at DESC")->fetchAll();
?>
<h1 class="h1">User Gallery (Uploaded Avatars)</h1>
<p class="small">Admin tool: preview users who uploaded profile images. You may remove accounts if necessary.</p>

<table class="table">
  <thead>
    <tr>
      <th>Avatar</th>
      <th>Name</th>
      <th>Email</th>
      <th>Joined</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($list as $u): ?>
    <tr>
      <td><?php if(!empty($u['avatar_path'])): ?><img class="avatar" src="/<?=esc($u['avatar_path'])?>" alt="avatar"><?php else: ?><span class="badge">none</span><?php endif; ?></td>
      <td><?=esc($u['name'])?></td>
      <td><?=esc($u['email'])?></td>
      <td class="small"><?=esc($u['created_at'])?></td>
      <td class="actions">
        <?php if ((int)$u['id'] !== (int)me()['id']): ?>
          <form method="post" action="/delete_user.php" onsubmit="return confirm('Delete this user and their items?');">
            <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
            <button class="btn danger small" type="submit">Delete</button>
          </form>
        <?php else: ?>
          <span class="small">You</span>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<?php require __DIR__ . '/includes/footer.php'; ?>
