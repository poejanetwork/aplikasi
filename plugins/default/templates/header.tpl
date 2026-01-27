<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin | Script By Abu Puja</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin script." name="description">
    <meta content="Abu_Puja" name="author">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{$theme}/assets/images/favicon.ico">

    <?php if ($page == 'news') { ?>
        <!-- Dropzone File Upload js -->
        <link rel="stylesheet" href="{$theme}/assets/vendor/dropzone/dropzone.min.css" type="text/css" />
    <?php } ?>

    <!-- Sweet Alert css-->
    <link href="{$theme}/assets/vendor/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme Config Js -->
    <script src="{$theme}/assets/js/config.js"></script>
    <!-- Vendor css -->
    <link href="{$theme}/assets/css/vendor.min.css" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="{$theme}/assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />
    <!-- Icons css -->
    <link href="{$theme}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
	{if $pagetype="news"}
        <!-- Dropzone File Upload js -->
    <link rel="stylesheet" href="{$theme}/assets/vendor/dropzone/dropzone.min.css" type="text/css" />
	{/if}
    
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        
        <!-- Sidenav Menu Start -->
        <div class="sidenav-menu">

            <!-- Brand Logo -->
            <a href="{$BASE_URL}{$ADMIN_URL}" class="logo">
                <span class="logo-light">
                    <span class="logo-lg"><img src="{$theme}/assets/images/logo.png" alt="logo"></span>
                    <span class="logo-sm text-center"><img src="{$theme}/assets/images/logo-sm.png" alt="small logo"></span>
                </span>

                <span class="logo-dark">
                    <span class="logo-lg"><img src="{$theme}/assets/images/logo-dark.png" alt="dark logo"></span>
                    <span class="logo-sm text-center"><img src="{$theme}/assets/images/logo-sm.png" alt="small logo"></span>
                </span>
            </a>

            <!-- Sidebar Hover Menu Toggle Button -->
            <button class="button-sm-hover">
                <i class="ti ti-circle align-middle"></i>
            </button>

            <!-- Full Sidebar Menu Close Button -->
            <button class="button-close-fullsidebar">
                <i class="ti ti-x align-middle"></i>
            </button>

            <div data-simplebar>

                <!--- Sidenav Menu -->
                <ul class="side-nav">

                    <li class="side-nav-item" data-pagename="dashboard">
                        <a href="{'dashboard'|surl}" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                            <span class="menu-text"> Dashboard </span>
                        </a>
                    </li>

                    <li class="side-nav-title mt-2">Apps Menu</li>

                    <li class="side-nav-item {if !'surat'|canAccess}d-none{/if}" data-pagename="sm,sk,disposisi">
                        <a data-bs-toggle="collapse" href="#sidebarSurat1" aria-expanded="false" aria-controls="sidebarSurat1" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-mail-opened-filled"></i></span>
                            <span class="menu-text"> Surat</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarSurat1">
                            <ul class="sub-menu">
                                <li class="side-nav-item">
                                    <a href="{'sm'|surl}" class="side-nav-link">
                                        <span class="menu-text">Surat Masuk</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="{'sk'|surl}" class="side-nav-link">
                                        <span class="menu-text">Surat Keluar</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="{'disposisi'|surl}" class="side-nav-link">
                                        <span class="menu-text">Pengaturan Disposisi</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="side-nav-item {if !'berita'|canAccess}d-none{/if}" data-pagename="news,news_category">
                        <a data-bs-toggle="collapse" href="#sidebarNews2" aria-expanded="false" aria-controls="sidebarNews2" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-news"></i></span>
                            <span class="menu-text"> Berita</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarNews2">
                            <ul class="sub-menu">
                                <li class="side-nav-item">
                                    <a href="{'news'|surl}" class="side-nav-link">
                                        <span class="menu-text">Lihat Berita</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="{'news_category'|surl}" class="side-nav-link">
                                        <span class="menu-text">Kategori Berita</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="side-nav-item {if !'doktrin'|canAccess}d-none{/if}" data-pagename="doktrin,doktrin_categories">
                        <a data-bs-toggle="collapse" href="#sidebarData3" aria-expanded="false" aria-controls="sidebarData3" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-api-app"></i></span>
                            <span class="menu-text"> Data</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarData3">
                            <ul class="sub-menu">
                                <li class="side-nav-item">
                                    <a href="{'doktrin'|surl}" class="side-nav-link">
                                        <span class="menu-text">Data Doktrin</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="{'doktrin_categories'|surl}" class="side-nav-link">
                                        <span class="menu-text">Pengaturan Kategori Doktrin</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="{'data'|surl}" class="side-nav-link">
                                        <span class="menu-text">Data</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="side-nav-item {if !'contents'|canAccess}d-none{/if}" data-pagename="contents">
                        <a href="{'contents'|surl}" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-box-multiple"></i></span>
                            <span class="menu-text"> Menu Konten</span>
                        </a>
                    </li>

                    <li class="side-nav-item {if !'users'|canAccess}d-none{/if}" data-pagename="users,user_group,user_privilege">
                        <a data-bs-toggle="collapse" href="#sidebarUsers1" aria-expanded="false" aria-controls="sidebarUsers1" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-file-invoice"></i></span>
                            <span class="menu-text"> Users</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarUsers1">
                            <ul class="sub-menu">
                                <li class="side-nav-item">
                                    <a href="{'users'|surl}" class="side-nav-link">
                                        <span class="menu-text">Lihat User</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="{'user_group'|surl}" class="side-nav-link">
                                        <span class="menu-text">User Group</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="{'user_privilege'|surl}" class="side-nav-link">
                                        <span class="menu-text">Akses User Group</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="side-nav-item {if !'settings'|canAccess}d-none{/if}" data-pagename="settings">
                        <a data-bs-toggle="collapse" href="#sidebarSettings2" aria-expanded="false" aria-controls="sidebarSettings2" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-settings"></i></span>
                            <span class="menu-text"> Pengaturan</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarSettings2">
                            <ul class="sub-menu">
                                <li class="side-nav-item">
                                    <a href="{'settings'|surl}" class="side-nav-link">
                                        <span class="menu-text">Umum</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="{'seo'|surl}" class="side-nav-link">
                                        <span class="menu-text">URL SEO</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="{'security'|surl}" class="side-nav-link">
                                        <span class="menu-text">Keamanan</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="side-nav-item {if !'bantuan'|canAccess}d-none{/if}" data-pagename="supports">
                        <a href="{'supports'|surl}" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-message-filled"></i></span>
                            <span class="menu-text"> Bantuan </span>
                        </a>
                    </li>

                    <li class="side-nav-item d-none">
                        <a href="javascript:void(0)" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-message-filled"></i></span>
                            <span class="menu-text"> Other </span>
                        </a>
                    </li>   

                </ul>

                <div class="clearfix"></div>
            </div>
        </div>
        <!-- Sidenav Menu End -->
        

        <!-- Topbar Start -->
        <header class="app-topbar">
            <div class="page-container topbar-menu">
                <div class="d-flex align-items-center gap-2">

                    <!-- Brand Logo -->
                    <a href="{$BASE_URL}{$ADMIN_URL}" class="logo">
                        <span class="logo-light">
                            <span class="logo-lg"><img src="{$theme}/assets/images/logo.png" alt="logo"></span>
                            <span class="logo-sm"><img src="{$theme}/assets/images/logo-sm.png" alt="small logo"></span>
                        </span>

                        <span class="logo-dark">
                            <span class="logo-lg"><img src="{$theme}/assets/images/logo-dark.png" alt="dark logo"></span>
                            <span class="logo-sm"><img src="{$theme}/assets/images/logo-sm.png" alt="small logo"></span>
                        </span>
                    </a>

                    <!-- Sidebar Menu Toggle Button -->
                    <button class="sidenav-toggle-button btn btn-secondary btn-icon">
                        <i class="ti ti-menu-deep fs-24"></i>
                    </button>

                    <!-- Horizontal Menu Toggle Button -->
                    <button class="topnav-toggle-button" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                        <i class="ti ti-menu-deep fs-22"></i>
                    </button>

                    <!-- Button Trigger Search Modal -->
                    <div class="topbar-search text-muted d-none d-xl-flex gap-2 align-items-center" data-bs-toggle="modal" data-bs-target="#searchModal" type="button">
                        <i class="ti ti-search fs-18"></i>
                        <span class="me-2">Search something..</span>
                        <button type="submit" class="ms-auto btn btn-sm btn-primary shadow-none">⌘K</span>
                    </div>

                </div>

                <div class="d-flex align-items-center gap-2">

                    <!-- Search for small devices -->
                    <div class="topbar-item d-flex d-xl-none">
                        <button class="topbar-link btn btn-outline-primary btn-icon" data-bs-toggle="modal" data-bs-target="#searchModal" type="button">
                            <i class="ti ti-search fs-22"></i>
                        </button>
                    </div>

                    <!-- Button Trigger Customizer Offcanvas -->
                    <div class="topbar-item d-none d-sm-flex">
                        <button class="topbar-link btn btn-outline-primary btn-icon" data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas" type="button">
                            <i class="ti ti-settings fs-22"></i>
                        </button>
                    </div>

                    <!-- Light/Dark Mode Button -->
                    <div class="topbar-item d-none d-sm-flex">
                        <button class="topbar-link btn btn-outline-primary btn-icon" id="light-dark-mode" type="button">
                            <i class="ti ti-moon fs-22"></i>
                        </button>
                    </div>

                    <!-- User Dropdown -->
                    <div class="topbar-item">
                        <div class="dropdown">
                            <a class="topbar-link btn btn-outline-primary dropdown-toggle drop-arrow-none" data-bs-toggle="dropdown" data-bs-offset="0,22" type="button" aria-haspopup="false" aria-expanded="false">
                                <img src="{$theme}/assets/images/users/avatar-1.png" width="24" class="rounded-circle me-lg-2 d-flex" alt="user-image">
                                <span class="d-lg-flex flex-column gap-1 d-none">
                                    {$admin_details.fullname}
                                </span>
                                <i class="ti ti-chevron-down d-none d-lg-block align-middle ms-2"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">Welcome !</h6>
                                </div>

                                <!-- item-->
                                <a href="{'settings'|surl}" class="dropdown-item">
                                    <i class="ti ti-settings me-1 fs-17 align-middle"></i>
                                    <span class="align-middle">Settings</span>
                                </a>

                                <!-- item-->
                                <a href="{'supports'|surl}" class="dropdown-item">
                                    <i class="ti ti-lifebuoy me-1 fs-17 align-middle"></i>
                                    <span class="align-middle">Support</span>
                                </a>

                                <div class="dropdown-divider"></div>

                                <!-- item-->
                                <a href="{'logout'|surl}" class="dropdown-item active fw-semibold text-danger">
                                    <i class="ti ti-logout me-1 fs-17 align-middle"></i>
                                    <span class="align-middle">Sign Out</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Topbar End -->

        <!-- Search Modal -->
        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content bg-transparent">
                    <div class="card mb-0 shadow-none">
                        <div class="px-3 py-2 d-flex flex-row align-items-center" id="top-search">
                            <i class="ti ti-search fs-22"></i>
                            <input type="search" class="form-control border-0" id="search-modal-input" placeholder="Search for actions, people,">
                            <button type="button" class="btn p-0" data-bs-dismiss="modal" aria-label="Close">[esc]</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <div class="page-content">
            <div class="page-container">


