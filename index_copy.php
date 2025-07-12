<?php
require_once 'includes/header.php';

// Get featured courses
$featuredCourses = $db->getRows("SELECT * FROM courses WHERE featured = 1 ORDER BY title LIMIT 3");

// Get testimonials
$testimonials = $db->getRows("SELECT * FROM testimonials WHERE featured = 1 ORDER BY RAND() LIMIT 3");

// Get team members
$teamMembers = $db->getRows("SELECT * FROM team_members ORDER BY name LIMIT 4");
?>

<!---------------------------------- Banner Carousel (Fixed Height) -------------------------------------------->
<div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="<?php echo SITE_URL; ?>/img/banner/banner1.jpg" class="d-block w-100" alt="Formation professionnelle">
            <div class="carousel-caption d-none d-md-block">
                <h2>Développez les compétences de votre équipe</h2>
                <p>Formations professionnelles adaptées aux besoins de votre entreprise</p>
            </div>
        </div>
        <!-- Other carousel items... -->
    </div>
    <!-- Carousel controls... -->
</div>

<!-- Featured Courses -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Cours en vedette</h2>
        <div class="row g-4">
            <?php foreach ($featuredCourses as $course): ?>
            <div class="col-md-4">
                <div class="card h-100">
                    <img src="<?php echo SITE_URL; ?>/uploads/courses/<?php echo htmlspecialchars($course['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($course['title']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($course['short_description']); ?></p>
                        <a href="<?php echo SITE_URL; ?>/courses/course<?php echo $course['id']; ?>.php" class="btn btn-primary">En savoir plus</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="<?php echo SITE_URL; ?>/courses.php" class="btn btn-outline-primary btn-lg">Voir tous nos cours</a>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Ce que disent nos clients</h2>
        <div class="row">
            <?php foreach ($testimonials as $testimonial): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="mb-3 text-warning">
                            <?php echo str_repeat('<i class="bi bi-star-fill"></i>', $testimonial['rating']); ?>
                            <?php if ($testimonial['rating'] < 5) echo str_repeat('<i class="bi bi-star"></i>', 5 - $testimonial['rating']); ?>
                        </div>
                        <p class="card-text">"<?php echo htmlspecialchars($testimonial['content']); ?>"</p>
                        <div class="d-flex align-items-center mt-auto">
                            <img src="<?php echo SITE_URL; ?>/uploads/testimonials/<?php echo htmlspecialchars($testimonial['image']); ?>" alt="<?php echo htmlspecialchars($testimonial['name']); ?>" class="rounded-circle me-3" width="50">
                            <div>
                                <h6 class="mb-0"><?php echo htmlspecialchars($testimonial['name']); ?></h6>
                                <small class="text-muted"><?php echo htmlspecialchars($testimonial['position']); ?>, <?php echo htmlspecialchars($testimonial['company']); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
require_once 'includes/footer.php';
?>