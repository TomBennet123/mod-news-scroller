(function () {
    'use strict';

    function initialiseScroller(container) {
        const scroller = container.querySelector('.scroller');
        if (!scroller) {
            return;
        }

        const styles = getComputedStyle(container);
        const totalScrollWidth = parseFloat(styles.getPropertyValue('--total-scroll-width')) || scroller.scrollWidth;
        const animationDuration = parseFloat(styles.getPropertyValue('--animation-duration')) || 24;
        const hoverSpeedFactor = parseFloat(styles.getPropertyValue('--hover-speed-factor')) || 0.15;

        if (!totalScrollWidth || !animationDuration) {
            return;
        }

        const baseSpeed = totalScrollWidth / animationDuration; // px per second
        let offset = 0;
        let lastTimestamp = performance.now();
        let currentSpeedFactor = 1;
        let targetSpeedFactor = 1;

        scroller.classList.add('is-js-controlled');

        function step(timestamp) {
            const delta = timestamp - lastTimestamp;
            lastTimestamp = timestamp;

            const smoothing = Math.min(delta / 180, 1);
            currentSpeedFactor += (targetSpeedFactor - currentSpeedFactor) * smoothing;

            const distance = baseSpeed * currentSpeedFactor * (delta / 1000);
            offset += distance;

            if (offset >= totalScrollWidth) {
                offset -= totalScrollWidth;
            }

            scroller.style.transform = `translateX(${-offset}px)`;
            requestAnimationFrame(step);
        }

        container.addEventListener('mouseenter', function () {
            targetSpeedFactor = hoverSpeedFactor;
        });

        container.addEventListener('mouseleave', function () {
            targetSpeedFactor = 1;
        });

        requestAnimationFrame(function (timestamp) {
            lastTimestamp = timestamp;
            step(timestamp);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.scroller-container').forEach(initialiseScroller);
    });
})();
