<?php
    include 'bdd_connexion.php';
    include 'http/httpcache.php';
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Liste des agents enregistrés</title>
    <title>Modifier un agent</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <br><br><br><br>
    <h1>Modifier un agent</h1>
    <br>
    <?php

      $id = $_GET["id"];

      if (isset($_POST["submit"])) {
        header('location: agents.php');
        $nom = $_POST["nom"];
        $fonction = $_POST["fonction"];

        $stmt = $conn->prepare("UPDATE agent SET nom_agent=:nom, fonction_agent=:fonction WHERE id_carte_agent=:id");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':fonction', $fonction);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
          $_SESSION['message'] = "<div class='alert alert-success'>L'agent a été modifié avec succès.</div>";
          header("location: agents.php");
        } else {
          $_SESSION['message'] = "<div class='alert alert-danger'>Une erreur s'est produite lors de la modification de l'agent : " . $stmt->errorInfo()[2] . "</div>";
          header("location: agents.php");
        }
      }

      $stmt = $conn->prepare("SELECT * FROM agent WHERE id_carte_agent=:id");
      $stmt->bindParam(':id', $id);
      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (!$row) {
        $_SESSION['message'] = "<div class='alert alert-danger'>Aucun agent trouvé avec cet ID.</div>";
        exit;
      }

      $nom = $row["nom_agent"];
      $fonction = $row["fonction_agent"];

    ?>
    <form method="post">
      <div class="form-group">
        <label for="nom">Nom de l'agent :</label>
        <br>
        <input type="text" class="form-control inputoption" id="nom" name="nom" value="<?php echo $nom; ?>">
      </div><br>
      <div class="form-group">
        <label for="fonction">Fonction de l'agent :</label>
        <br>
        <input type="text" class="form-control inputoption" id="fonction" name="fonction" value="<?php echo $fonction; ?>">
      </div><br>
      <button type="submit" class="btn btn-dark" name="submit">Enregistrer les modifications</button>
      <button type="button" class="btn btn-dark"><a href="agents.php" style="text-decoration: none;">Retour à la liste des agents</a></button>
    </form>
  </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
</body>
</html>