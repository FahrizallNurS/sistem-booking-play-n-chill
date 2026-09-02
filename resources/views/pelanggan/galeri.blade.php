<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Galeri - Play N Chill</title> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Modak&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        body {
            background-color: var(--purple-dark);
            position: relative;
            min-height: 100vh;
            margin: 0;
            padding-top: 85px; 
        }
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset("images/bg-segitiga.png") }}');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            opacity: 0.8;
            z-index: -1;
        }
        .navbar.fixed-top {
            position: fixed !important;
            top: 0 !important;
            width: 100% !important;
            z-index: 1030 !important;
            background: rgb(255, 255, 255);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body>

@include('partials.navbar')

<style>
    .filter-btn {
        background-color: #3a2377;
        color: #d8b8ff;
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 50px;
        padding: 8px 24px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    .filter-btn:hover, .filter-btn.active {
        background-color: #ffd700;
        color: #2b0054;
        border-color: #ffd700;
    }

    .gallery-card {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        aspect-ratio: 4 / 5; 
        cursor: pointer;
        transition: transform 0.3s ease;
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        background-color: #2b0054;
    }

    .gallery-card:hover {
        transform: translateY(-8px);
    }
    .gallery-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .gallery-card:hover .gallery-img {
        transform: scale(1.05);
    }
    .gallery-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to top, rgba(30, 10, 70, 1) 0%, rgba(30, 10, 70, 0.6) 50%, transparent 100%);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 24px;
        color: white;
    }
    .gallery-badge {
        background-color: transparent;
        color: #ffd700;
        border: 1px solid #ffd700;
        font-size: 0.65rem;
        padding: 4px 10px;
        border-radius: 4px;
        display: inline-block;
        margin-bottom: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        width: fit-content;
    }

    .cta-gallery {
      background: linear-gradient(135deg, #2E1f6e 0%, #4a33a5 100%);
      border-radius: 20px;
      padding: 60px 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      position: relative;
      overflow: hidden;
    }

    .btn-kuning {
        background-color: #ffd700;
        color: #2b0054;
        font-weight: 800;
        padding: 14px 36px;
        border-radius: 50px;
        border: none;
        letter-spacing: 0.5px;
        transition: transform 0.2s ease, background-color 0.2s ease;
    }
    .btn-kuning:hover {
        transform: scale(1.05);
        background-color: #ffea00;
    }

    .gallery-slider-wrapper {
        position: relative;
        width: 100%;
    }

    .slider-indicators {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-top: 15px;
    }
    .slider-dot {
        width: 8px;
        height: 8px;
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .slider-dot.active {
        background-color: #ffd700;
        width: 24px; 
        border-radius: 10px;
    }

    @media (min-width: 992px) {
        .slider-indicators {
            display: none !important; 
        }
    }

    @media (max-width: 991.98px) {
        .gallery-slider-container {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            gap: 1rem;
            padding-bottom: 1rem;
            scroll-snap-type: x mandatory; 
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none; 
            margin-right: 0;
            margin-left: 0;
        }
        
        .gallery-slider-container::-webkit-scrollbar {
            display: none;
        }

        .gallery-slider-item {
            flex: 0 0 100%; 
            width: 100%; 
            scroll-snap-align: center;
            scroll-snap-stop: always;
            padding-right: 0;
            padding-left: 0;
        }
    }

</style>

{{-- ═══ AREA KONTEN UTAMA ═══ --}}
<div class="container my-5" style="min-height: 50vh;">
    <div class="text-center text-white mb-5" data-aos="fade-up">
        {{-- Bungkus span dengan div agar dipaksa turun baris --}}
        <div class="mb-3">
            <span class="badge rounded-pill text-secondary px-4 py-2" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1); letter-spacing: 2px; color: #a0a0a0 !important;">GALERI</span>
        </div>
        
        <h1 class="mb-4 font-modak" style="font-size: 2.5rem; letter-spacing: 1px; line-height: 1.2;">Lihat Suasana Terbaik <br class="d-md-none"><span style="color: #ffffff; letter-spacing: 2px;">Play N Chill</span></h1>
        
        <p class="mx-auto" style="max-width: 600px; color: #ffffff; line-height: 1.6;">
            Jelajahi berbagai ruangan premium Play N Chill dan rasakan sendiri pengalaman gaming, karaoke, hingga private cinema yang nyaman, modern, dan eksklusif.
        </p>
    </div>

    <div class="d-flex justify-content-center flex-wrap mb-5 gap-3" data-aos="fade-up" data-aos-delay="100">
        <button class="filter-btn active">Semua</button>
        <button class="filter-btn">Galeri Reguler</button>
        <button class="filter-btn">Private Gaming</button>
        <button class="filter-btn">Private Nonton</button>
        <button class="filter-btn">Private Karaoke</button>
    </div>

    {{-- MENGGUNAKAN WRAPPER UNTUK SLIDER & BULLETS --}}
    <div class="gallery-slider-wrapper mb-5">
        
        <div class="gallery-slider-container row g-4" id="galleryContainer">
            @forelse($galeris as $index => $galeri)
                <div class="col-12 col-md-6 col-lg-3 gallery-slider-item gallery-item" data-kategori="{{ $galeri->kategori }}" data-aos="fade-up" data-aos-delay="100">
                    <div class="gallery-card">
                        @if($galeri->file_foto)
                            <img src="{{ asset('uploads/galeri/' . $galeri->file_foto) }}" alt="{{ $galeri->judul_foto }}" class="gallery-img" onerror="this.src='{{ asset('images/gaming.jpg') }}'">
                        @else
                            <img src="{{ asset('images/gaming.jpg') }}" alt="Default" class="gallery-img">
                        @endif
                        
                        <div class="gallery-overlay">
                            <span class="gallery-badge">{{ Str::upper($galeri->kategori) }}</span>
                            <h5 class="fw-bold mb-2" style="font-size: 1.25rem;">{{ $galeri->judul_foto }}</h5>
                            <p class="mb-0" style="font-size: 0.85rem; color: #d8b8ff; line-height: 1.5;">
                                {{ $galeri->deskripsi_foto }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 w-100">
                    <h4 class="text-white">Galeri sedang diperbarui.</h4>
                    <p style="color: #bca0e5;">Nantikan foto-foto ruangan menarik dari Play N Chill segera!</p>
                </div>
            @endforelse
        </div>

        {{-- BULLET INDICATORS (Hanya muncul jika ada foto) --}}
        @if($galeris->count() > 0)
        <div class="slider-indicators" id="sliderIndicators">
            @foreach($galeris as $index => $galeri)
                <div class="slider-dot {{ $index == 0 ? 'active' : '' }}"></div>
            @endforeach
        </div>
        @endif

    </div>

    <div class="cta-gallery text-center text-white mt-4" data-aos="zoom-in" data-aos-delay="200">
        <h2 class="fw-bold mb-3">Siap Merasakan Pengalaman Premium?</h2>
        <p class="mb-5 mx-auto" style="max-width: 550px; color: #d8b8ff;">
            Booking sekarang dan nikmati ruang gaming, private cinema, maupun karaoke terbaik hanya di Play N Chill.
        </p>
        <a href="{{ url('/booking') }}" class="btn btn-kuning">BOOKING SEKARANG</a>
    </div>
</div>

@include('partials.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: false, offset: 100 });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const galleryItems = document.querySelectorAll('.gallery-item');
        const container = document.getElementById('galleryContainer');
        const dots = document.querySelectorAll('.slider-dot');
        let autoSlideInterval;
        const autoSlideDelay = 3500; 
        function startAutoSlide() {
            stopAutoSlide(); 
            autoSlideInterval = setInterval(moveToNextSlide, autoSlideDelay);
        }

        function stopAutoSlide() {
            clearInterval(autoSlideInterval);
        }

        function moveToNextSlide() {
            let visibleItems = Array.from(galleryItems).filter(item => item.style.display !== 'none');
            if (visibleItems.length <= 1) return; 

            let centerViewport = window.innerWidth / 2;
            let minDistance = Infinity;
            let currentIndex = -1;

            visibleItems.forEach((item, index) => {
                let rect = item.getBoundingClientRect();
                let itemCenter = rect.left + (rect.width / 2);
                let distance = Math.abs(centerViewport - itemCenter);

                if (distance < minDistance) {
                    minDistance = distance;
                    currentIndex = index;
                }
            });

            let nextIndex = currentIndex + 1;
            if (nextIndex >= visibleItems.length) {
                nextIndex = 0;
            }

            let targetItem = visibleItems[nextIndex];
            let scrollPos = targetItem.offsetLeft - (container.clientWidth / 2) + (targetItem.clientWidth / 2);
            container.scrollTo({ left: scrollPos, behavior: 'smooth' });
        }

        if (container) {
            container.addEventListener('mouseenter', stopAutoSlide);
            container.addEventListener('mouseleave', startAutoSlide);
            container.addEventListener('touchstart', stopAutoSlide, { passive: true });
            container.addEventListener('touchend', startAutoSlide, { passive: true });

            startAutoSlide();
        }

        if (container && dots.length > 0) {
            container.addEventListener('scroll', () => {
                let centerViewport = window.innerWidth / 2;
                let minDistance = Infinity;
                let activeIndex = -1;

                galleryItems.forEach((item, index) => {
                    if (item.style.display !== 'none') {
                        let rect = item.getBoundingClientRect();
                        let itemCenter = rect.left + (rect.width / 2);
                        let distance = Math.abs(centerViewport - itemCenter);

                        if (distance < minDistance) {
                            minDistance = distance;
                            activeIndex = index;
                        }
                    }
                });

                dots.forEach(dot => dot.classList.remove('active'));
                if (activeIndex !== -1 && dots[activeIndex]) {
                    dots[activeIndex].classList.add('active');
                }
            });

            dots.forEach((dot, index) => {
                dot.addEventListener('click', function() {
                    let targetItem = galleryItems[index];
                    if (targetItem) {
                        let scrollPos = targetItem.offsetLeft - (container.clientWidth / 2) + (targetItem.clientWidth / 2);
                        container.scrollTo({ left: scrollPos, behavior: 'smooth' });
                    }
                });
            });
        }

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filterText = this.textContent.trim();
                let firstVisibleItemIndex = -1;

                galleryItems.forEach((item, index) => {
                    const kategori = item.getAttribute('data-kategori');
                    let isMatch = false;

                    if (filterText === 'Semua') {
                        isMatch = true;
                    } else if (filterText === 'Galeri Reguler' && kategori === 'Reguler') {
                        isMatch = true;
                    } else if (filterText === 'Private Gaming' && kategori === 'Private - Gaming') {
                        isMatch = true;
                    } else if (filterText === 'Private Nonton' && kategori === 'Private - Nonton') {
                        isMatch = true;
                    } else if (filterText === 'Private Karaoke' && kategori === 'Private - Karaoke') {
                        isMatch = true;
                    }

                    if (isMatch) {
                        item.style.display = 'block';
                        if (dots[index]) dots[index].style.display = 'block'; 
                        
                        if (firstVisibleItemIndex === -1) firstVisibleItemIndex = index;

                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'scale(1)';
                        }, 50);
                    } else {
                        item.style.opacity = '0';
                        item.style.transform = 'scale(0.8)';
                        if (dots[index]) dots[index].style.display = 'none'; 
                        
                        setTimeout(() => {
                            item.style.display = 'none';
                        }, 300);
                    }
                });

                stopAutoSlide();
                if (firstVisibleItemIndex !== -1) {
                    setTimeout(() => {
                        let targetItem = galleryItems[firstVisibleItemIndex];
                        let scrollPos = targetItem.offsetLeft - (container.clientWidth / 2) + (targetItem.clientWidth / 2);
                        container.scrollTo({ left: scrollPos, behavior: 'smooth' });
                        
                        startAutoSlide();
                    }, 350);
                }
            });
        });
    });
</script>

</body>
</html>