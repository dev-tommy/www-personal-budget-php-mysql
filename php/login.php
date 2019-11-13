<?php
include_once "../lib/mainLib.php";
startSessionIfNot();

if (isLoggedIn()) {
    header('Location: "../templates/addIncome-bs.php"');
    exit();
}

require_once "../mySqlDb/connectDB.php";

$personaBudgetDB = @new mysqli($host, $db_user, $db_password, $db_name);

if($personaBudgetDB->connect_errno != 0)
{
    echo "Error: ".$personaBudgetDB->connect_errno;
}
else
{
    $login = $_POST['login'];
    $login = htmlentities($login, ENT_QUOTES, "UTF-8");

    $pwd = $_POST['password'];

    if($sqlResult = @$personaBudgetDB->query(
        sprintf("SELECT * FROM users WHERE username='%s'",
        mysqli_real_escape_string($personaBudgetDB,$login)
        )))
    {
        if ($sqlResult->num_rows>0)
        {
            $row = $sqlResult->fetch_assoc(); //przenies do tablicy asocjacyjnej (nazwy kolumn z bazy jako indeksy)
            $sqlResult->close();
            if (password_verify($pwd,$row['password']))
            {
                $_SESSION['logged']=true;
                $_SESSION['userId'] = $row['id'];
                $_SESSION['user'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                unset($_SESSION['incorrectLogin']);
                header('Location: ../templates/addIncome-bs.php');
            }
            else
            {
                $_SESSION['incorrectLogin'] = true;
                header('Location: login-bs.php');
            }
        }
        else
        {
            $_SESSION['incorrectLogin']=true;
            header('Location: login-bs.php');
        }
    }

$personaBudgetDB->close();
}

?>