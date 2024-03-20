<?php

    require_once '../../vendor/autoload.php';

    use App\Page;

    $page = new Page();

    if(!$page->session->get('user')) {
        header('Location: index.php');
    }

    $user = $page->session->get('user');

    $users = $page->getAllUsers();

    echo $page->render('users/list.html.twig', ['user' => $user, 'users' => $users]);
    // echo $page->render('admin/users/list.html.twig', ['user' => $user, 'users' => $users]);
    //                     ^
    // c'est le 'admin/' qui a fait l'erreur durant l'exposé