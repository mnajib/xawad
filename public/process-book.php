<?php
// public/process-book.php

/*
 * ISTILAH BAHARU: require_once
 * -----------------------------------------------------------------------------
 * Memasukkan fail sambungan pangkalan data (db.php) dari folder utama (root).
 * Tanpa sambungan ini, kita tidak boleh menyimpan data ke dalam MariaDB.
 */
require_once __DIR__ . '/../db.php';

/*
 * ISTILAH BAHARU: Superglobal $_SERVER & Request Method
 * -----------------------------------------------------------------------------
 * $_SERVER['REQUEST_METHOD'] : Mengesan kaedah permintaan HTTP yang digunakan untuk mengakses fail ini.
 * SEBAB SEMAK 'POST'         : Memastikan skrip ini HANYA berjalan apabila borang dihantar dari 'book.php'.
 * MENGAPA PERLU?             : Mengelakkan pengguna daripada mengakses fail pemprosesan ini secara terus
 *                              melalui bar alamat pelayar (URL / kaedah GET).
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /*
     * LANGKAH 1: Mengambil & Membersihkan Data Input (Data Sanitization)
     * -----------------------------------------------------------------------------
     * $_POST['key']           : Membaca data yang dihantar melalui atribut name="..." dalam borang HTML.
     * Null Coalescing (?? '') : Menetapkan nilai asas (default) string kosong jika kunci $_POST tidak wujud,
     *                           sekali gus mengelakkan ralat 'Undefined index'.
     * trim()                  : Membuang ruang kosong (whitespace) yang tidak disengajakan pada awal & akhir teks.
     * strtoupper()            : Menukarkan semua aksara teks kepada huruf besar (contoh: plat kereta 'wyy8899' -> 'WYY8899').
     */
    $customer_name    = trim($_POST['customer_name'] ?? '');
    $car_plate        = strtoupper(trim($_POST['car_plate'] ?? ''));
    $service_type     = trim($_POST['service_type'] ?? '');
    $appointment_date = trim($_POST['appointment_date'] ?? '');
    $status           = 'Pending'; // Status awal secara lalai sebelum disahkan oleh pihak bengkel

    /*
     * LANGKAH 2: Pengesahan Data di Sebelah Pelayan (Server-Side Validation)
     * -----------------------------------------------------------------------------
     * empty()     : Menyemak sama ada pemboleh ubah kosong atau tidak bernilai.
     * die()       : Menghentikan pelaksanaan skrip secara terus dan memaparkan mesej ralat jika ada medan kosong.
     * SEBAB GUNA  : Pengesahan HTML (required) pada borang boleh dipintas oleh pengguna teknikal,
     *               jadi pengesahan PHP di sebelah pelayan wajib dibuat sebagai perlindungan utama.
     */
    if (empty($customer_name) || empty($car_plate) || empty($service_type) || empty($appointment_date)) {
        die("Ralat: Semua medan wajib diisi. <a href='book.php'>Kembali ke borang</a>");
    }

    /*
     * LANGKAH 3: Pangkalan Data & Keselamatan SQL (Prepared Statements)
     * -----------------------------------------------------------------------------
     * ISTILAH BAHARU: SQL Injection (Pencerobohan SQL)
     * CONTOH BAHAYA  : Jika kita menggabungkan string terus seperti "INSERT INTO ... VALUES ('$customer_name')",
     *                  penyerang boleh memasukkan kod SQL jahat ke dalam kotak nama.
     *
     * PENYELESAIAN   : Menggunakan Simbol Pemegang Tempat (Placeholder '?').
     * CARA KERJA     : Arahan SQL dihantar dan dihimpunkan (compiled) oleh pangkalan data TERLEBIH DAHULU
     *                  sebelum data sebenar diikat (bind). Data dimasukkan strictly sebagai teks biasa.
     */
    $sql = "INSERT INTO appointments (customer_name, car_plate, service_type, appointment_date, status) VALUES (?, ?, ?, ?, ?)";

    // Mempersediakan penyataan SQL pada objek sambungan $conn
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        /*
         * ISTILAH BAHARU: bind_param()
         * -------------------------------------------------------------------------
         * APA DIA    : Mengikat nilai pemboleh ubah PHP ke dalam simbol pemegang tempat '?'.
         * PARmeter 1 : "sssss" bermaksud kelima-lima nilai yang diikat bertipe String (Teks).
         *              (s = string, i = integer, d = double/float, b = blob).
         */
        $stmt->bind_param("sssss", $customer_name, $car_plate, $service_type, $appointment_date, $status);

        /*
         * ISTILAH BAHARU: execute()
         * -------------------------------------------------------------------------
         * APA DIA : Menjalankan arahan SQL yang telah diikat dengan data sebenar.
         * MEMULANG: Nilai 'true' jika penyimpanan berjaya, atau 'false' jika ralat berlaku.
         */
        if ($stmt->execute()) {
            /*
             * ISTILAH BAHARU: header("Location: ...") & Redirect
             * ---------------------------------------------------------------------
             * APA DIA : Menghantar arahan lencongan (redirection) HTTP ke pelayar pengguna.
             * SEBAB   : Memindahkan pengguna kembali ke 'index.php' setelah rekod berjaya disimpan.
             * exit    : Menghentikan skrip sejurus selepas lencongan dipanggil bagi mengelakkan
             *           kod seterusnya dieksekusi secara tidak sengaja.
             */
            header("Location: index.php?status=success");
            exit;
        } else {
            echo "Ralat Pelaksanaan: " . $stmt->error;
        }

        // Mematikan kenyataan bersedia (statement) untuk membebaskan memori pelayan
        $stmt->close();
    } else {
        echo "Ralat Penyediaan SQL: " . $conn->error;
    }

    // Mematikan sambungan pangkalan data
    $conn->close();

} else {
    /*
     * Jika pengguna cuba membuka fail ini terus dari pelayar (kaedah GET),
     * sekat dan bawa mereka secara automatik ke borang 'book.php'.
     */
    header("Location: book.php");
    exit;
}
