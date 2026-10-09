const scentProfiles = {
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

export function initializeScentQuiz() {
    const scentQuiz = document.querySelector('[data-scent-quiz]');

    if (!scentQuiz) {
        return;
    }

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
        const profile = scentProfiles[match];

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
