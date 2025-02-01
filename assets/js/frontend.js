document.addEventListener('DOMContentLoaded', function() {
    const carousels = document.querySelectorAll('.logo-carousel');
    
    carousels.forEach(carousel => {
        const track = carousel.querySelector('.logo-carousel-track');
        
        // Clone items for infinite scroll
        const items = track.innerHTML;
        track.innerHTML = items + items;
        
        // Pause on hover
        carousel.addEventListener('mouseenter', () => {
            track.style.animationPlayState = 'paused';
        });
        
        carousel.addEventListener('mouseleave', () => {
            track.style.animationPlayState = 'running';
        });
    });
});