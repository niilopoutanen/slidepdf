document.querySelectorAll('.pdf-slider').forEach(async (root) => {
    const pdfUrl = root.dataset.pdf;
    const workerUrl = root.dataset.worker;

    pdfjsLib.GlobalWorkerOptions.workerSrc = workerUrl;

    const wrapper = root.querySelector('.swiper-wrapper');
    const pdf = await pdfjsLib.getDocument(pdfUrl).promise;

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

        await page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;
    }
});
