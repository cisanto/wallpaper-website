<?php
if(isset($_GET['tapeta']))
{
    $titleName = $pdo->prepare('SELECT nazwa FROM nasze_tapety WHERE id= :tapeta');
    $titleName->bindParam(':tapeta',$_GET['tapeta']);
    $titleName->execute();
    $name = $titleName->fetchAll();
    foreach($name as $metaName)
    {
        $title = $metaName['nazwa'];
    }
}elseif(isset($_GET['kategorie']))
{
    $titleName = $pdo->prepare('SELECT nazwa_kategorii FROM kategorie WHERE nazwa_kategorii= :kategoria');
    $titleName->bindParam(':kategoria',$_GET['kategorie']);
    $titleName->execute();
    $name = $titleName->fetchAll();
    foreach($name as $metaName)
    {
        $title = $metaName['nazwa_kategorii'];
    }
}elseif(isset($_GET['con']))
{
    $title = 'Edycja : '.$_GET['con'];
}elseif(isset($_GET['add']))
{
    $title = 'Dodawanie';
}
else{
    $title = 'Twoje Tapety';
}
?>