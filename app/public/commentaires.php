<?php

require_once '../vendor/autoload.php';

use App\Page;  // Inclut la classe Page

$page = new Page();

if(!$page->session->get('user')) {
    header('Location: index.php');
}

$user = $page->session->get('user');

if(!isset($_GET['id']))
    header('location: ../accueil.php');

if($page->session->hasRole('admin')) {
    $autorisation = true;

} else {
    if($page->session->hasRole('standardiste'))
        $interventionsAutorisees = $page->getInterventions("standardistes.id = ".$user['id']);
        
    if($page->session->hasRole('client'))
        $interventionsAutorisees = $page->getInterventions("clients.id = ".$user['id']);

    if($page->session->hasRole('intervenant'))
        $interventionsAutorisees = $page->getInterventionsByIntervenant($user['id']);
    if(!$page->session->hasRole('admin')){
        foreach($interventionsAutorisees as $intervention) {
            if($intervention['id_intervention'] == $_GET['id'])
                break;
            header("location: accueil.php");
        }
    }
}

if(isset($_POST['nouveauCommentaire']) and $_POST['nouveauCommentaire'] != ''){
    $page->insertCommentaire([
        'id_intervention' => (int)$_GET['id'],
        'id_user' => $page->session->get('user')['id'],
        'texte_comm' => $_POST['nouveauCommentaire']
    ]);
}

$commentaires = $page->getCommentaires($_GET['id']);
// var_dump($commentaires);
// var_dump($data);
echo $page->render('commentaires.html', ['user' => $user, 'commentaires' => $commentaires]);
