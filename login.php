<?php
    session_start();
    require ('Db.php');
    require ('section/meta.php');
    require ('section/header.php');
    if(isset($_POST['login']))
    {
        $_POST['login'] = strip_tags(htmlspecialchars($_POST['login']));
    }
    if(isset($_POST['pass']))
    {
        $_POST['pass'] = strip_tags(htmlspecialchars($_POST['pass']));
    }
    if(isset($_POST['login']) and isset($_POST['pass']) and !empty($_POST['login']) and !empty($_POST['pass']))
    {
        $login = $_POST['login'];
        $pass = $_POST['pass'];
        $log_system = $pdo->prepare('SELECT * FROM uzytkownicy WHERE nazwa_uzytkownika= :logi AND haslo= :pass');
        $log_system->bindParam(':logi',$login);
        $log_system->bindParam(':pass',$pass);
        $log_system->execute();
        $log = $log_system->fetch();
        if($log==false)
        {
            echo '<p class="addNewWrong">Niepoprawne dane!</p>';
        }
        elseif($_POST['login']==$log['nazwa_uzytkownika'] and $_POST['pass']==$log['haslo'])
        {
            header ('Location: indexLogin.php');
            $_SESSION['user'] = $_POST['login'];
        }
        elseif($_POST['login']!==$log['nazwa_uzytkownika'] OR $_POST['pass']!==$log['haslo'])
        {
            echo '<p class="login-text">Niepoprawne dane!</p>';
        }
    }
    elseif(isset($_POST['login']) OR isset($_POST['pass']) OR !empty($_POST['login']) OR !empty($_POST['pass']))
    {
        echo '<p class="login-text">Wprowadź dane do logowania!</p>';
    }
    elseif(isset($_SESSION['user']))
    {
        header ('Location: indexLogin.php');
    }
?>
<table class="login-table">
        <tr>
            <td>
                <p>LOGOWANIE</p>
                <form action="login.php" method="post">
                    <p>Login: <input type="text" name="login"></p>
                    <p>Hasło: <input type="password" name="pass"></p>
                    <input class="log-btn" type="submit" value="Zaloguj">
                </form>
            </td>
        </tr>
    </table>
<?php require ('section/footer.php'); ?>
