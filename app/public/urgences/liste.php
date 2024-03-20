<?php

require_once '../../vendor/autoload.php';

use App\Page;  // Inclut la classe Page

$page = new Page();

if(!$page->session->get('user')) {
    header('Location: index.php');
}
$user=$page->session->get('user');

if(!$page->session->hasrole('admin')) {
    header('location: index.php');
}
$urgences = $page->getUrgences();

$data = ['user' => $user, 'urgences' => $urgences];
// var_dump($data);
echo $page->render('urgences/liste.html', $data);
