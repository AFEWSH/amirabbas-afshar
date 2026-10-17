const contactDialog = document.querySelector('#contact-dialog');

if (contactDialog) {
    const opener = document.querySelector('[data-contact-open]');
    let previousOverflow;

    const openContact = () => {
        if (contactDialog.open) return;
        previousOverflow = document.documentElement.style.overflow;
        contactDialog.showModal();
        document.documentElement.style.overflow = 'hidden';
        (contactDialog.querySelector('[aria-invalid="true"]') ?? contactDialog.querySelector('#contact-name'))?.focus();
    };

    opener?.addEventListener('click', openContact);
    contactDialog.querySelector('[data-contact-close]')?.addEventListener('click', () => contactDialog.close());
    contactDialog.addEventListener('click', (event) => {
        if (event.target !== contactDialog) return;
        const bounds = contactDialog.getBoundingClientRect();
        if (event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom) {
            contactDialog.close();
        }
    });
    contactDialog.addEventListener('close', () => {
        document.documentElement.style.overflow = previousOverflow ?? '';
        opener?.focus();
    });

    if (contactDialog.hasAttribute('data-auto-open')) openContact();
}
