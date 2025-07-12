<?php
require_once '../includes/header.php';

$pageTitle = 'Inscription au cours';
$success = false;
$courseId = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

// Get course details
$course = $db->getRow("SELECT * FROM courses WHERE id = ?", [$courseId]);

if (!$course) {
    header("Location: ../courses.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process enrollment
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $company = trim($_POST['company']);
    $position = trim($_POST['position']);
    $employeeCount = trim($_POST['employeeCount']);
    $paymentMethod = trim($_POST['paymentOption']);
    $termsAccepted = isset($_POST['terms']) ? 1 : 0;
    
    // Insert enrollment
    $sql = "INSERT INTO enrollments (course_id, first_name, last_name, email, phone, company, position, employee_count, payment_method) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $params = [$courseId, $firstName, $lastName, $email, $phone, $company, $position, $employeeCount, $paymentMethod];
    
    if ($db->insert($sql, $params)) {
        $success = true;
        
        // Send confirmation email (you would implement this)
        // sendEnrollmentConfirmation($email, $firstName, $course['title']);
    }
}
?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h2 id="courseTitleDisplay">Inscription au cours: <?php echo htmlspecialchars($course['title']); ?></h2>
                    <p class="lead" id="courseDescription"><?php echo htmlspecialchars($course['short_description']); ?></p>
                </div>
                
                <?php if ($success): ?>
                <div id="enrollmentSuccess" class="alert alert-success">
                    <h4 class="alert-heading">Inscription confirmée!</h4>
                    <p>Merci <?php echo htmlspecialchars($firstName); ?> pour votre inscription à notre cours "<?php echo htmlspecialchars($course['title']); ?>".</p>
                    <hr>
                    <p class="mb-0">Nous vous avons envoyé un email de confirmation avec les détails de votre inscription.</p>
                </div>
                <?php else: ?>
                <form id="enrollmentForm" method="POST" novalidate>
                    <!-- Form fields... -->
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
require_once '../includes/footer.php';
?>