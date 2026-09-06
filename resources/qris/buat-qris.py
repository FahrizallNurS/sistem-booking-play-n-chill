import qrcode

# Teks QRIS asli dari klien Play N Chill
qris_payload = "00020101021126710019ID.CO.CIMBNIAGA.WWW011893600022000080001002150000089768783820303UMI51450015ID.OR.QRNPG.WWW0215ID10243451747170303UMI5204799453033605802ID5912PLAY N CHILL6015Kota Yogyakarta61055521262120708X396060963045427"

# Konfigurasi QR Code agar beresolusi super HD
qr = qrcode.QRCode(
    version=1,
    error_correction=qrcode.constants.ERROR_CORRECT_H, 
    box_size=30, # Diperbesar agar gambar tajam
    border=2,    # Border disesuaikan agar pas dengan kotak putih di web
)

# Memproses data ke dalam gambar
qr.add_data(qris_payload)
qr.make(fit=True)

# Menyimpan hasil akhir
img = qr.make_image(fill_color="black", back_color="white")
img.save("qris_play_n_chill_hd.png")

print("Berhasil! Gambar qris_play_n_chill_hd.png yang HD sudah sukses dibuat.")