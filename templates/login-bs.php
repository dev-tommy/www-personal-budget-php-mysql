<?php
include "../lib/mainLib.php";
startSessionIfNot();

if (isLoggedIn()) {
    header('Location: addIncome-bs.php');
    exit();
}
?>


<!doctype html>
<html lang="pl">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Budżet osobisty - Rejestracja</title>
    <meta name="description" content="Zarządzaj swoim budżetem domowym">
    <meta name="keywords" content="budżet, osobisty, domowy, oszczędzanie, bezpieczeństwo finansowe">
    <meta name="author" content="Tomasz Frydrychowicz">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link href="https://fonts.googleapis.com/css?family=Lobster:400" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="../css/main.css">
</head>


<body>
    <header>
        <nav class="navbar navbar-dark bg-custom navbar-expand-lg fixed-top">
            <a class="navbar-brand" href="../index.php"><img src="../img/logo.png" width="36" height="36" class="d-inline-block mr-1 align-bottom" alt="">
                <span class="h3 text-warning">Budżet osobisty </span>
            </a>
            <?php
            if (isset($_SESSION['incorrectLogin'])) {
                echo '<button style="border-width:3px; border-color: rgba(228, 11, 31, 0.94);}" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Przełącznik nawigacji">';
            } else {
                echo '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Przełącznik nawigacji">';
            }
            ?>

            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainmenu">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link d-none" href="#"> Dodaj przychód </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link  d-none" href="#"> Dodaj wydatek </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link  d-none" href="#"> Przeglądaj bilans </a>
                    </li>
                </ul>

                <form class="form-inline" action="../php/login.php" method="POST">
                    <?php
                    if (isset($_SESSION['incorrectLogin'])) {
                        echo '<input style="border-width:3px;" class="form-control border-danger mr-1 my-1" name="login" type="text" placeholder="błędny login" required>';
                        echo '<input style="border-width:3px;" class="form-control border-danger mr-1 my-1" name="password" type="password" placeholder="lub błędne hasło" minlength="8" maxlength="64" required>';
                    } else {
                        echo '<input class="form-control mr-1 my-1" name="login" type="text" placeholder="Nick lub email" required>';
                        echo '<input class="form-control mr-1 my-1" name="password" type="password" placeholder="Hasło" minlength="8" maxlength="64" required>';
                    }
                    ?>
                    <div class="btn-group">
                        <button class="btn btn-success btn-sm px-2 my-1" type="submit">
                            <i class="material-icons">person</i>
                        </button>
                        <button class="btn btn-warning btn-sm pl-0 my-1" type="submit">
                            <span class="text-button"> Zaloguj się </span>
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
                    <?php if (!isset($_SESSION['newUserRegistered']) || ($_SESSION['newUserRegistered']) == false) : ?>

                        <form action="../php/register.php" method="POST">
                            <div class="card shadow-lg mb-5 bg-white rounded">
                                <div class="card-header bg-info card-topic text-center"> Rejestracja </div>
                                <div class="card-body">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="material-icons">account_box</i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" name="nickName" placeholder="Nazwa użytkownika" minlength="6" maxlength="50" required>
                                    </div>
                                    <?php
                                        if (isset($_SESSION['e_nick'])) {
                                            echo '<p class="text-danger text-center">' . $_SESSION['e_nick'] . '</p>';
                                            unset($_SESSION['e_nick']);
                                        }
                                        ?>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="material-icons">email</i>
                                            </span>
                                        </div>
                                        <input type="email" class="form-control" name="email" placeholder="Adres email" minlength="6" maxlength="50" required>
                                    </div>
                                    <?php
                                        if (isset($_SESSION['e_email'])) {
                                            echo '<p class="text-danger text-center">' . $_SESSION['e_email'] . '</p>';
                                            unset($_SESSION['e_email']);
                                        }
                                        ?>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="material-icons">lock</i>
                                            </span>
                                        </div>
                                        <input type="password" class="form-control" name="password" placeholder="Hasło" minlength="8" maxlength="64" required>
                                    </div>
                                    <?php
                                        if (isset($_SESSION['e_pwd'])) {
                                            echo '<p class="text-danger text-center">' . $_SESSION['e_pwd'] . '</p>';
                                            unset($_SESSION['e_pwd']);
                                        }
                                        ?>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="material-icons">lock</i>
                                            </span>
                                        </div>
                                        <input type="password" class="form-control" name="confirmPassword" placeholder="Potwierdź hasło" minlength="8" maxlength="64" required>
                                    </div>
                                    <?php
                                        if (isset($_SESSION['e_confirmPwd'])) {
                                            echo '<p class="text-danger text-center">' . $_SESSION['e_confirmPwd'] . '</p>';
                                            unset($_SESSION['e_confirmPwd']);
                                        }
                                        ?>
                                </div>

                                <div class="card-footer bg-info text-white ">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <div class="btn-group">
                                                <button class="btn btn-success btn-sm px-2" type="submit">
                                                    <i class="material-icons">person_add</i>
                                                </button>
                                                <button class="btn btn-warning btn-sm pl-0 text-button" type="submit">
                                                    Zarejestruj się
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
                                <h4>Rejestracja przebiegła poprawnie.<br /> Teraz możesz się zalogować.</h4>
                            </div>

                            <div class="card-footer bg-info text-white "> </div>
                        </div>
                    <?php
                    endif;
                    unset($_SESSION['newUserRegistered']);
                    ?>
                </div>
            </div>
        </div>
    </main>

    <?php showFooter() ?>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>