<?php
include_once "./lib/mainLib.php";
startSessionIfNot();

if (!isLoggedIn()) {
    header('Location: login-bs.php');
    exit();
}

$incomeCorrect = true;

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}


if (isset($_POST['amount']))
{
    if (!is_numeric($_POST['amount']))
    {
        echo "Błędna kwota"."<br />";
        $incomeCorrect = false;
    }
    else
    {
        $amount = $_POST['amount'];
        echo $amount. "<br />";
    }
} else {
    echo "Brak kwoty" . "<br />";
    $incomeCorrect = false;
}

if (isset($_POST['date']))
{
    if (!validateDate($_POST['date']))
    {
        echo "Błędna data" . "<br />";
        $incomeCorrect = false;
    }
    else
    {
        $date = $_POST['date'];
        echo $date. "<br />";
    }
} else {
    echo "Brak daty" . "<br />";
    $incomeCorrect = false;
}

if (isset($_POST['category']))
{
    if (gettype($_POST['category']) == 'string')
    {
        settype($_POST['category'], "integer");
    }
    if (!is_int($_POST['category'])) {
        echo "Błędna kategoria" . "<br />";
        $incomeCorrect = false;
    }
    else
    {
        $category = $_POST['category'];
        echo $category. "<br />";
    }
}
else
{
    echo "Brak kategorii" . "<br />";
    $incomeCorrect = false;
}

if (isset($_POST['comment']))
{
    if (!ctype_alnum($_POST['comment']) && $_POST['comment'] != null) {
        $incomeCorrect = false;
        echo "Komentarz musi składać się tylko z liter a-z, A-Z lub cyfr 0-9 !";
    }
    else
    {
        $comment = $_POST['comment'];
        echo $comment. "<br />";
    }
}
else
{
    $comment = null;
}

require_once "connectDB.php";
mysqli_report(MYSQLI_REPORT_STRICT);
$userId = $_SESSION['userId'];

if ($incomeCorrect)
{
    try
    {
        $personalBudgetDB = new mysqli($host, $db_user, $db_password, $db_name);
        if ($personalBudgetDB->connect_errno != 0)
        {
            throw new Exception(mysqli_connect_errno());
        }
        else
        {
            if ($personalBudgetDB->query("INSERT INTO incomes VALUES (NULL, '$userId' ,'$category','$amount','$date','$comment')"))
            {
                echo "Dodano !";
                $_SESSION['newIncomeAdded'] = true;
                header('Location: addIncome-bs.php');
                exit();

            }
            else
            {
                echo "Nie dodano";
            }
            $personalBudgetDB->close();
        }

    }
    catch (Exception $e)
    {
        echo 'Błąd serwera!';
    }
}
else
{
    echo "Nie dodano";
}

header('Location: addIncome-bs.php');
