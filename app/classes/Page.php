<?php

namespace App;

class Page
{
    private \Twig\Environment $twig;
    private $pdo;
    public $session;

    function __construct()
    {
        $prefixe = (substr_count(getcwd(), '/') == 4) ? "../" : "";
            // Test ternaire (var = (test) ? "vrai" : "faux";)
            // getcwd() : retourne le chemin du fichier dans lequel on est
            /* ancienne version :
            $prefixe = (str_contains(getcwd(), 'admin')
            or str_contains(getcwd(),'interventions')) ? "../" : "";*/
        $this->session = new Session();
        try {
            $this->pdo = new \PDO('mysql:host=mysql;dbname=b2-dev-web-1', "root", "");
        } catch (\PDOException $e) {
            var_dump($e->getMessage());
            die();
        }
        $loader = new \Twig\Loader\FilesystemLoader($prefixe . '../templates');  // un seul /.. au lieu de 2
        $this->twig = new \Twig\Environment($loader, [
            'cache' => $prefixe . '../var/cache/compilation_cache',
            'debug' => true
        ]);
            // toutes les listes en php sont des dictionnaires, '=>' permet d'accéder à une valeur à partir de l'identifiant
    }

    public function delete($table_name, $id)
    {
        $sql = "DELETE FROM $table_name WHERE id = $id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }

    public function insert(string $table_name, array $data)
    {
        $sql = "INSERT INTO " . $table_name . "(email, password) VALUES (:email, :password)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }

    public function getUserByEmail(array $data) 
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getAllUsers() {
        $sql = "SELECT * FROM users";
        $stmt = $this->pdo->prepare($sql);
        $stmt ->execute();
        $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $users;
    }

    public function getUsers($field = '', $condition = '') {
        if(!in_array($field,['id','email','password','created_at','updated_at','role']))
            return false;
        $sql = "SELECT * FROM users WHERE $field = :condition";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':condition', $condition);
        $stmt ->execute();
        $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        // var_dump($users);
        return $users;
    }
        // public function getUsers($field = '', $condition = '') {
        //     $sql = "SELECT * FROM users WHERE $field = '$condition'";
        //     $stmt = $this->pdo->prepare($sql);
        //     $stmt ->execute();
        //     $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        //     var_dump($users);
        //     return $users;
        // }
    //
    public function insertIntervention(array $data) {
        $sql = "INSERT INTO interventions (id_standardiste, id_client, titre, suivi, urgence, debut, fin)
        VALUES (:id_standardiste, :id_client, :titre, :suivi, :urgence, :debut, :fin)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }

    public function getInterventions($condition = 1) {
        $sql = "SELECT interventions.id, interventions.titre, interventions.suivi,
        interventions.urgence, interventions.debut, interventions.fin,
        standardistes.email AS standardiste, clients.email AS client
        FROM interventions LEFT JOIN users AS standardistes ON interventions.id_standardiste = standardistes.id
        LEFT JOIN users AS clients ON interventions.id_client = clients.id
        WHERE $condition";
        // var_dump($sql);
        $stmt = $this->pdo->prepare($sql);
        $stmt ->execute();
        $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $res;
    }

    public function updateInterventions(array $data) {
        $sql = "UPDATE interventions SET
        id_client = :id_client,
        id_standardiste = :id_standardiste,
        id_client = :id_client,
        titre = :titre, 
        suivi = :suivi,
        urgence = :urgence,
        debut = :debut,
        fin = :fin
        WHERE interventions.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }

    public function getIntervenantsByIntervention($idIntervention) {
        $stmt = $this->pdo->prepare("SELECT users.id, users.email
        FROM users JOIN infos_intervenants ON users.id = infos_intervenants.id_intervenant
        WHERE infos_intervenants.id_intervention = ".$idIntervention);
        $stmt ->execute();
        $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $res;
    }

    public function getInterventionsByIntervenant($idIntervenant) {
        $stmt = $this->pdo->prepare(
        "SELECT id_intervention FROM infos_intervenants
        WHERE id_intervenant = ".$idIntervenant);
        $stmt ->execute();
        $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $res;
    }

    public function getCommentaires($id_intervention) {
        $stmt = $this->pdo->prepare(
            "SELECT commentaires.id, commentaires.id_intervention, users.id AS id_user, 
            users.email, commentaires.texte_comm, commentaires.date_comm
            FROM commentaires LEFT JOIN users ON commentaires.id_user = users.id
            WHERE  commentaires.id_intervention = :id_intervention");
            $stmt->bindParam(':id_intervention', $id_intervention);
            $stmt->execute();
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $res;
    }

    public function insertCommentaire($data) {
        $sql = "INSERT INTO commentaires (id_intervention, id_user, texte_comm)
        VALUES (:id_intervention, :id_user, :texte_comm)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $res;
    }

    public function render(string $name, array $data) :string
    {
        return $this->twig->render($name, $data);
    }

}
