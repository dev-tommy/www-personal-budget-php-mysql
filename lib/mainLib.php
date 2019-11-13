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

function showFooter()
{
    echo '<footer class="text-center fixed-bottom mb-0 p-1 text-white bg-custom">';
    echo ' © 2019-';
    echo date("Y").": Tomasz Frydrychowicz";
    echo '</footer>';
}


?>