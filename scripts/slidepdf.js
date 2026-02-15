import * as pdfjsLib from './pdfjs/pdf.js';
pdfjsLib.GlobalWorkerOptions.workerSrc = SlidePDFData.workerUrl;


const createSwiperInstance = (el, config) => {
    if (typeof window.SlidePDFSwiper !== 'undefined') {
        return Promise.resolve(new window.SlidePDFSwiper(el, config));
    }
    return Promise.reject(new Error('Swiper API not available'));
};

function initSlidePDF(root) {
    if (root.dataset.initialized) return;
    root.dataset.initialized = 'true';
    root.classList.add('is-loading');

    const pdfUrl = root.dataset.pdf;
    const options = JSON.parse(root.dataset.swiperconfig || '{}');
    const isSingle = root.dataset.single === 'true';
    const pageNumber = parseInt(root.dataset.page || '1', 10);

    const loader = root.querySelector(".loading");

    pdfjsLib.getDocument({
        url: pdfUrl,
        wasmUrl: SlidePDFData.wasmUrl,
    }).promise.then(async (pdf) => {
        try {
            if (isSingle) {
                const canvas = document.createElement("canvas");
                const page = await pdf.getPage(pageNumber);
                const viewport = page.getViewport({ scale: options.scale ?? 1.5 });

                canvas.width = viewport.width;
                canvas.height = viewport.height;
                canvas.style.width = '100%';
                canvas.style.height = 'auto';

                await page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;
                const container = root.querySelector(".page");
                container.appendChild(canvas);
                loader.remove();
            } else {
                const wrapper = root.querySelector('.swiper-wrapper');
                const swiper = await createSwiperInstance(root, {
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
                    freeMode: {
                        enabled: true,
                        sticky: true,
                        momentum: true,
                    },
                    navigation: {
                        nextEl: root.querySelector('.next'),
                        prevEl: root.querySelector('.previous'),
                    },
                })

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

                    if (i === 1 && loader) {
                        const index = Array.from(swiper.slides).indexOf(loader);
                        if (index !== -1) {
                            swiper.removeSlide(index);
                        }
                    }
                }


            }
        }

        catch (err) {
            console.error('SlidePDF render error:', err);
        } finally {
            root.classList.remove('is-loading');
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
