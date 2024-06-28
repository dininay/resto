<?php

session_start();


// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    // Jika belum login, alihkan ke halaman login
    header("location: /resto/index.php?pesan=belum_login");
    exit;
}

include '../../koneksi.php';
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
$current_page = strtok($_SERVER["REQUEST_URI"],'?');
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
                    <li class="nav-item" data-item="dashboard">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Bar-Chart"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <!-- RE Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "Re") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-land-sourcing' || $current_page == 'datatables-validasi-lahan' 
                    || $current_page == 'datatables-loa-cd' || $current_page == 'datatables-validasi-data' || $current_page == 'land-sourcing-from'
                    || $current_page == 'land-sourcing-edit-form'|| $current_page == 'validasi-land-detail'|| $current_page == 'loa-cd-from'
                    || $current_page == 'loa-cd-edit-form'|| $current_page == 'validasi-data-detail') ? 'active' : ''; ?>" data-item="uikits">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Library"></i>
                            <span class="nav-text">UI kits</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>
                    <!-- Owner Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "Owner") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-approval-owner' || $current_page == 'datatables-doc-confirm' || $current_page == 'approval-owner-from'
                    || $current_page == 'approval-owner-edit-from') ? 'active' : ''; ?>" data-item="owner">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Tag-2"></i>
                            <span class="nav-text">Owner</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>

                    <!-- Legal Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "Legal") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-validasi-lahan-legal' || $current_page == 'datatables-checkval-legal' || $current_page == 'datatables-draft-sewa-legal' || $current_page == 'datatables-sign-psm-design-legal'|| $current_page == 'datatables-sp-submit-legal'  || $current_page == 'draft-sewa-from' || $current_page == 'draft-sewa-edit-from') ? 'active' : ''; ?>" data-item="extrakits">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Suitcase"></i>
                            <span class="nav-text">Extra kits</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>

                    <!-- Negosiator Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "Negosiator") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-doc-confirm-negosiator' || $current_page == 'datatables-dealing-draft-negosiator' || $current_page == 'datatables-validasi-negosiator') ? 'active' : ''; ?>" data-item="apps">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Computer-Secure"></i>
                            <span class="nav-text">Negosiator</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>

                    <!-- SDG Design Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "SDG-Design") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-checkval-negosiator' || $current_page == 'datatables-design' || $current_page == 'datatables-land-survey' || $current_page == 'datatables-formval-release-design') ? 'active' : ''; ?>" data-item="forms">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-File-Clipboard-File--Text"></i>
                            <span class="nav-text">SDG Design</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>

                    <!-- SDG QS Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "SDG-QS") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-data-picture' || $current_page == 'datatables-rab' || $current_page == 'datatables-validation-rab') ? 'active' : ''; ?>" data-item="sessions">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Administrator"></i>
                            <span class="nav-text">SDG QS</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>

                    <!-- Procurement Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "Procurement") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-checkval-rab-from-sdg' || $current_page == 'datatables-validation-sign-psm-design' || $current_page == 'datatables-vendor' || $current_page == 'datatables-tender') ? 'active' : ''; ?>" data-item="others">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Double-Tap"></i>
                            <span class="nav-text">Procurement</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>

                    <!-- SDG PK Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "SDG-PK") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-construction-act-vendor' || $current_page == 'datatables-monitoring-op') ? 'active' : ''; ?>" data-item="datatables">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-File-Horizontal-Text"></i>
                            <span class="nav-text">SDG PK</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <?php endif; ?>

                    <!-- Operation Head -->
                    <?php if ($_SESSION['level'] === "Admin" || $_SESSION['level'] === "Operation") : ?>
                    <li class="nav-item <?php echo ($current_page == 'datatables-soc-date' || $current_page == 'datatables-update-info' || $current_page == 'datatables-validation-monitoring') ? 'active' : ''; ?>" data-item="demos">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Safe-Box1"></i>
                            <span class="nav-text">Operation</span>
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
                        <h6>Dashboards</h6>
                        <p>Lorem ipsum dolor sit.</p>
                    </header>
                    <ul class="childNav">
                        <li class="nav-item">
                            <a href="dashboard1.html">
                                <i class="nav-icon i-Clock-3"></i>
                                <span class="item-name">Version 1</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="dashboard2.html">
                                <i class="nav-icon i-Clock-4"></i>
                                <span class="item-name">Version 2</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="dashboard3.html">
                                <i class="nav-icon i-Over-Time"></i>
                                <span class="item-name">Version 3</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="dashboard4.html">
                                <i class="nav-icon i-Clock"></i>
                                <span class="item-name">Version 4</span>
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
                        <li class="nav-item <?php echo $current_page == 'datatables-land-sourcing' ? 'active' : ''; ?>">
                            <a href="/resto/dashboard/datatables-land-sourcing.php">
                                <i class="nav-icon i-Bell1"></i>
                                <span class="item-name">Land Sourcing</span>
                            </a>
                        </li>					
                        <li class="nav-item <?php echo $current_page == 'datatables-validasi-lahan' ? 'active' : ''; ?>">
                            <a href="/resto/dashboard/datatables-validasi-lahan.php">
                                <i class="nav-icon i-Split-Horizontal-2-Window"></i>
                                <span class="item-name">Validation Land to Legal</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-loa-cd' ? 'active' : ''; ?>">
                            <a href="/resto/dashboard/datatables-loa-cd.php">
                                <i class="nav-icon i-Cursor-Click"></i>
                                <span class="item-name">LoA & CD</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-validasi-data' ? 'active' : ''; ?>">
                            <a href="/resto/dashboard/datatables-validasi-data.php">
                                <i class="nav-icon i-Line-Chart-2"></i>
                                <span class="item-name">Validasi Data to Legal</span>
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
						<li class="nav-item <?php echo $current_page == 'datatables-approval-owner'|| $current_page == 'approval-owner-from'|| $current_page == 'approval-owner-edit-from' ? 'active' : ''; ?>">
                            <a href="/resto/dashboard/datatables-approval-owner.php">
                                <i class="nav-icon i-Checked-User"></i>
                                <span class="item-name">Approval by owner</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-doc-confirm' ? 'active' : ''; ?>">
                            <a href="/resto/dashboard/datatables-doc-confirm.php">
                                <i class="nav-icon i-Medal-2"></i>
                                <span class="item-name">Document Confirmation</span>
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
                            <a href="/resto/dashboard/datatables-validasi-lahan-legal.php">
                                <i class="nav-icon i-Crop-2"></i>
                                <span class="item-name">Doc Validation from RE</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-checkval-legal' ? 'active' : ''; ?>">
                            <a href="/resto/dashboard/datatables-checkval-legal.php">
                                <i class="nav-icon i-Loading-3"></i>
                                <span class="item-name">Chacklist Validasi Data</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-draft-sewa-legal'  || $current_page == 'draft-sewa-from' || $current_page == 'draft-sewa-edit-from' ? 'active' : ''; ?>">
                            <a href="/resto/dashboard/datatables-draft-sewa-legal.php">
                                <i class="nav-icon i-Loading-2"></i>
                                <span class="item-name">Drafting Akta Sewa</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-sign-psm-design-legal' ? 'active' : ''; ?>">
                            <a href="/resto/dashboard/datatables-sign-psm-design-legal.php">
                                <i class="nav-icon i-Tag-2"></i>
                                <span class="item-name">Sign PSM & Design from Negosiator & SDG Design</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $current_page == 'datatables-sp-submit-legal' ? 'active' : ''; ?>">
                            <a href="/resto/dashboard/datatables-sp-submit-legal.php">
                                <i class="nav-icon i-Width-Window"></i>
                                <span class="item-name">Surat Pernyataan Legal & Submit</span>
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
                        <li class="nav-item">
                            <a href="../dashboard/datatables-doc-confirm-negosiator.php">
                                <i class="nav-icon i-Add-File"></i>
                                <span class="item-name">Doc Confirm Owner & Legal Confirm</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../dashboard/datatables-dealing-draft-negosiator.php">
                                <i class="nav-icon i-Email"></i>
                                <span class="item-name">Dealing Draft Sewa From Legal</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../dashboard/datatables-validasi-negosiator.php">
                                <i class="nav-icon i-Speach-Bubble-3"></i>
                                <span class="item-name">Validation to SDG Design</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- SDG Design -->
                <div class="submenu-area" data-parent="forms">
                    <header>
                        <h6><i class="nav-icon i-File-Clipboard-File--Text"></i> SDG Design</h6>
                        <p>SDG Design Division</p>
                    </header>
                    <ul class="childNav">
                        <li class="nav-item">
                            <a href="../dashboard/datatables-checkval-negosiator.php">
                                <i class="nav-icon i-File-Clipboard-Text--Image"></i>
                                <span class="item-name">Validation Data From Negosiator</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../dashboard/datatables-design.php">
                                <i class="nav-icon i-Split-Vertical"></i>
                                <span class="item-name">Design</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../dashboard/datatables-land-survey.php">
                                <i class="nav-icon i-Receipt-4"></i>
                                <span class="item-name">Land Survey</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../dashboard/datatables-formval-release-design.php">
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
                        <li class="nav-item">
                            <a href="../dashboard/datatables-data-picture.php">
                                <i class="nav-icon i-Bell1"></i>
                                <span class="item-name">Release Data Picture From SDG Design</span>
                            </a>
                        </li>
						<li class="nav-item">
                            <a href="../dashboard/datatables-rab.php">
                                <i class="nav-icon i-Checked-User"></i>
                                <span class="item-name">RAB</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../dashboard/datatables-validation-rab.php">
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
                        <li class="nav-item">
                            <a href="../dashboard/datatables-checkval-rab-from-sdg.php">
                                <i class="nav-icon i-Tag-2"></i>
                                <span class="item-name">Validation RAB from SDG QS</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../dashboard/datatables-validation-sign-psm-design.php">
                                <i class="nav-icon i-Pen-2"></i>
                                <span class="item-name">Validation Data Sign PSM & Design from legal</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../dashboard/datatables-vendor.php">
                                <i class="nav-icon i-Width-Window"></i>
                                <span class="item-name">Data Vendor</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../dashboard/datatables-tender.php">
                                <i class="nav-icon i-Error-404-Window"></i>
                                <span class="item-name">Tender Process</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- SDG PK -->
                <div class="submenu-area" data-parent="datatables">
                    <header>
                        <h6><i class="i-File-Horizontal-Text"></i> SDG Proyek Konstruksi</h6>
                        <p>SDG Proyek Konstruksi Division</p>
                    </header>
                    <ul class="childNav" data-parent="">
                        <li class="nav-item">
                            <a href="../dashboard/datatables-construction-act-vendor.php">
                                <i class="nav-icon i-Male"></i>
                                <span class="item-name">Construction Activity by Vendor</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../dashboard/datatables-monitoring-op.php">
                                <i class="nav-icon i-File-Horizontal"></i>
                                <span class="item-name">Monitoring to Operation</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- Operation -->
                <div class="submenu-area" data-parent="demos">
                    <header>
                        <h6><i class="nav-icon i-Safe-Box1"></i> Operation Dept</h6>
                        <p>Operation Devision</p>
                    </header>
                    <ul class="childNav">
						<li class="nav-item">
                            <a href="../dashboard/datatables-soc-date.php">
                                <i class="nav-icon i-Checked-User"></i>
                                <span class="item-name">SOC Date</span>
                            </a>
                        </li>					
                        <li class="nav-item">
                            <a href="../dashboard/datatables-update-info.php">
                                <i class="nav-icon i-Split-Horizontal-2-Window"></i>
                                <span class="item-name">Update Information</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../dashboard/datatables-validation-monitoring.php">
                                <i class="nav-icon i-Cursor-Click"></i>
                                <span class="item-name">Monitoring Until RTO</span>
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
