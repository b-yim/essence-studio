document.querySelectorAll('[data-password-toggle]').forEach((button) => {
    const input = document.getElementById(button.dataset.passwordToggle);
    const label = input.labels[0]?.textContent.trim().toLowerCase() || 'password';

    button.hidden = false;
    button.setAttribute('aria-pressed', 'false');
    button.addEventListener('click', () => {
        const isVisible = input.type === 'password';
        input.type = isVisible ? 'text' : 'password';
        button.textContent = isVisible ? 'Hide' : 'Show';
        button.setAttribute('aria-label', `${isVisible ? 'Hide' : 'Show'} ${label}`);
        button.setAttribute('aria-pressed', String(isVisible));
    });
});

document.querySelectorAll('[data-quantity]').forEach((control) => {
    const input = control.querySelector('input');
    const buttons = control.querySelectorAll('[data-step]');
    const updateButtons = () => {
        buttons.forEach((button) => {
            button.disabled = Number(button.dataset.step) < 0
                ? Number(input.value) <= Number(input.min)
                : Number(input.value) >= Number(input.max);
        });
    };

    buttons.forEach((button) => {
        button.hidden = false;
        button.addEventListener('click', () => {
            const quantity = Number(input.value) || Number(input.min);
            input.value = Math.min(Number(input.max), Math.max(Number(input.min), quantity + Number(button.dataset.step)));
            input.dispatchEvent(new Event('change', { bubbles: true }));
        });
    });
    input.addEventListener('input', updateButtons);
    input.addEventListener('change', updateButtons);
    updateButtons();
});

document.querySelectorAll('[data-product-detail]').forEach((detail) => {
    const quantity = detail.querySelector('input[name="quantity"]');
    const price = detail.querySelector('[data-variant-price]');
    const stockStatus = detail.querySelector('[data-stock-status]');
    const updateVariant = () => {
        const variant = detail.querySelector('input[name="product_variant_id"]:checked');
        if (!variant || !quantity) {
            return;
        }

        const stock = Number(variant.dataset.stock);
        price.textContent = variant.dataset.price;
        stockStatus.textContent = stock > 0 ? 'Available to order' : 'Currently unavailable';
        quantity.max = String(Math.max(1, Math.min(99, stock)));
        quantity.value = Math.min(Number(quantity.max), Math.max(1, Number(quantity.value) || 1));
        quantity.dispatchEvent(new Event('change', { bubbles: true }));
    };

    price.setAttribute('aria-live', 'polite');
    detail.querySelectorAll('input[name="product_variant_id"]').forEach((variant) => {
        variant.addEventListener('change', updateVariant);
    });
    updateVariant();
});

const headerPopovers = document.querySelectorAll('.header-popover');
headerPopovers.forEach((popover) => {
    popover.addEventListener('toggle', () => {
        if (popover.open) {
            headerPopovers.forEach((other) => {
                if (other !== popover) {
                    other.open = false;
                }
            });
        }
    });
});
document.addEventListener('click', (event) => {
    headerPopovers.forEach((popover) => {
        if (!popover.contains(event.target)) {
            popover.open = false;
        }
    });
});
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        headerPopovers.forEach((popover) => {
            if (popover.open) {
                popover.open = false;
                popover.querySelector('summary').focus();
            }
        });
    }
});

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

const scentQuiz = document.querySelector('[data-scent-quiz]');

if (scentQuiz) {
    const form = scentQuiz.querySelector('[data-scent-quiz-form]');
    const steps = [...scentQuiz.querySelectorAll('[data-scent-quiz-step]')];
    const actions = scentQuiz.querySelector('[data-scent-quiz-actions]');
    const backButton = scentQuiz.querySelector('[data-scent-quiz-back]');
    const nextButton = scentQuiz.querySelector('[data-scent-quiz-next]');
    const count = scentQuiz.querySelector('[data-scent-quiz-count]');
    const progress = scentQuiz.querySelector('[data-scent-quiz-progress]');
    const result = scentQuiz.querySelector('[data-scent-quiz-result]');
    const resultTitle = scentQuiz.querySelector('[data-scent-result-title]');
    const resultCopy = scentQuiz.querySelector('[data-scent-result-copy]');
    const resultLink = scentQuiz.querySelector('[data-scent-result-link]');
    const profiles = {
        fresh: {
            title: 'Fresh & Energetic',
            copy: 'You lean toward crisp citrus, marine freshness, and aromatic woods that feel effortless from morning onward.',
            category: 'fresh-aquatic',
        },
        warm: {
            title: 'Warm & Grounded',
            copy: 'You are drawn to amber, vanilla, and smooth woods—comfortable scents with a quietly confident trail.',
            category: 'warm-spiced',
        },
        bold: {
            title: 'Bold & Magnetic',
            copy: 'You suit deeper spice, smoked sweetness, and memorable evening scents that linger after you leave.',
            category: 'night-out',
        },
    };
    let currentStep = 0;

    const showStep = (index) => {
        currentStep = index;
        result.hidden = true;
        actions.hidden = false;
        steps.forEach((step, stepIndex) => {
            step.hidden = stepIndex !== currentStep;
        });
        count.textContent = `0${currentStep + 1} / 03`;
        progress.style.width = `${((currentStep + 1) / steps.length) * 100}%`;
        backButton.disabled = currentStep === 0;
        nextButton.disabled = !steps[currentStep].querySelector('input:checked');
        nextButton.firstChild.textContent = currentStep === steps.length - 1 ? 'See my result ' : 'Continue ';
    };

    const showResult = () => {
        const scores = { fresh: 0, warm: 0, bold: 0 };
        const answers = [...form.querySelectorAll('input:checked')];
        answers.forEach((answer) => {
            scores[answer.dataset.profile] += 1;
        });
        const firstChoice = answers[0]?.dataset.profile || 'fresh';
        const match = Object.keys(scores).reduce((best, profile) => {
            if (scores[profile] > scores[best]) {
                return profile;
            }

            return scores[profile] === scores[best] && profile === firstChoice ? profile : best;
        }, firstChoice);
        const profile = profiles[match];

        steps.forEach((step) => {
            step.hidden = true;
        });
        actions.hidden = true;
        result.hidden = false;
        count.textContent = 'YOUR MATCH';
        progress.style.width = '100%';
        resultTitle.textContent = profile.title;
        resultCopy.textContent = profile.copy;
        resultLink.href = `${scentQuiz.dataset.shopUrl}?category=${encodeURIComponent(profile.category)}`;
        resultTitle.focus({ preventScroll: true });
    };

    document.querySelectorAll('[data-scent-quiz-open]').forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            form.reset();
            showStep(0);
            scentQuiz.showModal();
        });
    });

    scentQuiz.querySelector('[data-scent-quiz-close]').addEventListener('click', () => scentQuiz.close());
    scentQuiz.querySelector('[data-scent-quiz-restart]').addEventListener('click', () => {
        form.reset();
        showStep(0);
    });
    scentQuiz.addEventListener('click', (event) => {
        if (event.target === scentQuiz) {
            scentQuiz.close();
        }
    });
    form.addEventListener('change', () => {
        nextButton.disabled = !steps[currentStep].querySelector('input:checked');
    });
    backButton.addEventListener('click', () => showStep(Math.max(0, currentStep - 1)));
    nextButton.addEventListener('click', () => {
        if (currentStep === steps.length - 1) {
            showResult();
            return;
        }

        showStep(currentStep + 1);
    });
}
