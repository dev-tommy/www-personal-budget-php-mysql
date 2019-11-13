<?php
require_once "../conf/connectDB.php";
include_once "../lib/mainLib.php";

startSessionIfNot();

if (!isLoggedIn()) {
    header('Location: ../templates/login-bs.php');
    exit();
}

function rand_color()
{
    return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
}

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
            break;
        case "currentYear":
            $startDate = strtotime(date("Y")."-01-01");
            $endDate = strtotime(date("Y-m-d"));
            break;
    }
}

$_SESSION["startdate"] = date("Y-m-d", $startDate);
$_SESSION["enddate"] = date("Y-m-d", $endDate);
$_SESSION["totalExpensesAmount"] = 0.00;

$_SESSION['chartElements']='';
$_SESSION['chartColors']='';

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
    /* change character set to utf8 */
    $personaBudgetDB->set_charset("utf8");

    $id = $_SESSION['userId'];
    $startDate = date("Y-m-d", $startDate);
    $endDate = date("Y-m-d", $endDate);

    $sql = "
        SELECT
            e_userid.name AS 'Category',
            SUM(e.amount) AS 'Sum of amounts'
        FROM
            expenses AS e,
            expenses_category_assigned_to_userid_$id AS e_userid
        WHERE
            e_userid.id = e.expense_category_assigned_to_user_id AND
            e.date_of_expense >= '$startDate' AND
            e.date_of_expense <= '$endDate' AND
            e.user_id = '$id'
        GROUP BY e.expense_category_assigned_to_user_id
        ";


    if ($result = @$personaBudgetDB->query( $sql ) )
    {
        if ($result->num_rows > 0)
        {
            $chartElements=array();
            $chartColors= array();
            while ($row = $result->fetch_assoc())
            {
                echo '
                <tr>
                    <th scope="row" class="text-left">'.$row["Category"].'</th>
                    <td class="text-right">'.$row["Sum of amounts"].'</td>
                </tr>
                ';

                $_SESSION["totalExpensesAmount"] += $row["Sum of amounts"];

                $chartElements[$row["Category"]]= $row["Sum of amounts"];
            }

            foreach ($chartElements as $key => $value)
            {
                $percentage = $value / $_SESSION["totalExpensesAmount"];
                $_SESSION['chartElements'] .= "'" . $key . "  ".round($percentage * 100)."%' : " . $percentage . ",\n\t\t\t\t";

                $_SESSION['chartColors'] .= "'" . $key . "  " . round($percentage * 100) . "%' : " . "'".rand_color()."'" . ",\n\t\t\t\t";
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
