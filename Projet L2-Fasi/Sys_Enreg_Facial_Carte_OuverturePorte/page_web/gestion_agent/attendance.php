<?php
    include 'bdd_connexion.php';
    include 'http/httpcache.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Liste des agents enregistrés</title>
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
                <li class="nav-item option" id="active">
                    <a class="nav-link" href="#">
                        <i class="fas fa-clipboard-check"></i> Attendance
                    </a>
                </li>
                <li class="nav-item option">
                    <a class="nav-link" href="registration.php">
                        <i class="fas fa-user-plus"></i> Registration
                    </a>
                </li>
            </ul>
        </div>
        <div class="container col-10" style="padding-left: 50px; margin-left: 220px;">
            <br>
            <h1>Liste des agents présents</h1>
            <br>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Rechercher un agent..." id="searchInput">
            </div>
            <?php
            try 
            {
                $stmt = $conn->prepare("SELECT agent.nom_agent, agent.fonction_agent, register.date_enregistrement, register.heure_enregistrement FROM register INNER JOIN agent ON register.agent_id = agent.id_carte_agent ORDER BY register.date_enregistrement DESC, register.heure_enregistrement DESC");
                $stmt->execute();

                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($rows) == 0) 
                {
                    echo "<table class='table table-striped'>";
                    echo "<thead><tr><th style='border-top-left-radius: 20px;'>Nom</th><th>Fonction</th><th>Date d'enregistrement</th><th style='border-top-right-radius: 20px;'>Heure d'enregistrement</th></tr></thead>";
                    echo "<tbody>";
                    echo "</tbody></table>";
                    echo "<p>Aucun agent enregistré.</p>";
                }
                else 
                {
                    echo "<table class='table table-striped'>";
                    echo "<thead><tr><th>Nom</th><th>Fonction</th><th>Date d'enregistrement</th><th>Heure d'enregistrement</th></tr></thead>";
                    echo "<tbody>";
                    foreach ($rows as $row) 
                    {
                        echo "<tr><td>" . $row["nom_agent"] . "</td><td>" . $row["fonction_agent"] . "</td><td>" . $row["date_enregistrement"] . "</td><td>" . $row["heure_enregistrement"] . "</td></tr>";
                    }
                    echo "</tbody></table>";
                }

            }
            catch (PDOException $e) 
            {
                    echo "Erreur : " . $e->getMessage();
            }

            $pdo = null;
            ?>
        </div>
    </div>

    <div class="loading">
		<img class="loadimg" src="https://th.bing.com/th/id/R.939a2cd4c86fd3f837135b9d6d2c35e7?rik=uDBU8pMb223sSg&pid=ImgRaw&r=0" alt="Chargement en cours...">
		<span class="loading-text">Chargement en cours...</span>
	</div>
	<script>
        window.addEventListener('load', function() {
            var loading = document.querySelector('.loading');
            var randomTime = Math.floor(Math.random() * 2000) + 1000;
            setTimeout(function() {
                loading.style.display = 'none';
            }, randomTime);
        });
    </script> 
    <script>
        $(document).ready(function(){
            $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>