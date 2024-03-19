<?php

    require_once '../vendor/autoload.php';

    use App\Page;  // Inclut la classe Page
    
    $page = new Page();
    if (isset($_POST['send'])) {
        $page->insert('users', [
            'email'     => $_POST['email'],
            'password'  => password_hash($_POST['mdp'], PASSWORD_DEFAULT)
        ]);

        header('Location: index.php');
    }

    echo $page->render('register.html', []);