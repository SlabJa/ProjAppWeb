<?php

function PokazKontakt()
{
    $wynik = '
    <div class="logowanie">
      <h1 class="heading">Odzyskiwanie hasła</h1>

      <div class="logowanie">
        <form method="post" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'">
          <table class="logowanie">
            <tr>
              <td class="log4_t">Twój e-mail:</td>
              <td><input type="email" name="email" class="logowanie" required /></td>
            </tr>

            <tr>
              <td>&nbsp;</td>
              <td>
                <input type="submit" name="send_contact" class="logowanie" value="Wyślij" />
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
    ';

    return $wynik;
}

function WyslijMailKontakt($odbiorca) {
    if(empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email']))
    {
        echo '[nie_wypelniles_pola]';
        echo PokazKontakt();
    }
    else
    {
        $mail['subject']    = $_POST['temat'];
        $mail['body']       = $_POST['tresc'];
        $mail['sender']     = $_POST['email'];
        $mail['recipient']  = $odbiorca;

        $header  = "From: Formularz kontaktowy <".$mail['sender'].">\n";
        $header .= "MIME-Version: 1.0\nContent-Type: text/plain; charset=utf-8\nContent-Transfer-Encoding: 8bit\n";
        $header .= "X-Sender: <".$mail['sender'].">\n";
        $header .= "X-Priority: 3\n";
        $header .= "Return-Path: <".$mail['sender'].">\n";

        mail($mail['recipient'], $mail['subject'], $mail['body'], $header);

        echo '[wiadomosc_wyslana]';
    }
}

function PrzypomnijHaslo() {
    include("cfg.php");

    if(empty($_POST['email']))
    {
        echo '[nie_wypelniles_pola]';
        echo PokazKontakt();
    }
    else
    {
        $mail['subject']    = "Odzyskiwanie hasła admina.";
        $mail['body']       = "Obecne hasło: ".$pass;
        $mail['sender']     = "noreply@strona.com";
        $mail['recipient']  = $_POST['email'];

        $header  = "From: Formularz kontaktowy <".$mail['sender'].">\n";
        $header .= "MIME-Version: 1.0\nContent-Type: text/plain; charset=utf-8\nContent-Transfer-Encoding: 8bit\n";
        $header .= "X-Sender: <".$mail['sender'].">\n";
        $header .= "X-Priority: 3\n";
        $header .= "Return-Path: <".$mail['sender'].">\n";

        mail($mail['recipient'], $mail['subject'], $mail['body'], $header);

        echo '[wiadomosc_wyslana]';
    }
}

if (isset($_POST['send_contact'])) {
    PrzypomnijHaslo();
} else {
    echo PokazKontakt();
}

?>
