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
