<?php
include 'bdd_connexion.php';
include 'http/httpcache.php';
session_start();

$sql = "SELECT * FROM agent";
$stmt = $conn->prepare($sql);
$stmt->execute();
$num_agents = $stmt->rowCount();

$delete_id = null;

if (isset($_GET["delete"])) {
    $delete_id = $_GET["delete"];
}

if (!is_null($delete_id)) {
    $sql = "DELETE FROM agent WHERE id_carte_agent=:delete_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':delete_id', $delete_id);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>L'agent a été supprimé avec succès.</div>";
        header("location: agents.php");
    } else {
        echo "<div class='alert alert-danger' id='error-message'>Une erreur s'est produite lors de la suppression de l'agent : " . $stmt->errorInfo()[2] . "</div>";
        echo "<script>setTimeout(function() { document.getElementById('error-message').classList.add('d-none'); }, 3000);</script>";
    }
}

$sql = "SELECT * FROM agent";
$stmt = $conn->prepare($sql);
$stmt->execute();

$conn = null;
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Liste des agents enregistrés</title>
    <title>Liste des agents</title>
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
                <li class="nav-item option" id="active">
                    <a class="nav-link" href="#">
                        <i class="fas fa-users"></i> Agents
                    </a>
                </li>
                <li class="nav-item option">
                    <a class="nav-link" href="attendance.php">
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
            <div class="alertmessage" id="error-message">
                <?php
                    if (isset($_SESSION['message'])) 
                    {
                        echo '<div class="alertmessage" id="error-message">';
                        echo $_SESSION['message'];
                        echo '</div>';
                        unset($_SESSION['message']);
                    }
                ?>
                <script>
                    setTimeout(function() {
                        var alertmessage = document.querySelector('#error-message');
                        if (alertmessage) {
                            alertmessage.classList.add('hide');
                            setTimeout(function() {
                                alertmessage.classList.add('d-none');
                            }, 1000);
                        }
                    }, 3000);
                </script>
            </div>
            <br>
            <h1>Liste des agents <span style="font-size: 25px;">(<?= $num_agents; ?>)</span></h1>
            <br>
            <button class="btn btn-dark"><a href="registration.php" style="color: #fff; text-decoration: none;">Ajouter un nouvel agent</a></button><br><br>
            <br>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Rechercher un agent..." id="searchInput">
            </div>
            <table class="table table-striped">
            <thead>
                <tr >
                    <th style="border-top-left-radius: 20px;">ID de la carte RFID</th>
                    <th>Nom</th>
                    <th>Fonction</th>
                    <th style="border-top-right-radius: 20px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($stmt->rowCount() > 0) 
                {
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
                    {
                        echo "<tr>
                                <td>" . htmlspecialchars($row["id_carte_agent"]) . "</td>
                                <td>" . htmlspecialchars($row["nom_agent"]) . "</td>
                                <td>" . htmlspecialchars($row["fonction_agent"]) . "</td>
                                <td style='text-align: center'>
                                    <a href='edit.php?id=" . htmlspecialchars($row["id_carte_agent"]) . "'><i class='fas fa-edit'></i></a>
                                    <a href='?delete=" . htmlspecialchars($row["id_carte_agent"]) . "' data-agent-name='" . htmlspecialchars($row["nom_agent"]) . "' onclick='return confirmDelete(this)'><i class='fas fa-trash-alt'></i></a>
                                </td>
                             </tr>";
                    }
                } 
                else 
                {
                    echo "<tr><td colspan='4'>Aucun agent trouvé</td></tr>";
                }
                ?>
            </tbody>
            </table>
        </div>
    </div>
    <div class="loading">
		<img class="loadimg" src="https://th.bing.com/th/id/R.939a2cd4c86fd3f837135b9d6d2c35e7?rik=uDBU8pMb223sSg&pid=ImgRaw&r=0" alt="Chargement en cours...">
		<span class="loading-text">Chargement en cours...</span>
	</div>
	<script>
        window.addEventListener('load', function() {
            var loading = document.querySelector('.loading');
            var randomTime = Math.floor(Math.random() * 2000) + 1000; // temps aléatoire entre 1 et 3 secondes
            setTimeout(function() {
                loading.style.display = 'none';
            }, randomTime);
        });
    </script>
    // cacher toutes les alertes initialement
var alertmessages = document.querySelectorAll('.alertmessage');
alertmessages.forEach(function(alertmessage) {
    alertmessage.classList.add('hide');
});

// afficher toutes les alertes après le chargement de la page
window.addEventListener('load', function() {
    var alertmessages = document.querySelectorAll('.alertmessage');
    alertmessages.forEach(function(alertmessage) {
        alertmessage.classList.remove('hide');
    });
});
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
    <script>
        function moveDiv() 
        {
            var div = document.querySelector('.moveable-div');
            if (div.style.transform === 'translateX(-50%)') {
                div.style.transform = 'translateX(0)';
            } else {
                div.style.transform = 'translateX(-50%)';
            }
        }
    </script>
    <script>
        function confirmDelete(link) {
        var agentName = link.getAttribute('data-agent-name');
        var confirmed = confirm("Voulez-vous vraiment supprimer l'agent " + agentName + " ?");
        if (confirmed) {
            $('#delete-modal').modal('show');
            $('#delete-modal form').attr('action', link.href);
        }
        return confirmed;
        }
    </script>    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>