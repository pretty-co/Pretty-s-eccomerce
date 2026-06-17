<?php
function secure_session_start() {
    session_start();
    

    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id(true);
        $_SESSION['initiated'] = true;

    if (isset($_SESSION['user_agent']) && $_SESSION['user_agent'] !== md5($_SERVER['HTTP_USER_AGENT'])) {
        session_destroy();
        header("Location: index.php");
        exit();
    }
    $_SESSION['user_agent'] = md5($_SERVER['HTTP_USER_AGENT']);
    

    if (isset($_SESSION['ip_address']) && $_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
        session_destroy();
        header("Location: index.php");
        exit();
    }
    $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
}

function check_session_timeout() {
    $timeout = 1800; 
    
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
        session_unset();
        session_destroy();
        header("Location: index.php?timeout=1");
        exit();
    }
    $_SESSION['last_activity'] = time();
}

function set_secure_cookie($name, $value, $expire = 0) {
    setcookie($name, $value, [
        'expires' => $expire,
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
}
?>