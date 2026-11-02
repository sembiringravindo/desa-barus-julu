<?php
require_once '../config/session.php';
session_destroy();
redirect(BASE_URL . 'auth/login.php');