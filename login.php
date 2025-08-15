<?php
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/header.php';

$errs = [];
$email = trim($_POST['email'] ?? '');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pwd = $_POST['password'] ?? '';
    $u = q("SELECT * FROM users WHERE email=:e", [':e'=>$email])->fetch();
    if ($u && password_verify($pwd, $u['password_hash'])) {
        $_SESSION['u'] = ['id'=>(int)$u['id'],'name'=>$u['name'],'email'=>$u['email'],'is_admin'=>(bool)$u['is_admin']];
        header("Location: /index.php");
        exit;
    } else {
        $errs[] = "Invalid credentials.";
    }
}
?>
<h1 class="h1">Login</h1>
<?php if (isset($_GET['ok'])): ?><div class="notice">Registration successful. Please sign in.</div><?php endif; ?>
<?php if ($errs): ?><div class="err"><ul><?php foreach($errs as $e){ echo "<li>".esc($e)."</li>"; } ?></ul></div><?php endif; ?>
<form method="post" class="form">
  <div class="field">
    <label for="email">Email</label>
    <input id="email" name="email" type="email" required value="<?=esc($email)?>">
  </div>
  <div class="field">
    <label for="password">Password</label>
    <input id="password" name="password" type="password" required>
  </div>
  <button class="btn primary" type="submit">Login</button>
</form>
<?php require __DIR__ . '/includes/footer.php'; ?>
