<?php
session_start();
require ('Db.php');
require ('section/meta.php');
require ('section/header.php');
?>
<form action="category.php" method="get">
    <input type="text" name="kategorie" style="display:none" >
</form>
<main class="categories-main listMain">
<?php
if(isset($_GET['kategorie']))
{
    $_GET['kategorie'] = strip_tags(htmlspecialchars($_GET['kategorie']));
}
if(isset($_GET['page']))
{
    $_GET['page'] = strip_tags(htmlspecialchars($_GET['page']));
}
echo '<div class="page">';
$resultPage = $pdo->prepare('SELECT * FROM nasze_tapety WHERE kategoria= :kategorie');
$resultPage->bindParam(':kategorie',$_GET['kategorie']);
$resultPage->execute();
$pageR = $resultPage->rowCount();
$pageRo = $pageR / 20;
$pageRow = ceil($pageRo);
$i = 1;
$howPage = 0;
$skip = 20;
echo '<form action="category.php?kategorie='.$cat['nazwa_kategorii'].'" method="get">';
while($i <= $pageRow)
{
    echo '<a href="category.php?kategorie='.$_GET['kategorie'].'&page='.$i.'" class="page" name="page">'.$i.'</a>';
    $i++;
}
if(isset($_GET['page']))
{   
    if($_GET['page'] > 1)
    {
        $howPage = ($_GET['page'] - 1) * $skip ;   
    }
}
echo '</div>';
echo '</form>';
$result = $pdo->prepare('SELECT * FROM nasze_tapety WHERE kategoria = :kategorie ORDER BY id LIMIT :pages ,20');
$result->bindParam(':kategorie',$_GET['kategorie']);
$result->bindParam(':pages', $howPage,  PDO::PARAM_INT);
$result->execute();
$wallpapers = $result->fetchAll();
echo '<section class="categories-section">';
echo '<p style="text-transform:capitalize"> Kategoria:  '.$_GET['kategorie'].'</p>';
echo '<div class="categories-new">';
if(isset($_GET['kategorie']))
{
    foreach($wallpapers as $wall){
        echo '<a href="tapeta.php?tapeta='.$wall['id'].'"><img class="home-images" src="tapety_images/'.$wall['nazwa'].'.jpg"/></a>';
    }
}
echo '</div>';
echo '</section>';
echo '</main>';
require ('section/footer.php');
?> 
