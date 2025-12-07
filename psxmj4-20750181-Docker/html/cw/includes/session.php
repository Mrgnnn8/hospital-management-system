<?php
session_start();

$timeout_seconds = 900;

if (isset($_SESSION['last_activity'])) {
    if ((time() - $_SESSION['last_activity']) > $timeout_seconds) {
        session_unset();
        session_destroy();
        header("Location: index.php?timeout=1");
        exit();
    }
}

$_SESSION['last_activity'] = time();

function require_login() {
    if (!isset($_SESSION['logged_in'])) {
        header("Location: index.php");
        exit();
    }
}

function require_admin() {
    require_login(); 

    if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
        
        if (function_exists('logAction') && isset($_SESSION['user_id'])) {
        }

        header("Location: home.php");
        exit(); 
    }
}

?>