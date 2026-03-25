<?php
require_once 'session_init.php';
require_once 'db.php';

// Access Control
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$role = $_SESSION['role'];
$userCountryId = $_SESSION['country_id'];

// Fetch countries for dropdown (super admin only)
$countries = [];
if ($role === 'super_admin' || $role === 'manager') {
    $stmt = $pdo->query("SELECT id, name FROM countries ORDER BY name ASC");
    $countries = $stmt->fetchAll();
}

// Pagination Setup
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Fetch news based on role
$totalRecords = 0;
if ($role === 'super_admin' || $role === 'manager') {
    // Count Total
    $stmt = $pdo->query("SELECT COUNT(*) FROM news");
    $totalRecords = $stmt->fetchColumn();

    // Fetch Page Data
    $stmt = $pdo->prepare("SELECT n.*, c.name as country_name FROM news n LEFT JOIN countries c ON n.country_id = c.id ORDER BY n.created_at DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
} else {
    // Count Total
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM news WHERE country_id = ?");
    $stmt->execute([$userCountryId]);
    $totalRecords = $stmt->fetchColumn();

    // Fetch Page Data
    $stmt = $pdo->prepare("SELECT n.*, c.name as country_name FROM news n LEFT JOIN countries c ON n.country_id = c.id WHERE n.country_id = :country_id ORDER BY n.created_at DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':country_id', $userCountryId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
}

$totalPages = ceil($totalRecords / $limit);
$newsList = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage News - J/Victoria College</title>
    <link rel="icon" type="image/jpeg" href="assets/images/logo.jpg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="assets/css/custom.css" rel="stylesheet">
    <script defer src="assets/js/main.js"></script>
</head>

<body class="bg-sky-50 min-h-screen p-4 md:p-8">
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header -->
        <div
            class="flex flex-col md:flex-row justify-between items-center bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-xl border border-sky-200">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Manage News</h1>
                <p class="text-slate-500 mt-1">Create and manage news articles</p>
            </div>
            <a href="dashboard.php"
                class="px-6 py-2 bg-sky-100 hover:bg-sky-200 text-slate-700 flex gap-2 items-center rounded-lg font-semibold transition-all">
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Create/Edit Form -->
            <div class="lg:col-span-1">
                <div class="bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-xl border border-sky-200 sticky top-8">
                    <h2 class="text-xl font-bold mb-4 text-slate-900">Add News Article</h2>
                    <form id="newsForm" enctype="multipart/form-data" class="space-y-4">
                        <input type="hidden" name="action" value="create_news">
                        <input type="hidden" name="id" id="news-id">

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Title <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="title" id="news-title" required
                                class="w-full px-4 py-2 bg-sky-50/50 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Content <span
                                    class="text-red-500">*</span></label>
                            <textarea name="content" id="news-content" required rows="4"
                                class="w-full px-4 py-2 bg-sky-50/50 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-500"></textarea>
                        </div>

                        <?php if ($role === 'super_admin' || $role === 'manager'): ?>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Select <span
                                        class="text-red-500">*</span></label>
                                <select name="target" id="news-target" required
                                    class="w-full px-4 py-2 bg-sky-50/50 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-500">
                                    <option value="">Select Country</option>
                                    <option value="global" class="font-bold text-red-600">Global News (All Countries)
                                    </option>
                                    <optgroup label="Specific Country">
                                        <?php foreach ($countries as $c): ?>
                                            <option value="<?= $c['id'] ?>">
                                                <?= htmlspecialchars($c['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                </select>
                            </div>
                        <?php endif; ?>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Images (Multiple)</label>
                            <input type="file" name="images[]" id="news-images" accept="image/*" multiple
                                class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-sky-100 file:text-sky-700 hover:file:bg-sky-200 cursor-pointer">
                            <p class="text-xs text-gray-500 mt-1">Select new images to append. Existing images are shown
                                below.</p>

                            <!-- Container for Existing Images -->
                            <div id="current-images-container" class="flex flex-wrap gap-3 mt-3"></div>
                            <!-- Hidden inputs for kept images -->
                            <div id="kept-images-inputs"></div>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" id="submit-btn"
                                class="flex-1 py-2 bg-emerald-600 hover:bg-emerald-700 rounded-lg font-bold transition-all shadow-lg text-white">
                                Create News
                            </button>
                            <button type="button" id="cancel-btn" onclick="resetForm()" style="display:none;"
                                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg font-bold transition-all text-gray-700">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- News List -->
            <div class="lg:col-span-2">
                <div class="bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-xl border border-sky-200">
                    <h2 class="text-xl font-bold mb-4 text-slate-900">Updated News</h2>
                    <div class="space-y-4">
                        <?php if (count($newsList) > 0): ?>
                            <?php foreach ($newsList as $news): ?>
                                <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition">
                                    <div class="flex justify-between items-start gap-4">
                                        <div class="flex-1">
                                            <h3 class="font-bold text-lg text-slate-800">
                                                <?= htmlspecialchars($news['title']) ?>
                                            </h3>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <?= htmlspecialchars(substr($news['content'], 0, 150)) ?>...
                                            </p>
                                            <div class="flex gap-2 mt-2">
                                                <?php if ($news['is_global']): ?>
                                                    <span
                                                        class="px-2 py-1 text-xs font-bold bg-red-50 text-red-700 rounded-full">Global</span>
                                                <?php else: ?>
                                                    <span class="px-2 py-1 text-xs font-bold bg-sky-50 text-sky-700 rounded-full">
                                                        <?= htmlspecialchars($news['country_name'] ?? 'Country') ?>
                                                    </span>
                                                <?php endif; ?>
                                                <span class="text-xs text-gray-400">
                                                    <?= date('M d, Y', strtotime($news['created_at'])) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <?php
                                        $images = json_decode($news['images'] ?? '[]', true);
                                        $firstImage = !empty($images) ? $images[0] : null;
                                        ?>
                                        <?php if ($firstImage): ?>
                                            <img src="<?= htmlspecialchars($firstImage) ?>"
                                                class="w-20 h-20 object-cover rounded-lg">
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex gap-2 mt-3 items-center">
                                        <button
                                            onclick='editNews(<?= htmlspecialchars(json_encode($news), ENT_QUOTES, 'UTF-8') ?>)'
                                            class="text-sm text-sky-600 hover:text-sky-700 font-semibold">Edit</button>
                                        <span class="text-gray-300">|</span>
                                        <button onclick="deleteNews(<?= $news['id'] ?>)"
                                            class="text-sm text-red-500 hover:text-red-600 underline">Delete</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <!-- Pagination -->
                            <?php if ($totalPages > 1): ?>
                                <div class="mt-6 flex justify-end items-center gap-2">
                                    <?php if ($page > 1): ?>
                                        <a href="?page=1"
                                            class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-50 text-slate-600 text-sm"
                                            title="First">&laquo;</a>
                                        <a href="?page=<?= $page - 1 ?>"
                                            class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-50 text-slate-600 text-sm">Prev</a>
                                    <?php else: ?>
                                        <span
                                            class="px-3 py-1 bg-gray-50 border border-gray-200 rounded text-gray-300 text-sm cursor-not-allowed">&laquo;</span>
                                        <span
                                            class="px-3 py-1 bg-gray-50 border border-gray-200 rounded text-gray-300 text-sm cursor-not-allowed">Prev</span>
                                    <?php endif; ?>

                                    <span class="text-xs md:text-sm text-gray-600 px-2">
                                        Page <span class="font-bold text-slate-800"><?= $page ?></span> of <?= $totalPages ?>
                                    </span>

                                    <?php if ($page < $totalPages): ?>
                                        <a href="?page=<?= $page + 1 ?>"
                                            class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-50 text-slate-600 text-sm">Next</a>
                                        <a href="?page=<?= $totalPages ?>"
                                            class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-50 text-slate-600 text-sm"
                                            title="Last">&raquo;</a>
                                    <?php else: ?>
                                        <span
                                            class="px-3 py-1 bg-gray-50 border border-gray-200 rounded text-gray-300 text-sm cursor-not-allowed">Next</span>
                                        <span
                                            class="px-3 py-1 bg-gray-50 border border-gray-200 rounded text-gray-300 text-sm cursor-not-allowed">&raquo;</span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-center text-gray-400 py-8">No news articles yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editNews(news) {
            document.getElementById('news-id').value = news.id;
            document.getElementById('news-title').value = news.title;
            document.getElementById('news-content').value = news.content;

            // Set Target Dropdown
            const targetSelect = document.getElementById('news-target');
            if (targetSelect) {
                if (news.is_global == 1) {
                    targetSelect.value = 'global';
                } else {
                    targetSelect.value = news.country_id;
                }
            }

            // Handle Images
            const images = news.images ? JSON.parse(news.images) : [];
            const container = document.getElementById('current-images-container');
            const inputsContainer = document.getElementById('kept-images-inputs');

            container.innerHTML = '';
            inputsContainer.innerHTML = '';

            images.forEach((img, index) => {
                // UI Element
                const div = document.createElement('div');
                div.className = 'relative group w-20 h-20';
                div.innerHTML = `
                    <img src="${img}" class="w-full h-full object-cover rounded-lg border border-gray-200">
                    <button type="button" onclick="removeImage('${img}', this)" 
                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs shadow hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity">
                        ✕
                    </button>
                `;
                container.appendChild(div);

                // Hidden Input
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'kept_images[]';
                input.value = img;
                inputsContainer.appendChild(input);
            });

            // Set Action to Update
            document.querySelector('input[name="action"]').value = 'update_news';

            // Update Buttons
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.innerText = 'Update News';
            submitBtn.classList.remove('bg-emerald-600', 'hover:bg-emerald-700');
            submitBtn.classList.add('bg-sky-600', 'hover:bg-sky-700');
            document.getElementById('cancel-btn').style.display = 'block';

            // Scroll to form
            document.getElementById('newsForm').scrollIntoView({ behavior: 'smooth' });
        }

        function removeImage(src, btn) {
            // Remove UI
            btn.closest('div').remove();

            // Remove Input
            const inputs = document.querySelectorAll('input[name="kept_images[]"]');
            for (let input of inputs) {
                if (input.value === src) {
                    input.remove();
                    break;
                }
            }
        }

        function resetForm() {
            document.getElementById('newsForm').reset();
            document.getElementById('news-id').value = '';
            document.querySelector('input[name="action"]').value = 'create_news';

            // Clear Images
            document.getElementById('current-images-container').innerHTML = '';
            document.getElementById('kept-images-inputs').innerHTML = '';

            const submitBtn = document.getElementById('submit-btn');
            submitBtn.innerText = 'Create News';
            submitBtn.classList.add('bg-emerald-600', 'hover:bg-emerald-700');
            submitBtn.classList.remove('bg-sky-600', 'hover:bg-sky-700');
            document.getElementById('cancel-btn').style.display = 'none';
        }

        document.getElementById('newsForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const action = formData.get('action');

            try {
                const response = await fetch('api/admin.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success || response.ok) {
                    const msg = action === 'create_news' ? 'News created successfully' : 'News updated successfully';
                    window.location.href = 'manage_news.php?msg=' + encodeURIComponent(msg);
                } else {
                    alert(data.error || 'Failed to save news');
                }
            } catch (error) {
                console.error(error);
                alert('An error occurred');
            }
        });

        async function deleteNews(id) {
            if (!confirm('Delete this news article?')) return;

            try {
                const formData = new FormData();
                formData.append('action', 'delete_news');
                formData.append('id', id);

                const response = await fetch('api/admin.php', {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Failed to delete news');
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