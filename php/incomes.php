<?php
require_once "connectDB.php";
include_once "./lib/mainLib.php";

startSessionIfNot();

if (!isLoggedIn()) {
    header('Location: login-bs.php');
    exit();
}

$_SESSION["totalIncomesAmount"] = 0.00;

if (!isset($_GET["startDate"]))
{
    $startDate = strtotime(date("Y-m")."-01");
}
else
{
    $startDate = strtotime($_GET["startDate"]);
    if ($startDate > strtotime(date("Y-m-d")))
    {
        $startDate = strtotime(date("Y-m-d"));
        echo "Data początkowa była późniejsza od dzisiejszej!";
        return;

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

if (isset($_SESSION['periodBalance']))
{
    switch ($_SESSION['periodBalance'])
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

$_SESSION["startdate"] = date("Y-m-d", $startDate);
$_SESSION["enddate"] = date("Y-m-d", $endDate);
$_SESSION["totalIncomesAmount"] = 0.00;

$personaBudgetDB = @new mysqli($host, $db_user, $db_password, $db_name);

if ($personaBudgetDB->connect_errno != 0)
{
    if ($personaBudgetDB->connect_errno == 2002) {
        echo "<span class='text-danger p-3'>Chwilowy brak dostępu do danych!<br />:(<br /></span>";
    } else {
        echo "Error: " . $personaBudgetDB->connect_errno;
    }
}
else
{
    $personaBudgetDB->set_charset("utf8");

    $id = $_SESSION['userId'];
    $startDate = date("Y-m-d", $startDate);
    $endDate = date("Y-m-d", $endDate);

    $sql = "
        SELECT
            i_userid.name AS 'Category',
            SUM(i.amount) AS 'Sum of amounts'
        FROM
            incomes AS i,
            incomes_category_assigned_to_userid_$id AS i_userid
        WHERE
            i_userid.id = i.income_category_assigned_to_user_id AND
            i.date_of_income >= '$startDate' AND
            i.date_of_income <= '$endDate' AND
            i.user_id = '$id'
        GROUP BY i.income_category_assigned_to_user_id
        ";


    if ($result = @$personaBudgetDB->query( $sql ) )
    {
        if ($result->num_rows > 0)
        {
            while ($row = $result->fetch_assoc())
            {
                echo '
                <tr>
                    <th scope="row" class="text-left">'.$row["Category"].'</th>
                    <td class="text-right">'.$row["Sum of amounts"].'</td>
                </tr>
                ';

                $_SESSION["totalIncomesAmount"] += $row["Sum of amounts"];
            }
            $result->close();
       }
        else
        {
            //echo "No results <br />";
        }
    }
    else
    {
        echo "SQL error";
    }

    $personaBudgetDB->close();
}
