<?php
function addNewWallpapers()
{
    require ('Db.php');
    $max_rozmiar = 1024*1024;
    if(isset($_POST['submit']))
    {
        if(isset($_FILES['fileToUpload']) and isset($_POST['name']) and isset($_POST['description']) and !empty($_POST['name']) and !empty($_POST['description']) and !empty($_FILES['fileToUpload']['name']) and isset($_POST['categoryList']))
        {
            if (is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {
            if ($_FILES['fileToUpload']['size'] > $max_rozmiar)
            {
                echo 'Błąd! Plik jest za duży!';
            }
            else {
                $ifName = $pdo->prepare('SELECT nazwa FROM 
                nasze_tapety WHERE nazwa= :names');
                $ifName->bindParam(':names',$_POST['name']);
                $ifName->execute();
                $name = $ifName->fetch();
                if($name == true)
                {
                    echo '<a class="addNewWrong">Podaj inną nazwę</a>';
                }elseif ($name == false)
                {
                    $height = $_FILES['fileToUpload']['tmp_name'];
                    $sizeHeight = getimagesize ($height);
                    move_uploaded_file($_FILES['fileToUpload']['tmp_name'],
                    $_SERVER['DOCUMENT_ROOT'].'/projekt_droptica/tapety_images/'.$_POST['name'].'.jpg');
                    $size = ($_FILES['fileToUpload']['size'] /1000);
                    $newWallpapers = $pdo->prepare("INSERT INTO nasze_tapety VALUES (NULL, :names, :size1, :size2,  :size, :descriptions, :categoryList,current_timestamp())");
                    $newWallpapers->bindParam(':names',$_POST['name']);
                    $newWallpapers->bindParam(':size1',$sizeHeight[1]);
                    $newWallpapers->bindParam(':size2',$sizeHeight[0]);
                    $newWallpapers->bindParam(':size',$size);
                    $newWallpapers->bindParam(':descriptions',$_POST['description']);
                    $newWallpapers->bindParam(':categoryList',$_POST['categoryList']);
                    $newWallpapers->execute();
                    header ('Location:list.php?con=tapeta');
                    echo 'Odebrano plik. Początkowa nazwa: '.$_FILES['fileToUpload']['name']=$_POST['name'].'.jpg';
                    echo '<br/>';
                    if (isset($_FILES['fileToUpload']['type'])) 
                    {
                    echo 'Typ: '.$_FILES['fileToUpload']['type'].'<br/>';            
                    }
                }
            }
            }
        }elseif(empty($_POST['name']) or empty($_POST['description']))
        {
            echo 'uzupełnij wszystkie dane!';
        }
    }
};

function photoChange ()
{
    require ('Db.php');
    if(isset($_FILES['fileToUpload']) and !empty($_FILES['fileToUpload']['name']))
    {
        $edit = $_POST['name-edit'];
        $whereEdit = $_COOKIE['edit'];
        $height = $_FILES['fileToUpload']['tmp_name'];
        $sizeHeight = getimagesize ($height);
        if(empty($_POST['name-edit']))
        {
            move_uploaded_file($_FILES['fileToUpload']['tmp_name'],
            $_SERVER['DOCUMENT_ROOT'].'/projekt_droptica/tapety_images/'.$whereEdit.'.jpg');
            $size = ($_FILES['fileToUpload']['size'] /1000);
            $newWallpapers = $pdo->prepare('UPDATE nasze_tapety SET `wysokosc`= :size1, `szerokosc`= :size2, `waga`= :size WHERE `nazwa`= :wheres');
            $newWallpapers->bindParam(':size1',$sizeHeight[1]);
            $newWallpapers->bindParam(':size2',$sizeHeight[0]);
            $newWallpapers->bindParam(':size',$size);
            $newWallpapers->bindParam(':wheres',$whereEdit);
            $newWallpapers->execute();
            echo '<a class="addNewWrong">zmieniono zdjęcie!</a>';
        }elseif(!empty($_POST['name-edit']))
        {
            move_uploaded_file($_FILES['fileToUpload']['tmp_name'],
            $_SERVER['DOCUMENT_ROOT'].'/projekt_droptica/tapety_images/'.$edit.'.jpg');
            $size = ($_FILES['fileToUpload']['size'] /1000);
            $newWallpapers = $pdo->prepare('UPDATE `nasze_tapety` SET `wysokosc`= :size1, `szerokosc`= :size2, `waga`= :size WHERE `nazwa`= :wheres ');
            $newWallpapers->bindParam(':size1',$sizeHeight[1]);
            $newWallpapers->bindParam(':size2',$sizeHeight[0]);
            $newWallpapers->bindParam(':size',$size);
            $newWallpapers->bindParam(':wheres',$whereEdit);
            $newWallpapers->execute();
            echo '<a class="addNewWrong">zmieniono zdjęcie!</a>';
        }
    }
}
?>

