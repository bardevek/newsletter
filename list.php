<?php
session_start();

require_once 'database.php';


if(!isset($_SESSION['loggedId'])){


    if (isset($_POST['login']))
    {

        $login = filter_input(INPUT_POST, 'login');
        $password = filter_input(INPUT_POST, 'pass');


       $userQuery = $db->prepare('SELECT id,password FROM admins WHERE login = :login');
       $userQuery->bindValue(':login', $login, PDO::PARAM_STR);
       $userQuery->execute();

       $user = $userQuery->fetch();


       if($user && password_verify($password, $user['password'])){

        $_SESSION['loggedId'] = $user['id'];
        unset($_SESSION['badAttempt']);
       }
        else
        {
           $_SESSION['badAttempt'] = true;
           header('Location: admin.php');
           exit(); 
       }

    } else
    {
        header('Locatin: admin.php');
        exit();
    }
}

$usersQuery = $db->query('SELECT * FROM users');
$users = $usersQuery->fetchAll();


?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Panel administracyjny</title>
    <meta name="description" content="Używanie PDO - odczyt z bazy MySQL">
    <meta name="keywords" content="php, kurs, PDO, połączenie, MySQL">
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">

    <link rel="stylesheet" href="main.css">
    <link href="https://fonts.googleapis.com/css?family=Lobster|Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
    <!--[if lt IE 9]>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <![endif]-->
</head>

<body>

    <div class="container">

        <header>
            <h1>Newsletter</h1>
        </header>

        <main>
            <article>
                
                <table>
                    <thead>
                        <tr><th colspan="2">Sum of record: <?= $usersQuery->rowCount() ?></th></tr>
                        <tr><th>ID</th><<th>E-mail</th></tr>
                    </thead>
                    <tbody>
                        <?php 
                            foreach ($users as $user) {
                                echo "<tr><td>{$user['id']}</td><td>{$user['email']}</td></tr>";
                            }
                         ?>
                    </tbody>
                </table>
                    
                    <p><a href="logout.php">Log out</a></p>
            </article>
        </main>

    </div>

</body>
</html>