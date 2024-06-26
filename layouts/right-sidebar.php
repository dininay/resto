<?php

session_start();


// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    // Jika belum login, alihkan ke halaman login
    header("location: /resto/index.php?pesan=belum_login");
    exit;
}

include '../koneksi.php';
$user_level = $_SESSION['level']; // Ganti dengan level pengguna yang sesuai

// Fungsi untuk menampilkan submenu sesuai dengan level pengguna
function displaySubMenu($submenu_name, $user_level) {
    // Tentukan daftar submenu berdasarkan level pengguna
    $submenus = array(
        "Legal Dept" => array("Doc Validation from RE", "Chacklist Validasi Data", "Drafting Akta Sewa"),
        "RE Dept" => array("Land Sourcing", "Approval by owner", "Document Confirmation", "Validation Land to Legal", "LoA & CD", "Validasi Data to Legal"),
        "Others" => array("Not Found", "User Profile", "Blank Page")
    );

    // Jika level pengguna adalah admin, tampilkan semua submenu
    if ($user_level === "Admin") {
        return $submenus[$submenu_name];
    } elseif (array_key_exists($submenu_name, $submenus)) {
        // Jika submenu tersedia untuk level pengguna yang sesuai, tampilkan submenu tersebut
        return $submenus[$submenu_name];
    } else {
        // Jika tidak ada submenu yang sesuai, kembalikan array kosong
        return array();
    }
}

$current_page = basename($_SERVER['REQUEST_URI'], ".php");

?>
<style>
    .nav-item.active a {
    background-color: #f0f0f0; /* Warna latar belakang untuk item aktif */
    color: #333; /* Warna teks untuk item aktif */
}

.nav-item.active a .nav-icon {
    color: #333; /* Warna ikon untuk item aktif */
}

</style>
       <div class="side-content-wrap">
            <div class="sidebar-left open rtl-ps-none" data-perfect-scrollbar data-suppress-scroll-x="true">
                <ul class="navigation-left">
                    <!-- Dashboard -->
                    <?php if ($_SESSION['level'] === "Admin") : ?>
                    <li class="nav-item <?php echo ($current_page == 'index' ||$current_page == 'datatables-analytics' || $current_page == 'datatables-report' 
                    || $current_page == 'datatables-notification') ? 'active' : ''; ?>" data-item="dashboard">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Bar-Chart"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>
                    <!-- RE Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "Re") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-land-sourcing' || $current_page == 'datatables-validasi-lahan' 
                    || $current_page == 'datatables-loa-cd' || $current_page == 'datatables-validasi-data'|| $current_page == 'datatables-bussiness-planning'
                    || $current_page == 'datatables-submit-to-owner' || $current_page == 'datatables-resto-name') ? 'active' : ''; ?>" data-item="uikits">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Library"></i>
                            <span class="nav-text">RE</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>
                    <!-- Owner Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "Owner") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-approval-owner' || $current_page == 'datatables-doc-confirm' || $current_page == 'datatables-gostore') ? 'active' : ''; ?>" data-item="owner">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Tag-2"></i>
                            <span class="nav-text">Owner</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>

                    <!-- Legal Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "Legal") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-validasi-lahan-legal' || $current_page == 'datatables-checkval-legal' 
                    || $current_page == 'datatables-draft-sewa-legal' || $current_page == 'datatables-sign-psm-legal'|| $current_page == 'datatables-sp-submit-legal'
                    || $current_page == 'datatables-release-doc-legal' || $current_page == 'datatables-design-legal' || $current_page == 'datatables-valdoc-legal' 
                    || $current_page == 'datatables-validasi-sp' || $current_page == 'datatables-obstacle-legal'|| $current_page == 'datatables-wovl'
                    || $current_page == 'datatables-wovd'|| $current_page == 'datatables-spk-legal'|| $current_page == 'datatables-mou-parkir') ? 'active' : ''; ?>" data-item="extrakits">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Suitcase"></i>
                            <span class="nav-text">Legal</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>

                    <!-- Negosiator Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "Negosiator") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-doc-confirm-negosiator' || $current_page == 'datatables-dealing-draft-negosiator' 
                    || $current_page == 'datatables-validasi-negosiator') ? 'active' : ''; ?>" data-item="apps">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Computer-Secure"></i>
                            <span class="nav-text">Negosiator</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>

                    <!-- SDG Design Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "SDG-Design") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-checkval-negosiator' || $current_page == 'datatables-design' 
                    || $current_page == 'datatables-land-survey' || $current_page == 'datatables-formval-release-design' || 
                    $current_page == 'datatables-obstacle-sdg') ? 'active' : ''; ?>" data-item="forms">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-File-Clipboard-File--Text"></i>
                            <span class="nav-text">SDG Design</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>

                    <!-- SDG QS Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "SDG-QS") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-data-picture' || $current_page == 'datatables-rab' 
                    || $current_page == 'datatables-validation-rab') ? 'active' : ''; ?>" data-item="sessions">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Administrator"></i>
                            <span class="nav-text">SDG QS</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>

                    <!-- Procurement Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "Procurement") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-checkval-rab-from-sdg' || 
                    $current_page == 'datatables-procurement' || $current_page == 'datatables-vendor' || 
                    $current_page == 'datatables-tender'|| $current_page == 'datatables-spk-sdgpk') ? 'active' : ''; ?>" data-item="others">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Double-Tap"></i>
                            <span class="nav-text">Procurement</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>

                    <!-- SDG EQP Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "SDG-EQP") : ?>
                    <li class="nav-item <?php echo ( $current_page == 'datatables-st-eqp'  || $current_page == 'datatables-sdgpk-eqp-rto'
                    ) ? 'active' : ''; ?>" data-item="eqp">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Bell1"></i>
                            <span class="nav-text">SDG EQP</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>

                    <!-- SDG PK Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "SDG-PK") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-kom-sdgpk' || $current_page == 'datatables-monitoring-op'|| $current_page == 'datatables-construction-act-vendor'|| 
                    $current_page == 'datatables-st-konstruksi'|| $current_page == 'datatables-sdgpk-rto') ? 'active' : ''; ?>" data-item="datatables">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-File-Horizontal-Text"></i>
                            <span class="nav-text">SDG PK</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>

                    <!-- Operation Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "Operation") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-soc-date' || $current_page == 'datatables-update-info' 
                    || $current_page == 'datatables-validation-monitoring' || $current_page == 'datatables-soc' || 
                    $current_page == 'datatables-soc-summary') ? 'active' : ''; ?>" data-item="demos">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Safe-Box1"></i>
                            <span class="nav-text">PMO</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>

                    <!-- HR Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "HR") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-hr-tm' || $current_page == 'datatables-hr-fulfillment'
                    || $current_page == 'datatables-hr-fulfillment-2' || $current_page == 'datatables-hr-fulfillment-3' 
                    || $current_page == 'datatables-hr-hot') ? 'active' : ''; ?>" data-item="hr">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-File-Clipboard-Text--Image"></i>
                            <span class="nav-text">HR</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>

                    <!-- HR Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "Academy") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-hr-kpt'
                    || $current_page == 'datatables-hr-kpt-2' || $current_page == 'datatables-hr-kpt-3') ? 'active' : ''; ?>" data-item="academy">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Loading-3"></i>
                            <span class="nav-text">Academy</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>

                    <!-- SCM Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "SCM") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-scm') ? 'active' : ''; ?>" data-item="scm">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Receipt-4"></i>
                            <span class="nav-text">SCM</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>

                    <!-- IT Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "IT") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-it' || $current_page == 'datatables-it-config') ? 'active' : ''; ?>" data-item="it">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Width-Window"></i>
                            <span class="nav-text">IT</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>

                    <!-- Marketing Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "Marketing") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-marketing') ? 'active' : ''; ?>" data-item="marketing">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Medal-2"></i>
                            <span class="nav-text">Marketing</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>

                    <!-- FAT Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "FAT") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-fat' || $current_page == 'datatables-spk-fat') ? 'active' : ''; ?>" data-item="fat">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Cursor-Click"></i>
                            <span class="nav-text">FAT</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>

                    <!-- IR Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "IR") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-ir') ? 'active' : ''; ?>" data-item="ir">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Split-Horizontal-2-Window"></i>
                            <span class="nav-text">IR</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="sidebar-left-secondary rtl-ps-none" data-perfect-scrollbar data-suppress-scroll-x="true">
                <i class="sidebar-close i-Close" (click)="toggelSidebar()"></i>
                <header>
                    
                </header>
                <!-- Submenu Dashboards -->
                <div class="submenu-area" data-parent="dashboard">
                    <header>
                        <h6><i class="nav-icon i-Bar-Chart"></i> Dashboards</h6>
                        <p>Dashboard Admin</p>
                    </header>
                    <ul class="childNav">
                        <li class="nav-item <?php echo $current_page == 'index' ? 'active' : ''; ?>">
                            <a href="../dashboard/index.php">
                                <i class="nav-icon i-Receipt-4"></i>
                                <span class="item-name">Home</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-analytics' ? 'active' : ''; ?>">
                            <a href="../dashboard/datatables-analytics.php">
                                <i class="nav-icon i-Clock-3"></i>
                                <span class="item-name">Analytics</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-report' ? 'active' : ''; ?>">
                            <a href="../dashboard/datatables-report.php">
                                <i class="nav-icon i-Clock-4"></i>
                                <span class="item-name">Report</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-notification' ? 'active' : ''; ?>">
                            <a href="../dashboard/datatables-notification.php">
                                <i class="nav-icon i-Over-Time"></i>
                                <span class="item-name">Notification</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- RE -->
                <div class="submenu-area" data-parent="uikits">
                    <header>
                        <h6><i class="nav-icon i-Library"></i> RE Dept</h6>
                        <p>Real Estate Devision</p>
                    </header>
                    <ul class="childNav">
                        <li class="nav-item <?php echo $current_page == 'datatables-bussiness-planning' ? 'active' : ''; ?>">
                            <a href="datatables-bussiness-planning.php">
                                <i class="nav-icon i-Receipt-4"></i>
                                <span class="item-name">Bussiness Planning</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-land-sourcing' ? 'active' : ''; ?>">
                            <a href="datatables-land-sourcing.php">
                                <i class="nav-icon i-Bell1"></i>
                                <span class="item-name">Land Sourcing</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-submit-to-owner' ? 'active' : ''; ?>">
                            <a href="datatables-submit-to-owner.php">
                                <i class="nav-icon i-Error-404-Window"></i>
                                <span class="item-name">Submit to Owner</span>
                            </a>
                        </li>					
                        <li class="nav-item <?php echo $current_page == 'datatables-validasi-lahan' ? 'active' : ''; ?>">
                            <a href="datatables-validasi-lahan.php">
                                <i class="nav-icon i-Split-Horizontal-2-Window"></i>
                                <span class="item-name">Validation Land to Legal</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-loa-cd' ? 'active' : ''; ?>">
                            <a href="datatables-loa-cd.php">
                                <i class="nav-icon i-Cursor-Click"></i>
                                <span class="item-name">LoA & CD</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-validasi-data' ? 'active' : ''; ?>">
                            <a href="datatables-validasi-data.php">
                                <i class="nav-icon i-Line-Chart-2"></i>
                                <span class="item-name">Validasi Data to Legal</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-resto-name' ? 'active' : ''; ?>">
                            <a href="datatables-resto-name.php">
                                <i class="nav-icon i-Split-Vertical"></i>
                                <span class="item-name">Resto Name</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- Owner -->
                <div class="submenu-area" data-parent="owner">
                    <header>
                        <h6><i class="nav-icon i-Tag-2"></i> Owner Dept</h6>
                        <p>Owner Surveyor Devision</p>
                    </header>
                    <ul class="childNav">
						<li class="nav-item <?php echo $current_page == 'datatables-approval-owner' ? 'active' : ''; ?>">
                            <a href="datatables-approval-owner.php">
                                <i class="nav-icon i-Checked-User"></i>
                                <span class="item-name">Bod Approval</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-doc-confirm' ? 'active' : ''; ?>">
                            <a href="datatables-doc-confirm.php">
                                <i class="nav-icon i-Medal-2"></i>
                                <span class="item-name">Document Confirmation</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-gostore' ? 'active' : ''; ?>">
                            <a href="datatables-gostore.php">
                                <i class="nav-icon i-Pen-2"></i>
                                <span class="item-name">Data GO Store</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- Legal -->
                <div class="submenu-area" data-parent="extrakits">
                    <header>
                        <h6><i class="nav-icon i-Suitcase"></i> Legal Dept</h6>
                        <p>Legal Devision</p>
                    </header>
                    <ul class="childNav">
                        <li class="nav-item <?php echo $current_page == 'datatables-validasi-lahan-legal' ? 'active' : ''; ?>">
                            <a href="datatables-validasi-lahan-legal.php">
                                <i class="nav-icon i-Crop-2"></i>
                                <span class="item-name">Doc Approval from BoD</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-wovl' ? 'active' : ''; ?>">
                            <a href="datatables-wovl.php">
                                <i class="nav-icon i-File-Clipboard-Text--Image"></i>
                                <span class="item-name">Receipt WO VL</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-release-doc-legal' ? 'active' : ''; ?>">
                            <a href="datatables-release-doc-legal.php">
                                <i class="nav-icon i-Line-Chart-2"></i>
                                <span class="item-name">Release Doc Legal Validation</span>
                            </a>
                        </li>
                        <!-- <li class="nav-item <?php echo $current_page == 'datatables-wovd' ? 'active' : ''; ?>">
                            <a href="datatables-wovd.php">
                                <i class="nav-icon i-Split-Vertical"></i>
                                <span class="item-name">Add WO VD Legal</span>
                            </a>
                        </li> -->
                        <li class="nav-item <?php echo $current_page == 'datatables-checkval-legal' ? 'active' : ''; ?>">
                            <a href="datatables-checkval-legal.php">
                                <i class="nav-icon i-Loading-3"></i>
                                <span class="item-name">Checklist WO VD</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-draft-sewa-legal' ? 'active' : ''; ?>">
                            <a href="datatables-draft-sewa-legal.php">
                                <i class="nav-icon i-Loading-2"></i>
                                <span class="item-name">Drafting Akta Sewa</span>
                            </a>
                        </li>
						<li class="nav-item <?php echo $current_page == 'datatables-sign-psm-legal' ? 'active' : ''; ?>">
                            <a href="datatables-sign-psm-legal.php">
                                <i class="nav-icon i-Checked-User"></i>
                                <span class="item-name">Validasi Data Sign PSM</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-design-legal' ? 'active' : ''; ?>">
                            <a href="datatables-design-legal.php">
                                <i class="nav-icon i-Tag-2"></i>
                                <span class="item-name">Work Order Obstacle from SDG Design</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-valdoc-legal' ? 'active' : ''; ?>">
                            <a href="datatables-valdoc-legal.php">
                                <i class="nav-icon i-Receipt-4"></i>
                                <span class="item-name">Validasi Doc Legal</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-sp-submit-legal' ? 'active' : ''; ?>">
                            <a href="datatables-sp-submit-legal.php">
                                <i class="nav-icon i-Width-Window"></i>
                                <span class="item-name">Surat Permit & PBG Legal Submit</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-validasi-sp' ? 'active' : ''; ?>">
                            <a href="datatables-validasi-sp.php">
                                <i class="nav-icon i-Speach-Bubble-3"></i>
                                <span class="item-name">Validation Permit & PBG Legal</span>
                            </a>
                        </li>
						<!-- <li class="nav-item <?php echo $current_page == 'datatables-obstacle-legal' ? 'active' : ''; ?>">
                            <a href="datatables-obstacle-legal.php">
                                <i class="nav-icon i-Email"></i>
                                <span class="item-name">Obstacle Land from SDG Design</span>
                            </a>
                        </li> -->
                        <li class="nav-item <?php echo $current_page == 'datatables-spk-legal' ? 'active' : ''; ?>">
                            <a href="datatables-spk-legal.php">
                                <i class="nav-icon i-Pen-2"></i>
                                <span class="item-name">Lampiran Izin Konstruksi</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-mou-parkir' ? 'active' : ''; ?>">
                            <a href="datatables-mou-parkir.php">
                                <i class="nav-icon i-Error-404-Window"></i>
                                <span class="item-name">MOU Parkir & Sampah</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- Negosiator -->
                <div class="submenu-area" data-parent="apps">
                    <header>
                        <h6><i class="nav-icon i-Computer-Secure"></i> Negosiator</h6>
                        <p>Negosiator Division</p>
                    </header>
                    <ul class="childNav">
                        <li class="nav-item <?php echo $current_page == 'datatables-doc-confirm-negosiator' ? 'active' : ''; ?>">
                            <a href="datatables-doc-confirm-negosiator.php">
                                <i class="nav-icon i-Add-File"></i>
                                <span class="item-name">Doc Receipt Confirm Owner & Legal Confirm</span>
                            </a>
                        </li>
                        <!-- <li class="nav-item <?php echo $current_page == 'datatables-dealing-draft-negosiator' ? 'active' : ''; ?>">
                            <a href="datatables-dealing-draft-negosiator.php">
                                <i class="nav-icon i-Email"></i>
                                <span class="item-name">Dealing Draft Sewa From Legal</span>
                            </a>
                        </li> -->
                        <!-- <li class="nav-item <?php echo $current_page == 'datatables-validasi-negosiator' ? 'active' : ''; ?>">
                            <a href="datatables-validasi-negosiator.php">
                                <i class="nav-icon i-Speach-Bubble-3"></i>
                                <span class="item-name">Validation to SDG Design</span>
                            </a>
                        </li> -->
                    </ul>
                </div>
                <!-- SDG Design -->
                <div class="submenu-area" data-parent="forms">
                    <header>
                        <h6><i class="nav-icon i-File-Clipboard-File--Text"></i> SDG Design</h6>
                        <p>SDG Design Division</p>
                    </header>
                    <ul class="childNav">
                        <li class="nav-item <?php echo $current_page == 'datatables-checkval-negosiator' ? 'active' : ''; ?>">
                            <a href="datatables-checkval-negosiator.php">
                                <i class="nav-icon i-File-Clipboard-Text--Image"></i>
                                <span class="item-name">Validation Data Dealing Draft Sewa</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-obstacle-sdg' ? 'active' : ''; ?>">
                            <a href="datatables-obstacle-sdg.php">
                                <i class="nav-icon i-Receipt-4"></i>
                                <span class="item-name">Land Survey & Layouting</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-design' ? 'active' : ''; ?>">
                            <a href="datatables-design.php">
                                <i class="nav-icon i-Split-Vertical"></i>
                                <span class="item-name">Design</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-formval-release-design' ? 'active' : ''; ?>">
                            <a href="datatables-formval-release-design.php">
                                <i class="nav-icon i-Close-Window"></i>
                                <span class="item-name">Form Validation Release Design</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- SDG QS -->
                <div class="submenu-area" data-parent="sessions">
                    <header>
                        <h6><i class="nav-icon i-Administrator"></i> SDG QS</h6>
                        <p>SDG QS Division</p>
                    </header>
                    <ul class="childNav">
                        <li class="nav-item <?php echo $current_page == 'datatables-data-picture' ? 'active' : ''; ?>">
                            <a href="datatables-data-picture.php">
                                <i class="nav-icon i-Bell1"></i>
                                <span class="item-name">Release Data Picture From SDG Design</span>
                            </a>
                        </li>
						<li class="nav-item <?php echo $current_page == 'datatables-rab' ? 'active' : ''; ?>">
                            <a href="datatables-rab.php">
                                <i class="nav-icon i-Checked-User"></i>
                                <span class="item-name">RAB</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-validation-rab' ? 'active' : ''; ?>">
                            <a href="datatables-validation-rab.php">
                                <i class="nav-icon i-Medal-2"></i>
                                <span class="item-name">Validation Data RAB to Procurement</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- Procurement -->
                <div class="submenu-area" data-parent="others">
                    <header>
                        <h6><i class="i-Double-Tap"></i> Procurement</h6>
                        <p>Procurement Division</p>
                    </header>
                    <ul class="childNav" data-parent="">
                        <li class="nav-item <?php echo $current_page == 'datatables-checkval-rab-from-sdg' ? 'active' : ''; ?>">
                            <a href="datatables-checkval-rab-from-sdg.php">
                                <i class="nav-icon i-Tag-2"></i>
                                <span class="item-name">Validation RAB from SDG QS & VD from Legal</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-vendor' ? 'active' : ''; ?>">
                            <a href="datatables-vendor.php">
                                <i class="nav-icon i-Width-Window"></i>
                                <span class="item-name">Data Vendor</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-procurement' ? 'active' : ''; ?>">
                            <a href="datatables-procurement.php">
                                <i class="nav-icon i-Pen-2"></i>
                                <span class="item-name">Data Procurement</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-tender' ? 'active' : ''; ?>">
                            <a href="datatables-tender.php">
                                <i class="nav-icon i-Error-404-Window"></i>
                                <span class="item-name">Tender Process</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-spk-sdgpk' ? 'active' : ''; ?>">
                            <a href="datatables-spk-sdgpk.php">
                                <i class="nav-icon i-Receipt-4"></i>
                                <span class="item-name">SPK, & Check Validation from Legal</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- SDG EQP -->
                <div class="submenu-area" data-parent="eqp">
                    <header>
                        <h6><i class=" i-Bell1"></i> SDG Equipment</h6>
                        <p>SDG Equipment Division</p>
                    </header>
                    <ul class="childNav" data-parent="">
                        <li class="nav-item <?php echo $current_page == 'datatables-st-eqp' ? 'active' : ''; ?>">
                            <a href="datatables-st-eqp.php">
                                <i class="nav-icon i-Crop-2"></i>
                                <span class="item-name">ST Equipment</span>
                            </a>
                        </li>
                        <!-- <li class="nav-item <?php echo $current_page == 'datatables-sdgpk-eqp-rto' ? 'active' : ''; ?>">
                            <a href="datatables-sdgpk-eqp-rto.php">
                                <i class="nav-icon i-File-Clipboard-Text--Image"></i>
                                <span class="item-name">Equipment Construction Progress</span>
                            </a>
                        </li> -->
                    </ul>
                </div>
                <!-- SDG PK -->
                <div class="submenu-area" data-parent="datatables">
                    <header>
                        <h6><i class="i-File-Horizontal-Text"></i> SDG Proyek Konstruksi</h6>
                        <p>SDG Proyek Konstruksi Division</p>
                    </header>
                    <ul class="childNav" data-parent="">
                        <li class="nav-item <?php echo $current_page == 'datatables-kom-sdgpk' ? 'active' : ''; ?>">
                            <a href="datatables-kom-sdgpk.php">
                                <i class="nav-icon i-Speach-Bubble-3"></i>
                                <span class="item-name">Result Kick Off Meeting</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-construction-act-vendor' ? 'active' : ''; ?>">
                            <a href="datatables-construction-act-vendor.php">
                                <i class="nav-icon i-Male"></i>
                                <span class="item-name">Construction Activity by Vendor per Month</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-monitoring-op' ? 'active' : ''; ?>">
                            <a href="datatables-monitoring-op.php">
                                <i class="nav-icon i-File-Horizontal"></i>
                                <span class="item-name">Construction Monitoring per Week</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-st-konstruksi' ? 'active' : ''; ?>">
                            <a href="datatables-st-konstruksi.php">
                                <i class="nav-icon i-Line-Chart-2"></i>
                                <span class="item-name">ST Kontraktor</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-sdgpk-rto' ? 'active' : ''; ?>">
                            <a href="datatables-sdgpk-rto.php">
                                <i class="nav-icon i-Clock-4"></i>
                                <span class="item-name">Kualitas Air, Listrik, IPAL</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- Operation -->
                <div class="submenu-area" data-parent="demos">
                    <header>
                        <h6><i class="nav-icon i-Safe-Box1"></i> PMO Dept</h6>
                        <p>PMO Division</p>
                    </header>
                    <ul class="childNav">
                        <li class="nav-item <?php echo $current_page == 'datatables-soc' ? 'active' : ''; ?>">
                            <a href="datatables-soc.php">
                                <i class="nav-icon i-Line-Chart-2"></i>
                                <span class="item-name">Entry SOC</span>
                            </a>
                        </li>
						<li class="nav-item <?php echo $current_page == 'datatables-soc-summary' ? 'active' : ''; ?>">
                            <a href="datatables-soc-summary.php">
                                <i class="nav-icon i-Checked-User"></i>
                                <span class="item-name">Summary SOC Progress</span>
                            </a>
                        </li>					
                        <li class="nav-item <?php echo $current_page == 'datatables-soc-date' ? 'active' : ''; ?>">
                            <a href="datatables-soc-date.php">
                                <i class="nav-icon i-Width-Window"></i>
                                <span class="item-name">Date SOC RTO</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-update-info' ? 'active' : ''; ?>">
                            <a href="datatables-update-info.php">
                                <i class="nav-icon i-Split-Horizontal-2-Window"></i>
                                <span class="item-name">Update Information All Division - Construction Progress</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-validation-monitoring' ? 'active' : ''; ?>">
                            <a href="datatables-validation-monitoring.php">
                                <i class="nav-icon i-Cursor-Click"></i>
                                <span class="item-name">Monitoring Dashboard Until RTO</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- HR -->
                <div class="submenu-area" data-parent="hr">
                    <header>
                        <h6><i class="i-Double-Tap"></i> HR</h6>
                        <p>HR Division</p>
                    </header>
                    <ul class="childNav" data-parent="">
                        <li class="nav-item <?php echo $current_page == 'datatables-hr-tm' ? 'active' : ''; ?>">
                            <a href="datatables-hr-tm.php">
                                <i class="nav-icon i-Tag-2"></i>
                                <span class="item-name">Data Technical Meeting</span>
                            </a>
                        </li>
						<li class="nav-item <?php echo $current_page == 'datatables-hr-fulfillment' ? 'active' : ''; ?>">
                            <a href="datatables-hr-fulfillment.php">
                                <i class="nav-icon i-Checked-User"></i>
                                <span class="item-name">Fulfillment Batch 1</span>
                            </a>
                        </li>
						<li class="nav-item <?php echo $current_page == 'datatables-hr-fulfillment-2' ? 'active' : ''; ?>">
                            <a href="datatables-hr-fulfillment-2.php">
                                <i class="nav-icon i-Checked-User"></i>
                                <span class="item-name">Fulfillment Batch 2</span>
                            </a>
                        </li>
						<li class="nav-item <?php echo $current_page == 'datatables-hr-fulfillment-3' ? 'active' : ''; ?>">
                            <a href="datatables-hr-fulfillment-3.php">
                                <i class="nav-icon i-Checked-User"></i>
                                <span class="item-name">Fulfillment Batch 3</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-hr-hot' ? 'active' : ''; ?>">
                            <a href="datatables-hr-hot.php">
                                <i class="nav-icon i-Medal-2"></i>
                                <span class="item-name">Hand Over Training</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- Legal -->
                <div class="submenu-area" data-parent="academy">
                    <header>
                        <h6><i class="nav-icon i-Suitcase"></i> Academy Dept</h6>
                        <p>Academy Devision</p>
                    </header>
                    <ul class="childNav">
                        <li class="nav-item <?php echo $current_page == 'datatables-hr-kpt' ? 'active' : ''; ?>">
                            <a href="datatables-hr-kpt.php">
                                <i class="nav-icon i-Pen-2"></i>
                                <span class="item-name">Ketepatan Periode Training Batch 1</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-hr-kpt-2' ? 'active' : ''; ?>">
                            <a href="datatables-hr-kpt-2.php">
                                <i class="nav-icon i-Pen-2"></i>
                                <span class="item-name">Ketepatan Periode Training Batch 2</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-hr-kpt-3' ? 'active' : ''; ?>">
                            <a href="datatables-hr-kpt-3.php">
                                <i class="nav-icon i-Pen-2"></i>
                                <span class="item-name">Ketepatan Periode Training Batch 3</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- SCM -->
                <div class="submenu-area" data-parent="scm">
                    <header>
                        <h6><i class="i-Double-Tap"></i> SCM</h6>
                        <p>SCM Division</p>
                    </header>
                    <ul class="childNav" data-parent="">
                        <li class="nav-item <?php echo $current_page == 'datatables-scm' ? 'active' : ''; ?>">
                            <a href="datatables-scm.php">
                                <i class="nav-icon i-File-Horizontal"></i>
                                <span class="item-name">SJ Utensil, Dry Stock, Frozen Stock</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- IT -->
                <div class="submenu-area" data-parent="it">
                    <header>
                        <h6><i class="i-Double-Tap"></i> IT</h6>
                        <p>IT Division</p>
                    </header>
                    <ul class="childNav" data-parent="">
                        <li class="nav-item <?php echo $current_page == 'datatables-it-config' ? 'active' : ''; ?>">
                            <a href="datatables-it-config.php">
                                <i class="nav-icon i-Receipt-4"></i>
                                <span class="item-name">Config System</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-it' ? 'active' : ''; ?>">
                            <a href="datatables-it.php">
                                <i class="nav-icon i-Speach-Bubble-3"></i>
                                <span class="item-name">POS Printer, CCTV, Sound, Internet</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- Marketing -->
                <div class="submenu-area" data-parent="marketing">
                    <header>
                        <h6><i class="i-Double-Tap"></i> Marketing</h6>
                        <p>Marketing Division</p>
                    </header>
                    <ul class="childNav" data-parent="">
                        <li class="nav-item <?php echo $current_page == 'datatables-marketing' ? 'active' : ''; ?>">
                            <a href="datatables-marketing.php">
                                <i class="nav-icon i-Error-404-Window"></i>
                                <span class="item-name">Resto & Merchant</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- FAT -->
                <div class="submenu-area" data-parent="fat">
                    <header>
                        <h6><i class="i-Double-Tap"></i> FAT</h6>
                        <p>FAT Division</p>
                    </header>
                    <ul class="childNav" data-parent="">
                        <li class="nav-item <?php echo $current_page == 'datatables-fat' ? 'active' : ''; ?>">
                            <a href="datatables-fat.php">
                                <i class="nav-icon i-Close-Window"></i>
                                <span class="item-name">File QRIS & ST</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-spk-fat' ? 'active' : ''; ?>">
                            <a href="datatables-spk-fat.php">
                                <i class="nav-icon i-Line-Chart-2"></i>
                                <span class="item-name">Data SPK</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- IR -->
                <div class="submenu-area" data-parent="ir">
                    <header>
                        <h6><i class="i-Double-Tap"></i> IR</h6>
                        <p>IR Division</p>
                    </header>
                    <ul class="childNav" data-parent="">
                        <li class="nav-item <?php echo $current_page == 'datatables-ir' ? 'active' : ''; ?>">
                            <a href="datatables-ir.php">
                                <i class="nav-icon i-Medal-2"></i>
                                <span class="item-name">Pengamanan Reguler</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <script>

document.querySelectorAll('.nav-item a').forEach(item => {
    item.addEventListener('click', function() {
        document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
        this.parentElement.classList.add('active');
    });
});

</script>
