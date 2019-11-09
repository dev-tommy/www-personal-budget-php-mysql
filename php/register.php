<?php
session_start();

if (isset($_POST['nickName']) && isset($_POST['email']) && isset($_POST['password'])  && isset($_POST['confirmPassword']))
{
    $registerDataCorrect = true;

    $nick = $_POST['nickName'];
    if ((strlen($nick)<6) || (strlen($nick)>32))
    {
        $registerDataCorrect = false;
        $_SESSION['e_nick']="Nick musi posiadać od 6 do 32 znaków!";
    }

    if (!ctype_alnum($nick))
    {
        $registerDataCorrect = false;
        $_SESSION['e_nick'] = "Nick musi składać się tylko z liter a-z, A-Z lub cyfr 0-9 !";
    }

    $email = $_POST['email'];

    $sanitizedEmail = filter_var($email,FILTER_SANITIZE_EMAIL);

    if ((!filter_var($sanitizedEmail, FILTER_VALIDATE_EMAIL)) || ($sanitizedEmail!=$email))
    {
        $registerDataCorrect = false;
        $_SESSION['e_email'] = "E-mail zawiera niedozwolone znaki";
    }

    $pwd =  $_POST['password'];
    $confirmPwd =  $_POST['confirmPassword'];

    if ((strlen($pwd) < 8) || (strlen($pwd) > 64))
    {
        $registerDataCorrect = false;
        $_SESSION['e_pwd'] = "Hasło musi posiadać od 8 do 64 znaków!";
    }

    if ($pwd!=$confirmPwd)
    {
        $registerDataCorrect = false;
        $_SESSION['e_pwd'] = "Wprowadzone hasła muszą być identyczne!";
    }

    $hashPwd = password_hash($pwd, PASSWORD_DEFAULT);


}

header('Location: login-bs.php');
