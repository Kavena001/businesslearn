<footer class="py-3" style="background-color: #65697d; color: white;">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>Formation Professionnelle</h5>
                <p class="small">Améliorez vos compétences pour exceller dans votre carrière.</p>
            </div>
            <div class="col-md-4">
                <h5>Liens rapides</h5>
                <ul class="list-unstyled small">
                    <li><a href="<?php echo SITE_URL; ?>/index.php" class="text-white">Accueil</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/courses.php" class="text-white">Cours</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/about.php" class="text-white">À propos</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/contact.php" class="text-white">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Contact</h5>
                <address class="small">
                    Email: <?php echo htmlspecialchars($siteSettings['contact_email'] ?? 'info@formationpro.com'); ?><br>
                    Téléphone: <?php echo htmlspecialchars($siteSettings['contact_phone'] ?? '+1 234 567 8900'); ?>
                </address>
            </div>
        </div>
        <div class="text-center mt-2">
            <p class="small mb-0">&copy; <?php echo date('Y'); ?> Formation Professionnelle. Tous droits réservés.</p>
        </div>
    </div>
</footer>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo SITE_URL; ?>/js/script.js"></script>
<script src="<?php echo SITE_URL; ?>/js/navbar-scroll.js"></script>
</body>
</html>