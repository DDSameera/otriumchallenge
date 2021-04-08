<?php require APPROOT . "/views/inc/header.php"; ?>

    <div id="layoutSidenav">
        <!--Start Layout Side Navigation Panel-->
                <div id="layoutSidenav_nav">
                    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                        <div class="sb-sidenav-menu">
                            <div class="nav">
                                <div class="sb-sidenav-menu-heading">Main Activites</div>
                                <a class="nav-link" href="index.html">
                                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                    Dashboard
                                </a>

                            </div>
                        </div>

                    </nav>
                </div>
        <!--End Layout Side Navigation Panel-->

        <!--Start Layout Side Navigation Content Panel-->
        <div id="layoutSidenav_content">

            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Turnover Report Generator</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="<?= WEBROOT ?>">Home</a></li>
                        <li class="breadcrumb-item active">Turn Over Report Generator</li>
                    </ol>


                    <div class="card mb-4">

                        <!--Tip-->
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Tip</strong>
                            <p>
                            <ul>
                                <li>Step 01 : Choose Start Date</li>
                                <li>Step 02 : Choose End Date</li>
                                <li>Step 03 : Select Brand Name</li>
                                <li>Step 04 : Select Turnover Mode</li>
                                <li>Step 05 : Presss "Click Here to Filter"</li>
                                <li>Step 06 : Press "Downlod CSV"</li>

                            </ul>
                            </p>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <!--Tip-->


                        <!--Filter Form-->
                        <form action="<?= URLROOT . '/' ?>" method="post">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Filters
                            </div>

                            <div class="card-body">
                                <div class="container">
                                    <!--Validation Errors-->

                                    <?php if (isset($_SESSION ['errors'])) : ?>
                                        <?php foreach ($_SESSION ['errors'] as $error): ?>
                                            <div class="alert alert-danger">
                                                <?= $error ?>
                                            </div>
                                        <?php endforeach ?>
                                    <?php endif; ?>


                                    <!--Validation Errors-->
                                    <div class="form form-group">
                                        <div class="form-row">
                                            <!--Start Date Input-->
                                            <div class="form-group col-md-3">
                                                <label>Choose Start Date : <span class="text-white badge badge-info">(Default : 2018-05-01)</span></label>
                                                <input class="form-control datepicker" type="text"
                                                       name="start_date_input"
                                                       placeholder="Enter Start Date"
                                                       value="<?= isset($_POST['start_date_input']) ? $_POST['start_date_input'] : "2018-05-01" ?>"/>
                                            </div>
                                            <!--Start Date Input-->

                                            <!--End Date Input-->
                                            <div class="form-group col-md-3">
                                                <label>Choose End Date : <span class="text-white badge badge-info">(Default : 2018-05-07)</span></label>
                                                <input class="form-control datepicker" type="text" name="end_date_input"
                                                       placeholder="Enter End Date"
                                                       value="<?= isset($_POST['end_date_input']) ? $_POST['end_date_input'] : "2018-05-07" ?>"/>
                                            </div>
                                            <!--End Date Input-->

                                            <!--Select Brand-->
                                            <div class="form-group col-md-3">
                                                <label>Select Brand Name <span class="text-white badge badge-info">(Default : -Select All-)</span></label>
                                                <select class="form-control" name="brand_category_input">
                                                    <option value="">-Select All-</option>
                                                    <?php foreach ($data['brandCategories'] as $bc): ?>
                                                        <option value="<?= $bc->id ?>" <?= ((isset($_POST['brand_category_input'])) && ($_POST['brand_category_input'] === $bc->id) ? "selected" : "") ?>><?= $bc->name ?></option>
                                                    <?php endforeach; ?>
                                                    ?>
                                                </select>
                                            </div>
                                            <!--Select Brand-->

                                            <!--Exclude VAT Input-->
                                            <div class="form-group col-md-3">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <label>Exclude VAT : <span class="text-white badge badge-info">(Default : False)</span></label>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">

                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                   name="exclude_vat_input"
                                                                   value="true" <?= ((isset($_POST['exclude_vat_input']) && ((filter_var($_POST['exclude_vat_input'], FILTER_VALIDATE_BOOLEAN) === true)) ? "checked" : "")) ?>>
                                                            <label class="form-check-label">True</label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6">

                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                   name="exclude_vat_input"
                                                                   value="false" <?= ((isset($_POST['exclude_vat_input']) && ((filter_var($_POST['exclude_vat_input'], FILTER_VALIDATE_BOOLEAN) === false)) ? "checked" : "")) ?>>
                                                            <label class="form-check-label">False</label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <!--Exclude VAT Input-->


                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">

                                <div class="d-flex flex-row justify-content-center">
                                    <div class="p-2">
                                        <!--Submit-->
                                        <button id="filterBtn" type="submit" class="btn btn-primary btn-block"
                                                value="Filter">Click Here to Filter
                                        </button>
                                        <!--Submit-->
                                    </div>

                                    <div class="p-2">
                                        <?php if (!empty($data['downLoadLink']) && !empty($data['turnOver'])) : ?>
                                            <a id="downloadCSVBtn" class="btn btn-success"
                                               href="<?= $data['downLoadLink'] ?>">Download
                                                CSV</a>
                                        <?php endif; ?>

                                    </div>

                                </div>


                            </div>
                        </form>
                        <!--Filter Form-->
                    </div>

                    <!--Turnover Data Table-->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table mr-1"></i>
                            Turnover
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>Brand Name</th>
                                        <th>Turn Over Date</th>
                                        <th>Price</th>

                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php if (!empty($data['turnOver'])): ?>
                                        <?php foreach ($data['turnOver'] as $to) : ?>
                                            <tr>
                                                <td><?= $to->name; ?></td>
                                                <td><?= $to->date; ?></td>
                                                <td><?= $to->turnover; ?></td>

                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center alert-warning"><span
                                                        class="font-weight-bold">No Avilable Data</span></td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <!--Turnover Data Table-->
                    <!--Brand Data Table-->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table mr-1"></i>
                            Brands
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>Brand Name</th>
                                        <th>Description</th>
                                        <th>Products (Qty)</th>
                                        <th>Entry Created At</th>
                                    </tr>
                                    </thead>

                                    <tbody>

                                    <?php if (!empty($data['brandItems'])): ?>
                                        <?php foreach ($data['brandItems'] as $bi) : ?>
                                            <tr>
                                                <td><?= $bi->brandName; ?></td>
                                                <td><?= (empty($bi->brandDescription) ? "Empty" : $bi->brandDescription); ?></td>
                                                <td><?= $bi->products; ?></td>
                                                <td><?= $bi->createdDate; ?></td>

                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center alert-warning"><span
                                                        class="font-weight-bold">No Avilable Data</span></td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <!--Brand Data Table-->


                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Developed By Sameera Dananjaya Wijerathna</div>
                    </div>
                </div>
            </footer>
        </div>
        <!--End Layout Side Navigation Content Panel-->
    </div>


<?php require APPROOT . "/views/inc/footer.php"; ?>