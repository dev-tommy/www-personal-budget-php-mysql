<?php
require_once "../conf/connectDB.php";
include_once "../lib/mainLib.php";
startSessionIfNot();

if (!isLoggedIn()) {
    header('Location: ../templates/login-bs.php');
    exit();
}

$expenseCorrect = true;

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
        $expenseCorrect = false;
    }
    else
    {
        $amount = $_POST['amount'];
        echo $amount. "<br />";
    }
} else {
    echo "Brak kwoty" . "<br />";
    $expenseCorrect = false;
}

if (isset($_POST['date']))
{
    if (!validateDate($_POST['date']))
    {
        echo "Błędna data" . "<br />";
        $expenseCorrect = false;
    }
    else
    {
        $date = $_POST['date'];
        echo $date. "<br />";
    }
} else {
    echo "Brak daty" . "<br />";
    $expenseCorrect = false;
}

if (isset($_POST['payment'])) {
    if (gettype($_POST['payment']) == 'string') {
        settype($_POST['payment'], "integer");
    }
    if (!is_int($_POST['payment'])) {
        echo "Błędna płatność" . "<br />";
        $expenseCorrect = false;
    } else {
        $payment = $_POST['payment'];
        echo $payment . "<br />";
    }
} else {
    echo "Brak rodzaju płatności" . "<br />";
    $expenseCorrect = false;
}

if (isset($_POST['category']))
{
    if (gettype($_POST['category']) == 'string')
    {
        settype($_POST['category'], "integer");
    }
    if (!is_int($_POST['category'])) {
        echo "Błędna kategoria" . "<br />";
        $expenseCorrect = false;
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
    $expenseCorrect = false;
}

if (isset($_POST['comment']))
{
    if (!ctype_alnum($_POST['comment']) && $_POST['comment'] != null) {
        $expenseCorrect = false;
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

mysqli_report(MYSQLI_REPORT_STRICT);
$userId = $_SESSION['userId'];

if ($expenseCorrect)
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
            if ($personalBudgetDB->query("INSERT INTO expenses VALUES (NULL, '$userId' ,'$category','$payment','$amount','$date','$comment')"))
            {
                echo "Dodano !";
                $_SESSION['newExpenseAdded'] = true;
                header('Location: ../templates/addExpense-bs.php');
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

header('Location: ../templates/addExpense-bs.php');

?>