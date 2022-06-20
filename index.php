<?php
session_start();
require ('Db.php');
$result = $pdo->query('SELECT nazwa, id FROM nasze_tapety ORDER BY id DESC LIMIT 8');
$wallpapers = $result->fetchAll();
$result_big =$pdo->query('SELECT nazwa, id, szerokosc FROM nasze_tapety ORDER BY szerokosc DESC LIMIT 8');
$wallpapersBig = $result_big->fetchAll();
require ('section/meta.php');
require ('section/header.php');
echo '<main class="home-main">';
echo '<section class="home-section">';
echo '<p> Najnowsze tapety</p>';
echo '<div class="home-new">';
foreach($wallpapers as $wall)
{
    echo '<a href="tapeta.php?tapeta='.$wall['id'].'"><img class="home-images" src="tapety_images/'.$wall['nazwa'].'.jpg"/></a>';
}
echo '</div>';
echo '</section>';
echo '<section class="home-section">';
echo '<p> Tapety o największej rozdzielczości</p>';
echo '<div class="home-new">';
foreach($wallpapersBig as $wallBig)
{
    echo '<a href="tapeta.php?tapeta='.$wallBig['id'].'"><img class="home-images" src="tapety_images/'.$wallBig['nazwa'].'.jpg"/></a>';
}
echo '</div>';
echo '</section>';
echo '</main>';
require ('section/footer.php')
?>