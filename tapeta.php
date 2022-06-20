<?php
session_start();
require ('Db.php');
if(isset($_GET['tapeta']))
{
    $_GET['tapeta'] = strip_tags(htmlspecialchars($_GET['tapeta']));
}
$result = $pdo->prepare('SELECT * FROM nasze_tapety WHERE id= :tapeta');
$result->bindParam(':tapeta',$_GET['tapeta']);
$result->execute();
$wallpapers = $result->fetchAll();
require ('section/meta.php');
$decsriptionTag = $pdo->prepare('SELECT opis FROM nasze_tapety WHERE id=:tapeta');
$decsriptionTag->bindParam(':tapeta',$_GET['tapeta']);
$decsriptionTag->execute();
$metaDesription = $decsriptionTag->fetchAll();
foreach($metaDesription as $metaDes)
{
    $tag = $metaDes['opis'];
}
require ('section/header.php');
echo '<form action="tapeta.php" method="get">';
echo '<input type="number" name="tapeta" style="display:none">';
echo '</form>';
if(isset($_GET['tapeta']) and is_numeric($_GET['tapeta']))
{
    foreach($wallpapers as $wall)
    {
        echo '<main class="main-wall">';
        echo '<div class="left-content"';
        echo '<a href="tapeta.php?tapeta='.$wall['id'].'"><img class="wallpapers-images" src="tapety_images/'.$wall['nazwa'].'.jpg"/></a>';
        echo '<a href="tapety_images/'.$wall['nazwa'].'.jpg" download="'.$wall['nazwa'].'">';
        echo '<button class="wallpapers-button">Pobierz</button>';
        echo '</a>';
        echo '</div>';
        echo '<div class="right-content">';
        echo '<table class="wall-table">';
        echo '<tr style="text-transform: capitalize;"><th>Kategoria:</th><th> '. $wall['kategoria'].'</th></tr>';
        echo '<tr style="text-transform: capitalize;"><th>Nazwa:</th><th> '. $wall['nazwa'].'</th></tr>';
        echo '<tr><th>Rozdzielczość:</th><th> '. $wall['szerokosc'].'px / '. $wall['wysokosc'].'px</tr>';
        echo '<tr><th>Waga:</th><th>'. $wall['waga'].'kb</th></tr>';
        echo '<tr><th>Opis:</th><th> '. $wall['opis'].'</th></tr>';
        echo '</table>';
        echo '</div>';
        echo '</main>';
    }
}
require ('section/footer.php')
?>
