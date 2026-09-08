<style>
    .custom-footer {
        background-color: #120930; 
        color: rgba(255, 255, 255, 0.7);
        padding: 50px 0 20px;
        font-family: 'Nunito', sans-serif;
        position: relative;
        z-index: 50;
    }
    .f-brand-container {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .f-logo-round {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #fff;
        padding: 2px;
        object-fit: contain;
        flex-shrink: 0;
    }
    .f-brand-title {
        font-family: 'Fredoka One', 'Nunito', sans-serif;
        color: #d4ff00; 
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
    .custom-footer .f-head {
        color: #ffffff;
        font-weight: 800;
        font-size: 1.1rem;
        margin-bottom: 20px;
    }
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
    .custom-footer .soc-btn-box {
        display: flex;
        align-items: center;
        background-color: #2a1f4c; 
        color: #ffffff;
        text-decoration: none;
        padding: 10px 12px;
        border-radius: 8px;
        margin-bottom: 12px;
        transition: background 0.3s;
        gap: 10px;
        font-size: 0.85rem;
        font-weight: 500;
        width: 100%;
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
        <div class="row">
            <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                <div class="f-brand-container">
                    <img src="{{ asset('gambar/Logo-PNC01.png') }}" alt="Logo Play N Chill" class="f-logo-round">                   
                    <div>
                        <div class="f-brand-title">Play N Chill</div>
                        <p class="f-tagline">Nikmati pengalaman tak terlupakan bersama teman dan keluarga.</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                <div class="f-head">Jam Operasional</div>
                <ul class="f-list">
                    <li>
                        <span class="fi"><img src="{{ asset('gambar/ic_jam.png') }}" alt="Jam"></span>
                        <div>
                            <div>Senin – Minggu: 10.00 – 01.00</div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="col-7 col-md-6 col-lg-3 pe-2">
                <div class="f-head">Hubungi Kami</div>
                <ul class="f-list">
                    <li>
                        <span class="fi"><img src="{{ asset('gambar/ic_tel.png') }}" alt="Phone"></span>
                        <span>+62 858-1960-0024</span>
                    </li>
                    <li>
                        <span class="fi"><img src="{{ asset('gambar/ic_email.png') }}" alt="Email"></span>
                        <span style="word-break: break-all;">playnchill2024@gmail.com</span>
                    </li>
                    <li>
                        <span class="fi"><img src="{{ asset('gambar/ic_lok.png') }}" alt="Location"></span>
                        <span>Jl. Puntadewa,<br>Ngrame RT02, Tamantirto, Kasihan, Bantul<br>Yogyakarta,<br>Selatan Sportorium UMY</span>
                    </li>
                </ul>
            </div>

            <div class="col-5 col-md-6 col-lg-3 ps-1">
                <div class="f-head">Ikuti Kami</div>
                <div class="d-flex flex-column">
                    <a class="soc-btn-box" href="https://www.instagram.com/playnchill.id" target="_blank">
                        <img src="{{ asset('gambar/ic_yt.png') }}" alt="YouTube"> Instagram
                    </a>
                    <a class="soc-btn-box" href="https://www.tiktok.com/@playnchill.id" target="_blank">
                        <img src="{{ asset('gambar/ic_tk.png') }}" alt="TikTok"> Tiktok
                    </a>
                    <a class="soc-btn-box" href="https://www.threads.com/@playnchill.id" target="_blank">
                        <img src="{{ asset('gambar/ic_ig.png') }}" alt="Instagram"> Threads
                    </a>
                </div>
            </div>
        </div>

        <hr class="f-divider">
        <p class="f-copy text-center">&copy; {{ date('Y') }} Play N Chill Yogyakarta. All rights reserved.</p>
    </div>
</footer>