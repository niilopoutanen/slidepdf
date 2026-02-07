import * as pdfjsLib from './pdfjs/pdf.js';
pdfjsLib.GlobalWorkerOptions.workerSrc =
    new URL('./pdfjs/pdf.worker.js', import.meta.url).toString();

document.querySelectorAll('.slidepdf').forEach(async (root) => {
    const pdfUrl = root.dataset.pdf;
    const options = JSON.parse(root.dataset.swiperconfig || '{}');
    const isSingle = root.dataset.single === 'true';
    const pageNumber = parseInt(root.dataset.page || '1', 10);

    const pdf = await pdfjsLib.getDocument(pdfUrl).promise;

    if (isSingle) {
        const canvas = root.querySelector('canvas');
        if (!canvas) {
            console.warn('No canvas found in .slidepdf.single for rendering single page.');
            return;
        }

        const page = await pdf.getPage(pageNumber);
        const viewport = page.getViewport({ scale: options.scale ?? 1.5 });

        canvas.width = viewport.width;
        canvas.height = viewport.height;
        canvas.style.width = '100%';
        canvas.style.height = 'auto';

        await page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;
    } else {
        const wrapper = root.querySelector('.swiper-wrapper');
        const swiper = new Swiper(root, {
            slidesPerView: options.slidesPerView ?? 1,
            spaceBetween: options.spaceBetween ?? 10,
            loop: options.loop ?? false,
            pagination: {
                el: root.querySelector('.swiper-pagination'),
                clickable: true,
            },
            navigation: {
                nextEl: root.querySelector('.next'),
                prevEl: root.querySelector('.previous'),
            },
        });

        for (let i = 1; i <= pdf.numPages; i++) {
            const slide = document.createElement('div');
            slide.className = 'swiper-slide';

            const canvas = document.createElement('canvas');
            slide.appendChild(canvas);
            wrapper.appendChild(slide);

            const page = await pdf.getPage(i);
            const viewport = page.getViewport({ scale: options.scale ?? 1 });

            canvas.width = viewport.width;
            canvas.height = viewport.height;
            slide.style.aspectRatio = `${viewport.width} / ${viewport.height}`;

            await page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;
            swiper.update();
        }
    }
});
