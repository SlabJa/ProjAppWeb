<?
    $nr_indeksu = '1234567';
    $nrGrupy = 'X';
    echo 'Jan Kowalski '.$nr_indeksu.' grupa '.$nrGrupy.' <br /><br />';
    echo 'Zastosowanie metody include() <br />';


    echo 'Metoda include(), require_once()'
    echo "A $color $fruit"; // A

    include 'vars.php';

    echo "A $color $fruit"; // A green apple


    echo 'Warunki if, else, elseif, switch'
    if ($a > $b) {
        echo "a is bigger than b";
        $b = $a;
    }


    echo 'Pętla while() i for() '
    /* example 1 */

    $i = 1;
    while ($i <= 10) {
        echo $i++;  /* the printed value would be
                    $i before the increment
                    (post-increment) */
    }

    /* example 2 */

    $i = 1;
    while ($i <= 10):
        echo $i;
        $i++;
    endwhile;


    echo 'Typy zmiennych $_GET, $_POST, $_SESSION'
    echo 'Hello ' . htmlspecialchars($_GET["name"]) . '!';
?>
