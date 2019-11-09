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



header('Location: addIncome-bs.php');
