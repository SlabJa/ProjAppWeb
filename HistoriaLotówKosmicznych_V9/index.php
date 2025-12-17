<?php
//Główny plik indeksowy. Odpowiada za strukturę HTML i dynamiczne ładowanie treści.


include 'cfg.php';
include 'showpage.php';

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

// ---------------------------------------------------------
// LOGIKA WYBORU PODSTRONY
// ---------------------------------------------------------

$id = 1; //Domyślnie strona główna

//Przypisanie id na podstawie id strony w bazie
if (isset($_GET['idp'])) {
  $id = (int)$_GET['idp'];
} 
else {
  $id = 1; // Strona główna, jeśli brak parametru
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Projekt 1">
    <meta name="keywords" content="HTML5, CSS3, JavaScript, PHP">
    <meta name="Author" content="Jan Słabuszewski">
    <title>Historia Lotów Kosmicznych</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/zmientlo.js" type="text/javascript"></script>
    <script src="js/timedate.js" type="text/javascript"></script>
</head>
<body onload="startclock()">
  <header class="site-header">
    <div class="container header-inner">
      <a href="index.php" class="logo-link">
        <img src="images/rocket_logo.png" alt="Logo" class="logo" loading="lazy">
      </a>
      <nav class="main-nav">
        <ul>
          <li><a href="index.php?idp=1">Strona główna</a></li>
          <li><a href="index.php?idp=3">Pionierzy</a></li>
          <li><a href="index.php?idp=4">Statki i rakiety</a></li>
          <li><a href="index.php?idp=2">Misje</a></li>
          <li><a href="index.php?idp=5">Galeria</a></li>
          <li><a href="index.php?idp=6">Filmy</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main>
    <?php
    //Wyswietlenie zawartości strony (z showpage.cfg)
    echo pokazpodstrone($id);
    ?>
  </main>

  <footer class="site-footer">
    <div class="container footer-inner">
      <div class="color-picker">
        <figure class="color-btn" style="--color:#071224" onclick="changeBackground('#071224')" title="Blue"></figure>
        <figure class="color-btn" style="--color:#0b0f17" onclick="changeBackground('#0b0f17')" title="Black"></figure>
        <figure class="color-btn" style="--color:#2c0000" onclick="changeBackground('#2c0000')" title="Red"></figure>
      </div>
      <div class="footer-text">
        <p><a href="https://www.nasa.gov">NASA</a> · <a href="https://www.esa.int">ESA</a></p>
        <div class="datetime">
          <div id="zegarek"></div>
          <div id="data"></div>
        </div>
      </div>
    </div>
  </footer>

  <script>
    // Animacja menu
    $(".main-nav a").on({
      "mouseenter" : function() {
        $(this).stop().animate({ 
          width: 220,
          height: 30
        }, 400);
      },
      "mouseleave" : function() {
        $(this).stop().animate({
          width: 160,
          height: 20
        }, 400);
      }
    });
  </script>

<?php
 $nr_indeksu = '175504';
 $nrGrupy = 'ISI3';
 echo 'Autor: Jan Słabuszewski '.$nr_indeksu.' grupa '.$nrGrupy.' <br /><br />';
?>

</body>
</html>
