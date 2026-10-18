import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const VARIANTS = {
    danger: {
        icon: 'warning',
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Sí, dar de baja',
    },
    warning: {
        icon: 'warning',
        confirmButtonColor: '#ea580c',
        confirmButtonText: 'Sí, continuar',
    },
    question: {
        icon: 'question',
        confirmButtonColor: '#ea580c',
        confirmButtonText: 'Sí, confirmar',
    },
};

function resolveConfirmTarget(element) {
    if (element?.matches?.('[data-confirm]')) {
        return element;
    }

    return element?.closest?.('[data-confirm]') ?? null;
}

function buildSwalOptions(target) {
    const variant = VARIANTS[target.dataset.confirmVariant] ?? VARIANTS.warning;
    const title = target.dataset.confirmTitle ?? '¿Está seguro?';
    const text = target.dataset.confirm ?? '';

    return {
        title,
        text,
        icon: variant.icon,
        showCancelButton: true,
        confirmButtonColor: variant.confirmButtonColor,
        cancelButtonColor: '#737373',
        confirmButtonText: target.dataset.confirmConfirm ?? variant.confirmButtonText,
        cancelButtonText: target.dataset.confirmCancel ?? 'Cancelar',
        reverseButtons: true,
        focusCancel: true,
    };
}

async function askConfirmation(target) {
    const result = await Swal.fire(buildSwalOptions(target));

    return result.isConfirmed;
}

function bindConfirmActions() {
    document.addEventListener('click', async (event) => {
        const link = event.target.closest('a[data-confirm]');

        if (!link) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        if (await askConfirmation(link)) {
            window.location.assign(link.href);
        }
    });

    document.addEventListener(
        'submit',
        async (event) => {
            const form = resolveConfirmTarget(event.target);

            if (!form || form.dataset.confirmBypass === '1') {
                return;
            }

            event.preventDefault();
            event.stopImmediatePropagation();

            if (await askConfirmation(form)) {
                form.dataset.confirmBypass = '1';
                form.requestSubmit();
            }
        },
        true,
    );
}

bindConfirmActions();

export { Swal };
