<?php
session_start();
require ('Db.php');
$title = 'Menu Administracyjne';
require ('section/header.php');
echo '<p class="login-menu"><a href="list.php?con=tapeta">  &nbsp; Tapety | &nbsp; </a><a> <a href="list.php?con=kategoria"> Kategorie | &nbsp;  </a><a href="list.php?con=uzytkownicy"> Użytkownicy</a></p>';
if(isset($_SESSION['user']))
{
    echo '<p class="login-menu">Witaj '.$_SESSION['user'].' </p>';
}elseif(!isset($_SESSION['user']))
{
    header ('Location: index.php');
}
require ('section/footer.php')
?> 