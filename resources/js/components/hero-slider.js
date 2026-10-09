export function initializeHeroSliders() {
    document.querySelectorAll('[data-hero-slider]').forEach((slider) => {
        const slides = [...slider.querySelectorAll('[data-hero-slide]')];

        if (slides.length === 0) {
            return;
        }

        const dots = [...slider.querySelectorAll('[data-hero-dot]')];
        const previousButton = slider.querySelector('[data-hero-previous]');
        const nextButton = slider.querySelector('[data-hero-next]');
        const kicker = slider.querySelector('[data-hero-kicker]');
        const title = slider.querySelector('[data-hero-title]');
        const count = slider.querySelector('[data-hero-count]');
        const number = slider.querySelector('[data-hero-number]');
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        let currentSlide = 0;
        let autoplayTimer;
        let pointerStartX;

        const showSlide = (index) => {
            currentSlide = (index + slides.length) % slides.length;

            slides.forEach((slide, slideIndex) => {
                const isActive = slideIndex === currentSlide;
                slide.classList.toggle('is-active', isActive);
                slide.setAttribute('aria-hidden', String(!isActive));
            });

            dots.forEach((dot, dotIndex) => {
                const isActive = dotIndex === currentSlide;
                dot.classList.toggle('is-active', isActive);
                dot.toggleAttribute('aria-current', isActive);
            });

            const activeSlide = slides[currentSlide];
            const slideNumber = String(currentSlide + 1).padStart(2, '0');
            kicker.textContent = activeSlide.dataset.kicker;
            title.textContent = activeSlide.dataset.title;
            count.textContent = `${slideNumber} / ${String(slides.length).padStart(2, '0')}`;
            number.textContent = slideNumber;
        };

        const stopAutoplay = () => window.clearInterval(autoplayTimer);
        const startAutoplay = () => {
            stopAutoplay();

            if (!prefersReducedMotion) {
                autoplayTimer = window.setInterval(() => showSlide(currentSlide + 1), 5500);
            }
        };
        const moveSlide = (direction) => {
            showSlide(currentSlide + direction);
            startAutoplay();
        };

        previousButton?.addEventListener('click', () => moveSlide(-1));
        nextButton?.addEventListener('click', () => moveSlide(1));
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                showSlide(index);
                startAutoplay();
            });
        });
        slider.addEventListener('keydown', (event) => {
            if (event.key === 'ArrowLeft') {
                moveSlide(-1);
            }

            if (event.key === 'ArrowRight') {
                moveSlide(1);
            }
        });
        slider.addEventListener('pointerdown', (event) => {
            pointerStartX = event.clientX;
        });
        slider.addEventListener('pointerup', (event) => {
            if (pointerStartX === undefined) {
                return;
            }

            const distance = event.clientX - pointerStartX;
            pointerStartX = undefined;

            if (Math.abs(distance) >= 50) {
                moveSlide(distance < 0 ? 1 : -1);
            }
        });
        slider.addEventListener('pointercancel', () => {
            pointerStartX = undefined;
        });
        slider.addEventListener('mouseenter', stopAutoplay);
        slider.addEventListener('mouseleave', startAutoplay);
        slider.addEventListener('focusin', stopAutoplay);
        slider.addEventListener('focusout', (event) => {
            if (!slider.contains(event.relatedTarget)) {
                startAutoplay();
            }
        });
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopAutoplay();
            } else {
                startAutoplay();
            }
        });

        showSlide(0);
        startAutoplay();
    });
}
