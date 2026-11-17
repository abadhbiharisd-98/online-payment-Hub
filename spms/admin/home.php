<?php
// Fetch system stats safely
try {
    $dept_count = $conn->query("SELECT count(department_id) as `count` FROM `department_list` where status = 1 ")->fetch_array()['count'] ?? 0;
} catch(\Throwable $e){ $dept_count = 0; }

try {
    $desg_count = $conn->query("SELECT count(designation_id) as `count` FROM `designation_list` where status = 1 ")->fetch_array()['count'] ?? 0;
} catch(\Throwable $e){ $desg_count = 0; }

try {
    $emp_count = $conn->query("SELECT count(employee_id) as `count` FROM `employee_list` where status = 1 ")->fetch_array()['count'] ?? 0;
} catch(\Throwable $e){ $emp_count = 0; }

try {
    $payroll_stat = $conn->query("SELECT count(payroll_id) as `count`, COALESCE(SUM(net_pay), 0) as `total` FROM `payroll_list`")->fetch_assoc();
    $payroll_count = $payroll_stat['count'] ?? 0;
    $total_disbursed = $payroll_stat['total'] ?? 0;
} catch(\Throwable $e){ 
    $payroll_count = 0; 
    $total_disbursed = 0;
}
?>

<style>
    .stat-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: none;
        border-radius: 12px;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08) !important;
    }
    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .welcome-banner {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 14px;
        color: white;
        padding: 24px 28px;
    }
    .quick-link-btn {
        transition: all 0.2s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        padding: 12px 16px;
        border-radius: 10px;
        background: #f8f9fa;
        color: #333;
        font-weight: 500;
        margin-bottom: 10px;
        border: 1px solid #e9ecef;
    }
    .quick-link-btn:hover {
        background: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
        transform: translateX(4px);
    }
    .quick-link-btn i {
        font-size: 1.2rem;
        width: 32px;
    }
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #495057;
    }
</style>

<!-- Welcome Banner -->
<div class="welcome-banner shadow-sm mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center">
        <div>
            <h2 class="h3 fw-bold mb-1">
                👋 Welcome back, <?php echo htmlspecialchars($_SESSION['fullname'] ?? 'Administrator'); ?>!
            </h2>
            <p class="mb-0 text-white-50">
                Online Payment Hub Dashboard &bull; <?php echo date("l, d F Y"); ?>
            </p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <a href="./?page=manage_employee" class="btn btn-light btn-sm fw-semibold shadow-sm px-3 py-2">
                <i class="fa fa-user-plus text-primary me-1"></i> Add Employee
            </a>
            <a href="./?page=manage_payroll" class="btn btn-warning btn-sm fw-semibold shadow-sm px-3 py-2 text-dark">
                <i class="fa fa-calculator me-1"></i> New Payment
            </a>
        </div>
    </div>
</div>

<!-- 4 Key Metrics Cards -->
<div class="row g-3 mb-4">
    <!-- Employees Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="./?page=employees" class="text-decoration-none">
            <div class="card stat-card shadow-sm h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning me-3">
                        <i class="fa fa-user-tie"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-medium text-uppercase">Total Employees</div>
                        <div class="h3 fw-bold mb-0 text-dark"><?php echo number_format($emp_count); ?></div>
                    </div>
                    <i class="fa fa-chevron-right text-muted opacity-25"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Departments Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="./?page=maintenance" class="text-decoration-none">
            <div class="card stat-card shadow-sm h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                        <i class="fa fa-building"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-medium text-uppercase">Departments</div>
                        <div class="h3 fw-bold mb-0 text-dark"><?php echo number_format($dept_count); ?></div>
                    </div>
                    <i class="fa fa-chevron-right text-muted opacity-25"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Designations Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="./?page=maintenance" class="text-decoration-none">
            <div class="card stat-card shadow-sm h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                        <i class="fa fa-id-badge"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-medium text-uppercase">Designations</div>
                        <div class="h3 fw-bold mb-0 text-dark"><?php echo number_format($desg_count); ?></div>
                    </div>
                    <i class="fa fa-chevron-right text-muted opacity-25"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Payroll Processed Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="./?page=payroll_list" class="text-decoration-none">
            <div class="card stat-card shadow-sm h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                        <i class="fa fa-file-invoice-dollar"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-medium text-uppercase">Payments Run</div>
                        <div class="h3 fw-bold mb-0 text-dark"><?php echo number_format($payroll_count); ?></div>
                    </div>
                    <i class="fa fa-chevron-right text-muted opacity-25"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Main Dashboard Content (2 Columns) -->
<div class="row g-4">
    <!-- Left Column: Recent Payroll Activity -->
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <h5 class="card-title mb-0 fw-bold text-dark">
                    <i class="fa fa-history text-primary me-2"></i> Recent Payment Activity
                </h5>
                <a href="./?page=payroll_list" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                    View All Payments <i class="fa fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase small text-muted">
                            <tr>
                                <th class="ps-3 py-3">Employee</th>
                                <th class="py-3">Month</th>
                                <th class="py-3">Payment Type</th>
                                <th class="py-3 text-end">Net Pay</th>
                                <th class="text-center pe-3 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $payroll_sql = "SELECT p.*, CONCAT(e.lastname, ', ', e.firstname, ' ', COALESCE(e.middlename,'')) as name, e.code 
                                            FROM payroll_list p 
                                            INNER JOIN employee_list e ON p.employee_id = e.employee_id 
                                            ORDER BY p.payroll_id DESC LIMIT 5";
                            $payroll_qry = $conn->query($payroll_sql);
                            if($payroll_qry && $payroll_qry->num_rows > 0):
                                while($row = $payroll_qry->fetch_assoc()):
                            ?>
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-semibold text-dark"><?php echo htmlspecialchars($row['name']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($row['code']); ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <i class="fa fa-calendar-alt text-primary me-1"></i>
                                        <?php echo date("F Y", strtotime($row['payroll_month'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        <?php echo ($row['payroll_type'] ?? 1) == 1 ? "Monthly" : "Semi-Monthly"; ?>
                                    </span>
                                </td>
                                <td class="text-end fw-bold text-success font-monospace">
                                    ₹<?php echo number_format($row['net_pay'], 2); ?>
                                </td>
                                <td class="text-center pe-3">
                                    <a href="./?page=view_payroll&id=<?php echo $row['payroll_id']; ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-1" title="View Payment Details">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa fa-file-invoice fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-2">No payment records generated yet.</p>
                                    <a href="./?page=manage_payroll" class="btn btn-sm btn-primary rounded-pill px-3">
                                        <i class="fa fa-plus me-1"></i> Create First Payment
                                    </a>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Quick Shortcuts & Recent Employees -->
    <div class="col-12 col-lg-4">
        <!-- Quick Actions Card -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="card-title mb-0 fw-bold text-dark">
                    <i class="fa fa-bolt text-warning me-2"></i> Quick Shortcuts
                </h5>
            </div>
            <div class="card-body p-3">
                <a href="./?page=manage_employee" class="quick-link-btn">
                    <i class="fa fa-user-plus text-primary"></i>
                    <span>Register New Employee</span>
                </a>
                <a href="./?page=manage_payroll" class="quick-link-btn">
                    <i class="fa fa-calculator text-success"></i>
                    <span>Process Payment</span>
                </a>
                <a href="./?page=tax_table" class="quick-link-btn">
                    <i class="fa fa-table text-warning"></i>
                    <span>Configure Tax Brackets</span>
                </a>
                <a href="./?page=maintenance" class="quick-link-btn mb-0">
                    <i class="fa fa-cogs text-secondary"></i>
                    <span>Manage Departments &amp; Roles</span>
                </a>
            </div>
        </div>

        <!-- Recent Employees Joined -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <h5 class="card-title mb-0 fw-bold text-dark">
                    <i class="fa fa-users text-primary me-2"></i> Active Staff
                </h5>
                <a href="./?page=employees" class="small text-primary text-decoration-none fw-semibold">View All</a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php 
                    $emp_sql = "SELECT e.*, d.name as dept, dd.name as desg 
                                FROM `employee_list` e 
                                INNER JOIN `department_list` d ON e.department_id = d.department_id 
                                INNER JOIN `designation_list` dd ON e.designation_id = dd.designation_id 
                                ORDER BY e.employee_id DESC LIMIT 4";
                    $emp_qry = $conn->query($emp_sql);
                    if($emp_qry && $emp_qry->num_rows > 0):
                        while($erow = $emp_qry->fetch_assoc()):
                            $initials = strtoupper(substr($erow['firstname'], 0, 1) . substr($erow['lastname'], 0, 1));
                    ?>
                    <li class="list-group-item px-3 py-2 d-flex align-items-center">
                        <div class="avatar-circle me-3">
                            <?php echo $initials; ?>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-semibold text-truncate text-dark">
                                <?php echo htmlspecialchars($erow['lastname'] . ', ' . $erow['firstname']); ?>
                            </div>
                            <small class="text-muted d-block text-truncate">
                                <?php echo htmlspecialchars($erow['desg']); ?> &bull; <?php echo htmlspecialchars($erow['dept']); ?>
                            </small>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 small">
                            Active
                        </span>
                    </li>
                    <?php 
                        endwhile;
                    else:
                    ?>
                    <li class="list-group-item text-center py-4 text-muted">
                        No employees found.
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
