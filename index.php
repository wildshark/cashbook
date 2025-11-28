<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashbook Pro</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-wallet2 me-2"></i>Cashbook Pro</a>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Dashboard Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 bg-white">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase mb-2">Total Income</h6>
                        <h3 class="text-success fw-bold" id="total-income">$0.00</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 bg-white">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase mb-2">Total Expenses</h6>
                        <h3 class="text-danger fw-bold" id="total-expense">$0.00</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 bg-info text-white">
                    <div class="card-body">
                        <h6 class="text-white-50 text-uppercase mb-2">Petty Cash Total</h6>
                        <h3 class="fw-bold" id="total-petty">$0.00</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 bg-primary text-white">
                    <div class="card-body">
                        <h6 class="text-white-50 text-uppercase mb-2">Current Balance</h6>
                        <h3 class="fw-bold" id="current-balance">$0.00</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions & Filters -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0 text-secondary">Transactions</h4>
            <div>
                <a href="api.php?action=export_cashbook" class="btn btn-outline-success me-2">
                    <i class="bi bi-download me-1"></i> Export Cashbook
                </a>
                <a href="api.php?action=export_petty" class="btn btn-outline-info me-2">
                    <i class="bi bi-download me-1"></i> Export Petty Cash
                </a>
                <button class="btn btn-outline-danger me-2" onclick="deleteAllData()">
                    <i class="bi bi-trash me-1"></i> Delete All CSV
                </button>
                <button class="btn btn-secondary me-2" data-bs-toggle="modal" data-bs-target="#pettyCashModal">
                    <i class="bi bi-receipt me-1"></i> Petty Cash Voucher
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                    <i class="bi bi-plus-lg me-1"></i> Add New
                </button>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Date</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th class="text-end">Amount</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="transaction-list">
                            <!-- Transactions will be loaded here -->
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Loading transactions...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Transaction Modal -->
    <div class="modal fade" id="addTransactionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Add Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-form">
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" id="t-date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" id="t-desc" placeholder="e.g., Office Supplies" required>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label">Type</label>
                                <select class="form-select" id="t-type">
                                    <option value="income">Income</option>
                                    <option value="expense" selected>Expense</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="t-amount" step="0.01" min="0.01" required>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Save Transaction</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Petty Cash Voucher Modal -->
    <div class="modal fade" id="pettyCashModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Petty Cash Voucher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="petty-cash-form">
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" id="pc-date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payee</label>
                            <input type="text" class="form-control" id="pc-payee" placeholder="e.g., John Doe" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" id="pc-desc" placeholder="e.g., Office Supplies" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="pc-amount" step="0.01" min="0.01" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Authorized By</label>
                            <input type="text" class="form-control" id="pc-auth" placeholder="e.g., Manager Name" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Create Voucher</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- View Voucher Modal -->
    <div class="modal fade" id="viewVoucherModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Petty Cash Voucher Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card border-0 bg-light mb-3">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-4 text-muted">Voucher No:</div>
                                <div class="col-8 fw-bold" id="v-id"></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 text-muted">Date:</div>
                                <div class="col-8" id="v-date"></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 text-muted">Payee:</div>
                                <div class="col-8" id="v-payee"></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 text-muted">Description:</div>
                                <div class="col-8" id="v-desc"></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 text-muted">Amount:</div>
                                <div class="col-8 fw-bold text-danger" id="v-amount"></div>
                            </div>
                            <div class="row">
                                <div class="col-4 text-muted">Authorized By:</div>
                                <div class="col-8" id="v-auth"></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toast-message">
                    Action successful.
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white text-center py-3 mt-5 shadow-sm">
        <div class="container">
            <p class="mb-0 text-muted">&copy; 2025 Cashbook Pro. All rights reserved.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html>
