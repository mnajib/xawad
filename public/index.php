<?php
// public/index.php

/*
 * ISTILAH BAHARU: require_once
 * -----------------------------------------------------------------------------
 * APA DIA: Arahan untuk memasukkan (import) fail PHP lain ke dalam fail ini.
 * SEBAB GUNA: Fail 'db.php' mengandungi kod sambungan ke pangkalan data MariaDB.
 *             Dengan 'require_once', kita tidak perlu menulis semula kod sambungan di setiap fail.
 * MENGAPA '_once': Memastikan fail tersebut hanya dimasukkan SEKALI sahaja bagi
 *                  mengelakkan ralat 'duplicate function/variable'.
 * ALTERNATIF: 'include' (jika fail tiada, skrip tetap berjalan dengan amaran),
 *             'require' (jika fail tiada, skrip akan terhenti secara terus).
 */
require_once __DIR__ . '/../db.php';

/*
 * ISTILAH BAHARU: SQL Statement & Query Execution
 * -----------------------------------------------------------------------------
 * SQL (Structured Query Language): Bahasa standard untuk berinteraksi dengan pangkalan data.
 * SELECT * : Mengambil KESEMUA lajur (columns) daripada jadual 'appointments'.
 * ORDER BY appointment_date ASC : Menyusun rekod mengikut tarikh secara menaik (Ascending / terawal ke terkini).
 * $conn->query(...) : Menjalankan (execute) arahan SQL tersebut melalui objek sambungan $conn.
 */
$sql = "SELECT * FROM appointments ORDER BY appointment_date ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">

    <!--
      ISTILAH BAHARU: Viewport Meta Tag
      APA DIA: Arahan kepada pelayar (browser) untuk melaraskan lebar paparan mengikut skrin peranti.
      SEBAB GUNA: Supaya laman web kelihatan kemas apabila dibuka di telefon bimbit atau tablet (Responsive Design).
    -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Temujanji Servis Kereta</title>

    <!--
      ISTILAH BAHARU: Local Static Assets (Bootstrap CSS)
      APA DIA: Fail CSS Bootstrap yang disimpan secara tempatan di dalam projek (folder assets/css/).
      SEBAB GUNA: Membolehkan paparan laman web kelihatan profesional tanpa memerlukan sambungan internet (CDN).
      ALTERNATIF: Menggunakan pautan CDN (Content Delivery Network) seperti JSDelivr.
    -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!--
      BOOTSTRAP WIDGET: Navbar (Bar Navigasi)
      FUNGSI: Menyediakan bar tajuk di bahagian atas laman web.
      KELAS BOOTSTRAP:
      - 'navbar-dark bg-primary': Latar belakang warna biru utama (primary) dengan teks putih.
    -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">🚗 Pusat Servis Kereta</a>
        </div>
    </nav>

    <div class="container">
        <!-- Tajuk Halaman & Butang Tindakan -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Senarai Temujanji</h2>
            <!--
              BOOTSTRAP WIDGET: Button (.btn)
              FUNGSI: Pautan yang direka bentuk menyerupai butang interaktif.
              KELAS: 'btn-success' memberi warna hijau (memberi gambaran tindakan positif/tambah).
            -->
            <a href="book.php" class="btn btn-success fw-semibold">+ Buat Temujanji Baharu</a>
        </div>

        <!--
          BOOTSTRAP WIDGET: Card & Table Component
          APA DIA: Bekas (container) bertema putih dengan bayangan lembut (.shadow-sm) untuk membungkus jadual.
        -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <!-- '.table-responsive' memastikan jadual boleh ditatal (scroll) secara mendatar pada skrin kecil -->
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nama Pelanggan</th>
                                <th>No. Plat Kereta</th>
                                <th>Jenis Servis</th>
                                <th>Tarikh</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            /*
                             * ISTILAH BAHARU: $result->num_rows
                             * APA DIA: Sifat (property) objek PHP yang menyimpan bilangan baris rekod yang dipulangkan oleh SQL.
                             * CARA KERJA: Jika melebihi 0 (wujud rekod), PHP akan menjalankan gegelung 'while'.
                             */
                            if ($result && $result->num_rows > 0):
                            ?>
                                <?php
                                /*
                                 * ISTILAH BAHARU: fetch_assoc() (Fetch Associative Array)
                                 * APA DIA: Fungsi untuk mengambil satu baris rekod daripada pangkalan data dan menukarnya
                                 *          kepada tatasusunan bersekutu (associative array).
                                 * CARA KERJA: Gegelung 'while' akan terus berjalan sehingga semua baris rekod selesai dibaca.
                                 * CONTOH AKSHES: $row['customer_name'] mengambil nilai dari lajur 'customer_name'.
                                 */
                                while ($row = $result->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>

                                        <!--
                                          ISTILAH BAHARU: htmlspecialchars()
                                          APA DIA: Fungsi keselamatan PHP untuk menukar aksara khas HTML (seperti '<' atau '>') kepada bentuk entiti.
                                          SEBAB GUNA: Mencegah serangan keselamatan XSS (Cross-Site Scripting) jika pengguna memasukkan kod berniat jahat.
                                          AMALAN TERBAIK: Sentiasa gunakan htmlspecialchars() apabila mencetak data input pengguna ke paparan HTML!
                                        -->
                                        <td class="fw-medium"><?php echo htmlspecialchars($row['customer_name']); ?></td>

                                        <td>
                                            <!-- BOOTSTRAP WIDGET: Badge (Lencana Visual) -->
                                            <span class="badge bg-secondary font-monospace fs-6"><?php echo htmlspecialchars($row['car_plate']); ?></span>
                                        </td>

                                        <td><?php echo htmlspecialchars($row['service_type']); ?></td>
                                        <td><?php echo $row['appointment_date']; ?></td>

                                        <td>
                                            <?php
                                                /*
                                                 * ISTILAH BAHARU: Ternary Operator ( ? : )
                                                 * APA DIA: Bentuk ringkas bagi struktur kawalan 'if-else'.
                                                 * CARA KERJA: (Syarat) ? 'Jika True' : 'Jika False';
                                                 * SEBAB GUNA: Membolehkan penentuan warna lencana dibuat secara dinamik mengikut status temujanji.
                                                 */
                                                $statusClass = ($row['status'] === 'Confirmed') ? 'bg-success' : 'bg-warning text-dark';
                                            ?>
                                            <span class="badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($row['status']); ?></span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <!-- Paparan sekiranya pangkalan data masih kosong -->
                                <tr>
                                    <td colspan="6" class="text-center text-muted p-4">Tiada rekod temujanji dijumpai.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--
      ISTILAH BAHARU: Local JavaScript Bundle (Bootstrap JS)
      APA DIA: Skrip JavaScript untuk membolehkan komponen interaktif Bootstrap (seperti dropdown, modal, atau alert) berfungsi.
      LOKASI: Diletakkan di bahagian bawah <body> supaya struktur HTML dimuatkan terlebih dahulu oleh pelayar.
    -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
