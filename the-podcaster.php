<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>📻 podcaster</title>
  <meta name="description" content="que personne ne fasse la blaGue avec la pod'castor 🦫">
</head>
<body><pre><?php

  // séparer ses identifiants et les protéger, une bonne habitude à prendre
  include "the-podcaster.dbconf.php";

  try {

    // instancie un objet $connexion à partir de la classe PDO
    $connexion = new PDO(DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_LOGIN, DB_PASS, DB_OPTIONS);

    // Requête de sélection 01
    $requete = "SELECT * FROM `podcasts`";
    $prepare = $connexion->prepare($requete);
    $prepare->execute();
    $resultat = $prepare->fetchAll();
    print_r([$requete, $resultat]); // debug & vérification

    // Requête de sélection 02
    $requete = "SELECT *
                FROM `podcasts`
                WHERE `podcast_id` = :podcast_id"; // on cible le podcast dont l'id est ...
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(":podcast_id" => 2)); // on cible le podcast dont l'id est 2
    $resultat = $prepare->fetchAll();
    print_r([$requete, $resultat]); // debug & vérification

    // Requête d'insertion
    $requete = "INSERT INTO `podcasts` (`podcast_name`, `podcast_description`, `podcast_url`)
                VALUES (:podcast_name, :podcast_description, :podcast_url);";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
      ":podcast_name" => "L'affaire Benalla",
      ":podcast_description" => "Retrouvez 4 épisodes de l'affaire Benalla",
      ":podcast_url" => "https://cdn.radiofrance-podcast.net/podcast2020/11/942a9fa8",
    ));
    $resultat = $prepare->rowCount(); // rowCount() nécessite PDO::MYSQL_ATTR_FOUND_ROWS => true
    $lastInsertedpodcastId = $connexion->lastInsertId(); // on récupère l'id automatiquement créé par SQL
    print_r([$requete, $resultat, $lastInsertedpodcastId]); // debug & vérification

    // Requête de modification
    $requete = "UPDATE `podcasts`
                SET `podcast_description` = :podcast_description
                WHERE `podcast_id` = :podcast_id;";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
      ":podcast_id"   => 3,
      ":podcast_description" => "Tous les jours, une demi-heure de reportage sans commentaire.\n\nInspirés par la célébrissime émission de radio américaine This American Life, 'Les Pieds sur Terre' s’organisent désormais autour de récits, d’histoires vraies, une, deux ou trois par émission, qui tournent autour d’un même thème. Ces histoires sont racontées à la première personne et nourries d’éléments de reportage.",
    ));
    $resultat = $prepare->rowCount();
    print_r([$requete, $resultat]); // debug & vérification

    // Requête de suppression
    $requete = "DELETE FROM `podcasts`
                WHERE ((`podcast_id` = :podcast_id));";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array($lastInsertedpodcastId)); // on lui passe l'id tout juste créé
    $resultat = $prepare->rowCount();
    print_r([$requete, $resultat, $lastInsertedpodcastId]); // debug & vérification

  } catch (PDOException $e) {

    // en cas d'erreur, on récup et on affiche, grâce à notre try/catch
    exit("❌🙀💀 OOPS :\n" . $e->getMessage());

  }

?></pre></body>
</html>