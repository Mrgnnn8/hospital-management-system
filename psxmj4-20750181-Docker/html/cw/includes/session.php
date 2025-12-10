<?php

// Allows for the tracking of a user across different pages.

session_start();

// Times out a user after 15 minutes, once they refresh they will be returned to index.php

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

// Enforces all pages past index.php to be logged in to view.

function require_login() {
    if (!isset($_SESSION['logged_in'])) {
        header("Location: index.php");
        exit();
    }
}

// Enforces is_admin = True to view present pages.

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