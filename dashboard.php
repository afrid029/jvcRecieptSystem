<?php
require_once 'session_init.php';

/* Block cache */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

/* Auth check */
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    echo "<script>window.location.pathname = 'index.php'</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - J/Victoria College</title>
    <link rel="icon" type="image/jpeg" href="assets/images/logo.jpg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
     <script>
        window.addEventListener("pageshow", function (event) {
            if (event.persisted) {
                // Page was restored from bfcache
                window.location.reload();
            }
        });
    </script>
</head>

<body class="bg-sky-50 text-slate-900 min-h-screen">
    <nav class="bg-white/90 backdrop-blur-md border-b border-sky-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between py-3 md:h-16 gap-3 md:gap-0">
                <div class="flex items-center gap-3">
                    <img src="assets/images/logo.jpg" alt="Logo" class="w-8 h-8 rounded-full border border-sky-300">
                    <span
                        class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-sky-600 to-blue-600">
                        J/Victoria College
                    </span>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-3 md:gap-4">
                    <div class="flex items-center text-sm md:text-base text-slate-600">
                        <span>Welcome, <span class="font-semibold text-slate-900"><?= htmlspecialchars($_SESSION['username']) ?></span></span>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin'): ?>
                            <span class="ml-2 text-[10px] uppercase tracking-wider font-bold bg-sky-100 text-sky-700 px-2 py-1 rounded-md border border-sky-200 whitespace-nowrap">Super Admin</span>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center gap-2">
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin'): ?>
                            <a href="admin_management.php" class="px-3 py-1.5 md:px-4 md:py-2 text-xs md:text-sm font-semibold text-white bg-sky-600 hover:bg-sky-700 rounded-lg transition-all shadow-md shadow-sky-200 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                                <span class="hidden sm:inline">Manage Admins</span>
                                <span class="sm:hidden">Admins</span>
                            </a>
                        <?php endif; ?>
                        <a href="logout.php"
                            class="px-3 py-1.5 md:px-4 md:py-2 text-xs md:text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-all shadow-md shadow-red-200 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                            </svg>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 md:py-12 px-2 sm:px-6 lg:px-8">
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-4 md:p-8 border border-sky-200 shadow-xl shadow-sky-200/50">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <h1 class="text-xl md:text-3xl font-bold text-slate-900">Dashboard</h1>
                <a href="receipt_form.php"
                    class="w-full sm:w-auto text-center px-4 py-2 md:px-6 md:py-2 text-sm md:text-base bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 rounded-lg text-white font-semibold shadow-lg shadow-sky-300/50 transition-all transform hover:-translate-y-0.5">
                    + Create Receipt
                </a>
            </div>

            <?php if (isset($_GET['msg'])): ?>
                <div id="status-msg" class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-lg text-emerald-700 transition-opacity duration-1000">
                    <?= htmlspecialchars($_GET['msg']) ?>
                </div>
                <script>
                    setTimeout(() => {
                        const msg = document.getElementById('status-msg');
                        if (msg) {
                            msg.style.opacity = '0';
                            setTimeout(() => msg.remove(), 1000);
                        }
                    }, 3000);
                </script>
            <?php endif; ?>

            <?php
            // Sorting Configuration - must be defined before table headers use these variables
            $allowed_sort_columns = ['date', 'email_sent'];
            $sort = isset($_GET['sort']) && in_array($_GET['sort'], $allowed_sort_columns) ? $_GET['sort'] : 'date';
            $order = isset($_GET['order']) && strtoupper($_GET['order']) === 'ASC' ? 'ASC' : 'DESC';
            ?>

            <div class="bg-white rounded-xl border border-sky-200 overflow-visible relative shadow-sm"> <!-- overflow-visible for dropdowns -->
                <div class="overflow-x-auto min-h-[400px]"> <!-- Scroll container -->
                    <table class="w-full text-left border-collapse min-w-[700px]"> <!-- Min width to force scroll on small screens -->
                        <thead>
                            <tr class="bg-sky-50 text-slate-600 border-b border-sky-200 uppercase text-[11px] tracking-wider font-bold">
                                <th class="p-4 font-semibold">
                                    <a href="?sort=date&order=<?= ($sort === 'date' && $order === 'ASC') ? 'DESC' : 'ASC' ?>" class="flex items-center gap-2 hover:text-sky-700 transition-colors cursor-pointer">
                                        Date
                                        <?php if ($sort === 'date'): ?>
                                            <?php if ($order === 'ASC'): ?>
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"/></svg>
                                            <?php else: ?>
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z"/></svg>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <svg class="w-3 h-3 opacity-30" fill="currentColor" viewBox="0 0 20 20"><path d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z"/></svg>
                                        <?php endif; ?>
                                    </a>
                                </th>
                                <th class="p-4 font-semibold">Receipt #</th>
                                <th class="p-4 font-semibold">Name</th>
                                <th class="p-4 font-semibold">Amount</th>
                                <th class="p-4 font-semibold">Purpose</th>
                                <th class="p-4 font-semibold">
                                    <a href="?sort=email_sent&order=<?= ($sort === 'email_sent' && $order === 'ASC') ? 'DESC' : 'ASC' ?>" class="flex items-center gap-2 hover:text-sky-700 transition-colors cursor-pointer">
                                        Status
                                        <?php if ($sort === 'email_sent'): ?>
                                            <?php if ($order === 'ASC'): ?>
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"/></svg>
                                            <?php else: ?>
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z"/></svg>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <svg class="w-3 h-3 opacity-30" fill="currentColor" viewBox="0 0 20 20"><path d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z"/></svg>
                                        <?php endif; ?>
                                    </a>
                                </th>
                                <th class="p-4 font-semibold text-center">Actions</th> <!-- Centered Actions -->
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-600">
                            <?php
                            require_once 'db.php';

                            // Pagination Configuration
                            $limit = 30; // Records per page
                            $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
                            if ($page < 1) $page = 1;
                            $offset = ($page - 1) * $limit;

                            // Build ORDER BY clause
                            $orderBy = "ORDER BY ";
                            if ($sort === 'email_sent') {
                                $orderBy .= "email_sent $order, date DESC";
                            } else {
                                $orderBy .= "date $order, created_at DESC";
                            }

                            // Get Total Count
                            $countStmt = $pdo->query("SELECT COUNT(*) FROM receipts");
                            $total_records = $countStmt->fetchColumn();
                            $total_pages = ceil($total_records / $limit);

                            // Fetch Records for current page
                            $stmt = $pdo->prepare("SELECT * FROM receipts $orderBy LIMIT :limit OFFSET :offset");
                            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
                            $stmt->execute();
                            $receipts = $stmt->fetchAll();


                            if (count($receipts) > 0):
                                foreach ($receipts as $r): 
                            ?>
                                <tr class="hover:bg-sky-50 transition-colors relative border-b border-sky-100 last:border-0">
                                    <td class="p-4 text-slate-500 text-sm"><?= htmlspecialchars($r['date']) ?></td>
                                    <td class="p-4 text-slate-900 font-semibold text-sm"><?= htmlspecialchars($r['receipt_number']) ?></td>
                                    <td class="p-4 text-slate-600 text-sm"><?= htmlspecialchars($r['received_from']) ?></td>
                                    <td class="p-4 text-emerald-600 font-bold">$<?= number_format($r['amount'], 2) ?></td>
                                    <td class="p-4 text-slate-500 text-sm"><?= htmlspecialchars($r['payment_purpose']) ?></td>
                                    <td class="p-4">
                                        <?php if ($r['email_sent']): ?>
                                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 rounded-md border border-emerald-100">Sent</span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 rounded-md border border-amber-100">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4 text-center">
                                        <!-- Action Menu -->
                                        <div class="relative inline-block text-left group">
                                        <div class="relative inline-block text-left group">
                                            <button onclick="toggleMenu('menu-<?= $r['id'] ?>')" class="text-slate-400 hover:text-slate-900 focus:outline-none p-2 rounded-lg hover:bg-sky-100 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                </svg>
                                            </button>
                                            
                                            <!-- Dropdown Menu -->
                                            <div id="menu-<?= $r['id'] ?>" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl py-2 z-50 border border-sky-200 ring-1 ring-black ring-opacity-5 origin-top-right transform transition-all duration-200">
                                                <div class="px-4 py-2 border-b border-sky-100 mb-2">
                                                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Manage</p>
                                                </div>
                                                <a href="receipt_form.php?id=<?= $r['id'] ?>" class="flex items-center px-4 py-2 text-sm text-slate-600 hover:bg-sky-50 hover:text-slate-900 transition-colors">
                                                    <svg class="w-4 h-4 mr-3 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    Edit Receipt
                                                </a>
                                                
                                                <?php if ($r['email_sent']): ?>
                                                    <div class="flex items-center px-4 py-2 text-sm text-slate-300 cursor-not-allowed">
                                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                        Email Sent
                                                    </div>
                                                <?php else: ?>
                                                    <a href="receipt_actions.php?action=send_email&id=<?= $r['id'] ?>" class="flex items-center px-4 py-2 text-sm text-slate-600 hover:bg-sky-50 hover:text-slate-900 transition-colors">
                                                        <svg class="w-4 h-4 mr-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                        Send Email
                                                    </a>
                                                <?php endif; ?>
 
                                                <div class="my-1 border-t border-sky-100"></div>
                                                
                                                <a href="#" onclick="showDeleteModal('receipt_actions.php?action=delete&id=<?= $r['id'] ?>')" class="flex items-center px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition-colors">
                                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    Delete
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-gray-400">No receipts found. Create one to get started!</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Pagination Controls -->
            <?php if ($total_pages > 1): ?>
            <div class="flex justify-between items-center mt-6 bg-sky-50 p-4 rounded-xl border border-sky-200">
                <div>
                    <span class="text-slate-500 text-sm">
                        Showing page <span class="font-bold text-slate-900"><?= $page ?></span> of <span class="font-bold text-slate-900"><?= $total_pages ?></span>
                    </span>
                </div>
                <div class="flex gap-2">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>&sort=<?= $sort ?>&order=<?= $order ?>" class="px-4 py-2 bg-white hover:bg-sky-50 rounded-lg text-sm font-semibold transition-colors border border-sky-200 text-slate-700 shadow-sm">
                            &larr; Previous
                        </a>
                    <?php else: ?>
                        <span class="px-4 py-2 bg-sky-50 text-slate-300 rounded-lg text-sm font-semibold border border-sky-100 cursor-not-allowed">
                            &larr; Previous
                        </span>
                    <?php endif; ?>
 
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $page + 1 ?>&sort=<?= $sort ?>&order=<?= $order ?>" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 rounded-lg text-sm font-semibold transition-all shadow-md shadow-sky-200 text-white">
                            Next &rarr;
                        </a>
                    <?php else: ?>
                        <span class="px-4 py-2 bg-sky-50 text-slate-300 rounded-lg text-sm font-semibold border border-sky-100 cursor-not-allowed">
                            Next &rarr;
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-[100] backdrop-blur-sm p-4">
        <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-sm border border-sky-100 text-center transform transition-all scale-95 opacity-0 duration-200" id="modalContainer">
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-100">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Delete Receipt?</h3>
            <p class="text-slate-500 mb-8">This action cannot be undone. Are you sure you want to remove this record?</p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 bg-sky-100 hover:bg-sky-200 text-slate-700 rounded-xl font-semibold transition-colors">
                    Cancel
                </button>
                <a id="confirmDeleteBtn" href="#" class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 rounded-xl font-semibold transition-all shadow-lg shadow-red-200 text-white text-center">
                    Delete
                </a>
            </div>
        </div>
    </div>

    <script>
        function toggleMenu(menuId) {
            // Close all others first
            document.querySelectorAll('[id^="menu-"]').forEach(el => {
                if (el.id !== menuId) el.classList.add('hidden');
            });
            const menu = document.getElementById(menuId);
            menu.classList.toggle('hidden');
        }

        // Modal Functions
        function showDeleteModal(deleteUrl) {
            const modal = document.getElementById('deleteModal');
            const container = document.getElementById('modalContainer');
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            
            deleteBtn.href = deleteUrl;
            modal.style.display = 'flex';
            modal.classList.remove('hidden');
            
            // Animation
            setTimeout(() => {
                container.classList.remove('scale-95', 'opacity-0');
                container.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const container = document.getElementById('modalContainer');
            
            container.classList.remove('scale-100', 'opacity-100');
            container.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.style.display = 'none';
                modal.classList.add('hidden');
            }, 200);
        }

        // Close menus when clicking outside
        window.onclick = function(event) {
            if (!event.target.closest('.relative.inline-block')) {
                document.querySelectorAll('[id^="menu-"]').forEach(el => {
                    el.classList.add('hidden');
                });
            }
            // Close modal if clicking outside container
            const modal = document.getElementById('deleteModal');
            if (event.target === modal) {
                closeDeleteModal();
            }
        }
    </script>
    <?php include 'loader.php'; ?>
</body>
</html>