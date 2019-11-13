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

function getPeriodBalanceMsg($get)
{
    if (isset($get['periodBalance'])) {
        $_SESSION['periodBalance'] = $get['periodBalance'];
        switch ($_SESSION['periodBalance']) {
            case 'currentMonth':
                $msg = "Bilans z bieżącego miesiąca:";
                break;
            case 'previousMonth':
                $msg = "Bilans z poprzedniego miesiąca:";
                break;
            case 'currentYear':
                $msg = "Bilans z bieżącego roku:";
                break;
        }
    } else {
        $_SESSION['periodBalance'] = 'currentMonth';
    }

    if (isset($get['startDate']) && isset($get['endDate'])) {
        $msg = 'Bilans za okres:<br /> od ' . $get['startDate'] . ' do ' . $get['endDate'];
    }

    if (!isset($msg)) {
        $msg = 'Bilans z bieżącego miesiąca:';
    }

    return $msg;
}

?>