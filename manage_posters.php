<?php
require_once 'session_init.php';
require_once 'db.php';

// Super Admin or Manager Only
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['super_admin', 'manager'])) {
    header("Location: dashboard.php");
    exit();
}

// Fetch posters
$stmt = $pdo->query("SELECT * FROM posters ORDER BY display_order ASC, created_at DESC");
$posters = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posters - J/Victoria College</title>
    <link rel="icon" type="image/jpeg" href="assets/images/logo.jpg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="assets/css/custom.css" rel="stylesheet">
    <script defer src="assets/js/main.js"></script>
</head>

<body class="bg-purple-50 min-h-screen p-4 md:p-8">
    <div class="max-w-6xl mx-auto space-y-6">
        <!-- Header -->
        <div
            class="flex flex-col md:flex-row justify-between items-center bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-xl border border-purple-200">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Manage Posters</h1>
                <p class="text-slate-500 mt-1">Homepage clickable posters</p>
            </div>
            <a href="dashboard.php"
                class="px-6 py-2 bg-purple-100 hover:bg-purple-200 text-slate-700 flex gap-2 items-center rounded-lg font-semibold transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Back to Dashboard
            </a>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-lg text-emerald-700">
                <?= htmlspecialchars($_GET['msg']) ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Create Form -->
            <div class="md:col-span-1">
                <div
                    class="bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-xl border border-purple-200 sticky top-8">
                    <h2 class="text-xl font-bold mb-4 text-slate-900">Add Poster</h2>
                    <form id="posterForm" enctype="multipart/form-data" class="space-y-4">
                        <input type="hidden" name="action" value="create_poster">

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Image <span
                                    class="text-red-500">*</span></label>
                            <input type="file" name="image" required accept="image/*"
                                class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200 cursor-pointer">
                            <p class="text-xs text-gray-500 mt-1">Recommended: Portrait or square format</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Display Order</label>
                            <input type="number" name="display_order" value="0"
                                class="w-full px-4 py-2 bg-purple-50/50 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>

                        <button type="submit"
                            class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 rounded-lg font-bold transition-all shadow-lg text-white">
                            Add Poster
                        </button>
                    </form>
                </div>
            </div>

            <!-- Posters List -->
            <div class="md:col-span-2">
                <div class="bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-xl border border-purple-200">
                    <h2 class="text-xl font-bold mb-4 text-slate-900">Existing Posters</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <?php if (count($posters) > 0): ?>
                            <?php foreach ($posters as $poster): ?>
                                <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition">
                                    <img src="<?= htmlspecialchars($poster['image']) ?>"
                                        class="w-full h-48 object-cover rounded-lg mb-3">
                                    <div class="flex justify-between items-center">
                                        <div class="text-xs text-gray-500">
                                            Order:
                                            <?= $poster['display_order'] ?>
                                        </div>
                                        <button onclick="deletePoster(<?= $poster['id'] ?>)"
                                            class="text-sm text-red-500 hover:text-red-600 underline">Delete</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-center text-gray-400 py-8 col-span-3">No posters yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('posterForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            try {
                const response = await fetch('api/admin.php', {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    window.location.href = 'manage_posters.php?msg=Poster added successfully';
                } else {
                    alert('Failed to add poster');
                }
            } catch (error) {
                console.error(error);
                alert('An error occurred');
            }
        });

        async function deletePoster(id) {
            if (!confirm('Delete this poster?')) return;

            try {
                const formData = new FormData();
                formData.append('action', 'delete_poster');
                formData.append('id', id);

                const response = await fetch('api/admin.php', {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Failed to delete poster');
                }
            } catch (error) {
                console.error(error);
                alert('An error occurred');
            }
        }
    </script>
    <?php include 'loader.php'; ?>
</body>

</html>