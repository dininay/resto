<?php
// Koneksi ke database
include "../../koneksi.php";

// Proses jika ada pengiriman data dari formulir untuk memperbarui status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["kode_lahan"]) && isset($_POST["status_approvowner"]) && isset($_POST["catatan_owner"])) {
    $kode_lahan = $_POST["kode_lahan"];
    $status_approvowner = $_POST["status_approvowner"];
    $catatan_owner = $_POST["catatan_owner"];

    // Inisialisasi variabel untuk status_approvlegal
    $status_approvlegal = null;
    $start_date = null;

    // Jika status_approvowner diubah menjadi Approve, ubah status_approvlegal menjadi In Process
    if ($status_approvowner == 'Approve') {
        $status_approvlegal = 'In Process';
        $status_vl = 'In Process';
        $start_date = date("Y-m-d H:i:s");

        // // Ambil jumlah hari SLA dari tabel master_sla berdasarkan divisi = VL
        // $sql_select_sla_vl = "SELECT sla FROM master_sla WHERE divisi = 'VL'";
        // $result_select_sla_vl = $conn->query($sql_select_sla_vl);

        // if ($result_select_sla_vl && $result_select_sla_vl->num_rows > 0) {
        //     $row_sla_vl = $result_select_sla_vl->fetch_assoc();
        //     $sla_vl_days = $row_sla_vl['sla'];

        //     // Tambahkan jumlah hari SLA VL ke start_date untuk mendapatkan slavl_date
        //     $slavl_date = date('Y-m-d H:i:s', strtotime($start_date . ' + ' . $sla_vl_days . ' days'));
        // } else {
        //     echo "Error: Tidak dapat mengambil data SLA VL dari tabel master_sla.";
        //     exit();
        // }

        // Ambil jumlah hari SLA dari tabel master_sla berdasarkan divisi = Legal
        $sql_select_sla_legal = "SELECT sla FROM master_sla WHERE divisi = 'Legal'";
        $result_select_sla_legal = $conn->query($sql_select_sla_legal);

        if ($result_select_sla_legal && $result_select_sla_legal->num_rows > 0) {
            $row_sla_legal = $result_select_sla_legal->fetch_assoc();
            $sla_legal_days = $row_sla_legal['sla'];

            // Tambahkan jumlah hari SLA Legal ke start_date untuk mendapatkan slalegal_date
            $slalegal_date = date('Y-m-d H:i:s', strtotime($start_date . ' + ' . $sla_legal_days . ' days'));
        } else {
            echo "Error: Tidak dapat mengambil data SLA Legal dari tabel master_sla.";
            exit();
        }

        // Mulai transaksi
        $conn->begin_transaction();

        try {
            // Query untuk memperbarui status_approvowner, catatan_owner, status_approvlegal, start_date, slalegal_date, status_vl, dan slavl_date
            $sql = "UPDATE re SET status_approvowner = ?, catatan_owner = ?, status_approvlegal = ?, start_date = ?, slalegal_date = ? WHERE kode_lahan = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $status_approvowner, $catatan_owner, $status_approvlegal, $start_date, $slalegal_date, $kode_lahan);
            $stmt->execute();

            // Komit transaksi
            $conn->commit();
            echo "Status berhasil diperbarui.";
        } catch (Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        }
    } elseif ($status_approvowner == 'Reject') {
        // Ambil kode lahan sebelum menghapus dari tabel re
        $sql = "SELECT kode_lahan FROM re WHERE kode_lahan = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $kode_lahan);
        $stmt->execute();
        $stmt->bind_result($kode_lahan);
        $stmt->fetch();
        $stmt->close();

        // Mulai transaksi
        $conn->begin_transaction();

        try {
            // Hapus data dari tabel re berdasarkan kode_lahan
            $sql = "DELETE FROM re WHERE kode_lahan = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $kode_lahan);
            $stmt->execute();

            // Perbarui status_land menjadi Reject pada tabel land berdasarkan kode lahan
            $sql = "UPDATE land SET status_land = 'Reject' WHERE kode_lahan = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $kode_lahan);
            $stmt->execute();

            // Komit transaksi
            $conn->commit();
            echo "Data berhasil dihapus dan status berhasil diperbarui.";
        } catch (Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        }
    } else {
        // Jika status tidak diubah menjadi Approve atau Reject, hanya perlu memperbarui status_approvowner
        $sql = "UPDATE re SET status_approvowner = ? WHERE kode_lahan = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $status_approvowner, $kode_lahan);

        // Eksekusi query
        if ($stmt->execute() === TRUE) {
            echo "<script>
                    alert('Status berhasil diperbarui.');
                    window.location.href = window.location.href;
                 </script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    // Redirect ke halaman datatables-approval-owner.php
    header("Location: ../datatables-approval-owner.php");
    exit;
}
?>
