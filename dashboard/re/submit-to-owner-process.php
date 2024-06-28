<?php
// Koneksi ke database
include "../../koneksi.php";

// Proses jika ada pengiriman data dari formulir untuk memperbarui status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"]) && isset($_POST["status_approvre"])) {
    $id = $_POST["id"];
    $status_approvre = $_POST["status_approvre"];

    // Inisialisasi variabel untuk status_approvlegal
    $re_date = date("Y-m-d");

    // Jika status_approvre diubah menjadi Approve, ubah re_date menjadi tanggal dan ambil jumlah dari tabel master_sla
    if ($status_approvre == 'Approve') {
        $re_date = date("Y-m-d H:i:s");

        
        // Ambil jumlah hari SLA dari tabel master_sla berdasarkan divisi = VL
        $sql_select_sla_vl = "SELECT sla FROM master_sla WHERE divisi = 'VL'";
        $result_select_sla_vl = $conn->query($sql_select_sla_vl);

        if ($result_select_sla_vl && $result_select_sla_vl->num_rows > 0) {
            $row_sla_vl = $result_select_sla_vl->fetch_assoc();
            $sla_vl_days = $row_sla_vl['sla'];

            // Tambahkan jumlah hari SLA VL ke start_date untuk mendapatkan slavl_date
            $slavl_date = date('Y-m-d H:i:s', strtotime($re_date . ' + ' . $sla_vl_days . ' days'));
        } else {
            echo "Error: Tidak dapat mengambil data SLA VL dari tabel master_sla.";
            exit();
        }

        // Ambil jumlah dari tabel master_sla dengan divisi = Owner Surveyor
        $sql_select_sla = "SELECT sla FROM master_sla WHERE divisi = 'Owner Surveyor'";
        $result_select_sla = $conn->query($sql_select_sla);

        if ($result_select_sla && $result_select_sla->num_rows > 0) {
            $row_sla = $result_select_sla->fetch_assoc();
            $sla = $row_sla['sla'];
        } else {
            echo "Error: Tidak dapat mengambil data SLA dari tabel master_sla.";
            exit();
        }
    }

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Query untuk memperbarui status_approvre dan re_date pada tabel land
        $sql = "UPDATE land SET status_approvre = ?, re_date = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $status_approvre, $re_date, $id);

        // Eksekusi query untuk memperbarui status_approvre dan re_date
        if ($stmt->execute() === TRUE) {
            // Jika status diubah menjadi Approve, tambahkan data ke tabel re
            if ($status_approvre == 'Approve') {
                // Hitung sla_date sebagai penjumlahan re_date dengan jumlah sla
                $sla_date = date('Y-m-d', strtotime($re_date . ' + ' . $sla . ' days'));

                // Ambil data dari tabel land
                $sql_select_land = "SELECT kode_lahan FROM land WHERE id = ?";
                $stmt_select_land = $conn->prepare($sql_select_land);
                $stmt_select_land->bind_param("i", $id);
                $stmt_select_land->execute();
                $stmt_select_land->bind_result($kode_lahan);
                $stmt_select_land->fetch();
                $stmt_select_land->close();

                // Insert data ke tabel re
                $status_approvowner = 'In Process'; // Sesuaikan dengan data yang diperlukan
                $status_vl = 'In Process'; 

                $sql_insert = "INSERT INTO re (kode_lahan, status_approvowner, sla_date, status_vl, slavl_date) 
                               VALUES (?, ?, ?, ?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bind_param("sssss", $kode_lahan, $status_approvowner, $sla_date, $status_vl, $slavl_date);
                $stmt_insert->execute();
            }

            // Komit transaksi
            $conn->commit();
            echo "Status berhasil diperbarui.";
        } else {
            // Rollback transaksi jika terjadi kesalahan
            $conn->rollback();
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        // Redirect ke halaman datatables-kom-sdgpk.php
    header("Location: ../datatables-submit-to-owner.php");
    exit; // Pastikan tidak ada output lain setelah header redirect
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}
?>