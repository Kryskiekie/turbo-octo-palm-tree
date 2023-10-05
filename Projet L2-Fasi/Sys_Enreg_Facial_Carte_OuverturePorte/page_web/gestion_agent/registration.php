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
    <title>Ajouter un agent</title>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container-fluid row">
        <div class="container col-2 sidebar">
            <ul class="navbar-nav">
                <br>
                <li class="nav-item option">
                    <a class="nav-link" href="index.php">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="nav-item option">
                    <a class="nav-link" href="agents.php">
                        <i class="fas fa-users"></i> Agents
                    </a>
                </li>
                <li class="nav-item option">
                    <a class="nav-link" href="attendance.php">
                        <i class="fas fa-clipboard-check"></i> Attendance
                    </a>
                </li>
                <li class="nav-item option" id="active">
                    <a class="nav-link" href="#">
                        <i class="fas fa-user-plus"></i> Registration
                    </a>
                </li>
            </ul>
        </div>
        <div class="container col-10" style="padding-left: 50px; margin-left: 220px;">
            <br>
            <h1>Ajouter un agent</h1>
            <br>
            <?php
            if (isset($_POST["send"])) {

                $id_carte_agent = $_POST["id_carte_agent"];
                $nom_agent = $_POST["nom_agent"];
                $fonction_agent = $_POST["fonction_agent"];
                
                if (isset($id_carte_agent) AND !empty($id_carte_agent) AND isset($nom_agent) AND !empty($nom_agent) AND isset($fonction_agent) AND !empty($fonction_agent))
                {
                    $stmt = $conn->prepare("INSERT INTO agent (id_carte_agent, nom_agent, fonction_agent) VALUES (:id_carte_agent, :nom_agent, :fonction_agent)");
                    $stmt->bindParam(':id_carte_agent', $id_carte_agent);
                    $stmt->bindParam(':nom_agent', $nom_agent);
                    $stmt->bindParam(':fonction_agent', $fonction_agent);

                    if ($stmt->execute()) 
                    {
                        $_SESSION['message'] =  "<div class='alert alert-success'>L'agent a été ajouté avec succès.</div>";
                        header("location: agents.php");
                    }
                    else 
                    {
                        $_SESSION['message'] =  "<div class='alert alert-danger' id='error-message'>Une erreur s'est produite lors de l'ajout de l'agent : " . $stmt->errorInfo()[2] . "</div>"; 
                        header("location: agents.php");
                    }
                    $conn = null;
                }
                else
                {
                    echo "<div class='alert alert-danger' id='error-message'>Veuillez remplir tous les champs.</div>";
                    echo "<script>setTimeout(function() { document.getElementById('error-message').classList.add('d-none'); }, 3000);</script>";
                }
            }
            ?>
            <form method="post" action="">
                <div class="form-group">
                    <label for="card_id">ID de la carte RFID :</label>
                    <input type="text" class="form-control inputoption" id="id_carte_agent" name="id_carte_agent" value="<?php echo isset($_POST['id_carte_agent']) ? htmlspecialchars($_POST['id_carte_agent']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="nom">Nom :</label>
                    <input type="text" class="form-control inputoption" id="nom_agent" name="nom_agent" value="<?php echo isset($_POST['nom_agent']) ? htmlspecialchars($_POST['nom_agent']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="fonction">Fonction :</label>
                    <input type="text" class="form-control inputoption" id="fonction_agent" name="fonction_agent" value="<?php echo isset($_POST['fonction_agent']) ? htmlspecialchars($_POST['fonction_agent']) : ''; ?>">
                </div>
                <button type="submit" class="btn btn-dark" name="send">Ajouter l'agent</button>
            </form>
        </div>
    </div>
    <div class="loading">
		<img class="loadimg" src="https://th.bing.com/th/id/R.939a2cd4c86fd3f837135b9d6d2c35e7?rik=uDBU8pMb223sSg&pid=ImgRaw&r=0" alt="Chargement en cours...">
		<span class="loading-text">Chargement en cours...</span>
	</div>
	<script>
        window.addEventListener('load', function() {
            var loading = document.querySelector('.loading');
            setTimeout(function() {
                loading.style.display = 'none';
            }, 900);
        });
    </script>
    <script src="js/myscript.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
</body>
</html>