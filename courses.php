<?php
require 'includes/config.php';
require 'includes/db.php';
require 'includes/header.php';

// Pagination settings
$perPage = 6;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) $currentPage = 1;

// Get total number of courses
$totalCourses = $db->getRow("SELECT COUNT(*) as count FROM courses")['count'];
$totalPages = ceil($totalCourses / $perPage);

// Calculate offset
$offset = ($currentPage - 1) * $perPage;

// Get courses for current page
$courses = $db->getRows("SELECT * FROM courses ORDER BY title LIMIT $perPage OFFSET $offset");
?>

<!-- Page Header -->
<header class="py-4" style="background-color: #0056b3; color: white;">
    <div class="container">
        <h1>Catalogue de cours</h1>
        <p class="lead">Tous nos programmes de formation pour développer vos compétences professionnelles</p>
    </div>
</header>

<!-- Courses Grid -->
<section class="container my-5">
    <?php if (!empty($courses)): ?>
        <div class="row">
            <?php foreach ($courses as $course): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if ($course['image']): ?>
                        <img src="uploads/courses/<?= htmlspecialchars($course['image']) ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($course['title']) ?>"
                             onerror="this.onerror=null;this.src='images/default-course.jpg'">
                    <?php else: ?>
                        <div class="bg-secondary" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                            <span class="text-white">Pas d'image</span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($course['title']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($course['short_description']) ?></p>
                        <div class="mt-3">
                            <span class="badge bg-info me-1">
                                <i class="bi bi-clock"></i> <?= htmlspecialchars($course['duration']) ?>
                            </span>
                            <span class="badge bg-secondary">
                                <?= htmlspecialchars($course['level']) ?>
                            </span>
                        </div>
                        <!-- UPDATED LINK HERE -->
                        <a href="course.php?id=<?= $course['id'] ?>" class="btn btn-primary mt-3">
                            En savoir plus
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">
            Aucun cours disponible pour le moment.
        </div>
    <?php endif; ?>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                    <a class="page-link" href="courses.php?page=<?= $i ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>
</section>

<?php require 'includes/footer.php'; ?>