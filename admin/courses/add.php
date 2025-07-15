<?php
require '../../includes/config.php';
require '../../includes/db.php';
require '../../includes/auth.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $short_description = trim($_POST['short_description']);
    $duration = trim($_POST['duration']);
    $level = trim($_POST['level']);
    $language = trim($_POST['language']);
    $certificate = isset($_POST['certificate']) ? 1 : 0;
    $featured = isset($_POST['featured']) ? 1 : 0;
    
    // Handle file upload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../uploads/courses/';
        $uploadFile = $uploadDir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check !== false) {
            // Generate unique filename
            $image = uniqid() . '.' . $imageFileType;
            $uploadFile = $uploadDir . $image;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                // File uploaded successfully
            } else {
                $_SESSION['message'] = "Une erreur s'est produite lors du téléchargement de l'image.";
                $_SESSION['message_type'] = 'danger';
            }
        } else {
            $_SESSION['message'] = "Le fichier n'est pas une image valide.";
            $_SESSION['message_type'] = 'danger';
        }
    }
    
    // Insert into database
    $sql = "INSERT INTO courses (title, description, short_description, duration, level, language, certificate, image, featured) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $params = [$title, $description, $short_description, $duration, $level, $language, $certificate, $image, $featured];
    
    if ($db->insert($sql, $params)) {
        $_SESSION['message'] = "Le cours a été ajouté avec succès!";
        $_SESSION['message_type'] = 'success';
        header('Location: list.php');
        exit;
    } else {
        $_SESSION['message'] = "Une erreur s'est produite lors de l'ajout du cours.";
        $_SESSION['message_type'] = 'danger';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Cours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container mt-4">
        <h2><i class="bi bi-book me-2"></i> Ajouter un nouveau cours</h2>
        
        <a href="list.php" class="btn btn-secondary mb-4">
            <i class="bi bi-arrow-left"></i> Retour à la liste
        </a>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['message_type'] ?>">
                <?= $_SESSION['message'] ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="bi bi-info-circle"></i> Informations de base
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="title" class="form-label">Titre du cours*</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="short_description" class="form-label">Description courte*</label>
                                <input type="text" class="form-control" id="short_description" name="short_description" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description complète*</label>
                                <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="bi bi-gear"></i> Paramètres
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="duration" class="form-label">Durée*</label>
                                <input type="text" class="form-control" id="duration" name="duration" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="level" class="form-label">Niveau*</label>
                                <select class="form-select" id="level" name="level" required>
                                    <option value="Débutant">Débutant</option>
                                    <option value="Intermédiaire">Intermédiaire</option>
                                    <option value="Avancé">Avancé</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="language" class="form-label">Langue*</label>
                                <select class="form-select" id="language" name="language" required>
                                    <option value="Français">Français</option>
                                    <option value="Anglais">Anglais</option>
                                    <option value="Bilingue">Bilingue</option>
                                </select>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="certificate" name="certificate" checked>
                                <label class="form-check-label" for="certificate">Certificat de complétion</label>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="featured" name="featured">
                                <label class="form-check-label" for="featured">Mettre en vedette</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="bi bi-image"></i> Image du cours
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="image" class="form-label">Image*</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="bi bi-save"></i> Enregistrer le cours
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>