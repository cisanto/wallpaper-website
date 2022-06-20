<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if(isset($tag))
    {
     echo '<meta name="description" content="'.$tag.'">';
    }?>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title><?php echo htmlspecialchars($title);?></title>
</head>
<body>
    <header class="header">
        <div class="header-info">
            <a href="index.php"><img src="images/logo.png" alt="logo"/></a>
            <?php
                if(!isset($_SESSION['user']))
                {
                    echo '<a href="login.php"><button class="login-button" id="login-button-id">Zaloguj</button></a>';
                }elseif(isset($_SESSION['user']))
                {
                    echo '<div class="menu">';
                    echo '<a href="logout.php"><button class="login-button" id="login-button-id">Wyloguj</button></a>';
                    echo '<a href="indexLogin.php"><button class="login-button" id="login-button-id">Menu</button></a>';
                    echo '</div>';
                }
        echo '</div>';
        $resultCategory = $pdo->query('SELECT nazwa_kategorii FROM kategorie');
        $category = $resultCategory->fetchAll();
        echo '<div class="category">';
        echo '<ul class="category-ul">';
        foreach($category as $cat)
        {
            echo '<li class="category-li"><a href="category.php?kategorie='.$cat['nazwa_kategorii'].'" style="text-transform:capitalize">'.$cat['nazwa_kategorii']. '</a></li>';
        }
        echo '</ul>';
        echo '</div>';
        ?>
    </header>

