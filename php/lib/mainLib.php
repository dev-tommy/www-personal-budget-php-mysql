<?php
function startSessionIfNot()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

function isLoggedIn()
{
    if (isset($_SESSION['logged']) && ($_SESSION['logged'] == true))
    {
        return true;
    }
    else
    {
        return false;
    }
}


?>