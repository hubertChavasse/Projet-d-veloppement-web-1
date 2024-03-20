<?php
require_once '../../vendor/autoload.php';

use App\Page;  // Inclut la classe Page

$page = new Page();

if(!$page->session->isConnected()) {
    header('Location: ../index.php');
}

$user = $page->session->get('user');

if(!$_GET['id'])
    var_dump("pas d'id");
    // header('location: ../accueil.php');

if(!$page->session->hasRole('admin')) {
    header('Location: ../index.php');
}

if (isset($_POST['nom']) or !isset($_POST['niveau'])) {
    $data = ['user' => $user,
        'urgence' => $page->getUrgences('id',$_GET['id'])[0]
    ];
    // var_dump($data);
    echo $page->render('urgences/modification.html', $data);

} else {  // REQUÊTE UPDATE
    $data = [
        'id' => (int)$_GET['id'],
        'nom' => $_POST['nom'],
        'niveau' => (int)$_POST['niveau']
    ];
    // var_dump($data);
    $urgences = getUrgences();
    foreach($urgence as $urgences) {
        if($_POST['niveau'] == $urgence) {
            var_dump("id déjà présent");
        }
    }
    $page->updateUrgences($data);
}