<?php
require_once 'includes/header.php';

$pageTitle = 'Contact';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form submission
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $company = trim($_POST['company']);
    $position = trim($_POST['position']);
    $interest = trim($_POST['interest']);
    $message = trim($_POST['message']);
    
    // Insert into database
    $sql = "INSERT INTO messages (first_name, last_name, email, phone, company, position, interest, message) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $params = [$firstName, $lastName, $email, $phone, $company, $position, $interest, $message];
    
    if ($db->insert($sql, $params)) {
        $success = true;
        
        // Send email notification (you would implement this)
        // sendContactEmail($firstName, $lastName, $email, $message);
    }
}
?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="text-center mb-4">Contactez-nous</h2>
                <p class="text-center mb-5">Vous avez des questions sur nos programmes de formation ? Remplissez le formulaire ci-dessous et notre équipe vous répondra dès que possible.</p>
                
                <?php if ($success): ?>
                <div class="alert alert-success">
                    Merci pour votre message ! Nous vous contacterons dès que possible.
                </div>
                <?php endif; ?>
                
                <form id="contactForm" method="POST" novalidate>
                    <!-- Form fields... -->
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Contact Information Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-4 text-center mb-4 mb-md-0">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                    <i class="bi bi-geo-alt text-primary"></i>
                </div>
                <h4>Adresse</h4>
                <p><?php echo nl2br(htmlspecialchars($siteSettings['contact_address'])); ?></p>
            </div>
            <div class="col-md-4 text-center mb-4 mb-md-0">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                    <i class="bi bi-telephone text-primary"></i>
                </div>
                <h4>Téléphone</h4>
                <p><?php echo htmlspecialchars($siteSettings['contact_phone']); ?><br>Lun-Ven: 9h-17h</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                    <i class="bi bi-envelope text-primary"></i>
                </div>
                <h4>Email</h4>
                <p><?php echo htmlspecialchars($siteSettings['contact_email']); ?></p>
            </div>
        </div>
    </div>
</section>

<?php
require_once 'includes/footer.php';
?>