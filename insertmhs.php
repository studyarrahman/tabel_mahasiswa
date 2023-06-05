<?php
include 'connection.php';

// insertdonasi.php


// INSERT INTO `mahasiswa`(`nama`, `nim`, `program_studi`, `fakultas`, `dosen_pa`, `tgl_lahir`, `nomor_hp`, `tgl_msk_kuliah`, `foto_mhs`) VALUES ('novri', '12150110009', 'teknik informatika', 'sains dan teknologi', 'muhammad fikry', '2002-11-17', '081270859531', '2021-09-05', 'belum upload');

// prepare > bind > execute

$conn = getConnection();

$response = array(); // Menambahkan inisialisasi variabel response

try {
    if ($_POST) {
        $nama = $_POST["nama"];
        $nim = $_POST["nim"];
        $program_studi = $_POST["program_studi"];
        $fakultas = $_POST["fakultas"];
        $dosen_pa = $_POST["dosen_pa"];
        $tgl_lahir = $_POST["tgl_lahir"];
        $nomor_hp = $_POST["nomor_hp"];
        $tgl_msk_kuliah = $_POST["tgl_msk_kuliah"];

        if (isset($_FILES["foto_mhs"]["name"])) {
            $image_name = $_FILES["foto_mhs"]["name"];
            $extensions = ["jpg", "png", "jpeg"];
            $extension = pathinfo($image_name, PATHINFO_EXTENSION);

            if (in_array($extension, $extensions)) {
                $upload_path = 'upload/' . $image_name;

                if (move_uploaded_file($_FILES["foto_mhs"]["tmp_name"], $upload_path)) {
                    $keterangan = "http://localhost/tabel_mhs/" . $upload_path;

                    $statement = $conn->prepare("INSERT INTO `mahasiswa`(`nama`, `nim`, `program_studi`, `fakultas`, `dosen_pa`, `tgl_lahir`, `nomor_hp`, `tgl_msk_kuliah`, `foto_mhs`) VALUES (:nama, :nim, :program_studi, :fakultas, :dosen_pa, :tgl_lahir, :nomor_hp, :tgl_msk_kuliah, :foto_mhs);");

                    $statement->bindParam(':nama', $nama);
                    $statement->bindParam(':nim', $nim);
                    $statement->bindParam(':program_studi', $program_studi);
                    $statement->bindParam(':fakultas', $fakultas);
                    $statement->bindParam(':dosen_pa', $dosen_pa);
                    $statement->bindParam(':tgl_lahir', $tgl_lahir);
                    $statement->bindParam(':nomor_hp', $nomor_hp);
                    $statement->bindParam(':tgl_msk_kuliah', $tgl_msk_kuliah);
                    $statement->bindParam(':foto_mhs', $keterangan); // Menggunakan variabel $keterangan sebagai nilai foto_mhs

                    $statement->execute();

                    $response["message"] = "Data Berhasil Direcord!";
                } else {
                    $response["message"] = "Gagal memindahkan file";
                }
            } else {
                $response["message"] = "Hanya diperbolehkan menginput gambar!";
            }
        } else {
            $statement = $conn->prepare("INSERT INTO `mahasiswa`(`nama`, `nim`, `program_studi`, `fakultas`, `dosen_pa`, `tgl_lahir`, `nomor_hp`, `tgl_msk_kuliah`) VALUES (:nama, :nim, :program_studi, :fakultas, :dosen_pa, :tgl_lahir, :nomor_hp, :tgl_msk_kuliah);");

            $statement->bindParam(':nama', $nama);
            $statement->bindParam(':nim', $nim);
            $statement->bindParam(':program_studi', $program_studi);
            $statement->bindParam(':fakultas', $fakultas);
            $statement->bindParam(':dosen_pa', $dosen_pa);
            $statement->bindParam(':tgl_lahir', $tgl_lahir);
            $statement->bindParam(':nomor_hp', $nomor_hp);
            $statement->bindParam(':tgl_msk_kuliah', $tgl_msk_kuliah);

            $statement->execute();
            $response["message"] = "Data berhasil direcord";
        }
    }
} catch (PDOException $e) {
    $response["message"] = "Error: " . $e->getMessage();
}

echo json_encode($response, JSON_PRETTY_PRINT);