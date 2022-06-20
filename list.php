<?php
session_start();
if(!isset($_SESSION['user']))
{
    header ('Location: index.php');
}
require ('Db.php');
require ('section/meta.php');
require ('section/header.php');
require ('lib/safeguardListXSS.php');
?>
    <p class="login-menu"><a href="list.php?con=tapeta">  &nbsp; Tapety | &nbsp; </a><a> <a href="list.php?con=kategoria"> Kategorie | &nbsp;  </a><a href="list.php?con=uzytkownicy"> Użytkownicy</a>
    <main class="listMain">
<form action="list.php" method="get">
    <input type="text" name="con" style="display:none">
</form>
<?php
if(!isset($_GET['add']))
{
    if($_GET['con'] == 'tapeta')
    {
        echo '<div class="page">';
        $resultPage = $pdo->query('SELECT * FROM nasze_tapety');
        $pageR = $resultPage->rowCount();
        $pageRo = $pageR / 20;
        $pageRow = ceil($pageRo);
        $i = 1;
        $howPage = 0;
        $skip = 20;
        echo '<form action="list.php?add=tapeta" method="get">';
        while($i <= $pageRow)
        {
            echo '<a href="list.php?con=tapeta&page='.$i.'" class="page" name="page">'.$i.'</a>';
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
        $resultWall = $pdo->prepare('SELECT id, data_dodania, nazwa, opis, kategoria FROM nasze_tapety ORDER BY id LIMIT :pages, 20');
        $resultWall->bindParam(':pages',$howPage, PDO::PARAM_INT);
        $resultWall->execute();
        $wallpapers = $resultWall->fetchAll();
        echo '<br>';
        echo '<div class="listContent">';
        echo '<form action="list.php?add=tapeta" method="get">
        <a href="list.php?add=addWall" class="addNew" name="add">Dodaj nową tapetę</a>
        </form>';
        echo '<br><br>';
        echo '<table class="table">';
        echo '<tr>';
        echo '<th class="info" style="font-weight:bold">id</th>';
        echo '<th class="info" style="font-weight:bold">data dodania</th>';
        echo '<th class="info" style="font-weight:bold">nazwa pliku</th>';
        echo '</tr>';
        echo '<br>';
        if(isset($_POST['edit']))
        {
            echo '<div class="edit-form">
            <form action="list.php?con=tapeta" method="post" enctype="multipart/form-data">
            <a>Nazwa:</a>
            <input type="text" name="name-edit" placeholder="'.$_POST['edit'].'"/>
            <a>Opis:</a>
            <input type="text" name="desc-edit" placeholder="'.$_POST['description-edit'].'"/>
            <a>Kategoria:</a>
            <select name="categoryList">';
            $categoryList = $pdo->query('SELECT nazwa_kategorii FROM kategorie');
            $selectList = $categoryList->fetchAll();
            foreach($selectList as $catList)
            {
                echo '<option value="'.$catList['nazwa_kategorii'].'">'.$catList['nazwa_kategorii'].'</option>';
            }
            echo '</select>';
            echo 'Wybierz plik w formacie jpg:';
            echo '<br>';
            echo '<input type="file" name="fileToUpload">
            <input class="btn-add" name="submit" type="submit" value="Edytuj!"/>
            </form>
            </div>
            <br>';
            setcookie('edit',$_POST['edit'], time()+3600);
            setcookie('description-edit',$_POST['description-edit'], time()+3600);
            } 
            if(isset($_POST['submit']))
            {
                if(isset($_POST['name-edit']) and !empty($_POST['name-edit']))
                {  
                    $ifName = $pdo->prepare('SELECT nazwa FROM nasze_tapety WHERE nazwa= :names_edit');
                    $ifName->bindParam(':names_edit',$_POST['name-edit']);
                    $ifName->execute();
                    $name = $ifName->fetch();
                    if($name == true)
                    {
                        echo '<a class="addNewWrong">Nazwa jest już zajęta!</a>';
                    }elseif ($name == false)
                    {
                        $edit = $_POST['name-edit'];
                        $whereEdit = $_COOKIE['edit'];
                        $edit1 = $pdo->prepare('UPDATE `nasze_tapety` SET `nazwa`= :names WHERE `nazwa`= :whereName');
                        $edit1->bindParam(':names',$edit);
                        $edit1->bindParam(':whereName',$whereEdit);
                        $edit1->execute();
                        echo '<a class="addNewWrong">Zmieniono nazwę!</a>';
                        rename ("tapety_images/".$whereEdit.".jpg","tapety_images/".$edit.".jpg");
                    }
                }
                if(isset($_POST['desc-edit']) and !empty($_POST['desc-edit']))
                {
                    $edit = $_POST['name-edit'];
                    $editDe = $_POST['desc-edit'];
                    $whereEdit = $_COOKIE['edit'];
                    $descriptionEdit = $_COOKIE['description-edit'];
                    if($editDe !== $descriptionEdit)
                    {
                        $descEdit = $pdo->prepare("UPDATE `nasze_tapety` SET `opis`= :descri WHERE `nazwa`= :whereName OR `nazwa`= :names");
                        $descEdit->bindParam(':names',$edit);
                        $descEdit->bindParam(':whereName',$whereEdit);
                        $descEdit->bindParam(':descri',$editDe);
                        $descEdit->execute();
                        echo '<a class="addNewWrong"> Zmieniono opis! </a>';
                    }else{
                        echo '<a class="addNewWrong">Podałeś taki sam opis!</a>';
                    }
                }
                if(isset($_POST['categoryList']))
                {
                    if(isset($_POST['name-edit']))
                    {
                        $edit = $_POST['name-edit'];
                    }else{
                        $edit = '';
                    }
                    $whereEdit = $_COOKIE['edit'];
                    $editCategory = $_POST['categoryList'];
                    $categoryEdit = $pdo->prepare('UPDATE `nasze_tapety` SET `kategoria`= :editCategory WHERE `nazwa`= :whereName OR `nazwa`= :edit');
                    $categoryEdit->bindParam(':editCategory',$editCategory);
                    $categoryEdit->bindParam(':whereName',$whereEdit);
                    $categoryEdit->bindParam(':edit',$edit);
                    $categoryEdit->execute();
                    echo '<a class="addNewWrong"> Zmieniono kategorię! </a>';
                }
                require ('lib/function.php');
                photoChange ();
            }   
            foreach($wallpapers as $wall)
                {
                    echo '<tr>';
                    echo '<th class="tb-up">'.$wall['id'].'</th>';
                    echo '<th class="tb-up">'.$wall['data_dodania'].'</th>';
                    echo '<th class="tb-up">'.$wall['nazwa'].'</th>';
                    echo '<th>
                    <form action="" method="post">
                    <button name="edit" value="'.$wall['nazwa'].'">Edytuj</button>
                    <input type="hidden" name="description-edit" value="'.$wall['opis'].'"/>
                    </form></th>';
                    echo '<th>
                    <form action="" method="post">
                    <button name="delete" value="'.$wall['id'].'">Usuń</button>
                    </form></th>';
                    if(isset($_POST['delete']))
                    {
                        $deleteFile = $pdo->prepare('SELECT nazwa FROM nasze_tapety WHERE id= :idDel');
                        $deleteFile->bindParam(':idDel',$_POST['delete']);
                        $deleteFile->execute();
                        $df = $deleteFile->fetchAll();
                        foreach($df as $delF){ unlink ("tapety_images/".$delF['nazwa'].".jpg");}
                        $delete = $pdo->prepare('DELETE FROM nasze_tapety WHERE id= :idDel');
                        $delete->bindParam(':idDel',$_POST['delete']);
                        $delete->execute();
                        header ('Location:list.php?con=tapeta');
                    }
                    echo '</tr>';
                }
        echo '</table>';
        echo '</div>';
    }elseif ($_GET['con'] == 'uzytkownicy')
    {
        echo '<div class="page">';
        $resultPage = $pdo->query('SELECT * FROM uzytkownicy');
        $pageR = $resultPage->rowCount();
        $pageRo = $pageR / 20;
        $pageRow = ceil($pageRo);
        $i = 1;
        $howPage = 0;
        $skip = 20;
        echo '<form action="list.php?add=uzytkownicy" method="get">';
        while($i <= $pageRow)
        {
            echo '<a href="list.php?con=uzytkownicy&page='.$i.'" class="page" name="page">'.$i.'</a>';
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
        $resultUser = $pdo->prepare('SELECT id, created_at, nazwa_uzytkownika FROM uzytkownicy ORDER BY id LIMIT :pages, 20 ');
        $resultUser->bindParam(':pages',$howPage, PDO::PARAM_INT);
        $resultUser->execute();
        $user = $resultUser->fetchAll();
        echo '<br>';
        echo '<div class="listContent">';
        echo '<form action="list.php?add=addUser" method="get">
        <a href="list.php?add=addUser" class="addNew" name="add">Dodaj nowego użytkownika</a>
        </form>';
        echo '<br><br>';
        echo '<table class="table">';
        echo '<tr>';
        echo '<th class="info" style="font-weight:bold">id</th>';
        echo '<th class="info" style="font-weight:bold">data dodania</th>';
        echo '<th class="info" style="font-weight:bold">login</th>';
        echo '</tr>';
        echo '<br>';
        if(isset($_POST['edit']))
        {
            echo '<div class="edit-form">
            <form action="list.php?con=uzytkownicy" method="post">
            <a> Nick: </a>
            <input type="text" name="name-edit" value="'.$_POST['edit'].'"/>
            <br>';
            echo '<a> Hasło: </a>
            <input type="password" name="pass-edit"/>
            <br>
            <input class="btn-add" type="submit" value="Edytuj!"/>
            </form>
            </div>
            <br>';
            setcookie('edit',$_POST['edit'], time()+3600);
        }
        if(isset($_POST['name-edit']) and !empty($_POST['name-edit']))
        {
            $ifName = $pdo->prepare('SELECT nazwa_uzytkownika FROM uzytkownicy WHERE nazwa_uzytkownika= :names_edit');
            $ifName->bindParam(':names_edit',$_POST['name-edit']);
            $ifName->execute();
            $name = $ifName->fetch();
            if($name == true)
            {
                if($_POST['pass-edit'] !== "")
                {
                    $whereEdit = $_COOKIE['edit'];
                    $passEdit = $_POST['pass-edit'];
                    $editPass = $pdo->prepare('UPDATE `uzytkownicy` SET `haslo`= :passEdit WHERE `nazwa_uzytkownika`= :names');
                    $editPass->bindParam(':passEdit',$passEdit);
                    $editPass->bindParam(':names',$whereEdit);
                    $editPass->execute();
                    header ('Location:list.php?con=uzytkownicy');
                    setcookie('edit',$_POST['edit'], time()-3600);
                }else{
                    echo '<a class="addNewWrong"> Podaj nowe dane!</a>';
                }
            }elseif ($name == false) 
            { 
                $edit = $_POST['name-edit'];
                $whereEdit = $_COOKIE['edit'];
                if($_POST['pass-edit'] !== "")
                {
                    $passEdit = $_POST['pass-edit'];
                    $edit1 = $pdo->prepare('UPDATE `uzytkownicy` SET `nazwa_uzytkownika`= :userNames, `haslo`= :passEdit WHERE `nazwa_uzytkownika`= :names');
                    $edit1->bindParam(':userNames',$edit);
                    $edit1->bindParam(':passEdit',$passEdit);
                    $edit1->bindParam(':names',$whereEdit);
                    $edit1->execute();
                }else{
                    $edit1 = $pdo->prepare('UPDATE `uzytkownicy` SET `nazwa_uzytkownika`= :userNames WHERE `nazwa_uzytkownika`= :names');
                    $edit1->bindParam(':userNames',$edit);
                    $edit1->bindParam(':names',$whereEdit);
                    $edit1->execute();
                }
                header ('Location:list.php?con=uzytkownicy');
                setcookie('edit',$_POST['edit'], time()-3600);
            }
        }
        foreach($user as $users)
        {
            echo '<tr>';
            echo '<th class="tb-up">'.$users['id'].'&nbsp;&nbsp;</th>';
            echo '<th class="tb-up">'.$users['created_at'].'&nbsp;&nbsp;</th>';
            echo '<th class="tb-up">'.$users['nazwa_uzytkownika'].'</th>';
            echo '<th>
            <form action="" method="post">
            <button name="edit" value="'.$users['nazwa_uzytkownika'].'">Edytuj</button>
            </form></th>';
            echo '<th>
            <form action="" method="post">
            <button name="delete" value="'.$users['id'].'">Usuń</button>
            </form></th>';
            if(isset($_POST['delete']))
            {
                $delete = $pdo->prepare('DELETE FROM uzytkownicy WHERE id= :userDelete');
                $delete->bindParam(':userDelete',$_POST['delete']);
                $delete->execute();
                header ('Location:list.php?con=uzytkownicy');
            }
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
    }elseif($_GET['con'] == 'kategoria')
    {
        echo '<div class="page">';
        $resultPage = $pdo->query('SELECT * FROM kategorie');
        $pageR = $resultPage->rowCount();
        $pageRo = $pageR / 20;
        $pageRow = ceil($pageRo);
        $i = 1;
        $howPage = 0;
        $skip = 20;
        echo '<form action="list.php?add=kategoria" method="get">';
        while($i <= $pageRow)
        {
            echo '<a href="list.php?con=kategoria&page='.$i.'" class="page" name="page">'.$i.'</a>';
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
        $resultCat = $pdo->prepare('SELECT id, data_dodania, nazwa_kategorii FROM kategorie ORDER BY id LIMIT :pages, 20');
        $resultCat->bindParam(':pages',$howPage, PDO::PARAM_INT);
        $resultCat->execute();
        $category = $resultCat->fetchAll();
        echo '<br>';
        echo '<div class="listContent">';
        echo '<form action="list.php?add=addCat" method="get">
        <a href="list.php?add=addCat" class="addNew" name="add">Dodaj nową kategorię</a>
        </form>';
        echo '<br><br>';
        echo '<table class="table">';
        echo '<tr>';
        echo '<th class="info" style="font-weight:bold">id</th>';
        echo '<th class="info" style="font-weight:bold">data dodania</th>';
        echo '<th class="info" style="font-weight:bold">nazwa kategorii</th>';
        echo '</tr>';
        echo '<br>';
        if(isset($_POST['edit']))
        {
            echo '<div class="edit-form">
            <form action="list.php?con=kategoria" method="post">
            <input type="text" name="name-edit" value="'.$_POST['edit'].'"/>
            <br>
            <input class="btn-add" type="submit" value="Edytuj!"/>
            </form>
            </div>
            <br>';
            setcookie('edit',$_POST['edit'], time()+3600);
        }
        if(isset($_POST['name-edit']) and !empty($_POST['name-edit']))
        {  
            $edit = $_POST['name-edit'];
            $whereEdit = $_COOKIE['edit']; 
            $ifName = $pdo->prepare('SELECT nazwa_kategorii FROM kategorie WHERE nazwa_kategorii= :names_edit');
            $ifName->bindParam(':names_edit',$edit);
            $ifName->execute();
            $name = $ifName->fetch();
            if($name == true)
            {
                echo '<a class="addNewWrong">Podaj inną nazwę</a>';
            }elseif ($name == false) 
            {
                $editCat = $pdo->prepare('UPDATE `nasze_tapety` SET `kategoria`= :names_edit WHERE `kategoria`= :editCat');
                $editCat->bindParam(':editCat',$whereEdit);
                $editCat->bindParam(':names_edit',$edit);
                $editCat->execute();
                $edit1 = $pdo->prepare('UPDATE `kategorie`SET `nazwa_kategorii`= :names_edit WHERE `nazwa_kategorii`= :editCat');
                $edit1->bindParam(':editCat',$whereEdit);
                $edit1->bindParam(':names_edit',$edit);
                $edit1->execute();
                header ('Location:list.php?con=kategoria');
                setcookie('edit',$_POST['edit'], time()-3600);
            }
        }
        foreach($category as $cat)
        {
            echo '<tr>';
            echo '<th class="tb-up">'.$cat['id'].'&nbsp;&nbsp;</th>';
            echo '<th class="tb-up">'.$cat['data_dodania'].'&nbsp;&nbsp;</th>';
            echo '<th class="tb-up" style="text-transform:capitalize" >'.$cat['nazwa_kategorii'].'</th>';
            echo '<th>
            <form action="" method="post">
            <button name="edit" value="'.$cat['nazwa_kategorii'].'">Edytuj</button>
            </form></th>';
            echo '<th>
            <form action="" method="post">
            <button name="delete" value="'.$cat['id'].'">Usuń</button>
            </form></th>';
            if(isset($_POST['delete']))
            {
                $delete = $pdo->prepare('DELETE FROM kategorie WHERE id= :del');
                $delete->bindParam(':del',$_POST['delete']);
                $delete->execute();
                header ('Location:list.php?con=kategoria');
            };
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
    }
}elseif(isset($_GET['add']) and $_GET['add'] == 'addWall')
{
    echo '<div class="addNewForm">';
    echo '<form action="" method="post" enctype="multipart/form-data">';
    echo '<div>';
    echo 'Wybierz plik w formacie jpg:';
    echo '<br>';
    echo '<input type="file" name="fileToUpload">';
    echo '<br>';
    echo '<br>';
    echo 'Kategoria: ';
    echo '<select name="categoryList">';
    $categoryList = $pdo->query('SELECT nazwa_kategorii FROM kategorie');
    $selectList = $categoryList->fetchAll();
    foreach($selectList as $catList)
    {
        echo '<option value="'.$catList['nazwa_kategorii'].'">'.$catList['nazwa_kategorii'].'</option>';
    }
    echo '</select>';
    echo '</div>';
    echo '<br>';
    echo '<div>';
    echo 'Nazwa: ';
    echo '<input type="text" name="name">';
    echo '</div>';
    echo '<br>';
    echo '<div>';
    echo 'Opis: ';
    echo '<input type="text" name="description">';
    echo '</div>';
    echo '<br>';
    echo '<input class="btn-add" type="submit" value="Stwórz" name="submit">';
    echo '</div>';
    if(isset($_POST['submit']))
    {
        require ('lib/function.php');
        addNewWallpapers();
    }
    echo '</form>';
}elseif(isset($_GET['add']) and $_GET['add'] == 'addCat')
{
    echo '<div class="addNewForm">';
    echo '<form action="" method="post">';
    echo '<div>';
    echo 'Nazwa kategorii: ';
    echo '<input type="text" name="category">';
    echo '</div>';
    echo '<br>';
    echo '<input class="btn-add" type="submit" value="Stwórz">';
    echo '</div>';
    echo '</form>';
    if(isset($_POST['category']) AND !empty($_POST['category']))
    {
        $ifName = $pdo->prepare('SELECT nazwa_kategorii FROM 
        kategorie WHERE nazwa_kategorii= :category');
        $ifName->bindParam(':category',$_POST['category']);
        $ifName->execute();
        $name = $ifName->fetch();
        if($name == true)
        {
            echo '<a class="addNewWrong">Podaj inną nazwę</a>';
        }elseif ($name == false) 
        {  
        $category = $_POST['category'];
        $addCat = $pdo->prepare('INSERT INTO kategorie VALUES (NULL, current_timestamp(), :categorys)');
        $addCat->bindParam(':categorys',$category);
        $addCat->execute();
        header ('Location:list.php?con=kategoria');
        }
    }elseif(isset($_POST['category']) OR $_POST['category'] = "")
    {
        echo '<br>';
        echo '<a class="addNew" style="color:red">Uzupełnij dane!</a>';
    }
}elseif(isset($_GET['add']) and $_GET['add'] == 'addUser')
{
    echo '<div class="addNewForm">';
    echo '<form action="" method="post">';
    echo '<div>';
    echo 'E-mail: ';
    echo '<input type="text" name="email">';
    echo '</div>';
    echo '<br>';
    echo '<div>';
    echo 'Login: ';
    echo '<input type="text" name="login">';
    echo '</div>';
    echo '<br>';
    echo '<div>';
    echo 'Hasło: ';
    echo '<input type="password" name="password">';
    echo '</div>';
    echo '<br>';
    echo '<input class="btn-add" type="submit" value="Stwórz">';
    echo '</div>';
    echo '</form>';
    if(isset($_POST['email']) and isset($_POST['login']) and isset($_POST['password']) and !empty($_POST['email']) and !empty($_POST['login']) and !empty($_POST['password']))
    {
        $ifName = $pdo->prepare('SELECT nazwa_uzytkownika FROM uzytkownicy WHERE nazwa_uzytkownika= :logi');
        $ifName->bindParam(':logi',$_POST['login']);
        $ifName->execute();
        $name = $ifName->fetch();
        if($name == true)
        {
            echo '<a class="addNewWrong">Login jest zajęty!
            <br>Podaj inne dane!</a>';
        }elseif ($name == false) 
        {  
            $email = $_POST['email'];
            $login = $_POST['login'];
            $password = $_POST['password'];
            $checkEmail = '/^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9\-.]+\.[a-zA-Z]{2,4}$/';
            if(preg_match($checkEmail, $email))
            {
                $addUser = $pdo->prepare('INSERT INTO uzytkownicy VALUES (NULL,:logi , :email, :pass, current_timestamp())');
                $addUser->bindParam(':logi',$login);
                $addUser->bindParam(':email',$email);
                $addUser->bindParam(':pass',$password);
                $addUser->execute();
                header ('Location:list.php?con=uzytkownicy');
            }else
            {
                echo '<a class="addNewWrong">Błędny format adresu E-mail</a>';
            }
        }
    }elseif(isset($_POST['email']) OR isset($_POST['login']) OR isset($_POST['password']) OR $_POST['email'] = "" OR $_POST['login'] = "" OR $_POST['password'] = "")
    {
        echo '<br>';
        echo '<a class="addNew" style="color:red">Uzupełnij dane!</a>';
    }
}
echo '</main>';
require ('section/footer.php');
?> 