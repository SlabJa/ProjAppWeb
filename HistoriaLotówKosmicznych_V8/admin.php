<?php
//Panel zarządzania treścią (CMS). Pozwala dodawać, edytować i usuwać podstrony.

include("cfg.php");

session_start();

// --- WYLOGOWANIE ---
if (isset($_GET['logout'])) {
    unset($_SESSION['zalogowany']);
    session_destroy();
    header("Location: admin.php");
    exit();
}

//$link = mysqli_connect("localhost", "root", "", "moja_strona");
//if (!$link) die("Błąd połączenia z bazą");


// ---------------------------------------------------------
// SEKCJA: FUNKCJE GENERUJĄCE FORMULARZE
// ---------------------------------------------------------

//Generacja formularza logowania do panelu CMS.
function FormularzLogowania() {
    $wynik = '
    <div class="logowanie">
     <h1 class="heading">Panel CMS:</h1>
      <div class="logowanie">
        <form method="post" name="LoginForm" enctype="multipart/form-data" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'">
         <table class="logowanie">
          <tr><td class="log4_t">[email]</td><td><input type="text" name="login_email" class="logowanie" /></td></tr>
          <tr><td class="log4_t">[haslo]</td><td><input type="password" name="login_pass" class="logowanie" /></td></tr>
          <tr><td>&nbsp;</td><td><input type="submit" name="x1_submit" class="logowanie" value="zaloguj" /></td></tr>
         </table>
        </form>
      </div>
    </div>
    ';
    return $wynik;
}

//Generacja formularza edycji istniejącej podstrony.
function EdytujPodstrone($link, $id) {
    $query = "SELECT * FROM page_list WHERE id=$id LIMIT 1";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_assoc($result);

    $wynik = '
    <div class="logowanie">
     <h1 class="heading">Edytuj podstronę:</h1>
      <form method="post" enctype="multipart/form-data" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'">
       <table class="logowanie">
        <tr><td class="log4_t">Tytuł</td><td><input type="text" name="tytul" class="logowanie" value="'.htmlspecialchars($row['page_title']).'"/></td></tr>

        <tr><td class="log4_t">Treść</td>
        <td><textarea name="tresc" rows="10" cols="60" class="logowanie">'.htmlspecialchars($row['page_content']).'</textarea></td></tr>

        <tr><td class="log4_t">Aktywna</td>
        <td><input type="checkbox" name="aktywny" '.($row['status'] ? "checked" : "").'></td></tr>

        <tr><td>&nbsp;</td><td><input type="submit" name="save_page" value="Zapisz" class="logowanie"></td></tr>
       </table>
      </form>
    </div>
    ';

    return $wynik;
}

//Generacja formularza dodawania nowej podstrony.
function DodajNowaPodstrone($link) {
    $wynik = '
    <div class="logowanie">
     <h1 class="heading">Dodaj nową podstronę:</h1>
      <form method="post" enctype="multipart/form-data" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'">
       <table class="logowanie">

        <tr><td class="log4_t">Tytuł</td><td><input type="text" name="tytul" class="logowanie"/></td></tr>

        <tr><td class="log4_t">Treść</td>
        <td><textarea name="tresc" rows="10" cols="60" class="logowanie"></textarea></td></tr>

        <tr><td class="log4_t">Aktywna</td>
        <td><input type="checkbox" name="aktywny"></td></tr>

        <tr><td>&nbsp;</td><td><input type="submit" name="add_page" class="logowanie" value="Dodaj"></td></tr>

       </table>
      </form>
    </div>
    ';
    return $wynik;
}

//Usuwanie podstrony z bazy danych.
function UsunPodstrone($link, $id) {
    $id_clear = (int)$id;

    mysqli_query($link, "DELETE FROM page_list WHERE id=$id LIMIT 1");

    $wynik = '
    <div class="logowanie">
     <h1 class="heading">Usuwanie podstrony</h1>
     <p>Podstrona została usunięta.</p>
     <a href="admin.php">Powrót</a>
    </div>
    ';
    return $wynik;
}


// ---------------------------------------------------------
// SEKCJA: LOGIKA AUTORYZACJI
// ---------------------------------------------------------

if (!isset($_SESSION['zalogowany'])) {
    if (isset($_POST['x1_submit'])) {
        // Pobranie danych z formularza
        $email = $_POST['login_email'];
        $password = $_POST['login_pass'];

        // Weryfikacja ($login i $pass z cfg.php)
        if ($email === $login && $password === $pass) {
            $_SESSION['zalogowany'] = true;
        } else {
            echo "<p style='color:red;'>Błędny login lub hasło!</p>";
        }
    }

    // Wyświetlenie formularza i zatrzymanie skryptu
    if (!isset($_SESSION['zalogowany'])) {
        echo FormularzLogowania();
        exit();
    }
}


// ---------------------------------------------------------
// SEKCJA: INTERFEJS ADMINISTRATORA (tylko dla zalogowanych)
// ---------------------------------------------------------

echo '
<div class="admin-menu">
    <a href="admin.php">[Lista podstron]</a> |
    <a href="admin.php?add=1">[Dodaj podstronę]</a> |
    <a href="admin.php?logout=1">[Wyloguj]</a>
</div>
<hr>';

// --- OBSŁUGA DODAWANIA STRONY ---
if (isset($_POST['add_page'])) {
    $tytul = mysqli_real_escape_string($link, $_POST['tytul']);
    $tresc = mysqli_real_escape_string($link, $_POST['tresc']);
    $aktywny = isset($_POST['aktywny']) ? 1 : 0;

    $sql = "INSERT INTO page_list (page_title, page_content, status) 
            VALUES ('$tytul', '$tresc', $aktywny)";
    if (mysqli_query($link, $sql)) {
        echo "<p>Podstrona dodana!</p>";
    } else {
        echo "<p>Błąd: ".mysqli_error($link)."</p>";
    }
}

// --- OBSŁUGA EDYCJI STRONY ---
if (isset($_POST['save_page']) && isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $tytul = mysqli_real_escape_string($link, $_POST['tytul']);
    $tresc = mysqli_real_escape_string($link, $_POST['tresc']);
    $aktywny = isset($_POST['aktywny']) ? 1 : 0;

    $sql = "UPDATE page_list SET page_title='$tytul', page_content='$tresc', status=$aktywny WHERE id=$id LIMIT 1";
    if (mysqli_query($link, $sql)) {
        echo "<p>Podstrona zaktualizowana!</p>";
    } else {
        echo "<p>Błąd: ".mysqli_error($link)."</p>";
    }
}


// --- ROUTING WIDOKÓW PANELU ---
if (isset($_GET['edit'])) {
    echo EdytujPodstrone($link, (int)$_GET['edit']);
} 
elseif (isset($_GET['add'])) {
    echo DodajNowaPodstrone($link);
} 
elseif (isset($_GET['delete'])) {
    echo UsunPodstrone($link, (int)$_GET['delete']);
} 
else {
    $result = mysqli_query($link, "SELECT * FROM page_list ORDER BY id ASC");
    echo '<table border="1" cellpadding="5"><tr><th>ID</th><th>Tytuł</th><th>Opcje</th></tr>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>'.htmlspecialchars($row['id']).'</td>';
        echo '<td>'.htmlspecialchars($row['page_title']).'</td>';
        echo '<td>
                <a href="admin.php?edit='.$row['id'].'">[Edytuj]</a> 
                <a href="admin.php?delete='.$row['id'].'">[Usuń]</a>
              </td>';
        echo '</tr>';
    }
    echo '</table>';
}
?>
