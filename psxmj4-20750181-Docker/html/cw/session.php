<?php
session_start();

$timeout_seconds = 900;

if (isset($_SESSION['last_activity'])) {
    if ((time() - $_SESSION['last_activity']) > $timeout_seconds) {
        session_unset();
        session_destroy();
        header("Location: login.php?timeout=1");
        exit();
    }
}

$_SESSION['last_activity'] = time();

function require_login() {
    if (!isset($_SESSION['logged_in'])) {
        header("Location: login.php");
        exit();
    }
}

?>