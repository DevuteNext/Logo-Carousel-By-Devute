document.addEventListener('DOMContentLoaded', function() {
    const carousels = document.querySelectorAll('.logo-carousel');
    
    carousels.forEach(carousel => {
        const track = carousel.querySelector('.logo-track');
        const speed = carousel.dataset.speed || '25';
        
        // Set animation duration
        track.style.animationDuration = `${speed}s`;
        
        // Ensure enough slides for smooth infinite scroll
        const slides = [...track.children];
        const totalWidth = slides.reduce((acc, slide) => acc + slide.offsetWidth, 0);
        const viewportWidth = carousel.offsetWidth;
        
        // Clone slides until we have enough to fill at least 2x viewport
        while (track.offsetWidth < (viewportWidth * 3)) {
            slides.forEach(slide => {
                const clone = slide.cloneNode(true);
                track.appendChild(clone);
            });
        }

        // Handle visibility change to prevent animation glitches
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                track.style.animationPlayState = 'paused';
            } else {
                track.style.animationPlayState = 'running';
            }
        });
    });
});