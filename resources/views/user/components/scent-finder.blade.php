<section class="scent-finder container" aria-labelledby="scent-finder-title">
    <div class="scent-finder-media">
        <img src="{{ asset('images/essence-editorial.webp') }}"
            alt="Golden perfume bottle illuminated by warm afternoon light" loading="lazy" width="1024"
            height="1536">
        <span>CURATED FOR YOU / 01</span>
    </div>
    <div class="scent-finder-copy">
        <span class="eyebrow">A personal edit</span>
        <h2 id="scent-finder-title">Find your <em>scent.</em></h2>
        <p>Three quick questions. One fragrance direction shaped around how you want to feel.</p>
        <div class="scent-finder-meta" aria-label="Quiz details">
            <span><strong>03</strong> questions</span>
            <span><strong>01</strong> minute</span>
        </div>
        <a class="button button-light" href="{{ route('products.index') }}" data-scent-quiz-open
            aria-haspopup="dialog">
            Take the scent quiz @include('user.components.icon', ['name' => 'arrow'])
        </a>
    </div>
</section>

<dialog class="scent-quiz" data-scent-quiz aria-labelledby="scent-quiz-title"
    data-shop-url="{{ route('products.index') }}">
    <form class="scent-quiz-form" data-scent-quiz-form>
        <header class="scent-quiz-header">
            <div>
                <span class="eyebrow">Your scent profile</span>
                <span class="scent-quiz-count" data-scent-quiz-count>01 / 03</span>
            </div>
            <button class="scent-quiz-close" type="button" data-scent-quiz-close aria-label="Close scent quiz">×</button>
            <span class="scent-quiz-progress" aria-hidden="true"><span data-scent-quiz-progress></span></span>
        </header>

        <div class="scent-quiz-step" data-scent-quiz-step>
            <fieldset>
                <legend id="scent-quiz-title">How do you want to <em>feel?</em></legend>
                <p>Choose the energy you want your fragrance to carry.</p>
                <div class="scent-quiz-options">
                    <label><input type="radio" name="feeling" value="fresh" data-profile="fresh"><span><strong>Fresh</strong><small>Bright and effortless</small></span></label>
                    <label><input type="radio" name="feeling" value="warm" data-profile="warm"><span><strong>Warm</strong><small>Soft and comforting</small></span></label>
                    <label><input type="radio" name="feeling" value="bold" data-profile="bold"><span><strong>Bold</strong><small>Magnetic and noticed</small></span></label>
                </div>
            </fieldset>
        </div>

        <div class="scent-quiz-step" data-scent-quiz-step hidden>
            <fieldset>
                <legend>Where will you wear <em>it?</em></legend>
                <p>Think about the moment you want this scent to belong to.</p>
                <div class="scent-quiz-options">
                    <label><input type="radio" name="moment" value="everyday" data-profile="fresh"><span><strong>Every day</strong><small>Easy and versatile</small></span></label>
                    <label><input type="radio" name="moment" value="work" data-profile="warm"><span><strong>Work mode</strong><small>Polished and composed</small></span></label>
                    <label><input type="radio" name="moment" value="evening" data-profile="bold"><span><strong>After dark</strong><small>Rich and memorable</small></span></label>
                </div>
            </fieldset>
        </div>

        <div class="scent-quiz-step" data-scent-quiz-step hidden>
            <fieldset>
                <legend>Which notes pull you <em>in?</em></legend>
                <p>Trust your instinct—there is no wrong answer.</p>
                <div class="scent-quiz-options">
                    <label><input type="radio" name="notes" value="citrus" data-profile="fresh"><span><strong>Citrus</strong><small>Cool, crisp and clean</small></span></label>
                    <label><input type="radio" name="notes" value="vanilla" data-profile="warm"><span><strong>Vanilla</strong><small>Creamy, smooth warmth</small></span></label>
                    <label><input type="radio" name="notes" value="spice" data-profile="bold"><span><strong>Spice</strong><small>Deep, smoky intensity</small></span></label>
                </div>
            </fieldset>
        </div>

        <div class="scent-quiz-result" data-scent-quiz-result hidden aria-live="polite">
            <span class="eyebrow">Your fragrance direction</span>
            <h3 data-scent-result-title tabindex="-1"></h3>
            <p data-scent-result-copy></p>
            <a class="button button-dark" href="{{ route('products.index') }}" data-scent-result-link>
                Meet your matches @include('user.components.icon', ['name' => 'arrow'])
            </a>
            <button class="text-button" type="button" data-scent-quiz-restart>Start again</button>
        </div>

        <footer class="scent-quiz-actions" data-scent-quiz-actions>
            <button class="text-button" type="button" data-scent-quiz-back disabled>Back</button>
            <button class="button button-dark" type="button" data-scent-quiz-next disabled>
                Continue @include('user.components.icon', ['name' => 'arrow'])
            </button>
        </footer>
    </form>
</dialog>
