import Lenis from 'lenis';
import { animate, inView } from 'motion';

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if (!reduceMotion) {
    const lenis = new Lenis({
        anchors: { offset: -100 },
        autoRaf: true,
        lerp: 0.1,
    });

    document.documentElement.dataset.smoothScroll = 'enabled';

    const heroCopy = document.querySelector('[data-hero-copy]');
    const heroRibbon = document.querySelector('[data-hero-ribbon]');

    if (heroCopy) {
        animate(heroCopy, {
            opacity: [0, 1],
            y: [18, 0],
            filter: ['blur(7px)', 'blur(0px)'],
        }, {
            duration: 0.7,
            ease: [0.16, 1, 0.3, 1],
        });
    }

    if (heroRibbon) {
        animate(heroRibbon, {
            opacity: [0, 1],
            y: [24, 0],
            filter: ['blur(8px)', 'blur(0px)'],
        }, {
            duration: 0.75,
            delay: 0.14,
            ease: [0.16, 1, 0.3, 1],
        });
    }

    const benefits = [...document.querySelectorAll('[data-benefit-reveal]')];
    inView('[data-benefit-reveal]', (element) => {
        const index = benefits.indexOf(element);
        animate(element, {
            opacity: [0, 1],
            y: [16, 0],
            filter: ['blur(5px)', 'blur(0px)'],
        }, {
            duration: 0.5,
            delay: Math.max(index, 0) % 4 * 0.06,
            ease: [0.16, 1, 0.3, 1],
        });
    }, {
        amount: 0.3,
        margin: '0px 0px -10% 0px',
    });

    window.addEventListener('pagehide', () => {
        lenis.destroy();
    }, { once: true });
}
