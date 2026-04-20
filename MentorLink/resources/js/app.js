import './bootstrap';

const formatLocalDate = (isoDate) => {
    const date = new Date(isoDate);

    if (Number.isNaN(date.getTime())) {
        return 'Synchronisation indisponible';
    }

    return new Intl.DateTimeFormat('fr-FR', {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(date);
};

const animateMetric = (element, targetValue) => {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReducedMotion) {
        element.textContent = `${targetValue}`;
        return;
    }

    const duration = 900;
    const startTime = performance.now();

    const step = (currentTime) => {
        const progress = Math.min((currentTime - startTime) / duration, 1);
        const nextValue = Math.round(progress * targetValue);
        element.textContent = `${nextValue}`;

        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };

    window.requestAnimationFrame(step);
};

const revealSections = () => {
    const items = document.querySelectorAll('[data-reveal]');

    if (!items.length) {
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        },
        {
            threshold: 0.2,
        },
    );

    items.forEach((item) => observer.observe(item));
};

const updateMetric = (name, value) => {
    const element = document.querySelector(`[data-metric-value="${name}"]`);

    if (!element || typeof value !== 'number') {
        return;
    }

    animateMetric(element, value);
};

const hydratePlatformOverview = async () => {
    const root = document.querySelector('[data-platform-endpoint]');

    if (!root) {
        return;
    }

    const endpoint = root.dataset.platformEndpoint;

    if (!endpoint) {
        return;
    }

    try {
        const response = await window.axios.get(endpoint);
        const data = response.data ?? {};
        const metrics = data.metrics ?? {};

        updateMetric('mentors', metrics.mentors);
        updateMetric('active-mentorships', metrics.active_mentorships);
        updateMetric('upcoming-sessions', metrics.upcoming_sessions);

        const status = document.querySelector('[data-platform-status]');
        if (status && data.status) {
            status.textContent = String(data.status).replace(/^./, (value) => value.toUpperCase());
        }

        const stackCount = document.querySelector('[data-stack-count]');
        if (stackCount && Array.isArray(data.stack)) {
            stackCount.textContent = data.database ? `${data.database}` : `${data.stack.length} modules`;
        }

        const healthLabel = document.querySelector('[data-health-label]');
        if (healthLabel) {
            healthLabel.textContent = 'Connexion backend confirmee';
        }

        const syncLabel = document.querySelector('[data-sync-label]');
        if (syncLabel && data.generated_at) {
            syncLabel.textContent = `Derniere synchronisation: ${formatLocalDate(data.generated_at)}`;
        }

        const stackItems = document.querySelector('[data-stack-items]');
        if (stackItems && Array.isArray(data.stack)) {
            stackItems.innerHTML = data.stack
                .map((item) => `<span>${item}</span>`)
                .join('');
        }
    } catch {
        const healthLabel = document.querySelector('[data-health-label]');
        const syncLabel = document.querySelector('[data-sync-label]');
        const healthDot = document.querySelector('[data-health-dot]');

        if (healthLabel) {
            healthLabel.textContent = 'Backend non verifie localement';
        }

        if (syncLabel) {
            syncLabel.textContent = 'Derniere synchronisation: impossible sans serveur actif';
        }

        if (healthDot) {
            healthDot.style.background = '#d97706';
            healthDot.style.boxShadow = '0 0 0 6px rgba(217, 119, 6, 0.16)';
        }
    }
};

document.addEventListener('DOMContentLoaded', () => {
    revealSections();
    hydratePlatformOverview();
});
