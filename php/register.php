<?php
require_once "../mySqlDb/connectDB.php";
include_once "../lib/mainLib.php";

startSessionIfNot();

if (isLoggedIn()) {
    header('Location: "../templates/addIncome-bs.php"');
    exit();
}

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


    mysqli_report(MYSQLI_REPORT_STRICT);

    if ($registerDataCorrect) {
        try {
            $personalBudgetDB = new mysqli($host, $db_user, $db_password, $db_name);
            if ($personalBudgetDB->connect_errno != 0) {
                throw new Exception(mysqli_connect_errno());
            } else {

                $result = $personalBudgetDB->query("SELECT id FROM users WHERE email='$sanitizedEmail'");
                if (!$result) throw new Exception($personalBudgetDB->error);

                $emailExist = $result->num_rows != 0;
                if ($emailExist) {
                    $registerDataCorrect = false;
                    $_SESSION['e_email'] = "E-mail jest już przyspisany do innego konta!";
                }

                $result = $personalBudgetDB->query("SELECT id FROM users WHERE username='$nick'");
                if (!$result) throw new Exception($personalBudgetDB->error);

                $nickExist = $result->num_rows != 0;
                if ($nickExist) {
                    $registerDataCorrect = false;
                    $_SESSION['e_nick'] = "Nick jest już przyspisany do innego konta!";
                }

                if ($registerDataCorrect) {
                    if ($personalBudgetDB->query("INSERT INTO users VALUES (NULL, '$nick','$hashPwd','$sanitizedEmail')")) {
                        $_SESSION['newUserRegistered'] = true;

                        $result = $personalBudgetDB->query("SELECT id FROM users WHERE username='$nick'");
                        if (!$result) throw new Exception($personalBudgetDB->error);

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $newUserId = $row['id'];

                            $personalBudgetDB->query("CREATE TABLE incomes_category_assigned_to_userid_$newUserId LIKE incomes_category_default");
                            $personalBudgetDB->query("INSERT INTO incomes_category_assigned_to_userid_$newUserId SELECT * FROM incomes_category_default");

                            $personalBudgetDB->query("CREATE TABLE expenses_category_assigned_to_userid_$newUserId LIKE expenses_category_default");
                            $personalBudgetDB->query("INSERT INTO expenses_category_assigned_to_userid_$newUserId SELECT * FROM expenses_category_default");

                            $personalBudgetDB->query("CREATE TABLE payment_methods_assigned_to_userid_$newUserId LIKE payment_methods_default");
                            $personalBudgetDB->query("INSERT INTO payment_methods_assigned_to_userid_$newUserId SELECT * FROM payment_methods_default");
                        } else {
                            echo "Błąd odczytu id nowego użytkownika";
                            exit();
                        }
                    } else {
                        echo "Błąd dodawania użytkownika";
                        exit();
                    }
                }
                $personalBudgetDB->close();
            }
        } catch (Exception $e) {
            echo 'Błąd serwera!';
            exit();
        }
    }

}

header('Location: ../templates/login-bs.php');
