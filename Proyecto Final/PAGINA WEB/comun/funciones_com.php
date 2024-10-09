<?php 
//como poner_checked pero para variables de sesiones
function s_poner_checked($c,$v)
{
    if (isset($_SESSION[$c]) && $_SESSION[$c] == $v) {
        echo 'checked';
    }
}

//como poner_selected pero para variables de sesiones
function s_poner_selected($c,$v)
{
    if (isset($_SESSION[$c]) && $_SESSION[$c] == $v) {
        echo 'selected';
    }
}
 ?>