<style>
    .custom-footer {
        background-color: #120930; /* Warna ungu sangat gelap sesuai gambar */
        color: rgba(255, 255, 255, 0.7);
        padding: 50px 0 20px;
        font-family: 'Nunito', sans-serif;
        position: relative;
        z-index: 50;
    }
    
    /* 1. Bagian Brand & Logo */
    .f-brand-container {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 40px;
    }
    .f-logo-round {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: #fff;
        padding: 4px;
        object-fit: contain;
    }
    .f-brand-title {
        font-family: 'Fredoka One', 'Nunito', sans-serif;
        color: #d4ff00; /* Warna kuning kehijauan terang */
        font-size: 1.4rem;
        margin-bottom: 5px;
        font-weight: 700;
        line-height: 1.2;
    }
    .f-tagline {
        font-size: 0.85rem;
        margin: 0;
        line-height: 1.4;
    }

    /* 2. Judul Section (Jam, Hubungi, Ikuti) */
    .custom-footer .f-head {
        color: #ffffff;
        font-weight: 800;
        font-size: 1.1rem;
        margin-bottom: 15px;
    }

    /* 3. List Teks & Ikon */
    .custom-footer .f-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .custom-footer .f-list li {
        display: flex;
        align-items: flex-start;
        margin-bottom: 15px;
        gap: 12px;
        font-size: 0.85rem;
        line-height: 1.5;
    }
    .custom-footer .fi {
        flex-shrink: 0;
        width: 16px;
        display: flex;
        justify-content: center;
        margin-top: 3px;
    }
    .custom-footer .fi img {
        width: 100%;
        height: auto;
        filter: brightness(0) invert(1); 
    }

    /* 4. Tombol Sosial Media Kotak */
    .custom-footer .soc-btn-box {
        display: flex;
        align-items: center;
        background-color: #2a1f4c; /* Warna kotak ungu lebih terang */
        color: #ffffff;
        text-decoration: none;
        padding: 10px 15px;
        border-radius: 8px;
        margin-bottom: 12px;
        transition: background 0.3s;
        gap: 12px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    .custom-footer .soc-btn-box:hover {
        background-color: #3e2e6b;
        color: #ffffff;
    }
    .custom-footer .soc-btn-box img {
        width: 16px;
        height: 16px;
        filter: brightness(0) invert(1);
    }

    /* 5. Garis & Copyright */
    .custom-footer .f-divider {
        border-color: rgba(255, 255, 255, 0.05);
        margin: 40px 0 20px;
    }
    .custom-footer .f-copy {
        text-align: center;
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.4);
        margin: 0;
    }
</style>

<footer class="custom-footer">
    <div class="container">
        
        <!-- BARIS 1: Logo & Brand -->
        <div class="row">
            <div class="col-12">
                <div class="f-brand-container">
                    <img src="{{ asset('logo/PNCLOGO.jpg') }}" alt="Logo Play N Chill" class="f-logo-round">                   
                    <div>
                        <div class="f-brand-title">Play N Chill</div>
                        <p class="f-tagline">Nikmati pengalaman tak terlupakan bersama teman dan keluarga.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- BARIS 2 / Kolom 1: Jam Operasional -->
            <div class="col-12 col-md-4 mb-4 pb-2">
                <div class="f-head">Jam Operasional</div>
                <ul class="f-list">
                    <li>
                        <span class="fi"><img src="{{ asset('gambar/ic_jam.png') }}" alt="Jam"></span>
                        <div>
                            <div>Senin – Kamis: 14.00 – 22.00</div>
                            <div class="my-1">Jumat: 13.00 – 00.00</div>
                            <div>Sabtu – Minggu: 10.00 – 00.00</div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- BARIS 3 / Kolom 2: Hubungi Kami (Kiri) -->
            <div class="col-7 col-md-4 pe-2">
                <div class="f-head">Hubungi Kami</div>
                <ul class="f-list">
                    <li>
                        <span class="fi"><img src="{{ asset('gambar/ic_tel.png') }}" alt="Phone"></span>
                        <span>+62 857-3532-9227</span>
                    </li>
                    <li>
                        <span class="fi"><img src="{{ asset('gambar/ic_email.png') }}" alt="Email"></span>
                        <span style="word-break: break-all;">playnchillmadiun@gmail.com</span>
                    </li>
                    <li>
                        <span class="fi"><img src="{{ asset('gambar/ic_lok.png') }}" alt="Location"></span>
                        <span>Jl. Puntadewa,<br>Ngrame RT02, Tamantirto, Kasihan, Bantul<br>Yogyakarta,<br>Selatan Sportorium UMY</span>
                    </li>
                </ul>
            </div>

            <!-- BARIS 3 / Kolom 3: Ikuti Kami (Kanan) -->
            <div class="col-5 col-md-4 ps-1">
                <div class="f-head">Ikuti Kami</div>
                <div class="d-flex flex-column">
                    <a class="soc-btn-box" href="https://youtube.com/@playnchillmadiun?si=KVGMA9tC2ktJHAY0" target="_blank">
                        <img src="{{ asset('gambar/ic_yt.png') }}" alt="YouTube"> YouTube
                    </a>
                    <a class="soc-btn-box" href="https://www.tiktok.com/@playnchill.madiun?_r=1&_t=ZS-96DA4Nfui1t" target="_blank">
                        <img src="{{ asset('gambar/ic_tk.png') }}" alt="TikTok"> TikTok
                    </a>
                    <a class="soc-btn-box" href="https://share.google/fMbFjkoIuMs0P7Mfh" target="_blank">
                        <img src="{{ asset('gambar/ic_ig.png') }}" alt="Instagram"> Instagram
                    </a>
                </div>
            </div>
        </div>

        <hr class="f-divider">
        <p class="f-copy text-center">&copy; {{ date('Y') }} Play N Chill Yogyakarta. All rights reserved.</p>
    </div>
</footer>