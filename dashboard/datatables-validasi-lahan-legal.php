<?php
// Koneksi ke database
include "../koneksi.php";

// Proses jika ada pengiriman data dari formulir untuk memperbarui status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["kode_lahan"]) && isset($_POST["status_approvlegal"]) && isset($_POST["catatan_legal"])) {
    $kode_lahan = $_POST["kode_lahan"];
    $status_approvlegal = $_POST["status_approvlegal"];
    $catatan_legal = $_POST["catatan_legal"];

    // Inisialisasi variabel untuk status_approvlegal
    $status_approvnego = null;
    $end_date = null;
    $nego_date = null;

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        if ($status_approvlegal == 'Approve') {
            $status_approvnego = 'In Process';
            $status_vl = 'In Process';
            $end_date = date("Y-m-d H:i:s");

            // Ambil jumlah hari SLA dari tabel master_sla berdasarkan divisi = VL
            $sql_select_sla_vl = "SELECT sla FROM master_sla WHERE divisi = 'VL'";
            $result_select_sla_vl = $conn->query($sql_select_sla_vl);

            if ($result_select_sla_vl && $result_select_sla_vl->num_rows > 0) {
                $row_sla_vl = $result_select_sla_vl->fetch_assoc();
                $sla_vl_days = $row_sla_vl['sla'];

                // Tambahkan jumlah hari SLA VL ke end_date untuk mendapatkan vl_date
                $vl_date = date('Y-m-d H:i:s', strtotime($end_date . ' + ' . $sla_vl_days . ' days'));

                // Query untuk memperbarui status_approvlegal, status_approvnego, status_vl, end_date, dan vl_date
                $sql = "UPDATE re SET status_approvlegal = ?, catatan_legal = ?, status_approvnego = ?, end_date = ? WHERE kode_lahan = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssss", $status_approvlegal, $catatan_legal, $status_approvnego, $end_date, $kode_lahan);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "Error: Tkode_lahanak dapat mengambil data SLA VL dari tabel master_sla.";
            }
        } elseif ($status_approvlegal == 'Reject') {
            // Ambil kode lahan sebelum menghapus dari tabel re
            $sql_select_kode_lahan = "SELECT kode_lahan FROM re WHERE kode_lahan = ?";
            $stmt_select_kode_lahan = $conn->prepare($sql_select_kode_lahan);
            $stmt_select_kode_lahan->bind_param("i", $kode_lahan);
            $stmt_select_kode_lahan->execute();
            $stmt_select_kode_lahan->bind_result($kode_lahan);
            $stmt_select_kode_lahan->fetch();
            $stmt_select_kode_lahan->close();

            // Hapus data dari tabel re berdasarkan kode_lahan
            $sql_delete_re = "DELETE FROM re WHERE kode_lahan = ?";
            $stmt_delete_re = $conn->prepare($sql_delete_re);
            $stmt_delete_re->bind_param("s", $kode_lahan);
            $stmt_delete_re->execute();
            $stmt_delete_re->close();

            // Perbarui status_land menjadi Reject pada tabel land berdasarkan kode lahan
            $sql_update_land = "UPDATE land SET status_land = 'Reject', status_approve = 'In Process' WHERE kode_lahan = ?";
            $stmt_update_land = $conn->prepare($sql_update_land);
            $stmt_update_land->bind_param("s", $kode_lahan);
            $stmt_update_land->execute();
            $stmt_update_land->close();
        } else {
            // Query untuk memperbarui status_approvlegal
            $sql = "UPDATE re SET status_approvlegal = ? WHERE kode_lahan = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $status_approvlegal, $kode_lahan);
            $stmt->execute();
            $stmt->close();
        }

        // Komit transaksi
        $conn->commit();
        echo "Status berhasil diperbarui.";
            // Redirect ke halaman datatables-kom-sdgpk.php
    header("Location: datatables-validasi-lahan-legal.php");
    exit; // Pastikan tidak ada output lain setelah header redirect
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

// Query untuk mengambil data dari tabel land dengan status_approvowner 'Approve'
$sql = "SELECT l.*, r.*
FROM land l
JOIn re r ON l.kode_lahan = r.kode_lahan
WHERE status_approvowner = 'Approve' AND status_approvlegal IN ('In Process', 'Pending', 'Approve')";
$result = $conn->query($sql);

// Inisialisasi variabel $data dengan array kosong
$data = [];

// Periksa apakah query mengembalikan hasil yang valid
if ($result && $result->num_rows > 0) {
    // Ambil data dan masukkan ke dalam array $data
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Dashboard Resto | Mie Gacoan<</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet" />
    <link href="../dist-assets/css/themes/lite-purple.min.css" rel="stylesheet" />
    <link href="../dist-assets/css/plugins/perfect-scrollbar.min.css" rel="stylesheet" />
    <link href="../dist-assets/css/plugins/datatables.min.css" rel="stylesheet"  />
	<link rel="stylesheet" type="text/css" href="../dist-assets/css/feather-icon.css">
	<link rel="stylesheet" type="text/css" href="../dist-assets/css/icofont.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="text-left">
    <div class="app-admin-wrap layout-sidebar-compact sidebar-dark-purple sidenav-open clearfix">
        <?php
			include '../layouts/right-sidebar.php';
		?>
        <!--=============== Left side End ================-->
        <div class="main-content-wrap d-flex flex-column">
            <?php
			include '../layouts/top-sidebar.php';
		?>
			<!-- ============ Body content start ============= -->
            <div class="main-content">
                <div class="breadcrumb">
                    <h1>Datatables Validasi Lahan From RE</h1>
                </div>
                <div class="separator-breadcrumb border-top"></div>
                <!-- end of row-->
                <div class="row mb-4">
                    <div class="col-md-12 mb-4">
                        <div class="card text-left">
                            <div class="card-body">
                                <h4 class="card-title mb-3"></h4>
								<div class="footer-bottom border-top float-right">
									  <span class="flex-grow-1"></span></p>
								</div>
                                <p>
							  <div class="table-responsive">
                              <table class="display table table-striped table-bordered" id="zero_configuration_table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Kode Lokasi</th>
                                                <th>Nama Lokasi</th>
                                                <th>Alamat Lokasi</th>
                                                <th>Luas Area</th>
                                                <th>Approval Owner</th>
                                                <th>Catatan Owner</th>
                                                <th>Start Date</th>
                                                <th>Approval Legal</th>
                                                <th>Catatan Legal</th>
                                                <th>End Date</th>
                                                <th>Lampiran</th>
                                                <th>SLA</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($data as $row): ?>
                                            <tr>
                                                <td><?= $row['kode_lahan'] ?></td>
                                                <td><?= $row['nama_lahan'] ?></td>
                                                <td><?= $row['lokasi'] ?></td>
                                                <td><?= $row['luas_area'] ?></td>
                                                <td>
                                                    <?php
                                                        // Tentukan warna badge berdasarkan status approval owner
                                                        $badge_color = '';
                                                        switch ($row['status_approvowner']) {
                                                            case 'Approve':
                                                                $badge_color = 'success';
                                                                break;
                                                            case 'Pending':
                                                                $badge_color = 'danger';
                                                                break;
                                                            case 'In Process':
                                                                $badge_color = 'warning';
                                                                break;
                                                            default:
                                                                $badge_color = 'secondary'; // Warna default jika status tidak dikenali
                                                                break;
                                                        }
                                                    ?>
                                                    <span class="badge rounded-pill badge-<?php echo $badge_color; ?>">
                                                        <?php echo $row['status_approvowner']; ?>
                                                    </span>
                                                </td>
                                                <td><?= $row['catatan_owner'] ?></td>
                                                <td><?= $row['start_date'] ?></td>
                                                <td>
                                                    <?php
                                                        // Tentukan warna badge berdasarkan status approval owner
                                                        $badge_color = '';
                                                        switch ($row['status_approvlegal']) {
                                                            case 'Approve':
                                                                $badge_color = 'success';
                                                                break;
                                                            case 'Pending':
                                                                $badge_color = 'danger';
                                                                break;
                                                            case 'In Process':
                                                                $badge_color = 'warning';
                                                                break;
                                                            default:
                                                                $badge_color = 'secondary'; // Warna default jika status tidak dikenali
                                                                break;
                                                        }
                                                    ?>
                                                    <span class="badge rounded-pill badge-<?php echo $badge_color; ?>">
                                                        <?php echo $row['status_approvlegal']; ?>
                                                    </span>
                                                </td>
                                                <td><?= $row['catatan_legal'] ?></td>
                                                <td><?= $row['end_date'] ?></td>
                                                <?php
                                                // Bagian ini di dalam loop yang menampilkan data tabel
                                                $lamp_land_files = explode(",", $row['lamp_land']); // Pisahkan nama file menjadi array
                                                ?>

                                                <td>
                                                    <ul style="list-style-type: none; padding: 0; margin: 0;">
                                                        <?php foreach ($lamp_land_files as $file): ?>
                                                            <li style="display: inline-block; margin-right: 5px;">
                                                                <a href="uploads/<?= $file ?>" target="_blank">
                                                                    <i class="fas fa-file-pdf nav-icon"></i>
                                                                </a>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </td>
                                                <td>
                                                    <?php
                                                    // Mendapatkan tanggal sla_date dari kolom data
                                                    $slaLegalDate = new DateTime($row['slalegal_date']);
                                                    
                                                    // Mendapatkan tanggal hari ini
                                                    $today = new DateTime();
                                                    
                                                    // Menghitung selisih hari antara sla_date dan hari ini
                                                    $diff = $today->diff($slaLegalDate);
                                                    
                                                    // Jika status_approvowner adalah "Approve"
                                                    if ($row['status_approvlegal'] == "Approve") {
                                                        echo '<button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#approvalModal">Done</button>';
                                                        echo '<p>Status changed to Approved on: ' . $row['end_date'] . '</p>';
                                                    } else {
                                                        // Menghitung jumlah hari terlambat
                                                        $lateDays = $slaLegalDate->diff($today)->days;
                                                        
                                                        // Jika terlambat
                                                        if ($today > $slaLegalDate) {
                                                            echo '<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#lateApprovalModal">Terlewat ' . $lateDays . ' hari</button>';
                                                        } else {
                                                            // Jika selisih kurang dari atau sama dengan 5 hari, tampilkan peringatan "H - X"
                                                            if ($diff) {
                                                                echo '<button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#deadlineModal">H - ' . $diff->days . '</button>';
                                                            } else {
                                                                // Tampilkan peringatan "H + X"
                                                                echo '<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deadlineModal">H + ' . $diff->days . ' hari</button>';
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <!-- Tombol Edit -->
                                                    <?php if ($row['status_approvlegal'] != "Approve"): ?>
                                                        <button class="btn btn-sm btn-warning edit-btn" data-toggle="modal" data-target="#editModal" data-id="<?= $row['kode_lahan'] ?>" data-status="<?= $row['status_approvlegal'] ?>">
                                                            <i class="nav-icon i-Pen-2"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </td>

                                                <!-- Modal -->
                                                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel">Edit Status</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="statusForm" method="post" action="">
                                                                    <input type="hidden" name="kode_lahan" id="modalKodeLahan">
                                                                    <div class="form-group">
                                                                        <label for="statusSelect">Status Approve Legal</label>
                                                                        <select class="form-control" id="statusSelect" name="status_approvlegal">
                                                                            <option value="In Process">In Process</option>
                                                                            <option value="Pending">Pending</option>
                                                                            <option value="Approve">Approve</option>
                                                                            <option value="Reject">Reject</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="catatan">Catatan Legal</label>
                                                                        <input type="text" class="form-control" id="catatan" name="catatan_legal">
                                                                    </div>
                                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Kode Lokasi</th>
                                                <th>Nama Lokasi</th>
                                                <th>Alamat Lokasi</th>
                                                <th>Luas Area</th>
                                                <th>Approval Owner</th>
                                                <th>Catatan Owner</th>
                                                <th>Start Date</th>
                                                <th>Approval Legal</th>
                                                <th>Catatan Legal</th>
                                                <th>End Date</th>
                                                <th>SLA Date</th>
                                                <th>Lampiran</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <!-- Modal untuk "Tepat Waktu" -->
                                    <div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="approvalModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="approvalModalLabel">Pemberitahuan</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Data sudah approve tepat waktu.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal untuk "Deadline Approval" -->
                                    <div class="modal fade" id="deadlineModal" tabindex="-1" role="dialog" aria-labelledby="deadlineModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deadlineModalLabel">Pemberitahuan</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Tersisa waktu <?php echo $diff->days; ?> hari, segera lakukan approval.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal untuk "Terlambat Approval" -->
                                    <div class="modal fade" id="lateApprovalModal" tabindex="-1" role="dialog" aria-labelledby="lateApprovalModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="lateApprovalModalLabel">Pemberitahuan</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Data sudah terlambat untuk di-approve. Telah terlambat <?php echo $lateDays; ?> hari.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end of col-->
                    <!-- end of col-->
                    <!-- end of col-->
                    <!-- end of col-->
                    <!-- end of col-->
                    <!-- end of col-->
                    <!-- end of col-->
                    <!-- end of col-->
                    <!-- end of col-->
                    <!-- end of col-->
                    <!-- end of col-->
                    <!-- end of col-->
                    <!-- end of col-->
                    <!-- end of col-->
                </div>
                <!-- end of row-->
                <!-- end of main-content -->
                <!-- Footer Start -->
                <div class="flex-grow-1"></div>
                <!-- fotter end -->
            </div>
        </div>
    </div><!-- ============ Search UI Start ============= -->
    <div class="search-ui">
        <div class="search-header">
            <img src="../../dist-assets/images/logo.png" alt="" class="logo">
            <button class="search-close btn btn-icon bg-transparent float-right mt-2">
                <i class="i-Close-Window text-22 text-muted"></i>
            </button>
        </div>
        <input type="text" placeholder="Type here" class="search-input" autofocus>
        <div class="search-title">
            <span class="text-muted">Search results</span>
        </div>
        <div class="search-results list-horizontal">
            <div class="list-item col-md-12 p-0">
                <div class="card o-hidden flex-row mb-4 d-flex">
                    <div class="list-thumb d-flex">
                        <!-- TUMBNAIL -->
                        <img src="../../dist-assets/images/products/headphone-1.jpg" alt="">
                    </div>
                    <div class="flex-grow-1 pl-2 d-flex">
                        <div class="card-body align-self-center d-flex flex-column justify-content-between align-items-lg-center flex-lg-row">
                            <!-- OTHER DATA -->
                            <a href="" class="w-40 w-sm-100">
                                <div class="item-title">Headphone 1</div>
                            </a>
                            <p class="m-0 text-muted text-small w-15 w-sm-100">Gadget</p>
                            <p class="m-0 text-muted text-small w-15 w-sm-100">$300
                                <del class="text-secondary">$400</del>
                            </p>
                            <p class="m-0 text-muted text-small w-15 w-sm-100 d-none d-lg-block item-badges">
                                <span class="badge badge-danger">Sale</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="list-item col-md-12 p-0">
                <div class="card o-hidden flex-row mb-4 d-flex">
                    <div class="list-thumb d-flex">
                        <!-- TUMBNAIL -->
                        <img src="../../dist-assets/images/products/headphone-2.jpg" alt="">
                    </div>
                    <div class="flex-grow-1 pl-2 d-flex">
                        <div class="card-body align-self-center d-flex flex-column justify-content-between align-items-lg-center flex-lg-row">
                            <!-- OTHER DATA -->
                            <a href="" class="w-40 w-sm-100">
                                <div class="item-title">Headphone 1</div>
                            </a>
                            <p class="m-0 text-muted text-small w-15 w-sm-100">Gadget</p>
                            <p class="m-0 text-muted text-small w-15 w-sm-100">$300
                                <del class="text-secondary">$400</del>
                            </p>
                            <p class="m-0 text-muted text-small w-15 w-sm-100 d-none d-lg-block item-badges">
                                <span class="badge badge-primary">New</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="list-item col-md-12 p-0">
                <div class="card o-hidden flex-row mb-4 d-flex">
                    <div class="list-thumb d-flex">
                        <!-- TUMBNAIL -->
                        <img src="../../dist-assets/images/products/headphone-3.jpg" alt="">
                    </div>
                    <div class="flex-grow-1 pl-2 d-flex">
                        <div class="card-body align-self-center d-flex flex-column justify-content-between align-items-lg-center flex-lg-row">
                            <!-- OTHER DATA -->
                            <a href="" class="w-40 w-sm-100">
                                <div class="item-title">Headphone 1</div>
                            </a>
                            <p class="m-0 text-muted text-small w-15 w-sm-100">Gadget</p>
                            <p class="m-0 text-muted text-small w-15 w-sm-100">$300
                                <del class="text-secondary">$400</del>
                            </p>
                            <p class="m-0 text-muted text-small w-15 w-sm-100 d-none d-lg-block item-badges">
                                <span class="badge badge-primary">New</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="list-item col-md-12 p-0">
                <div class="card o-hidden flex-row mb-4 d-flex">
                    <div class="list-thumb d-flex">
                        <!-- TUMBNAIL -->
                        <img src="../../dist-assets/images/products/headphone-4.jpg" alt="">
                    </div>
                    <div class="flex-grow-1 pl-2 d-flex">
                        <div class="card-body align-self-center d-flex flex-column justify-content-between align-items-lg-center flex-lg-row">
                            <!-- OTHER DATA -->
                            <a href="" class="w-40 w-sm-100">
                                <div class="item-title">Headphone 1</div>
                            </a>
                            <p class="m-0 text-muted text-small w-15 w-sm-100">Gadget</p>
                            <p class="m-0 text-muted text-small w-15 w-sm-100">$300
                                <del class="text-secondary">$400</del>
                            </p>
                            <p class="m-0 text-muted text-small w-15 w-sm-100 d-none d-lg-block item-badges">
                                <span class="badge badge-primary">New</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- PAGINATION CONTROL -->
        <div class="col-md-12 mt-5 text-center">
            <nav aria-label="Page navigation example">
                <ul class="pagination d-inline-flex">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <!-- ============ Search UI End ============= -->
    <div class="customizer">
        <div class="handle"><i class="i-Gear spin"></i></div>
        <div class="customizer-body" data-perfect-scrollbar="" data-suppress-scroll-x="true">
            <div class="accordion" id="accordionCustomizer">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <p class="mb-0">Sidebar Colors</p>
                    </div>
                    <div class="collapse show" id="collapseOne" aria-labelledby="headingOne" data-parent="#accordionCustomizer">
                        <div class="card-body">
                            <div class="colors sidebar-colors"><a class="color gradient-purple-indigo" data-sidebar-class="sidebar-gradient-purple-indigo"><i class="i-Eye"></i></a><a class="color gradient-black-blue" data-sidebar-class="sidebar-gradient-black-blue"><i class="i-Eye"></i></a><a class="color gradient-black-gray" data-sidebar-class="sidebar-gradient-black-gray"><i class="i-Eye"></i></a><a class="color gradient-steel-gray" data-sidebar-class="sidebar-gradient-steel-gray"><i class="i-Eye"></i></a><a class="color dark-purple active" data-sidebar-class="sidebar-dark-purple"><i class="i-Eye"></i></a><a class="color slate-gray" data-sidebar-class="sidebar-slate-gray"><i class="i-Eye"></i></a><a class="color midnight-blue" data-sidebar-class="sidebar-midnight-blue"><i class="i-Eye"></i></a><a class="color blue" data-sidebar-class="sidebar-blue"><i class="i-Eye"></i></a><a class="color indigo" data-sidebar-class="sidebar-indigo"><i class="i-Eye"></i></a><a class="color pink" data-sidebar-class="sidebar-pink"><i class="i-Eye"></i></a><a class="color red" data-sidebar-class="sidebar-red"><i class="i-Eye"></i></a><a class="color purple" data-sidebar-class="sidebar-purple"><i class="i-Eye"></i></a></div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <p class="mb-0">RTL</p>
                    </div>
                    <div class="collapse show" id="collapseTwo" aria-labelledby="headingTwo" data-parent="#accordionCustomizer">
                        <div class="card-body">
                            <label class="checkbox checkbox-primary">
                                <input id="rtl-checkbox" type="checkbox" /><span>Enable RTL</span><span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../dist-assets/js/plugins/jquery-3.3.1.min.js"></script>
    <script src="../dist-assets/js/plugins/bootstrap.bundle.min.js"></script>
    <script src="../dist-assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../dist-assets/js/scripts/script.min.js"></script>
    <script src="../dist-assets/js/scripts/sidebar.compact.script.min.js"></script>
    <script src="../dist-assets/js/scripts/customizer.script.min.js"></script>
    <script src="../dist-assets/js/plugins/datatables.min.js"></script>
    <script src="../dist-assets/js/scripts/datatables.script.min.js"></script>
	<script src="../dist-assets/js/icons/feather-icon/feather.min.js"></script>
    <script src="../dist-assets/js/icons/feather-icon/feather-icon.js"></script>
    
    <script>
    // JavaScript to handle opening the modal and setting form values
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var kodeLahan = button.data('id'); // Extract info from data-* attributes
        var status = button.data('status'); // Extract status

        // Update the modal's content.
        var modal = $(this);
        modal.find('#modalKodeLahan').val(kodeLahan);
        modal.find('#statusSelect').val(status);
    });
</script>

    <script>
$(document).ready(function() {
    $(".edit-btn").click(function() {
        // Sembunyikan semua form yang terbuka
        $(".status-form").hide();
        // Tampilkan form di samping tombol edit yang diklik
        $(this).next(".status-form").show();
    });
});
</script>
</body>

</html>