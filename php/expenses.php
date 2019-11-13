<?php
require_once "../mySqlDb/connectDB.php";
require_once "../mySqlDb/queries.php";
include_once "../lib/mainLib.php";

startSessionIfNot();

if (!isLoggedIn()) {
    header('Location: ../templates/login-bs.php');
    exit();
}

if (!isset($_SESSION["startDate"]) || !isset($_SESSION["endDate"])) {
    $_SESSION["startDate"] = strtotime(date("Y-m") . "-01");
    $_SESSION["endDate"] = strtotime("+1 month, -1 day", $_SESSION["startDate"]);
}

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
    $id = $_SESSION['userId'];
    $startDate = date("Y-m-d",  $_SESSION["startDate"]);
    $endDate = date("Y-m-d", $_SESSION["endDate"]);

    $personaBudgetDB->set_charset("utf8");
    $sql = getExpenseQuery($id, $startDate, $endDate);

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
