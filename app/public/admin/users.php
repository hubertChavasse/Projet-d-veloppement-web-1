<?php

    require_once '../../vendor/autoload.php';

    use App\Page;

    $page = new Page();

    if(!$page->session->get('user')) {
        header('Location: index.php');
    }

    $user = $page->session->get('user');

    $users = $page->getAllUsers();

    echo $page->render('admin/users/list.html.twig', ['user' => $user, 'users' => $users]);
