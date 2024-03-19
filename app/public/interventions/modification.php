<script>
    
</script>
<?php
require_once '../../vendor/autoload.php';

use App\Page;  // Inclut la classe Page

$page = new Page();

if(!$page->session->isConnected()) {
    header('Location: index.php');
}

$user = $page->session->get('user');

if(!$_GET['id'])
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


if(!isset($_POST['titre'])) {  // FORMULAIRE DE MODIFICATION
    $data = [
        'user' => $user,
        'infosIntervention' => $page->getInterventions("interventions.id = ".$_GET['id'])[0],
        'clients' => $page->getUsers('role', 'client'),
        'intervenants' => $page->getUsers('role', 'intervenant')
    ];
    // var_dump($data);
    echo $page->render('interventions/modification.html', $data);
} else {  // REQUÊTE UPDATE
    $data = [
        'id' => (int)$_GET['id'],
        'id_standardiste' => $user['id'],
        'id_client' => (int)$_POST['idClient'],
        'titre' => $_POST['titre'],
        'suivi' => $_POST['suivi'],
        'urgence' => (int)$_POST['urgence'],
        'debut' => $_POST['debut'],
    ];
    $data['fin'] = ($_POST['fin'] != '')? $_POST['fin'] : '0000-01-01';
    // var_dump($data);
    $page->updateInterventions($data);
    header('location: ../accueil.php');
}