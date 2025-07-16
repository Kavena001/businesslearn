<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Use absolute paths for includes
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/db.php';

// Verify database connection
if (!$db || !method_exists($db, 'getRow')) {
    die('Database connection failed');
}

// Get and validate course ID
$courseId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($courseId <= 0) {
    header('Location: courses.php');
    exit;
}

// Fetch course details
try {
    $course = $db->getRow("SELECT * FROM courses WHERE id = ?", [$courseId]);
    if (!$course) {
        header('Location: courses.php');
        exit;
    }
} catch (Exception $e) {
    die('Error loading course: ' . htmlspecialchars($e->getMessage()));
}

// Process learning objectives
$objectives = [];
if (!empty($course['objectives'])) {
    $objectives = json_decode($course['objectives'], true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $objectives = array_filter(explode("\n", $course['objectives']));
    }
}

require __DIR__ . '/includes/header.php';
?>

<!-- Course Detail Header -->
<header class="py-5" style="background-color: #0056b3; color: white;">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1><?= htmlspecialchars($course['title']) ?></h1>
                <p class="lead"><?= htmlspecialchars($course['short_description']) ?></p>
            </div>
            <div class="col-md-4 text-end">
                <a href="enrollment/enroll.php?course_id=<?= $course['id'] ?>" 
                   class="btn btn-light btn-lg enroll-btn"
                   onclick="localStorage.setItem('selectedCourse', <?= $course['id'] ?>); localStorage.setItem('selectedCourseTitle', '<?= addslashes(htmlspecialchars($course['title'])) ?>')">
                   S'inscrire maintenant
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Course Content -->
<section class="container my-5">
    <div class="row">
        <div class="col-md-8">
            <?php if (!empty($course['image'])): ?>
                <img src="uploads/courses/<?= htmlspecialchars($course['image']) ?>" 
                     class="img-fluid mb-4" 
                     alt="<?= htmlspecialchars($course['title']) ?>"
                     onerror="this.onerror=null;this.src='assets/images/course-placeholder.jpg'">
            <?php else: ?>
                <div class="bg-secondary mb-4" style="height: 300px; display: flex; align-items: center; justify-content: center;">
                    <span class="text-white">Image non disponible</span>
                </div>
            <?php endif; ?>
            
            <h2>Description du cours</h2>
            <div class="course-description">
                <?= nl2br(htmlspecialchars($course['description'])) ?>
            </div>
            
            <?php if (!empty($objectives)): ?>
                <h3 class="mt-4">Objectifs d'apprentissage</h3>
                <ul class="learning-objectives">
                    <?php foreach ($objectives as $objective): 
                        if (!empty(trim($objective))): ?>
                            <li><?= htmlspecialchars(trim($objective)) ?></li>
                        <?php endif;
                    endforeach; ?>
                </ul>
            <?php endif; ?>
            
            <h3 class="mt-4">Public cible</h3>
            <p class="target-audience">
                <?= nl2br(htmlspecialchars($course['target_audience'] ?? 'Ce cours s\'adresse à tous les professionnels souhaitant développer leurs compétences.')) ?>
            </p>
        </div>
        
        <div class="col-md-4">
            <div class="card course-details-card">
                <div class="card-header" style="background-color: #65697d; color: white;">
                    <h5 class="mb-0">Détails du cours</h5>
                </div>
                <div class="card-body">
                    <p><strong>Durée:</strong> <?= htmlspecialchars($course['duration']) ?></p>
                    <p><strong>Effort:</strong> <?= htmlspecialchars($course['weekly_effort'] ?? '4-6 heures/semaine') ?></p>
                    <p><strong>Niveau:</strong> <?= htmlspecialchars($course['level']) ?></p>
                    <p><strong>Langue:</strong> <?= htmlspecialchars($course['language'] ?? 'Français') ?></p>
                    <p><strong>Certificat:</strong> <?= $course['certificate'] ? 'Oui' : 'Non' ?></p>
                    <a href="enrollment/enroll.php?course_id=<?= $course['id'] ?>" 
                       class="btn btn-primary w-100 mt-3 enroll-btn"
                       onclick="localStorage.setItem('selectedCourse', <?= $course['id'] ?>); localStorage.setItem('selectedCourseTitle', '<?= addslashes(htmlspecialchars($course['title'])) ?>')">
                       S'inscrire maintenant
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php 
require __DIR__ . '/includes/footer.php'; 
?>

<script>
// Enhanced enrollment button handling
document.addEventListener('DOMContentLoaded', function() {
    const enrollButtons = document.querySelectorAll('.enroll-btn');
    
    enrollButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Prevent default if needed
            if (button.getAttribute('href') === '#') {
                e.preventDefault();
            }
            
            // Store course info
            localStorage.setItem('selectedCourse', <?= $course['id'] ?>);
            localStorage.setItem('selectedCourseTitle', '<?= addslashes(htmlspecialchars($course['title'])) ?>');
            
            // Optional: Analytics tracking
            console.log('Enrollment initiated for course <?= $course['id'] ?>');
        });
    });
});
</script>