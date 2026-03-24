<?php
session_start();
require_once '../app/config/config.php';
require_once '../app/core/Database.php';
require_once '../app/core/Controller.php';
require_once '../app/core/App.php';

$init = new App(); // Khởi chạy Router
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);