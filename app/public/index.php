<?php

    require_once '../vendor/autoload.php';

    use App\Page;  // Inclut la classe Page

    $page = new Page();

    if($page->session->get('user')) {
        header('Location: accueil.php');
    }
    $msg = false;

    if (isset($_POST['send'])) {
        // var_dump($_POST);  // var_dump : affiche les détails d'une variable pour le débogage (type, longueur)
        $user = $page->getUserByEmail([
            'email' => $_POST['email']
        ]);
        // var_dump($user);
        
        if (!$user) {  // Vérification email
            $msg = "Email ou mot de passe incorrect !";
        } else {  // Vérification mot de passe
            if (!password_verify($_POST['password'], $user['password'])) {
                $msg = "Email ou mot de passe incorrect !";
            } else {
                $page->session->add('user', $user);
                header('Location: accueil.php');
                // var_dump($page->session->get('user'));
            }
        }
    }


    echo $page->render('index.html', ['msg' => $msg]);