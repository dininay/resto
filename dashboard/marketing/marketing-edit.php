<?php
// Koneksi ke database tracking_resto
include "../../koneksi.php";

// Proses jika ada pengiriman data dari formulir
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil nilai tgl_berlaku dan penanggungjawab dari formulir
    $id = $_POST['id'];
    $gmaps = $_POST['gmaps'];
    $id_m_gojek = $_POST['id_m_gojek'];
    $id_m_shopee = $_POST['id_m_shopee'];
    $id_m_grab = $_POST['id_m_grab'];
    $email_resto = $_POST['email_resto'];
    // Periksa apakah kunci 'lampiran' ada dalam $_FILES
    $lamp_gmaps = "";

    if(isset($_FILES["lamp_gmaps"])) {
        $lamp_gmaps_paths = array();

        // Loop through each file
        foreach($_FILES['lamp_gmaps']['name'] as $key => $filename) {
            $file_tmp = $_FILES['lamp_gmaps']['tmp_name'][$key];
            $file_name = $_FILES['lamp_gmaps']['name'][$key];
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($file_name);

            // Attempt to move the uploaded file to the target directory
            if (move_uploaded_file($file_tmp, $target_file)) {
                $lamp_gmaps_paths[] = $target_file;
            } else {
                echo "Gagal mengunggah file " . $file_name . "<br>";
            }
        }

        // Join all file paths into a comma-separated string
        $lamp_gmaps = implode(",", $lamp_gmaps_paths);
    }

    // Update data di database
    $sql = "UPDATE socdate_marketing SET lamp_gmaps = '$lamp_gmaps', gmaps = '$gmaps', id_m_gojek = '$id_m_gojek', id_m_grab = '$id_m_grab', id_m_shopee = '$id_m_shopee', email_resto = '$email_resto' WHERE id = '$id'";
    // var_dump($sql);
    if ($conn->query($sql) === TRUE) {
        header("Location: /Resto/dashboard/datatables-marketing.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Menutup koneksi database
$conn->close();
?>
