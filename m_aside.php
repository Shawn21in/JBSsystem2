<aside class="left-sidebar bg-sidebar">
    <div class="sidebar sidebar-with-footer" id="sidebar">
        <!-- Aplication Brand -->
        <div class="app-brand">
            <a href="member.php" title="Sleek Dashboard"><img alt="庫點子文創資訊產業有限公司" class="brand-icon" src="images/logo-member.png"> <span class="brand-name text-truncate">JBS 雲端打卡平台</span></a>
        </div><!-- begin sidebar scrollbar -->
        <div class="menu_bar" data-simplebar="" style="height: 100%;">
            <!-- sidebar menu -->
            <ul class="nav sidebar-inner" id="sidebar-menu">
                <li class="has-sub">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#dashboard" aria-expanded="false" aria-controls="dashboard">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span class="nav-text">基本資料</span> <b class="caret"></b>
                    </a>

                    <ul class="collapse" id="dashboard" data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li class="" data-no="profile">
                                <a class="sidenav-item-link" href="m_profile.php#profile">
                                    <span class="nav-text">公司建檔設定</span>
                                </a>
                            </li>
                            <li class="has-sub">
                                <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#number" aria-expanded="false" aria-controls="number">
                                    <span class="nav-text">常用編號設定</span> <b class="caret"></b>
                                </a>
                                <ul class="collapse" id="number">
                                    <div class="sub-menu">
                                        <li class="" data-no="bank">
                                            <a href="m_bank.php">銀行編號</a>
                                        </li>
                                        <li class="" data-no="education">
                                            <a href="m_education.php">學歷編號</a>
                                        </li>
                                        <li class="" data-no="jobs">
                                            <a href="m_jobs.php">職位編號</a>
                                        </li>
                                        <li class="" data-no="part">
                                            <a href="m_part.php">部門編號</a>
                                        </li>
                                        <li class="" data-no="family">
                                            <a href="m_family.php">健保眷屬關係</a>
                                        </li>
                                        <li class="" data-no="reason">
                                            <a href="m_reason.php">健保退保原因</a>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                            <li class="" data-no="deduction">
                                <a class="sidenav-item-link" href="m_deduction.php">
                                    <span class="nav-text">加扣款編號設定</span>
                                </a>
                            </li>
                            <li class="" data-no="seclab1">
                                <a class="sidenav-item-link" href="m_seclab1.php">
                                    <span class="nav-text">勞保等級設定</span>
                                </a>
                            </li>
                            <li class="" data-no="purchaser1">
                                <a class="sidenav-item-link" href="m_purchaser1.php">
                                    <span class="nav-text">健保等級設定</span>
                                </a>
                            </li>
                            <li class="" data-no="attendance">
                                <a class="sidenav-item-link" href="m_attendance.php">
                                    <span class="nav-text">員工班別設定</span>
                                </a>
                            </li>
                            <li class="" data-no="holidays">
                                <a class="sidenav-item-link" href="m_holidays.php">
                                    <span class="nav-text">國定假日設定</span>
                                </a>
                            </li>
                            <li class="" data-no="employee">
                                <a class="sidenav-item-link" href="m_employee.php">
                                    <span class="nav-text">員工基本資料</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>

                <li class="has-sub ">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#app" aria-expanded="false" aria-controls="app">
                        <i class="mdi mdi-pencil-box-multiple"></i>
                        <span class="nav-text">出勤作業</span> <b class="caret"></b>
                    </a>

                    <ul class="collapse " id="app" data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li class="" data-no="employeeattend">
                                <a class="sidenav-item-link" href="m_employeeattend.php">
                                    <span class="nav-text">員工出勤作業</span>
                                </a>
                            </li>
                            <li class="" data-no="daka">
                                <a class="sidenav-item-link" href="m_daka.php">
                                    <span class="nav-text">打卡資料匯入</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>

                <!-- <li class="has-sub ">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#components" aria-expanded="false" aria-controls="components">
                        <i class="mdi mdi-folder-multiple-outline"></i>
                        <span class="nav-text">薪資作業</span> <b class="caret"></b>
                    </a>

                    <ul class="collapse " id="components" data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li class="" data-no="">
                                <a class="sidenav-item-link" href="alert.html">
                                    <span class="nav-text">Alert</span>
                                </a>
                            </li>

                            <li class="" data-no="">
                                <a class="sidenav-item-link" href="badge.html">
                                    <span class="nav-text">Badge</span>
                                </a>
                            </li>

                            <li class="" data-no="">
                                <a class="sidenav-item-link" href="breadcrumb.html">
                                    <span class="nav-text">Breadcrumb</span>

                                </a>
                            </li>

                            <li class="has-sub ">
                                <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#buttons" aria-expanded="false" aria-controls="buttons">
                                    <span class="nav-text">Buttons</span> <b class="caret"></b>
                                </a>

                                <ul class="collapse " id="buttons">
                                    <div class="sub-menu">
                                        <li class="" data-no="">
                                            <a href="button-default.html">Button Default</a>
                                        </li>

                                        <li class="" data-no="">
                                            <a href="button-dropdown.html">Button Dropdown</a>
                                        </li>

                                        <li class="" data-no="">
                                            <a href="button-group.html">Button Group</a>
                                        </li>

                                        <li class="" data-no="">
                                            <a href="button-social.html">Button Social</a>
                                        </li>

                                        <li class="" data-no="">
                                            <a href="button-loading.html">Button Loading</a>
                                        </li>
                                    </div>
                                </ul>
                            </li>

                            <li class="" data-no="">
                                <a class="sidenav-item-link" href="card.html">
                                    <span class="nav-text">Card</span>
                                </a>
                            </li>

                            <li class="" data-no="">
                                <a class="sidenav-item-link" href="carousel.html">
                                    <span class="nav-text">Carousel</span>
                                </a>
                            </li>

                            <li class="" data-no="">
                                <a class="sidenav-item-link" href="collapse.html">
                                    <span class="nav-text">Collapse</span>
                                </a>
                            </li>

                            <li class="" data-no="">
                                <a class="sidenav-item-link" href="list-group.html">
                                    <span class="nav-text">List Group</span>
                                </a>
                            </li>

                            <li class="" data-no="">
                                <a class="sidenav-item-link" href="modal.html">
                                    <span class="nav-text">Modal</span>
                                </a>
                            </li>

                            <li class="" data-no="">
                                <a class="sidenav-item-link" href="pagination.html">
                                    <span class="nav-text">Pagination</span>
                                </a>
                            </li>

                            <li class="" data-no="">
                                <a class="sidenav-item-link" href="popover-tooltip.html">
                                    <span class="nav-text">Popover & Tooltip</span>
                                </a>
                            </li>

                            <li class="" data-no="">
                                <a class="sidenav-item-link" href="progress-bar.html">
                                    <span class="nav-text">Progress Bar</span>
                                </a>
                            </li>

                            <li class="" data-no="">
                                <a class="sidenav-item-link" href="spinner.html">
                                    <span class="nav-text">Spinner</span>
                                </a>
                            </li>

                            <li class="" data-no="">
                                <a class="sidenav-item-link" href="switcher.html">
                                    <span class="nav-text">Switcher</span>
                                </a>
                            </li>

                            <li class="" data-no="">
                                <a class="sidenav-item-link" href="tab.html">
                                    <span class="nav-text">Tab</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>

                <li class="has-sub ">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#icons" aria-expanded="false" aria-controls="icons">
                        <i class="mdi mdi-diamond-stone"></i>
                        <span class="nav-text">統計圖表</span> <b class="caret"></b>
                    </a>

                    <ul class="collapse " id="icons" data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li class="" data-no="">
                                <a class="sidenav-item-link" href="material-icon.html">
                                    <span class="nav-text">Material Icon</span>
                                </a>
                            </li>

                            <li class="" data-no="">
                                <a class="sidenav-item-link" href="flag-icon.html">
                                    <span class="nav-text">Flag Icon</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>

                <li class="has-sub ">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#forms" aria-expanded="false" aria-controls="forms">
                        <i class="mdi mdi-email-mark-as-unread"></i>
                        <span class="nav-text">系統參數</span> <b class="caret"></b>
                    </a>

                    <ul class="collapse " id="forms" data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li class="" data-no="">
                                <a class="sidenav-item-link" href="basic-input.html">
                                    <span class="nav-text">Basic Input</span>
                                </a>
                            </li>

                            <li class="" data-no="">
                                <a class="sidenav-item-link" href="input-group.html">
                                    <span class="nav-text">Input Group</span>
                                </a>
                            </li>

                            <li class="" data-no="">
                                <a class="sidenav-item-link" href="checkbox-radio.html">
                                    <span class="nav-text">Checkbox & Radio</span>
                                </a>
                            </li>

                            <li class="" data-no="">
                                <a class="sidenav-item-link" href="form-validation.html">
                                    <span class="nav-text">Form Validation</span>
                                </a>
                            </li>

                            <li class="" data-no="">
                                <a class="sidenav-item-link" href="form-advance.html">
                                    <span class="nav-text">Form Advance</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li> -->

            </ul>
        </div>
    </div>
</aside>