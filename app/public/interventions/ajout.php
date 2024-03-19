<?php
require_once '../../vendor/autoload.php';

use App\Page;  // Inclut la classe Page

$page = new Page();

if(!$page->session->isConnected()) {
    header('Location: index.php');
}

$user = $page->session->get('user');

// if(!isset($_GET['id']))
//     header('location: ../accueil.php');

// $id = $_GET['id'];

if($user['role'] !='admin' and $user['role'] != 'standardiste') {
    header('location: ../index.php');
} elseif(!isset($_POST['titre'])) {
    $data = [
        'user' => $user,
        'clients' => $page->getUsers('role', 'client'),
        'intervenants' => $page->getUsers('role', 'intervenant')
    ];
    echo $page->render('interventions/ajout.html', $data);
} else {
    $data = [
        'id_standardiste' => $user['id'],
        'id_client' => (int)$_POST['idClient'],
        'titre' => $_POST['titre'],
        'suivi' => $_POST['suivi'],
        'urgence' => (int)$_POST['urgence'],
        'debut' => $_POST['debut'],
    ];
    $data['fin'] = ($_POST['fin'] != '')? $_POST['fin'] : '0000-01-01';
    // var_dump($data);
    $page->insertIntervention($data);
    header('location: ../accueil.php');
}