<?php
// Koneksi ke database tracking_resto
include "../../koneksi.php";

// Periksa apakah ada data yang dikirimkan melalui URL (ID)
if(isset($_GET['id'])) {
    // Ambil ID dari URL
    $id = $_GET['id'];

    // Query untuk mendapatkan data resep berdasarkan ID
    $result = $conn->query("
        SELECT 
        draft.*,
        summary_soc.*,
        resto.*,
        soc_fat.*, 
        soc_hrga.*, 
        soc_it.*, 
        soc_legal.*, 
        soc_marketing.*, 
        soc_rto.*, 
        soc_sdg.*, 
        note_ba.*, 
        note_legal.*,
        doc_legal.*,
        sign.*
        FROM draft
        INNER JOIN resto ON draft.kode_lahan = resto.kode_lahan
        INNER JOIN summary_soc ON resto.kode_lahan = summary_soc.kode_lahan
        INNER JOIN soc_fat ON summary_soc.kode_lahan = soc_fat.kode_lahan
        INNER JOIN soc_hrga ON soc_fat.kode_lahan = soc_hrga.kode_lahan
        INNER JOIN soc_it ON soc_fat.kode_lahan = soc_it.kode_lahan
        INNER JOIN soc_legal ON soc_fat.kode_lahan = soc_legal.kode_lahan
        INNER JOIN soc_marketing ON soc_fat.kode_lahan = soc_marketing.kode_lahan
        INNER JOIN soc_rto ON soc_fat.kode_lahan = soc_rto.kode_lahan
        INNER JOIN soc_sdg ON soc_fat.kode_lahan = soc_sdg.kode_lahan
        INNER JOIN note_ba ON soc_fat.kode_lahan = note_ba.kode_lahan
        INNER JOIN note_legal ON soc_fat.kode_lahan = note_legal.kode_lahan
        INNER JOIN doc_legal ON note_legal.kode_lahan = doc_legal.kode_lahan
        INNER JOIN sign ON soc_fat.kode_lahan = sign.kode_lahan
        WHERE summary_soc.id = '$id'");

    // Periksa apakah data ditemukan
    if ($result->num_rows > 0) {
        // Ambil data resep
        $row = $result->fetch_assoc();
        $status_go = $row['status_go'];
    } else {
        echo "Data tidak ditemukan.";
    }
}
$options = "";
if ($status_go == "On Schedule") {
    $options = "<option>Pilih</option><option value='On Schedule'>On Schedule</option><option value='Accelerated'>Accelerated</option><option value='Hold'>Hold</option><option value='Delay'>Delay</option>";
} elseif ($status_go == "Hold") {
    $options = "<option>Pilih</option><option value='On Schedule'>On Schedule</option><option value='Accelerated'>Accelerated</option><option value='Hold'>Hold</option><option value='Delay'>Delay</option>";
} elseif ($status_go == "Delay") {
    $options = "<option>Pilih</option><option value='On Schedule'>On Schedule</option><option value='Accelerated'>Accelerated</option><option value='Hold'>Hold</option><option value='Delay'>Delay</option>";
} else {
    // Default jika status tidak sesuai
    $options = "<option>Pilih</option><option value='On Schedule'>On Schedule</option><option value='Accelerated'>Accelerated</option><option value='Hold'>Hold</option><option value='Delay'>Delay</option>";
}
?>

<!DOCTYPE html>
<html lang="en" dir="">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Dashboard Resto | Mie Gacoan</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../dist-assets/css/themes/lite-purple.min.css" rel="stylesheet" />
    <link href="../../dist-assets/css/plugins/perfect-scrollbar.min.css" rel="stylesheet" />
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<body class="text-left">
    <div class="app-admin-wrap layout-sidebar-compact sidebar-dark-purple sidenav-open clearfix">
		<?php
			include '../../layouts/right-sidebar-data.php';
		?>

        <!--=============== Left side End ================-->
        <div class="main-content-wrap d-flex flex-column">
			<?php
			include '../../layouts/top-sidebar.php';
		?>

			<!-- ============ Body content start ============= -->
            <div class="main-content">
                <div class="breadcrumb">
                    <h1>Summary SOC</h1>
                    <ul>
                        <li><a href="href">Form</a></li>
                        <li>Summary SOC</li>
                    </ul>
                </div>
                <div class="separator-breadcrumb border-top"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-5">
                            <div class="card-body">
                            <form method="post" action="summary-soc-edit.php" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="kode_lahan">Kode Lokasi</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" id="kode_lahan" name="kode_lahan" type="text" placeholder="" value="<?php echo $row['kode_lahan']; ?>" readonly/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="go_fix">GO Fix</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" id="go_fix" name="go_fix" type="date" placeholder="" value="<?php echo $row['go_fix']; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="rto_act">RTO Actual</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" id="rto_act" name="rto_act" type="date" placeholder="" value="<?php echo $row['rto_act']; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="type_kitchen">Type Kitchen</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="type_kitchen" name="type_kitchen">
                                            <option value="">Pilih</option>
                                            <option value="double" <?php echo (isset($row['type_kitchen']) && $row['type_kitchen'] == 'Double') ? 'selected' : ''; ?>>Double</option>
                                            <option value="triple" <?php echo (isset($row['type_kitchen']) && $row['type_kitchen'] == 'Triple') ? 'selected' : ''; ?>>Triple</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="jam_ops">Jam Operasional</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" id="jam_ops" name="jam_ops" type="text" placeholder="ex : 8-22" value="<?php echo $row['jam_ops']; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="project_sales">Project Sales</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" id="project_sales" name="project_sales" type="text" placeholder="100.000.000" value="<?php echo $row['project_sales']; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="crew_needed">Crew Needed</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" id="crew_needed" name="crew_needed" type="number" placeholder="ex : 50" value="<?php echo $row['crew_needed']; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="spk_release">SPK Release</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" id="spk_release" name="spk_release" type="date" placeholder="" value="<?php echo $row['spk_release']; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="gocons_progress">RTO Score</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" id="gocons_progress" name="gocons_progress" type="text" placeholder="" value="<?php echo $row['gocons_progress']; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="rto_score">GO Construction Progress</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" id="rto_score" name="rto_score" type="text" placeholder="" value="
                                            <?php 
                                                $total1 = rtrim(50 * number_format(($row['bangunan_mural'] + $row['daya_listrik'] + $row['supply_air'] + $row['aliran_air'] + $row['kualitas_keramik'] + $row['paving_loading']) / 6, 2) / 100, '0') . (number_format(50 * (($row['bangunan_mural'] + $row['daya_listrik'] + $row['supply_air'] + $row['aliran_air'] + $row['kualitas_keramik'] + $row['paving_loading']) / 6 / 100), 2)[strlen(number_format(50 * (($row['bangunan_mural'] + $row['daya_listrik'] + $row['supply_air'] + $row['aliran_air'] + $row['kualitas_keramik'] + $row['paving_loading']) / 6 / 100), 2)) - 1] == '.' ? '0' : '');
                                                $total2 = rtrim(25 * number_format(($row['perijinan'] + $row['sampah_parkir'] + $row['akses_jkm'] + $row['pkl']) / 4, 2) / 100, '0') . (number_format(25 * (($row['perijinan'] + $row['sampah_parkir'] + $row['akses_jkm'] + $row['pkl']) / 4 / 100), 2)[strlen(number_format(25 * (($row['perijinan'] + $row['sampah_parkir'] + $row['akses_jkm'] + $row['pkl']) / 4 / 100), 2)) - 1] == '.' ? '0' : '');
                                                $total3 = rtrim(6 * number_format(($row['cctv'] + $row['audio_system'] + $row['lan_infra'] + $row['internet_cust'] + $row['internet_km']) / 5, 2) / 100, '0') . (number_format(6 * (($row['cctv'] + $row['audio_system'] + $row['lan_infra'] + $row['internet_cust'] + $row['internet_km']) / 5 / 100), 2)[strlen(number_format(6 * (($row['cctv'] + $row['audio_system'] + $row['lan_infra'] + $row['internet_cust'] + $row['internet_km']) / 5 / 100), 2)) - 1] == '.' ? '0' : '');
                                                $total4 = rtrim(8 * number_format(($row['security'] + $row['cs']) / 2, 2) / 100, '0') . (number_format(8 * (($row['security'] + $row['cs']) / 2 / 100), 2)[strlen(number_format(8 * (($row['security'] + $row['cs']) / 2 / 100), 2)) - 1] == '.' ? '0' : '');
                                                $total5 = rtrim(5 * number_format(($row['post_content'] + $row['ojol'] + $row['tikor_maps']) / 3, 2) / 100, '0') . (number_format(5 * (($row['post_content'] + $row['ojol'] + $row['tikor_maps']) / 3 / 100), 2)[strlen(number_format(5 * (($row['post_content'] + $row['ojol'] + $row['tikor_maps']) / 3 / 100), 2)) - 1] == '.' ? '0' : '');
                                                $total6 = rtrim(6 * number_format(($row['qris'] + $row['edc']) / 2, 2) / 100, '0') . (number_format(6 * (($row['qris'] + $row['edc']) / 2 / 100), 2)[strlen(number_format(6 * (($row['qris'] + $row['edc']) / 2 / 100), 2)) - 1] == '.' ? '0' : '');

                                                $total = $total1 + $total2 + $total3 + $total4 + $total5 + $total6;
                                                echo $total; 
                                            ?>%" readonly/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="status_go">Status GO</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="status_go" name="status_go">
                                            <?php echo $options; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10">
                                        <button class="btn btn-primary" type="submit">Simpan</button>
                                    </div>
                                </div>
                            </form>

                            </div>
                        </div>
                    </div>
                </div>

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
    <script src="../../dist-assets/js/plugins/jquery-3.3.1.min.js"></script>
    <script src="../../dist-assets/js/plugins/bootstrap.bundle.min.js"></script>
    <script src="../../dist-assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../../dist-assets/js/scripts/script.min.js"></script>
    <script src="../../dist-assets/js/scripts/sidebar.compact.script.min.js"></script>
    <script src="../../dist-assets/js/scripts/customizer.script.min.js"></script>
    
</body>

</html>