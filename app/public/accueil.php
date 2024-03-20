<?php

require_once '../vendor/autoload.php';

use App\Page;  // Inclut la classe Page

$page = new Page();

if(!$page->session->get('user')) {
    header('Location: index.php');
}

$user = $page->session->get('user');

if($page->session->hasrole('client')) {
    $interventions = $page->getInterventions('clients.id = '.$user['id']);
    $modif = false;
}

if($page->session->hasrole('admin')) {
    $interventions = $page->getInterventions();
    $modif = true;
}

if($page->session->hasrole('standardiste')) {
    if(isset($_GET['all'])) {
        $interventions = $page->getInterventions();
        $modif = false;
    } else {
        $interventions = $page->getInterventions("standardistes.id = ".$user['id']);
        $modif = true;
    }
}


if($page->session->hasrole('intervenant')) {
    $id_interventions = $page->getInterventionsByIntervenant($user['id']);
    $interventions = [];
    foreach ($id_interventions as $id_intervention) {
        $interventions[] = $page->getInterventions('interventions.id = '.$id_intervention['id_intervention'])[0];
    }
    // var_dump($interventions);
    $modif = false;
}


for ($i=0; $i<count($interventions); $i++) {  // AJOUT DES INTERVENANTS
    $interventions[$i]['intervenants'] = $page->getIntervenantsByIntervention($interventions[$i]['id']);
    // var_dump($interventions[$i]['intervenants']);
}
// var_dump($interventions[0]['intervenants']);


$data = ['user' => $user, 'interventions' => $interventions, 'modif' => $modif];
// var_dump($data);
echo $page->render('accueil.html.twig', $data);
