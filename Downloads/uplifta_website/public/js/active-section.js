// Highlight active nav link on scroll and click, and use pushState for clean URLs

document.addEventListener('DOMContentLoaded', function () {
    const navLinks = document.querySelectorAll('#navLinks .nav-link');
    const sections = Array.from(navLinks).map(link => document.querySelector(link.getAttribute('href')));

    // Helper: Remove all active classes
    function clearActive() {
        navLinks.forEach(link => {
            link.classList.remove('text-blue-700', 'font-semibold', 'border-b-2', 'border-blue-700', 'active');
            link.classList.add('text-gray-800');
        });
    }

    // Helper: Set active class
    function setActive(link) {
        clearActive();
        link.classList.add('text-blue-700', 'font-semibold', 'border-b-2', 'border-blue-700', 'active');
        link.classList.remove('text-gray-800');
    }

    // On click, scroll to section and update URL (no #)
    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = link.getAttribute('href').replace('#', '');
            const target = document.getElementById(targetId);
            if (target) {
                window.scrollTo({
                    top: target.offsetTop - 80, // adjust for fixed header
                    behavior: 'smooth'
                });
                setActive(link);
                // Update URL without #
                window.history.pushState({}, '', window.location.pathname + (targetId === 'home' ? '' : '?section=' + targetId));
            }
        });
    });

    // On scroll, highlight current section
    window.addEventListener('scroll', function () {
        let current = 'home';
        sections.forEach((section, i) => {
            if (section && window.scrollY + 100 >= section.offsetTop) {
                current = navLinks[i].getAttribute('href').replace('#', '');
            }
        });
        navLinks.forEach(link => {
            if (link.getAttribute('href').replace('#', '') === current) {
                setActive(link);
            }
        });
    });

    // On load, scroll to section if ?section= is present
    const params = new URLSearchParams(window.location.search);
    const section = params.get('section');
    if (section) {
        const target = document.getElementById(section);
        if (target) {
            window.scrollTo({
                top: target.offsetTop - 80,
                behavior: 'smooth'
            });
            navLinks.forEach(link => {
                if (link.getAttribute('href').replace('#', '') === section) {
                    setActive(link);
                }
            });
        }
    }
});
