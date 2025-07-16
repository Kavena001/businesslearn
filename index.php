<?php
require 'includes/config.php';
require 'includes/db.php';
require 'includes/header.php';

// Get featured courses from database
$featured_courses = $db->getRows("SELECT * FROM courses WHERE featured = 1 ORDER BY title LIMIT 3");

// Get testimonials from database
$testimonials = $db->getRows("SELECT * FROM testimonials WHERE featured = 1 ORDER BY RAND() LIMIT 3");
?>

<!---------------------------------- Banner Carousel -------------------------------------------->
<div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="img/banner/banner1.jpg" class="d-block w-100" alt="Formation professionnelle">
            <div class="carousel-caption d-none d-md-block">
                <h2>Développez les compétences de votre équipe</h2>
                <p>Formations professionnelles adaptées aux besoins de votre entreprise</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="img/banner/banner2.jpg" class="d-block w-100" alt="Développement des compétences">
            <div class="carousel-caption d-none d-md-block">
                <h2>Formations certifiantes</h2>
                <p>Améliorez la productivité et l'efficacité de vos employés</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="img/banner/banner3.jpg" class="d-block w-100" alt="Apprentissage en ligne">
            <div class="carousel-caption d-none d-md-block">
                <h2>Apprentissage flexible</h2>
                <p>Formations en ligne et en présentiel adaptées à vos horaires</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="img/banner/banner4.jpg" class="d-block w-100" alt="Équipe professionnelle">
            <div class="carousel-caption d-none d-md-block">
                <h2>Experts du secteur</h2>
                <p>Formateurs expérimentés avec une expertise pratique</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="img/banner/banner5.jpg" class="d-block w-100" alt="Certification">
            <div class="carousel-caption d-none d-md-block">
                <h2>Investissez dans votre capital humain</h2>
                <p>Des solutions de formation qui génèrent un retour sur investissement tangible</p>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Précédent</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Suivant</span>
    </button>
</div>

<!-- How We Help Businesses Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Comment nous aidons les entreprises</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                            <i class="bi bi-graph-up text-primary"></i>
                        </div>
                        <h4>Amélioration de la productivité</h4>
                        <p>Nos formations ciblées permettent à vos employés d'acquérir des compétences pratiques qui améliorent immédiatement leur performance au travail.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                            <i class="bi bi-people text-primary"></i>
                        </div>
                        <h4>Rétention des talents</h4>
                        <p>Les employés qui bénéficient de développement professionnel sont plus engagés et plus susceptibles de rester dans votre entreprise.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                            <i class="bi bi-lightbulb text-primary"></i>
                        </div>
                        <h4>Innovation continue</h4>
                        <p>En formant vos équipes aux dernières tendances et technologies, vous favorisez une culture d'innovation dans votre organisation.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Courses -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Cours en vedette</h2>
        
        <?php if (!empty($featured_courses)): ?>
            <div class="row g-4">
                <?php foreach ($featured_courses as $course): ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <?php if (!empty($course['image'])): ?>
                            <img src="uploads/courses/<?= htmlspecialchars($course['image']) ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($course['title']) ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($course['title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($course['short_description']) ?></p>
                            <a href="course.php?id=<?= $course['id'] ?>" 
                               class="btn btn-primary">
                               En savoir plus
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">Aucun cours vedette pour le moment.</div>
        <?php endif; ?>
        
        <div class="text-center mt-5">
            <a href="courses.php" class="btn btn-outline-primary btn-lg">Voir tous nos cours</a>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Ce que disent nos clients</h2>
        
        <?php if (!empty($testimonials)): ?>
            <div class="row">
                <?php foreach ($testimonials as $testimonial): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="mb-3 text-warning">
                                <?= str_repeat('<i class="bi bi-star-fill"></i>', $testimonial['rating']) ?>
                                <?= ($testimonial['rating'] < 5) ? str_repeat('<i class="bi bi-star"></i>', 5 - $testimonial['rating']) : '' ?>
                            </div>
                            <p class="card-text">"<?= htmlspecialchars($testimonial['content']) ?>"</p>
                            <div class="d-flex align-items-center mt-auto">
                                <?php if (!empty($testimonial['image'])): ?>
                                    <img src="uploads/testimonials/<?= htmlspecialchars($testimonial['image']) ?>" 
                                         alt="<?= htmlspecialchars($testimonial['name']) ?>" 
                                         class="rounded-circle me-3" width="50">
                                <?php endif; ?>
                                <div>
                                    <h6 class="mb-0"><?= htmlspecialchars($testimonial['name']) ?></h6>
                                    <small class="text-muted">
                                        <?= htmlspecialchars($testimonial['position']) ?>, 
                                        <?= htmlspecialchars($testimonial['company']) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Aucun témoignage disponible pour le moment.</div>
        <?php endif; ?>
    </div>
</section>

<!-- Call to Action 
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="mb-4">Prêt à transformer les compétences de votre équipe?</h2>
        <p class="lead mb-4">Contactez-nous pour une consultation gratuite et découvrez comment nous pouvons répondre aux besoins spécifiques de votre entreprise.</p>
        <a href="contact.php" class="btn btn-light btn-lg me-3">Nous contacter</a>
        <a href="courses.php" class="btn btn-outline-light btn-lg">Explorer nos cours</a>
    </div>
</section>
-->
<?php
require 'includes/footer.php';
?>

<script>
// Initialize carousel with autoplay
document.addEventListener('DOMContentLoaded', function() {
    var carousel = new bootstrap.Carousel(document.getElementById('bannerCarousel'), {
        interval: 5000,
        wrap: true,
        pause: false
    });
    
    // Ensure all images are loaded before carousel starts
    var carouselImages = document.querySelectorAll('#bannerCarousel img');
    var imagesLoaded = 0;
    
    carouselImages.forEach(function(img) {
        if (img.complete) {
            imagesLoaded++;
        } else {
            img.addEventListener('load', function() {
                imagesLoaded++;
                if (imagesLoaded === carouselImages.length) {
                    carousel.cycle();
                }
            });
        }
    });
    
    if (imagesLoaded === carouselImages.length) {
        carousel.cycle();
    }
});
</script>