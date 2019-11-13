<?php
function startSessionIfNot()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

function isLoggedIn()
{
    if (isset($_SESSION['logged']) && ($_SESSION['logged'] == true))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function showFooter()
{
    echo '<footer class="text-center fixed-bottom mb-0 p-1 text-white bg-custom">';
    echo ' © 2019-';
    echo date("Y").": Tomasz Frydrychowicz";
    echo '</footer>';
}

function setCorrectDates($get)
{
    $months = array('styczeń', 'luty', 'marzec', 'kwiecień', 'maj', 'czerwiec', 'lipiec', 'sierpień', 'wrzesień', 'październik', 'listopad', 'grudzień');

    if (isset($get['periodBalance'])) {
        switch ($get['periodBalance']) {
            case 'currentMonth':
                $startDate = strtotime(date("Y-m") . "-01");
                $endDate = $endDate = strtotime("+1 month, -1 day", $startDate);
                $currentMonthName =  $months[date("m")-1];
                $msg = 'Bilans z bieżącego miesiąca ['. $currentMonthName.']:';
            break;
            case 'previousMonth':
                $startDate = strtotime(date("Y-m") . "-01");
                $startDate = strtotime("-1 month", $startDate);
                $endDate = strtotime("+1 month, -1 day", $startDate);
                $previousMonthName =  $months[date("m",$startDate)-1];
                $msg = 'Bilans z poprzedniego miesiąca ['. $previousMonthName.']:';
            break;
            case 'currentYear':
                $startDate = strtotime(date("Y") . "-01-01");
                $endDate = strtotime(date("Y-m-d"));
                $msg = 'Bilans z bieżącego roku ['. date("Y").']:';
            break;
        }
    }
    else
    {
        if (isset($get['startDate']) && isset($get['endDate']))
        {
            $startDate = strtotime($get["startDate"]);
            if ($startDate > strtotime(date("Y-m-d"))) {
                $startDate = strtotime(date("Y-m-d"));
                echo "Data początkowa była późniejsza od dzisiejszej!";
                return;
            }

            $endDate = strtotime($_GET["endDate"]);
            if ($endDate > strtotime(date("Y-m-d"))) {
                $endDate = strtotime(date("Y-m-d"));
                echo "Data początkowa była późniejsza od dzisiejszej!";
            }

            if ($startDate > $endDate) {
                $startDate = $endDate;
            }

            $msg = 'Bilans za okres:<br /> od ' . date("Y-m-d", $startDate) . ' do ' . date("Y-m-d", $endDate);
        }
        else
        {
            $startDate = strtotime(date("Y-m") . "-01");
            $endDate = strtotime("+1 month, -1 day", $startDate);
            $currentMonthName =  $months[date("m") - 1];
            $msg = 'Bilans z bieżącego miesiąca [' . $currentMonthName . ']:';
        }
    }

    $_SESSION["startDate"] =  $startDate;
    $_SESSION["endDate"] =  $endDate;
    $_SESSION["periodBalanceMsg"] = $msg;
}

function rand_color()
{
    return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
}

?>