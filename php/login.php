<?php
include "./lib/mainLib.php";
startSessionIfNot();

if (!isLoggedIn) {
    header('Location: login-bs.php');
    exit();
}
