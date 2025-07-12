// Enrollment form handling
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('enrollmentForm');
    
    if (form) {
        // Set course info if coming from course page
        const urlParams = new URLSearchParams(window.location.search);
        const courseId = urlParams.get('course_id');
        
        if (courseId) {
            // Could fetch course details via AJAX here
            document.getElementById('courseId').value = courseId;
        }
        
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    }
});