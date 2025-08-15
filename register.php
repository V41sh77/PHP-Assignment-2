<?php
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/header.php';

$errs = [];
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pwd = $_POST['password'] ?? '';
    $pwd2 = $_POST['confirm'] ?? '';
    if ($name === '') $errs[] = "Name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errs[] = "Valid email required.";
    if (strlen($pwd) < 8) $errs[] = "Password must be at least 8 characters.";
    if ($pwd !== $pwd2) $errs[] = "Passwords do not match.";
    [$pic, $uerr] = save_pic($_FILES['avatar'] ?? null);
    if ($uerr) $errs[] = $uerr;

    if (!$errs) {
        try {
            q("INSERT INTO users (name,email,password_hash,is_admin,avatar_path) VALUES (:n,:e,:h,0,:a)", [
                ':n'=>$name, ':e'=>$email, ':h'=>password_hash($pwd, PASSWORD_DEFAULT), ':a'=>$pic
            ]);
            header("Location: /login.php?ok=1");
            exit;
        } catch (PDOException $ex) {
            if ($ex->getCode() === '23000') $errs[] = "Email already registered.";
            else $errs[] = "Registration failed.";
        }
    }
}
?>
<h1 class="h1">Create Account</h1>
<?php if ($errs): ?><div class="err"><ul><?php foreach($errs as $e){ echo "<li>".esc($e)."</li>"; } ?></ul></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="form">
  <div class="field">
    <label for="name">Name</label>
    <input id="name" name="name" type="text" maxlength="100" required value="<?=esc($name)?>">
  </div>
  <div class="field">
    <label for="email">Email</label>
    <input id="email" name="email" type="email" required value="<?=esc($email)?>">
  </div>
  <div class="field">
    <label for="password">Password</label>
    <input id="password" name="password" type="password" required minlength="8">
  </div>
  <div class="field">
    <label for="confirm">Confirm Password</label>
    <input id="confirm" name="confirm" type="password" required minlength="8">
  </div>
  <div class="field">
    <label for="avatar">Profile Image (optional)</label>
    <input id="avatar" name="avatar" type="file" accept="image/*">
  </div>
  <button class="btn primary" type="submit">Register</button>
</form>
<?php require __DIR__ . '/includes/footer.php'; ?>
