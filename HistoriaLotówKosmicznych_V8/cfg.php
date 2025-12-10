<?php
// Plik konfiguracyjny zawierający dane dostępowe do bazy danych oraz dane logowania administratora.

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$baza = 'moja_strona';

// Dane logowania do panelu administratora
$login = "admin";
$pass = "pass1";

// Nawiązanie połączenia z bazą danych
$link = mysqli_connect($dbhost, $dbuser, $dbpass);

if(!$link) echo '<b>przerwane polaczenie </b>';
if(!mysqli_select_db($link, $baza)) echo 'nie wybrano bazy';
?>