<?php
require_once 'session_init.php';
require 'db.php';

// Access Control - Super Admin Only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header("Location: dashboard.php");
    echo "<script>window.location.pathname = 'dashboard.php'</script>";
    exit();
}

// Fetch all manager users
$stmt = $pdo->query("
    SELECT u.* 
    FROM users u 
    WHERE u.role = 'manager'
    ORDER BY u.created_at DESC
");
$managers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Supervisors - J/Victoria College</title>
    <link rel="icon" type="image/jpeg" href="assets/images/logo.jpg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Arima:wght@100..700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Arima', sans-serif;
        }
    </style>
    <script>
        window.addEventListener("pageshow", function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</head>

<body class="bg-teal-50 text-slate-900 min-h-screen p-4 md:p-8">

    <div class="max-w-6xl mx-auto space-y-8">
        <!-- Header -->
        <div
            class="flex flex-col md:flex-row justify-between items-center bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-xl border border-teal-200">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Manage Supervisors</h1>
                <p class="text-slate-500 mt-1">Create & manage supervisor accounts</p>
            </div>
            <div class="flex gap-4 mt-4 md:mt-0">
                <a href="dashboard.php"
                    class="px-6 py-2 bg-teal-100 hover:bg-teal-200 text-slate-700 flex gap-2 items-center rounded-lg font-semibold transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Back
                    to
                    Dashboard</a>
            </div>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div id="status-msg"
                class="p-4 bg-emerald-50 border border-emerald-200 rounded-lg text-emerald-700 transition-opacity duration-1000">
                <?= htmlspecialchars($_GET['msg']) ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div id="error-msg"
                class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 transition-opacity duration-1000">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>
        <script>
            setTimeout(() => {
                const msg = document.getElementById('status-msg');
                const err = document.getElementById('error-msg');
                if (msg) {
                    msg.style.opacity = '0';
                    setTimeout(() => msg.remove(), 1000);
                }
                if (err) {
                    err.style.opacity = '0';
                    setTimeout(() => err.remove(), 1000);
                }
            }, 3000);
        </script>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Create Supervisor Form -->
            <div class="md:col-span-1 h-fit">
                <div
                    class="bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-xl border border-teal-200 sticky top-8">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2 text-slate-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Add Supervisor
                    </h2>
                    <form action="supervisor_actions.php" method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="create">

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Username <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="username" required
                                class="w-full px-4 py-2 bg-teal-50/50 border border-teal-200 rounded-lg text-slate-900 focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Password <span
                                    class="text-red-500">*</span></label>
                            <input type="password" name="password" required
                                class="w-full px-4 py-2 bg-teal-50/50 border border-teal-200 rounded-lg text-slate-900 focus:ring-2 focus:ring-teal-500">
                        </div>
                        <button type="submit"
                            class="w-full py-2 bg-teal-600 hover:bg-teal-700 rounded-lg font-bold transition-all shadow-lg text-sm text-white">
                            Create Supervisor
                        </button>
                    </form>
                </div>
            </div>

            <!-- List Supervisors -->
            <div class="md:col-span-2">
                <div
                    class="bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-xl border border-teal-200 overflow-hidden">
                    <h2 class="text-xl font-bold mb-4 text-slate-900">Existing Supervisors</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-teal-200 text-slate-600 text-sm uppercase">
                                    <th class="p-3">ID</th>
                                    <th class="p-3">Username</th>
                                    <th class="p-3">Role</th>
                                    <th class="p-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-teal-100">
                                <?php if (count($managers) > 0): ?>
                                    <?php foreach ($managers as $user): ?>
                                        <tr class="hover:bg-teal-50 transition-colors">
                                            <td class="p-3 text-slate-500">#<?= $user['id'] ?></td>
                                            <td class="p-3 font-medium text-slate-900">
                                                <?= htmlspecialchars($user['username']) ?>
                                            </td>
                                            <td class="p-3">
                                                <span
                                                    class="px-2 py-1 text-xs font-bold bg-teal-50 text-teal-700 rounded-full border border-teal-100">Manager</span>
                                            </td>
                                            <td class="p-3 flex items-center gap-3">
                                                <!-- Change Password -->
                                                <button
                                                    onclick="openPasswordModal(<?= $user['id'] ?>, '<?= htmlspecialchars($user['username']) ?>')"
                                                    class="text-sm text-teal-600 hover:text-teal-700 underline">Change
                                                    Password</button>

                                                <!-- Delete -->
                                                <form id="delete-form-<?= $user['id'] ?>" action="supervisor_actions.php"
                                                    method="POST"
                                                    onsubmit="event.preventDefault(); showDeleteModal('delete-form-<?= $user['id'] ?>');"
                                                    class="inline">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                                    <button type="submit"
                                                        class="text-sm text-red-500 hover:text-red-600 underline">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="p-6 text-center text-gray-400 italic">No supervisors created yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div id="passwordModal"
        class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md border border-teal-100 relative">
            <h3 class="text-xl font-bold mb-4 text-slate-900">Change Password for <span id="modalUsername"
                    class="text-teal-600"></span></h3>
            <form action="supervisor_actions.php" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="update_password">
                <input type="hidden" name="id" id="modalUserId">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">New Password <span
                            class="text-red-500">*</span></label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2 bg-teal-50 border border-teal-200 rounded-lg text-slate-900 focus:ring-2 focus:ring-teal-500">
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closePasswordModal()"
                        class="px-4 py-2 text-slate-600 hover:text-slate-900">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-teal-600 hover:bg-teal-700 rounded-lg font-bold text-white">Update
                        Password</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal"
        class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-[100] backdrop-blur-sm p-4">
        <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-sm border border-teal-100 text-center transform transition-all scale-95 opacity-0 duration-200"
            id="modalContainer">
            <div
                class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-100">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>
            <h3 class="text-xl font-bold mb-2 text-slate-900">Delete Supervisor?</h3>
            <p class="text-slate-500 mb-8">This action cannot be undone. Are you sure you want to remove this
                supervisor?</p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2.5 bg-teal-100 hover:bg-teal-200 text-slate-700 rounded-xl font-semibold transition-colors">
                    Cancel
                </button>
                <button id="confirmDeleteBtn" onclick="executeDelete()"
                    class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 rounded-xl font-semibold transition-all shadow-lg shadow-red-200 text-white">
                    Delete
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentFormId = null;

        function openPasswordModal(id, username) {
            document.getElementById('modalUserId').value = id;
            document.getElementById('modalUsername').textContent = username;
            document.getElementById('passwordModal').classList.remove('hidden');
            document.getElementById('passwordModal').classList.add('flex');
        }

        function closePasswordModal() {
            document.getElementById('passwordModal').classList.add('hidden');
            document.getElementById('passwordModal').classList.remove('flex');
        }

        // Delete Modal Functions
        function showDeleteModal(formId) {
            currentFormId = formId;
            const modal = document.getElementById('deleteModal');
            const container = document.getElementById('modalContainer');

            modal.style.display = 'flex';
            modal.classList.remove('hidden');

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
                currentFormId = null;
            }, 200);
        }

        function executeDelete() {
            if (currentFormId) {
                document.getElementById(currentFormId).submit();
            }
        }

        // Close when clicking outside
        window.onclick = function (event) {
            const pModal = document.getElementById('passwordModal');
            const dModal = document.getElementById('deleteModal');

            if (event.target === pModal) closePasswordModal();
            if (event.target === dModal) closeDeleteModal();
        }
    </script>
    <?php include 'loader.php'; ?>
</body>

</html>
