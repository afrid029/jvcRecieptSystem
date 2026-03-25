<?php
require_once 'session_init.php';
if (!isset($_SESSION['user_id'])) {
     header("Location: index.php");
    echo "<script>window.location.pathname = 'index.php'</script>";
    exit();
}
require 'db.php';

$receipt = null;
$is_edit = false;

if (isset($_GET['id'])) {
    $is_edit = true;
    $stmt = $pdo->prepare("SELECT * FROM receipts WHERE id = :id");
    $stmt->execute([':id' => $_GET['id']]);
    $receipt = $stmt->fetch();
    if (!$receipt) {
        die("Receipt not found");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_edit ? 'Edit' : 'Create' ?> Receipt - J/Victoria College</title>
    <link rel="icon" type="image/jpeg" href="assets/images/logo.jpg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
     <script>
        window.addEventListener("pageshow", function (event) {
            if (event.persisted) {
                // Page was restored from bfcache
                window.location.reload();
            }
        });
    </script>
</head>
<body class="bg-emerald-50 text-slate-900 min-h-screen py-10 px-4">
    
    <div class="max-w-4xl mx-auto bg-white/90 backdrop-blur-sm rounded-2xl p-8 border border-emerald-200 shadow-xl shadow-emerald-200/50">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-xl md:text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-emerald-500">
                <?= $is_edit ? 'Edit Receipt' : 'Create New Receipt' ?>
            </h1>
            <a href="dashboard.php" class="px-4 py-2 bg-emerald-100 hover:bg-emerald-200 text-slate-700 rounded-lg font-semibold transition-colors flex items-center gap-2 text-sm md:text-base">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Dashboard
            </a>
        </div>

        <form action="receipt_actions.php" method="POST" class="space-y-6">
            <input type="hidden" name="action" value="<?= $is_edit ? 'update' : 'create' ?>">
            <?php if ($is_edit): ?>
                <input type="hidden" name="id" value="<?= $receipt['id'] ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Info (Left) -->
                 <div class="space-y-4 order-2 md:order-1"> <!-- Mobile: Order 2, Desktop: Order 1 (Left) -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Received From (Mr/Mrs/Ms) <span class="text-red-500">*</span></label>
                        <input type="text" name="received_from" required 
                            value="<?= $is_edit ? htmlspecialchars($receipt['received_from']) : '' ?>"
                            class="w-full px-4 py-2 bg-emerald-50/50 border border-emerald-200 rounded-lg text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required
                            value="<?= $is_edit ? htmlspecialchars($receipt['email']) : '' ?>"
                            class="w-full px-4 py-2 bg-emerald-50/50 border border-emerald-200 rounded-lg text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Phone # <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" required
                            value="<?= $is_edit ? htmlspecialchars($receipt['phone']) : '' ?>"
                            class="w-full px-4 py-2 bg-emerald-50/50 border border-emerald-200 rounded-lg text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Receipt Details (Right) -->
                <div class="space-y-4 order-1 md:order-2">
                    <?php if ($is_edit): ?>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Receipt #</label>
                        <input type="text" name="receipt_number" readonly
                            value="<?= htmlspecialchars($receipt['receipt_number']) ?>"
                            class="w-full px-4 py-2 bg-emerald-100 border border-emerald-200 rounded-lg text-slate-400 cursor-not-allowed">
                    </div>
                    <?php endif; ?>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="date" required
                            value="<?= $is_edit ? htmlspecialchars($receipt['date']) : date('Y-m-d') ?>"
                            class="w-full px-4 py-2 bg-emerald-50/50 border border-emerald-200 rounded-lg text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Amount <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-2.5 text-slate-500 font-semibold">$</span>
                            <input type="number" step="0.01" name="amount" required
                                value="<?= $is_edit ? htmlspecialchars($receipt['amount']) : '' ?>"
                                class="w-full pl-8 pr-4 py-2 bg-emerald-50/50 border border-emerald-200 rounded-lg text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Full Width Fields -->
             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                     <label class="block text-sm font-medium text-slate-700 mb-1">City <span class="text-red-500">*</span></label>
                     <input type="text" name="city" required
                         value="<?= $is_edit ? htmlspecialchars($receipt['city'] ?? '') : '' ?>"
                         class="w-full px-4 py-2 bg-emerald-50/50 border border-emerald-200 rounded-lg text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Address <span class="text-red-500">*</span></label>
                    <textarea name="address" required rows="1"
                        class="w-full px-4 py-2 bg-emerald-50/50 border border-emerald-200 rounded-lg text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"><?= $is_edit ? htmlspecialchars($receipt['address']) : '' ?></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Payment Method -->
                <div class="bg-emerald-50/30 p-4 rounded-xl border border-emerald-200">
                    <label class="block text-sm font-bold text-slate-700 mb-3">Method of Payment <span class="text-red-500">*</span></label>
                    <div class="space-y-2">
                        <?php 
                        $methods = ['Cash', 'Cheque', 'e-Transfer', 'Other']; 
                        $current_method = $is_edit ? $receipt['payment_method'] : '';
                        $is_custom_method = $is_edit && !in_array($current_method, ['Cash', 'Cheque', 'e-Transfer']);
                        ?>
                        <?php foreach ($methods as $method): ?>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="radio" name="payment_method" value="<?= $method ?>" required
                                    <?= ($is_edit && ($method === $current_method || ($method === 'Other' && $is_custom_method))) ? 'checked' : '' ?>
                                    class="text-emerald-500 focus:ring-emerald-500 bg-white border-emerald-300"
                                    onchange="toggleOther('method', this.value)">
                                <span class="text-slate-700"><?= $method ?></span>
                            </label>
                        <?php endforeach; ?>
                        
                        <div id="method_other_container" class="<?= $is_custom_method ? '' : 'hidden' ?> mt-2 pl-6">
                            <input type="text" name="payment_method_other" placeholder="Enter payment method"
                                value="<?= $is_custom_method ? htmlspecialchars($current_method) : '' ?>"
                                class="w-full px-3 py-1.5 bg-white border border-emerald-200 rounded text-sm text-slate-900 focus:ring-1 focus:ring-emerald-500">
                        </div>
                    </div>
                </div>

                <!-- Purpose & Country -->
                <div class="bg-emerald-50/30 p-4 rounded-xl border border-emerald-200 space-y-4">
                    
                    <!-- Country Selection (Super Admin Only) -->
                    <?php if ($_SESSION['role'] === 'super_admin' || $_SESSION['role'] === 'manager'): ?>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Country / OBA <span class="text-red-500">*</span></label>
                        <select name="country_id" required class="w-full px-3 py-2 bg-white border border-emerald-300 rounded-lg text-slate-900 focus:ring-2 focus:ring-emerald-500">
                             <option value="">Select Country</option>
                             <?php
                             $cStmt = $pdo->query("SELECT id, name FROM countries ORDER BY name ASC");
                             while($c = $cStmt->fetch()):
                                 $sel = ($is_edit && $receipt['country_id'] == $c['id']) ? 'selected' : '';
                             ?>
                                <option value="<?= $c['id'] ?>" <?= $sel ?>><?= htmlspecialchars($c['name']) ?></option>
                             <?php endwhile; ?>
                        </select>
                    </div>
                    <?php else: ?>
                        <input type="hidden" name="country_id" value="<?= $_SESSION['country_id'] ?>">
                    <?php endif; ?>

                    <!-- Purpose -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Purpose of Payment <span class="text-red-500">*</span></label>
                        <select name="purpose_id" required onchange="toggleOther('purpose', this.options[this.selectedIndex].text)"
                                class="w-full px-3 py-2 bg-white border border-emerald-300 rounded-lg text-slate-900 focus:ring-2 focus:ring-emerald-500">
                             <option value="">Select Purpose</option>
                             <?php
                             $pStmt = $pdo->query("SELECT * FROM purposes WHERE is_active = 1 ORDER BY name ASC");
                             $hasOther = false;
                             while($p = $pStmt->fetch()):
                                 $sel = ($is_edit && $receipt['purpose_id'] == $p['id']) ? 'selected' : '';
                             ?>
                                <option value="<?= $p['id'] ?>" <?= $sel ?>><?= htmlspecialchars($p['name']) ?></option>
                             <?php endwhile; ?>
                        </select>
                        
                        <!-- Other Purpose Input -->
                        <?php 
                             // Logic: If is_edit and other_purpose is set, show it.
                             $showOtherPurpose = $is_edit && !empty($receipt['other_purpose']);
                        ?>
                        <div id="purpose_other_container" class="<?= $showOtherPurpose ? '' : 'hidden' ?> mt-2">
                             <input type="text" name="other_purpose" placeholder="Enter specific purpose"
                                    value="<?= $is_edit ? htmlspecialchars($receipt['other_purpose'] ?? '') : '' ?>"
                                    class="w-full px-3 py-1.5 bg-white border border-emerald-200 rounded text-sm text-slate-900 focus:ring-1 focus:ring-emerald-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-4 pt-4">
                <a href="dashboard.php" 
                    class="px-6 py-2.5 bg-emerald-100 hover:bg-emerald-200 text-slate-700 rounded-lg font-semibold transition-colors">
                    Cancel
                </a>
                <button type="submit" id="submitBtn"
                    class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-blue-500 text-white rounded-lg font-semibold shadow-lg shadow-emerald-300/50 transition-all transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0"
                    <?= $is_edit ? 'disabled' : '' ?>>
                    <?= $is_edit ? 'Update Receipt' : 'Create Receipt' ?>
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleOther(type, value) {
            const container = document.getElementById(type + '_other_container');
            const input = container.querySelector('input');
            
            if (value === 'Other') {
                container.classList.remove('hidden');
                input.required = true;
                input.focus();
            } else {
                container.classList.add('hidden');
                input.required = false;
            }
            
            // Trigger form input event for the dirty check logic
            document.querySelector('form').dispatchEvent(new Event('input'));
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = document.getElementById('submitBtn');
            const isEdit = <?= $is_edit ? 'true' : 'false' ?>;

            if (isEdit) {
                // Serialize form data to compare
                const getFormData = () => new URLSearchParams(new FormData(form)).toString();
                const initialData = getFormData();

                form.addEventListener('input', () => {
                    const currentData = getFormData();
                    if (currentData !== initialData) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    } else {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                });
                
                // Also listen for change events for selects/radios just in case
                form.addEventListener('change', () => {
                    form.dispatchEvent(new Event('input'));
                });
            }
        });
    </script>
    <?php include 'loader.php'; ?>
</body>
</html>
