@extends('layouts.app')

@section('title', 'Manual Book')
@section('hide_newsletter')

@section('styles')
<script src="https://cdn.tailwindcss.com"></script>
<style>
    .pdf-canvas {
        max-width: 100%;
        box-shadow: 0 12px 28px rgba(15, 23, 42, .12);
        background: white;
    }
    .viewer-stage {
        min-height: calc(100vh - 260px);
        max-height: calc(100vh - 220px);
        scroll-behavior: smooth;
    }
    .pdf-pages {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.5rem;
    }
    .pdf-page {
        width: 100%;
        display: flex;
        justify-content: center;
    }
</style>
@endsection

@section('content')
<section class="bg-slate-950 text-white">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="mb-2 inline-flex items-center gap-2 rounded-full border border-cyan-300/30 bg-cyan-300/10 px-3 py-1 text-sm font-semibold text-cyan-200">
                    <i class="bi bi-question-circle"></i>
                    Help Center
                </p>
                <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Manual Book</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-300">
                    Panduan penggunaan E-Book Online. File ini selalu mengikuti upload terbaru dari admin.
                </p>
            </div>

        </div>
    </div>
</section>

<section class="bg-slate-100 px-4 py-6 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        @if($manualBook && $manualBook->file_url)
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl">
                <div class="flex flex-wrap items-center justify-center gap-2 border-b border-slate-200 bg-white px-4 py-3 text-slate-800">
                    <button type="button" id="prev-page" title="Halaman sebelumnya" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 hover:bg-slate-50">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button type="button" id="next-page" title="Halaman berikutnya" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 hover:bg-slate-50">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                    <span class="mx-1 h-8 w-px bg-slate-200"></span>
                    <button type="button" id="zoom-out" title="Perkecil" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 hover:bg-slate-50">
                        <i class="bi bi-dash-lg"></i>
                    </button>
                    <button type="button" id="zoom-in" title="Perbesar" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 hover:bg-slate-50">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                    <span class="mx-1 h-8 w-px bg-slate-200"></span>
                    <input id="page-num" type="number" min="1" value="1" class="h-10 w-16 rounded-lg border border-slate-300 text-center font-semibold text-slate-900">
                    <span class="text-sm font-semibold text-slate-600">of <span id="page-count">0</span></span>
                    <span class="mx-1 h-8 w-px bg-slate-200"></span>
                    <a href="{{ $manualBook->file_url }}" target="_blank" rel="noopener" title="Buka PDF" aria-label="Buka PDF" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 hover:bg-slate-50">
                        <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                    <a href="{{ $manualBook->download_url }}" title="Download PDF" aria-label="Download PDF" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-slate-900 hover:bg-slate-50">
                        <i class="bi bi-download"></i>
                    </a>
                </div>
                <div id="viewer-stage" class="viewer-stage flex justify-center overflow-auto bg-slate-200 p-4">
                    <div id="pdf-pages" class="pdf-pages"></div>
                </div>
            </div>
            <div class="mt-4 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Manual Book PDF</h2>
                    <p class="text-sm text-slate-500">Buka file PDF langsung di browser atau download untuk dibaca offline.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ $manualBook->file_url }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                        <i class="bi bi-box-arrow-up-right"></i>
                        Buka PDF
                    </a>
                    <a href="{{ $manualBook->download_url }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-cyan-400 px-4 py-2 text-sm font-bold text-slate-950 transition hover:bg-cyan-300">
                        <i class="bi bi-download"></i>
                        Download
                    </a>
                </div>
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center shadow-sm">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-3xl text-slate-400">
                    <i class="bi bi-file-earmark-pdf"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-900">Manual Book belum tersedia</h2>
                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                    Admin belum mengupload file PDF manual book. Silakan cek kembali setelah file tersedia.
                </p>
            </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
@if($manualBook && $manualBook->file_url)
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    const pdfUrl = @json($manualBook->file_url);
    const viewerStage = document.getElementById('viewer-stage');
    const pagesContainer = document.getElementById('pdf-pages');
    const pageInput = document.getElementById('page-num');
    const pageCount = document.getElementById('page-count');
    const prevButton = document.getElementById('prev-page');
    const nextButton = document.getElementById('next-page');
    const zoomOutButton = document.getElementById('zoom-out');
    const zoomInButton = document.getElementById('zoom-in');

    let pdfDocument = null;
    let currentPage = 1;
    let scale = 1.15;
    let rendering = false;
    let pendingRender = false;
    let pageObserver = null;

    function renderSinglePage(pageNumber) {
        const pageWrapper = document.createElement('div');
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');

        pageWrapper.className = 'pdf-page';
        pageWrapper.dataset.pageNumber = pageNumber;
        canvas.className = 'pdf-canvas';

        pageWrapper.appendChild(canvas);
        pagesContainer.appendChild(pageWrapper);

        return pdfDocument.getPage(pageNumber).then((page) => {
            const viewport = page.getViewport({ scale });

            canvas.height = viewport.height;
            canvas.width = viewport.width;

            return page.render({ canvasContext: context, viewport }).promise;
        });
    }

    function observePages() {
        if (pageObserver) {
            pageObserver.disconnect();
        }

        pageObserver = new IntersectionObserver((entries) => {
            const visiblePage = entries
                .filter((entry) => entry.isIntersecting)
                .sort((first, second) => second.intersectionRatio - first.intersectionRatio)[0];

            if (visiblePage) {
                currentPage = parseInt(visiblePage.target.dataset.pageNumber, 10);
                pageInput.value = currentPage;
            }
        }, {
            root: viewerStage,
            threshold: [0.25, 0.5, 0.75],
        });

        document.querySelectorAll('.pdf-page').forEach((page) => pageObserver.observe(page));
    }

    function renderPages() {
        rendering = true;
        pendingRender = false;
        pagesContainer.innerHTML = '';

        const pageRenders = [];

        for (let pageNumber = 1; pageNumber <= pdfDocument.numPages; pageNumber += 1) {
            pageRenders.push(renderSinglePage(pageNumber));
        }

        Promise.all(pageRenders).then(() => {
            rendering = false;
            observePages();
            scrollToPage(currentPage, false);

            if (pendingRender) {
                renderPages();
            }
        });
    }

    function queueRender() {
        if (rendering) {
            pendingRender = true;
            return;
        }

        renderPages();
    }

    function scrollToPage(pageNumber, smooth = true) {
        const targetPage = pagesContainer.querySelector(`[data-page-number="${pageNumber}"]`);

        if (!targetPage) {
            return;
        }

        viewerStage.scrollTo({
            top: Math.max(targetPage.offsetTop - 16, 0),
            behavior: smooth ? 'smooth' : 'auto',
        });
    }

    function goToPage(pageNumber) {
        const targetPage = Math.min(Math.max(pageNumber, 1), pdfDocument.numPages);
        currentPage = targetPage;
        pageInput.value = currentPage;
        scrollToPage(currentPage);
    }

    pdfjsLib.getDocument(pdfUrl).promise.then((pdf) => {
        pdfDocument = pdf;
        pageCount.textContent = pdf.numPages;
        pageInput.max = pdf.numPages;
        renderPages();
    });

    prevButton.addEventListener('click', () => {
        if (currentPage > 1) {
            goToPage(currentPage - 1);
        }
    });

    nextButton.addEventListener('click', () => {
        if (pdfDocument && currentPage < pdfDocument.numPages) {
            goToPage(currentPage + 1);
        }
    });

    pageInput.addEventListener('change', () => {
        if (pdfDocument) {
            goToPage(parseInt(pageInput.value || '1', 10));
        }
    });

    zoomOutButton.addEventListener('click', () => {
        scale = Math.max(scale - 0.15, 0.6);
        queueRender();
    });

    zoomInButton.addEventListener('click', () => {
        scale = Math.min(scale + 0.15, 2.4);
        queueRender();
    });
</script>
@endif
@endsection
