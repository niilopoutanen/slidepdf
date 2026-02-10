import * as pdfjsLib from './pdfjs/pdf.js';
pdfjsLib.GlobalWorkerOptions.workerSrc =
    new URL('./pdfjs/pdf.worker.js', import.meta.url).toString();

const createSwiperInstance = (el, config) => {
    if (typeof Swiper !== 'undefined') {
        return Promise.resolve(new Swiper(el, config));
    }

    if (window.elementorFrontend && elementorFrontend.utils && elementorFrontend.utils.swiper) {
        return new elementorFrontend.utils.swiper(el, config);
    }

    return Promise.reject(new Error('Swiper API not available'));
};

function initSlidePDF(root) {
    if (root.dataset.initialized) return;
    root.dataset.initialized = 'true';

    const pdfUrl = root.dataset.pdf;
    const options = JSON.parse(root.dataset.swiperconfig || '{}');
    const isSingle = root.dataset.single === 'true';
    const pageNumber = parseInt(root.dataset.page || '1', 10);

    pdfjsLib.getDocument(pdfUrl).promise.then(async (pdf) => {
        if (isSingle) {
            const canvas = root.querySelector('canvas');
            if (!canvas) return;
            const page = await pdf.getPage(pageNumber);
            const viewport = page.getViewport({ scale: options.scale ?? 1.5 });

            canvas.width = viewport.width;
            canvas.height = viewport.height;
            canvas.style.width = '100%';
            canvas.style.height = 'auto';

            await page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;
        } else {
            const wrapper = root.querySelector('.swiper-wrapper');
            const swiper = createSwiperInstance(root, {
                slidesPerView: options.slidesPerView || 1,
                spaceBetween: options.spaceBetween || 10,
                loop: options.loop || false,
                speed: options.speed || 300,
                centeredSlides: options.centeredSlides || false,
                autoHeight: options.autoHeight || false,
                pagination: {
                    el: root.querySelector('.swiper-pagination'),
                    clickable: true,
                },
                navigation: {
                    nextEl: root.querySelector('.next'),
                    prevEl: root.querySelector('.previous'),
                },
            }).then(async (swiper) => {

                for (let i = 1; i <= pdf.numPages; i++) {
                    const slide = document.createElement('div');
                    slide.className = 'swiper-slide';
                    const pageDiv = document.createElement('div');
                    pageDiv.classList.add('page');

                    const canvas = document.createElement('canvas');
                    pageDiv.appendChild(canvas);
                    slide.appendChild(pageDiv);
                    wrapper.appendChild(slide);

                    const page = await pdf.getPage(i);
                    const viewport = page.getViewport({ scale: options.scale ?? 1 });

                    canvas.width = viewport.width;
                    canvas.height = viewport.height;
                    slide.style.aspectRatio = `${viewport.width} / ${viewport.height}`;

                    await page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;
                    swiper.update();
                }
            });

        }


    });
}

document.querySelectorAll('.slidepdf').forEach(initSlidePDF);

if (window.elementorFrontend) {
    window.addEventListener('elementor/frontend/init', () => {
        if (!window.elementorFrontend.hooks) return;

        window.elementorFrontend.hooks.addAction(
            'frontend/element_ready/slidepdf.default',
            ($scope) => {
                $scope.find('.slidepdf').each((_, el) => initSlidePDF(el));
            }
        );
    });
}
