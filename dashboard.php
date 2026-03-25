<?php
require_once 'session_init.php';
require_once 'db.php';

/* Block cache */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

echo "<script> console.log('" . ($_SESSION['user_id'] ?? 'not set') . "') </script>";
/* Auth check */
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    echo "<script>window.location.pathname = 'index.php'</script>";
    exit();
}

$role = $_SESSION['role'];
$userCountryId = $_SESSION['country_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - J/Victoria College</title>
    <link rel="icon" type="image/jpeg" href="assets/images/logo.jpg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="assets/css/custom.css" rel="stylesheet">
    <script defer src="assets/js/main.js"></script>
    <script>
        console.log('Ho')
    </script>
    <style>
        .action-menu-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            z-index: 50;
            min-width: 160px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .action-menu-dropdown.active {
            display: block;
            animation: fadeIn 0.15s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="bg-gray-50 text-slate-900 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white/95 backdrop-blur-md border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between py-3 md:h-20 gap-3 md:gap-0">
                <div class="flex items-center gap-3">
                    <a href="index.php">
                        <img src="assets/images/logo.jpg" alt="Logo"
                            class="w-10 h-10 rounded-full border border-theme-red object-cover hover:opacity-80 transition">
                    </a>
                    <div>
                        <span class="block text-lg font-bold font-arima text-slate-800">J/Victoria College</span>
                        <span class="block text-[10px] uppercase tracking-widest text-gray-400">Administration
                            Portal</span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden md:block text-right">
                        <div class="text-sm font-bold text-slate-700 capitalize"><?= htmlspecialchars($_SESSION['username']) ?>
                        </div>
                        <div class="text-xs text-gray-400 uppercase">
                            <?= $role == 'super_admin' ? 'Super Administrator' : ($role == 'manager' ? 'Manager' : 'OBA Admin') ?>
                        </div>
                    </div>
                    <a href="logout.php"
                        class="px-4 py-2 bg-red-700 hover:bg-red-600 text-slate-100 rounded-lg text-sm font-bold transition flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="20" y1="4" x2="20" y2="20"></line>
                                <polyline points="10 17 5 12 10 7"></polyline>
                                <line x1="5" y1="12" x2="16" y2="12"></line>
                            </svg>
                        Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <!-- Action Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Create Receipt (All) -->
            <a href="receipt_form.php"
                class="group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition transform hover:-translate-y-1 flex flex-col items-center justify-center text-center">
                <div
                    class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-emerald-600 group-hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-800">New Receipt</h3>
                <p class="text-xs text-gray-400 mt-1">Record a new donation</p>
            </a>

            <!-- Manage Events (All) -->
            <a href="manage_events.php"
                class="group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition transform hover:-translate-y-1 flex flex-col items-center justify-center text-center">
                <div
                    class="w-12 h-12 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-purple-600 group-hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-800">Manage Events</h3>
                <p class="text-xs text-gray-400 mt-1">Update upcoming events</p>
            </a>

            <!-- Manage News (All) -->
            <a href="manage_news.php"
                class="group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition transform hover:-translate-y-1 flex flex-col items-center justify-center text-center">
                <div
                    class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-blue-600 group-hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                        </path>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-800">Manage News</h3>
                <p class="text-xs text-gray-400 mt-1">Create news articles</p>
            </a>

            <?php if ($role === 'super_admin'): ?>
                <!-- Manage Countries/Purposes (Super Admin) -->
                <a href="manage_countries.php"
                    class="group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition transform hover:-translate-y-1 flex flex-col items-center justify-center text-center">
                    <div
                        class="w-12 h-12 bg-sky-50 text-sky-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-sky-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800">OSA Management</h3>
                    <p class="text-xs text-gray-400 mt-1">Countries & Purposes</p>
                </a>

                <a href="manage_ads.php"
                    class="group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition transform hover:-translate-y-1 flex flex-col items-center justify-center text-center">
                    <div
                        class="w-12 h-12 bg-orange-50 text-orange-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-orange-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800">Advertisements</h3>
                    <p class="text-xs text-gray-400 mt-1">Homepage ads carousel</p>
                </a>

                <a href="manage_posters.php"
                    class="group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition transform hover:-translate-y-1 flex flex-col items-center justify-center text-center">
                    <div
                        class="w-12 h-12 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-purple-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800">Posters</h3>
                    <p class="text-xs text-gray-400 mt-1">Homepage posters</p>
                </a>

                <a href="admin_management.php"
                    class="group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition transform hover:-translate-y-1 flex flex-col items-center justify-center text-center">
                    <div
                        class="w-12 h-12 bg-orange-50 text-orange-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-orange-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800">Admin Users</h3>
                    <p class="text-xs text-gray-400 mt-1">Create & Manage Admins</p>
                </a>

                <!-- Manage Supervisors (Super Admin) -->
                <a href="manage_supervisors.php"
                    class="group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition transform hover:-translate-y-1 flex flex-col items-center justify-center text-center">
                    <div
                        class="w-12 h-12 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-teal-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800">Manage Supervisors</h3>
                    <p class="text-xs text-gray-400 mt-1">Create & Manage Managers</p>
                </a>

            <?php elseif ($role === 'manager'): ?>
                <!-- Manager role: Advertisements, Posters -->
                <a href="manage_ads.php"
                    class="group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition transform hover:-translate-y-1 flex flex-col items-center justify-center text-center">
                    <div
                        class="w-12 h-12 bg-orange-50 text-orange-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-orange-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800">Advertisements</h3>
                    <p class="text-xs text-gray-400 mt-1">Homepage ads carousel</p>
                </a>

                <a href="manage_posters.php"
                    class="group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition transform hover:-translate-y-1 flex flex-col items-center justify-center text-center">
                    <div
                        class="w-12 h-12 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-purple-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800">Posters</h3>
                    <p class="text-xs text-gray-400 mt-1">Homepage posters</p>
                </a>

            <?php else: ?>
                <!-- Manage OBA Info (Country Admin) -->
                <a href="manage_oba.php"
                    class="group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition transform hover:-translate-y-1 flex flex-col items-center justify-center text-center">
                    <div
                        class="w-12 h-12 bg-sky-50 text-sky-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-sky-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800">OSA Details</h3>
                    <p class="text-xs text-gray-400 mt-1">Update Info & Logo</p>
                </a>
                <!-- Placeholder to balance grid -->
                <div class="hidden lg:block"></div>
            <?php endif; ?>
        </div>

        <!-- Recent Receipts Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h2 class="text-lg font-bold text-slate-800">Donation Receipts</h2>
                <!-- Filter info could go here -->
            </div>

            <?php
            // Data Fetching Logic
            $limit = 20;
            $page = $_GET['page'] ?? 1;
            $offset = ($page - 1) * $limit;

            $where = "";
            $params = [];

            if ($role !== 'super_admin' && $role !== 'manager') {
                $where = "WHERE r.country_id = ?";
                $params[] = $userCountryId;
            }

            // Count
            $countParams = $params; // Copy for count query
            $total = $pdo->prepare("SELECT COUNT(*) FROM receipts r $where");
            $total->execute($countParams);
            $totalRecs = $total->fetchColumn();
            $totalPages = ceil($totalRecs / $limit);

            // Query
            // Join purpose name
            $sql = "SELECT r.*, p.name as purpose_name, c.name as country_name, c.flag_image 
                     FROM receipts r 
                     LEFT JOIN purposes p ON r.purpose_id = p.id 
                     LEFT JOIN countries c ON r.country_id = c.id
                     $where 
                     ORDER BY r.date DESC, r.id DESC 
                     LIMIT $limit OFFSET $offset";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $receipts = $stmt->fetchAll();
            ?>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 uppercase font-bold text-xs border-b border-gray-200">
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Receipt #</th>
                            <th class="px-6 py-4">Donor</th>
                            <th class="px-6 py-4">Amount</th>
                            <th class="px-6 py-4">Purpose</th>
                            <?php if ($role === 'super_admin' || $role === 'manager'): ?>
                                <th class="px-6 py-4">Country</th>
                            <?php endif; ?>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (count($receipts) > 0):
                            foreach ($receipts as $r): ?>
                                <tr class="hover:bg-sky-50 transition group">
                                    <td class="px-6 py-4 text-gray-500"><?= $r['date'] ?></td>
                                    <td class="px-6 py-4 font-bold text-slate-700"><?= $r['receipt_number'] ?></td>
                                    <td class="px-6 py-4 font-medium text-slate-900"><?= $r['received_from'] ?></td>
                                    <td class="px-6 py-4 font-bold text-emerald-600">$<?= number_format($r['amount'], 2) ?></td>
                                    <td class="px-6 py-4 text-slate-500">
                                        <?= htmlspecialchars($r['purpose_name']) ?>
                                        <?php if ($r['other_purpose'])
                                            echo '<span class="text-xs text-gray-400">(' . htmlspecialchars($r['other_purpose']) . ')</span>'; ?>
                                    </td>
                                    <?php if ($role === 'super_admin' || $role === 'manager'): ?>
                                        <td class="px-6 py-4">
                                            <?php if ($r['country_name']): ?>
                                                <div class="flex items-center justify-center gap-2">
                                                    <?php if ($r['flag_image']): ?>
                                                        <img src="<?= htmlspecialchars($r['flag_image']) ?>" class="w-6 h-6 rounded-full object-cover border border-gray-200" title="<?= htmlspecialchars($r['country_name']) ?>">
                                                    <?php else: ?>
                                                        <span class="px-2 py-1 bg-gray-100 rounded text-xs font-bold text-gray-600"><?= htmlspecialchars($r['country_name']) ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-gray-300">-</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                    <td class="px-6 py-4 text-center">
                                        <?php if ($r['email_sent']): ?>
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Sent</span>
                                        <?php else: ?>
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-right relative">
                                        <!-- Desktop Actions -->
                                        <div class="hidden md:flex items-center justify-end gap-3">
                                            <?php if (!$r['email_sent']): ?>
                                                <div class="relative group">
                                                    <button onclick="sendEmail(this, <?= $r['id'] ?>)"
                                                        class="w-9 h-9 flex items-center justify-center rounded-full bg-sky-50 text-sky-600 hover:bg-sky-600 hover:text-white transition shadow-sm border border-sky-100">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                    </button>
                                                    <!-- Tooltip -->
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-slate-800 text-white text-[10px] rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10 shadow-lg">
                                                        Send Email Receipt
                                                        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-slate-800"></div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <div class="relative group">
                                                <a href="receipt_form.php?id=<?= $r['id'] ?>"
                                                    class="w-9 h-9 flex items-center justify-center rounded-full bg-slate-50 text-slate-600 hover:bg-slate-600 hover:text-white transition shadow-sm border border-slate-100">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <!-- Tooltip -->
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-slate-800 text-white text-[10px] rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10 shadow-lg">
                                                    Edit Record
                                                    <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-slate-800"></div>
                                                </div>
                                            </div>
                                            <div class="relative group">
                                                <button onclick="deleteReceipt(this, <?= $r['id'] ?>)"
                                                    class="w-9 h-9 flex items-center justify-center rounded-full bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition shadow-sm border border-red-100">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                                <!-- Tooltip -->
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-slate-800 text-white text-[10px] rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10 shadow-lg">
                                                    Delete Record
                                                    <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-slate-800"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Mobile Actions -->
                                        <div class="md:hidden flex justify-end">
                                            <button onclick="toggleActionMenu(event, <?= $r['id'] ?>)" 
                                                class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-600 active:bg-gray-200 transition">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                            </button>
                                            <div id="action-menu-<?= $r['id'] ?>" class="action-menu-dropdown text-left">
                                                <?php if (!$r['email_sent']): ?>
                                                    <button onclick="sendEmail(this, <?= $r['id'] ?>)" class="w-full px-4 py-3 flex items-center gap-3 text-sky-600 hover:bg-sky-50 font-bold border-b border-gray-50">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                        Send Email
                                                    </button>
                                                <?php endif; ?>
                                                <a href="receipt_form.php?id=<?= $r['id'] ?>" class="w-full px-4 py-3 flex items-center gap-3 text-slate-600 hover:bg-slate-50 font-bold border-b border-gray-50">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    Edit Receipt
                                                </a>
                                                <button onclick="deleteReceipt(this, <?= $r['id'] ?>)" class="w-full px-4 py-3 flex items-center gap-3 text-red-600 hover:bg-red-50 font-bold">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-8 text-gray-400">No receipts found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="px-6 py-4 border-t border-gray-100 flex justify-between items-center bg-gray-50/50 text-sm">
                    <span class="text-gray-500">Page <?= $page ?> of <?= $totalPages ?></span>
                    <div class="flex gap-1 md:gap-2">
                        <!-- First & Prev -->
                        <?php if ($page > 1): ?>
                            <a href="?page=1"
                                class="px-2 py-1 md:px-3 bg-white border border-gray-200 rounded hover:bg-gray-50 text-slate-600"
                                title="First">&laquo;</a>
                            <a href="?page=<?= $page - 1 ?>"
                                class="px-2 py-1 md:px-3 bg-white border border-gray-200 rounded hover:bg-gray-50 text-slate-600">Prev</a>
                        <?php else: ?>
                            <button disabled
                                class="px-2 py-1 md:px-3 bg-gray-50 border border-gray-100 rounded text-gray-300 cursor-not-allowed">&laquo;</button>
                            <button disabled
                                class="px-2 py-1 md:px-3 bg-gray-50 border border-gray-100 rounded text-gray-300 cursor-not-allowed">Prev</button>
                        <?php endif; ?>

                        <!-- Next & Last -->
                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page + 1 ?>"
                                class="px-2 py-1 md:px-3 bg-white border border-gray-200 rounded hover:bg-gray-50 text-slate-600">Next</a>
                            <a href="?page=<?= $totalPages ?>"
                                class="px-2 py-1 md:px-3 bg-white border border-gray-200 rounded hover:bg-gray-50 text-slate-600"
                                title="Last">&raquo;</a>
                        <?php else: ?>
                            <button disabled
                                class="px-2 py-1 md:px-3 bg-gray-50 border border-gray-100 rounded text-gray-300 cursor-not-allowed">Next</button>
                            <button disabled
                                class="px-2 py-1 md:px-3 bg-gray-50 border border-gray-100 rounded text-gray-300 cursor-not-allowed">&raquo;</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php if (isset($_GET['msg'])): ?>
        <div id="toast"
            class="fixed bottom-4 right-4 bg-slate-800 text-white px-6 py-3 rounded-lg shadow-2xl flex items-center gap-3 animate-bounce-in z-[300]">
            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span><?= htmlspecialchars($_GET['msg']) ?></span>
        </div>
        <script>setTimeout(() => document.getElementById('toast').remove(), 3000);</script>
    <?php endif; ?>

    <!-- Event Popup Modal (Reused) -->
    <div id="eventModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="closeEventModal()">
        </div>
        <div
            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl p-4 h-full md:h-auto flex items-center justify-center pointer-events-none">
            <div
                class="bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in relative pointer-events-auto max-h-[90vh] flex flex-col md:flex-row w-full">
                <button onclick="closeEventModal()"
                    class="absolute top-4 right-4 z-50 bg-black/50 text-white p-2 rounded-full hover:bg-black/70 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="w-full md:w-2/3 bg-black flex items-center justify-center relative group">
                    <img id="modalEventImage" src="" alt="Event"
                        class="max-h-[50vh] md:max-h-[80vh] w-full object-contain cursor-zoom-in transition-transform duration-300"
                        onclick="toggleZoom(this)">
                </div>
                <div class="w-full md:w-1/3 p-8 flex flex-col overflow-y-auto">
                    <span id="modalEventCountry"
                        class="inline-block px-3 py-1 bg-theme-red text-white text-xs font-bold rounded-full w-fit mb-4">Global</span>
                    <h2 id="modalEventTitle" class="text-2xl font-bold text-slate-800 mb-2 font-arima"></h2>
                    <div class="flex items-center gap-2 text-theme-red font-semibold mb-6">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span id="modalEventDate"></span>
                    </div>
                    <p id="modalEventDesc" class="text-gray-600 leading-relaxed text-sm"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEventModal(ev) {
            document.getElementById('modalEventTitle').innerText = ev.title;
            document.getElementById('modalEventDate').innerText = new Date(ev.event_date).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            document.getElementById('modalEventDesc').innerText = ev.description || 'No description available.';
            document.getElementById('modalEventImage').src = ev.image || 'assets/images/logo.jpg';

            const badge = document.getElementById('modalEventCountry');
            if (ev.country_name) {
                badge.innerText = ev.country_name;
                badge.className = 'inline-block px-3 py-1 bg-black text-white text-xs font-bold rounded-full w-fit mb-4';
            } else {
                badge.innerText = 'Global';
                badge.className = 'inline-block px-3 py-1 bg-theme-red text-white text-xs font-bold rounded-full w-fit mb-4';
            }

            document.getElementById('eventModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEventModal() {
            document.getElementById('eventModal').classList.add('hidden');
            document.body.style.overflow = '';
            // Reset zoom
            const img = document.getElementById('modalEventImage');
            img.classList.remove('scale-150', 'cursor-zoom-out');
            img.classList.add('cursor-zoom-in');
        }

        function toggleZoom(img) {
            if (img.classList.contains('scale-150')) {
                img.classList.remove('scale-150', 'cursor-zoom-out');
                img.classList.add('cursor-zoom-in');
            } else {
                img.classList.add('scale-150', 'cursor-zoom-out');
                img.classList.remove('cursor-zoom-in');
            }
        }
    </script>
</body>

<script>
    // Upcoming Events Fetcher
    async function loadUpcomingEvents() {
        const container = document.getElementById('upcoming-events-container');
        if (!container) return; // Might not exist if I haven't added HTML yet

        try {
            const data = await apiRequest('api/public.php?action=upcoming_events');
            if (data.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-400 py-4">No upcoming events.</div>';
                return;
            }
            container.innerHTML = data.slice(0, 3).map(ev => `
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-gray-100 overflow-hidden cursor-pointer group" onclick='openEventModal(${JSON.stringify(ev)})'>
                    <div class="h-32 bg-gray-100 relative">
                        <img src="${ev.image || 'assets/images/logo.jpg'}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute top-2 right-2 bg-theme-red text-white text-[10px] px-2 py-0.5 rounded-full font-bold">
                            ${new Date(ev.event_date).toLocaleDateString()}
                        </div>
                    </div>
                    <div class="p-3">
                         <h4 class="font-bold text-slate-800 text-sm line-clamp-1 mb-1">${ev.title}</h4>
                         <p class="text-xs text-gray-500 line-clamp-1">${ev.country_name || 'Global Event'}</p>
                    </div>
                </div>
            `).join('');
        } catch (e) { console.error(e); }
    }
    document.addEventListener('DOMContentLoaded', loadUpcomingEvents);

    // Actions
    async function sendEmail(btn, id) {
        const originalContent = btn.innerHTML;
        btn.innerHTML = 'Sending...';
        btn.classList.add('opacity-50', 'pointer-events-none');

        try {
            await apiRequest(`receipt_actions.php?action=send_email&id=${id}`);
            showAlert('Email sent successfully');
            setTimeout(() => window.location.reload(), 1000);
        } catch (err) {
            console.error(err);
            showAlert('Email sent (or check status)', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } finally {
            btn.innerHTML = originalContent;
            btn.classList.remove('opacity-50', 'pointer-events-none');
        }
    }

    async function deleteReceipt(btn, id) {
        if (!await showConfirm('Delete this receipt?')) return;

        const originalText = btn.innerText;
        btn.innerText = 'Run...';
        btn.disabled = true;
        btn.classList.add('opacity-50');

        try {
            await apiRequest(`receipt_actions.php?action=delete&id=${id}`);
            showAlert('Receipt deleted successfully');
            setTimeout(() => window.location.reload(), 1000);
        } catch (e) {
            showAlert('Deletion failed: ' + e.message, 'error');
            btn.innerText = originalText;
            btn.disabled = false;
            btn.classList.remove('opacity-50');
        }
    }

    function toggleActionMenu(event, id) {
        event.stopPropagation();
        const menu = document.getElementById('action-menu-' + id);
        
        // Close all other menus
        document.querySelectorAll('.action-menu-dropdown').forEach(m => {
            if (m !== menu) m.classList.remove('active');
        });
        
        menu.classList.toggle('active');
        
        // Close on outside click
        const closer = () => {
            menu.classList.remove('active');
            document.removeEventListener('click', closer);
        };
        setTimeout(() => document.addEventListener('click', closer), 10);
    }
</script>
</body>

</html>