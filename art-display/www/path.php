<?php
// Определение базового URL
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$base_url = $protocol . "://" . $host."/";

// Определение константы BASE_URL
define('BASE_URL', $base_url);?>