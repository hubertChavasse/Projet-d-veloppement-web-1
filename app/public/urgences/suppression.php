<?php

require_once '../../vendor/autoload.php';

use App\Page;  // Inclut la classe Page

$page = new Page();

if(!$page->session->isConnected())
    header('location: ../index.php');

$user = $page->session->get('user');

if(!isset($_GET['id']))
    header('location: ../accueil.php');

if($page->session->hasRole('admin')) {
    $autorisation = true;
} elseif($page->session->hasRole('standardiste')) {
    $autorisation = false;
    $interventionsStandardiste = $page->getInterventions("standardistes.id = ".$user['id']);
    foreach($interventionsStandardiste as $intervention) {
        if($intervention['id'] == $_GET['id'])
            $autorisation = true;
    }    
} else {
    $autorisation = false;
}

if(!$autorisation)
    header('location: ../index.php');

if(!isset($_POST['confirmation'])) {  // confirmation
    // var_dump($page->getInterventions("interventions.id = $id")[0]);
    echo $page->render('interventions/suppression.html', [
        'user' => $page->session->get('user'),
        'intervention' => $page->getInterventions("interventions.id = ".$_GET['id'])[0]
            // il faut spécifier 'interventions.id' parce que c'est une requête join
    ]);
} else {  // suppression
    $page->delete('interventions', $id);
    header('location: ../accueil.php');
}

// var_dump ("DELETE FROM " . $table_name . "WHERE id = " . $id);