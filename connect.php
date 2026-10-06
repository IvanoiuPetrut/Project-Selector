<?php
// Afiseaza erorile doar in modul de dezvoltare (APP_DEBUG=1)
$debug = getenv('APP_DEBUG') === '1';
error_reporting($debug ? E_ALL : E_ALL & ~E_DEPRECATED);
ini_set('display_errors', $debug ? '1' : '0');

// Codul verifica valorile returnate de mysqli, nu exceptii (comportamentul de dinainte de PHP 8.1)
mysqli_report(MYSQLI_REPORT_OFF);

global $link;
// Conectare la baza de date; valorile vin din variabilele de mediu (vezi .env.example)
$link = mysqli_connect(
  getenv('DB_HOST') ?: 'localhost',
  getenv('DB_USER') ?: 'root',
  getenv('DB_PASSWORD') ?: '',
  getenv('DB_NAME') ?: 'project_selector',
  (int) (getenv('DB_PORT') ?: 3306)
);

// Verficare conexiune
if ($link === false) {
  die('ERROR: Could not connect. ' . mysqli_connect_error());
}
mysqli_set_charset($link, 'utf8mb4');
?>