<?php
require __DIR__ . '/includes/header.php';
session_destroy();
header("Location: /index.php");
exit;
