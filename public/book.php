<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">

    <!--
      ISTILAH BAHARU: Viewport Meta Tag
      APA DIA: Arahan kepada pelayar (browser) untuk melaraskan lebar paparan mengikut skrin peranti.
      SEBAB GUNA: Supaya borang kelihatan kemas dan mesra pengguna apabila dibuka pada telefon bimbit.
    -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borang Temujanji - Pusat Servis Kereta</title>

    <!--
      ISTILAH BAHARU: Local Static Assets (Bootstrap CSS)
      APA DIA: Fail CSS Bootstrap tempatan (folder assets/css/).
      SEBAB GUNA: Mengayakan borang HTML dengan rekabentuk kemas tanpa memerlukan sambungan internet.
    -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- BAR NAVIGASI (NAVBAR) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">🚗 Pusat Servis Kereta</a>
        </div>
    </nav>

    <!-- KANDUNGAN UTAMA BORANG -->
    <div class="container" style="max-width: 600px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Tempah Temujanji</h2>
            <!-- Butang Kembali ke Halaman Utama -->
            <a href="index.php" class="btn btn-outline-secondary">← Kembali ke Senarai</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">

                <!--
                  ISTILAH BAHARU: HTML Form (<form>) & HTTP Method
                  -----------------------------------------------------------------------------
                  action="process-book.php" : Meminta pelayar menghantar data borang ke fail 'process-book.php' untuk diproses.
                  method="POST"             : Kaedah penghantaran data secara tersembunyi (dalam HTTP request body).

                  SEBAB GUNA 'POST': Data seperti nama dan plat kereta tidak akan kelihatan pada URL.
                  ALTERNATIF       : method="GET" (Data dihantar melalui URL, cth: process.book.php?name=Ahmad.
                                     GET kurang sesuai untuk simpanan pangkalan data).
                -->
                <form action="process-book.php" method="POST">

                    <!-- INPUT 1: NAMA PELANGGAN -->
                    <div class="mb-3">
                        <!--
                          ATRIBUT 'for' & 'id': Menghubungkan label dengan input supaya apabila label ditekan,
                          kotak input akan diaktifkan (focused).
                        -->
                        <label for="customer_name" class="form-label fw-semibold">Nama Penuh</label>

                        <!--
                          ISTILAH BAHARU: Attribute 'name' & 'required'
                          -----------------------------------------------------------------------------
                          name="customer_name" : Nama pemboleh ubah yang akan dibaca oleh PHP melalui $_POST['customer_name'].
                          required             : Pengesahan peringkat pelayar (browser validation) untuk memastikan
                                                 kotak input ini TIDAK BOLEH dibiarkan kosong sebelum borang dihantar.
                          placeholder          : Teks petunjuk sementara di dalam kotak input.
                        -->
                        <input type="text" class="form-control" id="customer_name" name="customer_name" required placeholder="Contoh: Ahmad Razak">
                    </div>

                    <!-- INPUT 2: NOMBOR PLAT KERETA -->
                    <div class="mb-3">
                        <label for="car_plate" class="form-label fw-semibold">Nombor Plat Kereta</label>
                        <!-- 'font-monospace' ialah kelas Bootstrap untuk menggunakan fon berjarak tetap (kemas untuk no. plat) -->
                        <input type="text" class="form-control font-monospace" id="car_plate" name="car_plate" required placeholder="Contoh: WYY 8899">
                    </div>

                    <!-- INPUT 3: JENIS SERVIS (DROPDOWN) -->
                    <div class="mb-3">
                        <label for="service_type" class="form-label fw-semibold">Jenis Servis</label>

                        <!--
                          ISTILAH BAHARU: HTML Select Element (<select>)
                          APA DIA: Elemen pilihan tetingkap susur keluar (dropdown list).
                          CARA KERJA: Nilai pilihan yang dipilih dalam atribut 'value' pada tag <option>
                                     akan dihantar ke PHP melalui $_POST['service_type'].
                        -->
                        <select class="form-select" id="service_type" name="service_type" required>
                            <!-- Pilihan asal yang dilumpuhkan (disabled) supaya pengguna diwajibkan membuat pilihan -->
                            <option value="" selected disabled>Sila pilih jenis servis...</option>
                            <option value="Basic Engine Oil Change">Penukaran Minyak Enjin Asas</option>
                            <option value="Full Major Service">Servis Major Penuh</option>
                            <option value="Brake Pad Replacement">Penukaran Pad Brek</option>
                            <option value="Tire Alignment & Balancing">Imbangan & Jajaran Tayar</option>
                        </select>
                    </div>

                    <!-- INPUT 4: TARIKH TEMUJANJI -->
                    <div class="mb-4">
                        <label for="appointment_date" class="form-label fw-semibold">Tarikh Temujanji</label>

                        <!--
                          ISTILAH BAHARU: Dynamic HTML Attribute via PHP date()
                          -----------------------------------------------------------------------------
                          type="date"           : Memaparkan kalendar interaktif secara automatik pada pelayar.
                          min="<?php echo date('Y-m-d'); ?>" : Memasukkan tarikh hari ini secara dinamik menggunakan PHP.
                          SEBAB GUNA 'min'     : Mencegah pengguna daripada memilih tarikh yang telah berlalu (masa lepas).
                        -->
                        <input type="date" class="form-control" id="appointment_date" name="appointment_date" required min="<?php echo date('Y-m-d'); ?>">
                    </div>

                    <!-- BUTANG HANTAR (SUBMIT) -->
                    <div class="d-grid">
                        <!--
                          type="submit" : Memicu (trigger) penghantaran borang ke fail yang dinyatakan dalam atribut 'action'.
                        -->
                        <button type="submit" class="btn btn-success btn-lg">Hantar Tempahan</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- LOCAL BOOTSTRAP JAVASCRIPT BUNDLE -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
