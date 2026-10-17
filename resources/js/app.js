const themeToggle = document.querySelector('[data-theme-toggle]');

if (themeToggle) {
    const applyTheme = (theme) => {
        const isLight = theme === 'light';
        document.documentElement.dataset.theme = theme;
        document.documentElement.style.colorScheme = theme;
        document.querySelector('meta[name="theme-color"]').content = isLight ? '#f7f8fa' : '#111719';
        themeToggle.setAttribute('aria-pressed', String(isLight));
        themeToggle.setAttribute('aria-label', isLight ? themeToggle.dataset.darkLabel : themeToggle.dataset.lightLabel);
        themeToggle.title = themeToggle.getAttribute('aria-label');
    };

    applyTheme(document.documentElement.dataset.theme);
    themeToggle.hidden = false;
    themeToggle.addEventListener('click', () => {
        const theme = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
        applyTheme(theme);
        try { localStorage.setItem('portfolio-theme', theme); } catch {}
    });
    window.addEventListener('storage', (event) => {
        if (event.key === 'portfolio-theme' || event.key === null) {
            applyTheme(event.newValue === 'light' ? 'light' : 'dark');
        }
    });
}

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
