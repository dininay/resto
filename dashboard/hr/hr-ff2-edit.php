<?php
// Koneksi ke database tracking_resto
include "../../koneksi.php";

// Proses jika ada pengiriman data dari formulir
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil nilai tgl_berlaku dan penanggungjawab dari formulir
    $id = $_POST['id'];
    $ff_2 = $_POST['ff_2'];
    // Periksa apakah kunci 'lampiran' ada dalam $_FILES
    $lamp_ff2 = "";

    if(isset($_FILES["lamp_ff2"])) {
        $lamp_ff2_paths = array();

        // Loop through each file
        foreach($_FILES['lamp_ff2']['name'] as $key => $filename) {
            $file_tmp = $_FILES['lamp_ff2']['tmp_name'][$key];
            $file_name = $_FILES['lamp_ff2']['name'][$key];
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($file_name);

            // Attempt to move the uploaded file to the target directory
            if (move_uploaded_file($file_tmp, $target_file)) {
                $lamp_ff2_paths[] = $target_file;
            } else {
                echo "Gagal mengunggah file " . $file_name . "<br>";
            }
        }

        // Join all file paths into a comma-separated string
        $lamp_ff2 = implode(",", $lamp_ff2_paths);
    }

    // Update data di database
    $sql = "UPDATE socdate_hr SET lamp_ff2 = '$lamp_ff2', ff_2 = '$ff_2' WHERE id = '$id'";
    // var_dump($sql);
    if ($conn->query($sql) === TRUE) {
        header("Location: /Resto/dashboard/datatables-hr-fulfillment-2.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Menutup koneksi database
$conn->close();
?>
