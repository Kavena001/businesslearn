<?php
require '../includes/config.php';
require '../includes/db.php';
require '../includes/header.php';

$success = false;
$courseId = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

// Get course details
$course = $db->getRow("SELECT * FROM courses WHERE id = ?", [$courseId]);

if (!$course) {
    header("Location: ../courses.php");
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $company = trim($_POST['company']);
    $position = trim($_POST['position']);
    $employeeCount = trim($_POST['employeeCount']);
    $paymentMethod = trim($_POST['paymentOption']);
    $termsAccepted = isset($_POST['terms']) ? 1 : 0;
    
    // Insert enrollment with all required fields
    $sql = "INSERT INTO enrollment (course_id, first_name, last_name, email, phone, company, position, employee_count, payment_method, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
            'name' => "$firstName $lastName"
        ];
    } else {
        // Log error for debugging
        error_log("Enrollment failed: " . print_r($_POST, true));
    }
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
                <form id="enrollmentForm" method="POST" novalidate>
                    <div class="row g-3">
                        <!-- Personal Information -->
                        <div class="col-md-6">
                            <label for="firstName" class="form-label">Prénom *</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" required>
                            <div class="invalid-feedback">Veuillez entrer votre prénom.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="lastName" class="form-label">Nom *</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" required>
                            <div class="invalid-feedback">Veuillez entrer votre nom.</div>
                        </div>
                        
                        <!-- Contact Information -->
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">Veuillez entrer une adresse email valide.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Téléphone *</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                            <div class="invalid-feedback">Veuillez entrer votre numéro de téléphone.</div>
                        </div>
                        
                        <!-- Company Information -->
                        <div class="col-12">
                            <label for="company" class="form-label">Entreprise *</label>
                            <input type="text" class="form-control" id="company" name="company" required>
                            <div class="invalid-feedback">Veuillez entrer le nom de votre entreprise.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="position" class="form-label">Poste *</label>
                            <input type="text" class="form-control" id="position" name="position" required>
                            <div class="invalid-feedback">Veuillez entrer votre poste.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="employeeCount" class="form-label">Nombre d'employés</label>
                            <select class="form-select" id="employeeCount" name="employeeCount">
                                <option value="1-10">1-10</option>
                                <option value="11-50">11-50</option>
                                <option value="51-200">51-200</option>
                                <option value="201-500">201-500</option>
                                <option value="500+">500+</option>
                            </select>
                        </div>
                        
                        <!-- Payment Options -->
                        <div class="col-12 mt-4">
                            <h5>Options de paiement *</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="paymentOption" id="paymentCard" value="card" checked required>
                                <label class="form-check-label" for="paymentCard">
                                    Carte de crédit
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="paymentOption" id="paymentInvoice" value="invoice" required>
                                <label class="form-check-label" for="paymentInvoice">
                                    Facture (entreprises seulement)
                                </label>
                            </div>
                        </div>
                        
                        <!-- Terms and Conditions -->
                        <div class="col-12 mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
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
    
    // Set course info from URL if coming from course page
    const urlParams = new URLSearchParams(window.location.search);
    const courseId = urlParams.get('course_id');
    if (courseId) {
        localStorage.setItem('selectedCourse', courseId);
    }
});
</script>