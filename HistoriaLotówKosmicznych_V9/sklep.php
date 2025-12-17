<?php
//Funkcje do zarządzania kategoriami sklepu (wersja proceduralna).

//Dodawanie nowej kategorii.
function DodajKategorie($link) {
    echo '
    <div class="logowanie">
        <h3 class="heading">Dodaj Kategorię</h3>
        <form method="post" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'">
            <table class="logowanie">
                <tr>
                    <td class="log4_t">Matka (0 = Główna):</td>
                    <td><input type="number" name="matka" value="0" class="logowanie" /></td>
                </tr>
                <tr>
                    <td class="log4_t">Nazwa:</td>
                    <td><input type="text" name="nazwa" class="logowanie" required /></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="dodaj_kat" value="Dodaj" class="logowanie" /></td>
                </tr>
            </table>
        </form>
    </div><hr>';

    // Obsługa wysłania formularza
    if (isset($_POST['dodaj_kat'])) {
        $matka = (int)$_POST['matka'];
        $nazwa = mysqli_real_escape_string($link, $_POST['nazwa']);

        $query = "INSERT INTO kategorie (matka, nazwa) VALUES ('$matka', '$nazwa')";
        
        if (mysqli_query($link, $query)) {
            echo '<p class="success">Kategoria dodana pomyślnie!</p>';
            // Odświeżenie, aby zapobiec ponownemu wysłaniu formularza
            echo '<meta http-equiv="refresh" content="0">'; 
        } else {
            echo '<p class="error">Błąd: ' . mysqli_error($link) . '</p>';
        }
    }
}

//Usuwanie kategorii po id.
function UsunKategorie($link) {
    if (isset($_GET['usun_kat'])) {
        $id = (int)$_GET['usun_kat'];

        $query = "DELETE FROM kategorie WHERE id = '$id' LIMIT 1";
        
        if (mysqli_query($link, $query)) {
            echo '<p class="success">Kategoria usunięta.</p>';
        } else {
            echo '<p class="error">Błąd usuwania.</p>';
        }
    }
}

//Edycja kategorii.
function EdytujKategorie($link) {
    if (isset($_GET['edytuj_kat'])) {
        $id = (int)$_GET['edytuj_kat'];
        
        $result = mysqli_query($link, "SELECT * FROM kategorie WHERE id='$id' LIMIT 1");
        $row = mysqli_fetch_assoc($result);

        echo '
        <div class="logowanie">
            <h3 class="heading">Edytuj Kategorię ID: '.$id.'</h3>
            <form method="post" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'">
                <table class="logowanie">
                    <tr>
                        <td class="log4_t">Nazwa:</td>
                        <td><input type="text" name="nazwa_edit" value="'.htmlspecialchars($row['nazwa']).'" class="logowanie" /></td>
                    </tr>
                    <tr>
                        <td class="log4_t">Matka:</td>
                        <td><input type="number" name="matka_edit" value="'.$row['matka'].'" class="logowanie" /></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><input type="submit" name="zapisz_kat" value="Zapisz Zmiany" class="logowanie" /></td>
                    </tr>
                </table>
            </form>
        </div><hr>';

        // Obsługa zapisu zmian
        if (isset($_POST['zapisz_kat'])) {
            $nazwa = mysqli_real_escape_string($link, $_POST['nazwa_edit']);
            $matka = (int)$_POST['matka_edit'];

            $update_query = "UPDATE kategorie SET nazwa='$nazwa', matka='$matka' WHERE id='$id' LIMIT 1";
            
            if (mysqli_query($link, $update_query)) {
                echo '<p class="success">Zaktualizowano kategorię!</p>';
                // Powrót do widoku listy
                echo '<a href="admin.php?pokaz_sklep=1">Wróć do listy</a>';
            } else {
                echo '<p class="error">Błąd aktualizacji.</p>';
            }
        }
    }
}

//Wyświetlaniw drzewa kategorii przez pętle zagnieżdżone.
function PokazKategorie($link) {
    echo '<h3 class="heading">Drzewo Kategorii</h3>';
    
    // Pętla 1: Matki (matka == 0)
    $query_parents = "SELECT * FROM kategorie WHERE matka = 0 ORDER BY id ASC";
    $result_parents = mysqli_query($link, $query_parents);

    if (!$result_parents) return;

    echo '<ul style="list-style-type:none; padding-left:0;">';

    while ($parent = mysqli_fetch_assoc($result_parents)) {
        $parent_id = $parent['id'];
        
        echo '<li>';
        echo '<b>' . htmlspecialchars($parent['nazwa']) . '</b> (ID: ' . $parent_id . ') ';
        echo ' <a href="admin.php?edytuj_kat='.$parent_id.'">[Edytuj]</a>';
        echo ' <a href="admin.php?usun_kat='.$parent_id.'" onclick="return confirm(\'Czy usunąć?\')">[Usuń]</a>';
        
        // Pętla 2: Dzieci (matka == ID rodzica)
        $query_children = "SELECT * FROM kategorie WHERE matka = '$parent_id' ORDER BY id ASC";
        $result_children = mysqli_query($link, $query_children);

        if (mysqli_num_rows($result_children) > 0) {
            echo '<ul style="padding-left: 20px; color: #555;">';
            while ($child = mysqli_fetch_assoc($result_children)) {
                echo '<li>';
                echo '&#8627; ' . htmlspecialchars($child['nazwa']) . ' (ID: ' . $child['id'] . ')';
                echo ' <a href="admin.php?edytuj_kat='.$child['id'].'" style="font-size:0.8em">[Edytuj]</a>';
                echo ' <a href="admin.php?usun_kat='.$child['id'].'" style="font-size:0.8em" onclick="return confirm(\'Czy usunąć?\')">[Usuń]</a>';
                echo '</li>';
            }
            echo '</ul>';
        }
        
        echo '</li><hr style="border:0; border-top:1px dashed #ccc; margin:5px 0;">';
    }
    echo '</ul>';
}
?>