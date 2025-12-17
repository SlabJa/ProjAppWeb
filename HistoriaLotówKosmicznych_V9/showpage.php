<?php
//Funkcja pobiera treść podstrony na podstawie jej ID.
function pokazpodstrone($id)
{
    global $link;

    $id_clear = (int)$id;

    $query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($link, $query);

    // Sprawdzenie czy zapytanie zwróciło wynik
    if (!$result) {
        return '[blad_zapytania]'; 
    }

    $row = mysqli_fetch_array($result);

    // Sprawdzenie czy strona istnieje
    if(empty($row['id']))
    {
        $web = '[nie_znaleziono_strony]';
    }
    else
    {
        $web = $row['page_content'];
    }

    return $web;
}
?>