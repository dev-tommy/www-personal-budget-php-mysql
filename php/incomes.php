<?php
include_once "./lib/mainLib.php";
startSessionIfNot();

if (!isLoggedIn()) {
    header('Location: login-bs.php');
    exit();
}

$msg1 = "Bilans z okresu:";

if (!isset($_GET["startDate"]))
{
    $startDate = strtotime(date("Y-m")."-01");
    $msg1 = "Bilans z bieżącego miesiąca";
}
else
{
    $startDate = strtotime($_GET["startDate"]);
    if ($startDate > strtotime(date("Y-m-d")))
    {
        $startDate = strtotime(date("Y-m-d"));
        echo "Data początkowa była późniejsza od dzisiejszej!";
    }
}

if (!isset($_GET["endDate"])) {
    $endDate = strtotime(date("Y-m-d"));
} else {
    $endDate = strtotime($_GET["endDate"]);
    if ($endDate > strtotime(date("Y-m-d")))
    {
        $endDate = strtotime(date("Y-m-d"));
        echo "Data początkowa była późniejsza od dzisiejszej!";
    }
}

if ($startDate > $endDate)
{
    $startDate = $endDate;
}

if (isset($_GET["periodBalance"]))
{
    $period = $_GET["periodBalance"];
    switch ($period)
    {
        case "previousMonth":
            $startDate = strtotime(date("Y-m")."-01");
            $startDate = strtotime("-1 month", $startDate);
            $endDate = strtotime("+1 month, -1 day", $startDate);
            $msg1 = "Bilans z poprzedniego miesiąca";
            break;
        case "currentYear":
            $startDate = strtotime(date("Y")."-01-01");
            $endDate = strtotime(date("Y-m-d"));
            $msg1 = "Bilans z bieżącego roku";
            break;
    }
}

$_SESSION["msg"] = $msg1;
$_SESSION["startdate"] = date("Y-m-d", $startDate);
$_SESSION["enddate"] = date("Y-m-d", $endDate);

?>
