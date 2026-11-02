<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load database
require_once __DIR__ . '/database.php';

// Load helper
require_once __DIR__ . '/helper.php';