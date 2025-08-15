<?php

$__db_host = '127.0.0.1';
$__db_name = 'grocery_app';
$__db_user = 'root';
$__db_pass = '';


try {
    $__dsn = "mysql:host={$__db_host};dbname={$__db_name};charset=utf8mb4";
    $cxn = new PDO($__dsn, $__db_user, $__db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $oops) {
    http_response_code(500);
    echo "Database connection failed.";
    exit;
}


function q($sql, $params = []) {
    global $cxn;
    $st = $cxn->prepare($sql);
    $st->execute($params);
    return $st;
}
