<?php
include_once "./lib/mainLib.php";
startSessionIfNot();

if (!isLoggedIn()) {
    header('Location: login-bs.php');
    exit();
}

if (!isset($_SESSION['periodBalance'])) {
    $_SESSION['periodBalance'] = 'currentMonth';
}

?>

<!doctype html>
<html lang="pl">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Budżet osobisty - Dodaj wydatek</title>
    <meta name="description" content="Zarządzaj swoim budżetem domowym">
    <meta name="keywords" content="budżet, osobisty, domowy, oszczędzanie, bezpieczeństwo finansowe">
    <meta name="author" content="Tomasz Frydrychowicz">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link href="https://fonts.googleapis.com/css?family=Lobster%7CPoppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="../css/main.css">
    <script src="../js/script.js"></script>
    <script src="../js/chartjs.js"></script>
</head>

<body onload="calculateBalance(); drawChart(); setDate('fromDateBalance', 'toDateBalance', 'currentMonth');">
    <header>
        <nav class="navbar navbar-dark bg-custom navbar-expand-xl fixed-top">
            <a class="navbar-brand" href="../index.php"><img src="../img/logo.png" width="36" height="36" class="d-inline-block mr-1 align-bottom" alt="">
                <span class="h3 text-warning">Budżet osobisty </span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainmenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse pt-2" id="mainmenu">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link h4" href="addIncome-bs.php"> Dodaj przychód </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link h4" href="addExpense-bs.php"> Dodaj wydatek </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active h4" href="#"> Przeglądaj bilans </a>
                    </li>
                </ul>

                <form class="form-inline" action="logout.php">
                    <div class="btn-group">
                        <button class="btn btn-primary btn-sm px-2 my-1" data-toggle="modal" data-target="#periodSelectionWindow" type="button">
                            <i class="material-icons">calendar_today</i>
                        </button>
                        <button class="btn btn-warning btn-sm pl-0 my-1" data-toggle="modal" data-target="#periodSelectionWindow" type="button">
                            <span class="text-button"> Bilans za.. </span>
                        </button>
                    </div>

                    <div class="btn-group mx-2">
                        <button class="btn btn-danger btn-sm px-2 my-1" type="submit">
                            <i class="material-icons">exit_to_app</i>
                        </button>
                        <button class="btn btn-warning btn-sm pl-0 my-1" type="submit">
                            <span class="text-button"> Wyloguj się </span>
                        </button>
                    </div>
                </form>
            </div>
        </nav>
    </header>

    <main>
        <div class="container move-under-navbar">
            <div class="row">
                <div class="col-md-10 offset-md-1 text-center ">
                    <div id="periodBalanceCaption" class="card shadow p-2 sentence-period font-italic font-weight-light">
                        Bilans z bieżącego miesiąca
                    </div>
                </div>
            </div>
            <div class="row my-4">
                <div class="col-md-6 col-12 text-center">
                    <div class="card shadow-lg mb-5 bg-white rounded">
                        <div class="card-header bg-info card-topic text-center"> Przychody </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead class="bg-success text-light">
                                    <tr>
                                        <th scope="col" class="text-left">Kategoria</th>
                                        <th scope="col" class="text-right">Kwota [zł]</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include "incomes.php";
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-success">
                                        <th scope="row" class="text-left">Razem:</th>
                                        <td id="sum-of-incomes" class="text-right font-weight-bold text-warning bg-dark h4">
                                            <?php echo $_SESSION["totalIncomesAmount"] ?>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12 text-center">
                    <div class="card shadow-lg mb-5 bg-white rounded">
                        <div class="card-header bg-info card-topic text-center"> Wydatki </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead class="bg-danger text-light">
                                    <tr>
                                        <th scope="col" class="text-left">Kategoria</th>
                                        <th scope="col" class="text-right">Kwota [zł]</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include "expenses.php";
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-success">
                                        <th scope="row" class="text-left">Razem:</th>
                                        <td id="sum-of-expenses" class="text-right font-weight-bold text-warning bg-dark h4">
                                            <?php echo $_SESSION["totalExpensesAmount"] ?>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <div>
                        <h3 id="balance"> Twój bilans: </h3>
                        <div class="h5" id="balanceComment"> </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <canvas width="800" height="700" id="chartpie">
                        Sorry, canvas not supported
                    </canvas>
                </div>

            </div>
        </div>
    </main>

    <?php showFooter() ?>

    <script>
        function drawChart() {
            var elements = {
                <?php echo $_SESSION['chartElements'];
                $_SESSION['chartElements'] = ''; ?>
            };

            var colors = {
                <?php echo $_SESSION['chartColors'];
                $_SESSION['chartColors'] = ''; ?>
            };

            var canvas = document.getElementById('chartpie');
            var chart = chartJS.PieChart(elements, colors, canvas);
            chart.draw();
        }
    </script>





    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <div class="modal fade" id="periodSelectionWindow" tabindex="0" role="dialog">
        <div class="modal-dialog  modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="periodSelectionWindowTitle">Wybierz przediał czasu:</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="text-danger">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-primary btn-lg btn-block" data-dismiss="modal" onclick="setDate('fromDateBalance', 'toDateBalance', 'currentMonth')">Bieżący miesiąc</button>
                    <button type="button" class="btn btn-primary btn-lg btn-block" data-dismiss="modal" onclick="setDate('fromDateBalance', 'toDateBalance', 'previousMonth')">Poprzedni
                        miesiąc</button>
                    <button type="button" class="btn btn-primary btn-lg btn-block" data-dismiss="modal" onclick="setDate('fromDateBalance', 'toDateBalance', 'currentYear')">Bieżący rok</button>
                    <div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text my-2">Od: </div>
                            </div>
                            <input type="date" id="fromDateBalance" class="form-control my-2" onclick="enableButton()" name="date" min="2000-01-01" required>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text ">Do: </div>
                            </div>
                            <input type="date" id="toDateBalance" class="form-control" onclick="enableButton()" name="date" min="2000-01-01" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Anuluj</button>
                    <button type="button" class="btn btn-success" data-dismiss="modal" onclick="showBalance('fromDateBalance', 'toDateBalance')">Ustaw</button>
                </div>
            </div>
        </div>
    </div>
</body>



</html>