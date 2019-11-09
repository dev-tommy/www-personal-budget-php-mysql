<?php
include "./lib/mainLib.php";
startSessionIfNot();

if (!isLoggedIn()) {
    header('Location: login-bs.php');
    exit();
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

    <link href="https://fonts.googleapis.com/css?family=Lobster:400" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="../css/main.css">
    <script src="../js/script.js"></script>
</head>

<body onload="setActualDate();">
    <header>
        <nav class="navbar navbar-dark bg-custom navbar-expand-lg fixed-top">
            <a class="navbar-brand" href="../index.php"><img src="../img/logo.png" width="36" height="36" class="d-inline-block mr-1 align-bottom" alt="">
                <span class="h3 text-warning">Budżet osobisty </span>
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainmenu">

                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse pt-2" id="mainmenu">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link active h4" href="#"> Dodaj przychód </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link h4" href="addExpense-bs.php"> Dodaj wydatek </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link h4" href="viewBalance-bs.php"> Przeglądaj bilans </a>
                    </li>
                </ul>
                <form class="form-inline" action="logout.php">
                    <div class="btn-group">
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
                <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                    <?php if (!isset($_SESSION['newIncomeAdded']) || ($_SESSION['newIncomeAdded']) == false) : ?>
                        <form action="addIncome.php" method="POST">
                            <div class="card shadow-lg mb-5 bg-white rounded">
                                <div class="card-header bg-info card-topic text-center"> Dodaj przychód </div>
                                <div class="card-body">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="material-icons">attach_money</i>
                                            </span>
                                        </div>
                                        <input name="amount" type="number" class="form-control" placeholder="Kwota" step="0.01" required>
                                    </div>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="material-icons">calendar_today</i>
                                            </span>
                                        </div>
                                        <input name="date" type="date" id="defaultToday" class="form-control" min="2000-01-01" required>
                                    </div>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="material-icons">shop</i>
                                            </span>
                                        </div>
                                        <select name="category" class="form-control" required>
                                            <option disabled value="" selected>Wybierz kategorię</option>
                                            <option value=1>Wynagrodzenie</option>
                                            <option value=2>Odsetki bankowe</option>
                                            <option value=3>Sprzedaż Allegro</option>
                                            <option value=4>Inne</option>
                                        </select>
                                    </div>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="material-icons">comment</i>
                                            </span>
                                        </div>
                                        <textarea class="form-control " name="comment" rows=4 cols=45 maxlength=180 placeholder="Komentarz (opcjonalnie):"></textarea>
                                    </div>
                                </div>

                                <div class="card-footer bg-info text-white">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <div class="btn-group mr-5">
                                                <button class="btn btn-success btn-sm px-2" type="submit">
                                                    <i class="material-icons">add_circle</i>
                                                </button>
                                                <button class="btn btn-warning btn-sm pl-0 text-button" type="submit">
                                                    Dodaj
                                                </button>
                                            </div>
                                            <div class="btn-group ml-5">
                                                <button class="btn btn-danger btn-sm px-2" type="reset">
                                                    <i class="material-icons">cancel</i>
                                                </button>
                                                <button class="btn btn-warning btn-sm pl-0 text-button" type="reset">
                                                    Wyczyść
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                    <?php else : ?>
                        <div class="card shadow-lg mb-5 bg-white rounded">
                            <div class="card-header bg-info card-topic text-center"> Gratulacje !!! </div>
                            <div class="card-body text-center">
                                <i class="material-icons btn btn-success btn-sm px-2 my-2">done</i>
                                <h4>Przychód dodano poprawnie.</h4>
                            </div>

                            <div class="card-footer bg-info text-white ">
                                <form action="addIncome-bs.php">
                                    <div class="col-md-12 text-center">
                                        <div class="btn-group">
                                            <button class="btn btn-success btn-sm px-2" type="submit">
                                                <i class="material-icons">add_circle</i>
                                            </button>
                                            <button class="btn btn-warning btn-sm pl-0 text-button" type="submit">
                                                Ok
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php
                    endif;
                    unset($_SESSION['newIncomeAdded']);
                    ?>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-center fixed-bottom mb-0 p-1 text-white bg-custom">
        © 2019 Copyright: Tomasz Frydrychowicz
    </footer>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>