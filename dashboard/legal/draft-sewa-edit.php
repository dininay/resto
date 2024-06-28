<?php
// Koneksi ke database tracking_resto
include "../../koneksi.php";

// Proses jika ada pengiriman data dari formulir
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil nilai tgl_berlaku dan penanggungjawab dari formulir
    $id = $_POST['id'];

    $catatan = $_POST["catatan_legal"];
$jadwal_psm = $_POST["jadwal_psm"];
// Periksa apakah kunci 'lampiran' ada dalam $_FILES
$lamp_draf = "";

    if(isset($_FILES["lamp_draf"])) {
        $lamp_draf_paths = array();

        // Loop through each file
        foreach($_FILES['lamp_draf']['name'] as $key => $filename) {
            $file_tmp = $_FILES['lamp_draf']['tmp_name'][$key];
            $file_name = $_FILES['lamp_draf']['name'][$key];
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($file_name);

            // Attempt to move the uploaded file to the target directory
            if (move_uploaded_file($file_tmp, $target_file)) {
                $lamp_draf_paths[] = $target_file;
            } else {
                echo "Gagal mengunggah file " . $file_name . "<br>";
            }
        }

        // Join all file paths into a comma-separated string
        $lamp_draf = implode(",", $lamp_draf_paths);
    }

    // Update data di database
    $sql = "UPDATE draft SET lamp_draf = '$lamp_draf', catatan_legal = '$catatan', jadwal_psm = '$jadwal_psm' WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: /Resto/dashboard/datatables-draft-sewa-legal.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Menutup koneksi database
$conn->close();
?>
