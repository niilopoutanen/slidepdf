import * as pdfjsLib from './pdfjs/pdf.js';

pdfjsLib.GlobalWorkerOptions.workerSrc =
    new URL('./pdfjs/pdf.worker.js', import.meta.url).toString();


document.querySelectorAll('.slidepdf').forEach(async (root) => {
    const pdfUrl = root.dataset.pdf;
    const options = JSON.parse(root.dataset.options || '{}');

    const wrapper = root.querySelector('.swiper-wrapper');
    const pdf = await pdfjsLib.getDocument(pdfUrl).promise;

    let swiper = new Swiper(root, {
        slidesPerView: options.slides_per_view ?? 1,
        spaceBetween: options.space_between ?? 10,
        loop: options.loop ?? false,
        pagination: {
            el: root.querySelector('.swiper-pagination'),
            clickable: true,
        },
        navigation: {
            nextEl: root.querySelector('.swiper-button-next'),
            prevEl: root.querySelector('.swiper-button-prev'),
        },
        loop: false,
    });
    for (let i = 1; i <= pdf.numPages; i++) {
        const slide = document.createElement('div');
        slide.className = 'swiper-slide';

        const canvas = document.createElement('canvas');
        slide.appendChild(canvas);
        wrapper.appendChild(slide);

        const page = await pdf.getPage(i);
        const viewport = page.getViewport({ scale: 1 });

        canvas.width = viewport.width;
        canvas.height = viewport.height;
        slide.style.aspectRatio = `${viewport.width} / ${viewport.height}`;

        await page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;
        swiper.update();
    }


});
