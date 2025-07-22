<?php
require '../includes/config.php';
require '../includes/db.php';
require '../includes/header.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$success = false;
$error = '';
$courseId = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

// Validate course ID
if ($courseId <= 0) {
    header("Location: ../courses.php");
    exit;
}

// Get course details
$course = $db->getRow("SELECT * FROM courses WHERE id = ?", [$courseId]);

if (!$course) {
    header("Location: ../courses.php");
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate and sanitize inputs
        $firstName = trim(filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING));
        $lastName = trim(filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING));
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
        $phone = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING));
        $company = trim(filter_input(INPUT_POST, 'company', FILTER_SANITIZE_STRING));
        $position = trim(filter_input(INPUT_POST, 'position', FILTER_SANITIZE_STRING));
        $employeeCount = trim(filter_input(INPUT_POST, 'employeeCount', FILTER_SANITIZE_STRING));
        $paymentMethod = trim(filter_input(INPUT_POST, 'paymentOption', FILTER_SANITIZE_STRING));
        $termsAccepted = isset($_POST['terms']) ? 1 : 0;

        // Basic validation
        if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || 
            empty($company) || empty($position) || empty($paymentMethod) || !$termsAccepted) {
            throw new Exception("Tous les champs obligatoires doivent être remplis.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Adresse email invalide.");
        }

        // Insert enrollment with all required fields
        $sql = "INSERT INTO enrollments (
                    course_id, 
                    first_name, 
                    last_name, 
                    email, 
                    phone, 
                    company, 
                    position, 
                    employee_count, 
                    payment_method, 
                    status,
                    created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $params = [
            $courseId, 
            $firstName, 
            $lastName, 
            $email, 
            $phone, 
            $company, 
            $position, 
            $employeeCount, 
            $paymentMethod,
            'pending' // Explicitly setting status
        ];
        
        $enrollmentId = $db->insert($sql, $params);
        
        if ($enrollmentId) {
            $success = true;
            
            // Store in session for admin notification
            $_SESSION['new_enrollment'] = [
                'id' => $enrollmentId,
                'course' => $course['title'],
                'name' => "$firstName $lastName",
                'email' => $email
            ];
            
            // Redirect to prevent form resubmission
            header("Location: enroll.php?course_id=$courseId&success=1");
            exit;
        } else {
            throw new Exception("Échec de l'inscription. Veuillez réessayer.");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        error_log("Enrollment Error: " . $e->getMessage());
    }
}

// Check for success redirect
if (isset($_GET['success']) && $_GET['success'] == 1 && isset($_SESSION['new_enrollment'])) {
    $success = true;
    $firstName = $_SESSION['new_enrollment']['name'];
    $email = $_SESSION['new_enrollment']['email'];
}
?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h2>Inscription au cours: <?= htmlspecialchars($course['title']) ?></h2>
                    <p class="lead"><?= htmlspecialchars($course['short_description']) ?></p>
                </div>
                
                <?php if ($success): ?>
                <div id="enrollmentSuccess" class="alert alert-success">
                    <h4 class="alert-heading">Inscription confirmée!</h4>
                    <p>Merci <?= htmlspecialchars($firstName) ?> pour votre inscription à notre cours "<?= htmlspecialchars($course['title']) ?>".</p>
                    <hr>
                    <p class="mb-0">Un email de confirmation a été envoyé à <?= htmlspecialchars($email) ?>.</p>
                    <div class="mt-3">
                        <a href="../courses.php" class="btn btn-outline-primary">Voir d'autres cours</a>
                    </div>
                </div>
                <?php else: ?>
                    <?php if (!empty($error)): ?>
                    <div class="alert alert-danger mb-4"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                <form id="enrollmentForm" method="POST" novalidate>
                    <div class="row g-3">
                        <!-- Personal Information -->
                        <div class="col-md-6">
                            <label for="firstName" class="form-label">Prénom *</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" 
                                   value="<?= isset($_POST['firstName']) ? htmlspecialchars($_POST['firstName']) : '' ?>" required>
                            <div class="invalid-feedback">Veuillez entrer votre prénom.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="lastName" class="form-label">Nom *</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" 
                                   value="<?= isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName']) : '' ?>" required>
                            <div class="invalid-feedback">Veuillez entrer votre nom.</div>
                        </div>
                        
                        <!-- Contact Information -->
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
                            <div class="invalid-feedback">Veuillez entrer une adresse email valide.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Téléphone *</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>" required>
                            <div class="invalid-feedback">Veuillez entrer votre numéro de téléphone.</div>
                        </div>
                        
                        <!-- Company Information -->
                        <div class="col-12">
                            <label for="company" class="form-label">Entreprise *</label>
                            <input type="text" class="form-control" id="company" name="company" 
                                   value="<?= isset($_POST['company']) ? htmlspecialchars($_POST['company']) : '' ?>" required>
                            <div class="invalid-feedback">Veuillez entrer le nom de votre entreprise.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="position" class="form-label">Poste *</label>
                            <input type="text" class="form-control" id="position" name="position" 
                                   value="<?= isset($_POST['position']) ? htmlspecialchars($_POST['position']) : '' ?>" required>
                            <div class="invalid-feedback">Veuillez entrer votre poste.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="employeeCount" class="form-label">Nombre d'employés</label>
                            <select class="form-select" id="employeeCount" name="employeeCount">
                                <option value="1-10" <?= (isset($_POST['employeeCount']) && $_POST['employeeCount'] == '1-10') ? 'selected' : '' ?>>1-10</option>
                                <option value="11-50" <?= (isset($_POST['employeeCount']) && $_POST['employeeCount'] == '11-50') ? 'selected' : '' ?>>11-50</option>
                                <option value="51-200" <?= (isset($_POST['employeeCount']) && $_POST['employeeCount'] == '51-200') ? 'selected' : '' ?>>51-200</option>
                                <option value="201-500" <?= (isset($_POST['employeeCount']) && $_POST['employeeCount'] == '201-500') ? 'selected' : '' ?>>201-500</option>
                                <option value="500+" <?= (isset($_POST['employeeCount']) && $_POST['employeeCount'] == '500+') ? 'selected' : '' ?>>500+</option>
                            </select>
                        </div>
                        
                        <!-- Payment Options -->
                        <div class="col-12 mt-4">
                            <h5>Options de paiement *</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="paymentOption" id="paymentCard" value="card" 
                                    <?= (!isset($_POST['paymentOption']) || (isset($_POST['paymentOption']) && $_POST['paymentOption'] == 'card')) ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="paymentCard">
                                    Carte de crédit
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="paymentOption" id="paymentInvoice" value="invoice" 
                                    <?= (isset($_POST['paymentOption']) && $_POST['paymentOption'] == 'invoice') ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="paymentInvoice">
                                    Facture (entreprises seulement)
                                </label>
                            </div>
                        </div>
                        
                        <!-- Terms and Conditions -->
                        <div class="col-12 mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" 
                                    <?= isset($_POST['terms']) ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="terms">
                                    J'accepte les <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">conditions générales</a> *
                                </label>
                                <div class="invalid-feedback">Vous devez accepter les conditions.</div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="col-12 mt-4">
                            <button class="btn btn-primary px-4 py-2" type="submit">Confirmer l'inscription</button>
                            <a href="../course.php?id=<?= $courseId ?>" class="btn btn-outline-secondary ms-2">Retour</a>
                        </div>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Conditions générales</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>1. Politique d'annulation</h6>
                <p>Les annulations effectuées plus de 14 jours avant le début du cours bénéficient d'un remboursement intégral...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>

<script>
// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('enrollmentForm');
    
    if (form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    }
});
</script>