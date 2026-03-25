<?php
require_once 'session_init.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
// Super Admin or Country Admin
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - J/Victoria College</title>
    <link rel="icon" type="image/jpeg" href="assets/images/logo.jpg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="assets/css/custom.css" rel="stylesheet">
    <script defer src="assets/js/main.js"></script>
</head>

<body class="bg-purple-50 min-h-screen p-4 md:p-8">

    <div class="max-w-6xl mx-auto">
        <div
            class="flex flex-col md:flex-row justify-between items-center bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-xl border border-purple-200 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Events Management</h1>
                <p class="text-slate-500 mt-1">Manage upcoming events</p>
            </div>
            <div class="flex gap-4 mt-4 md:mt-0">
                <a href="dashboard.php"
                    class="px-6 py-2 bg-purple-100 hover:bg-purple-200 text-slate-700 flex gap-2 items-center rounded-lg font-semibold transition-all">
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


        <div class="bg-white rounded-2xl shadow-sm border border-purple-200 p-6">
            <div class="flex justify-end mb-6">
                <button onclick="openModal()"
                    class="px-4 py-2 bg-theme-red text-white rounded-lg font-bold hover:bg-red-800 transition">+ Add New
                    Event</button>
            </div>

            <!-- Event List (Responsive) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500">
                                <th class="px-6 py-4 font-bold">Event</th>
                                <th class="px-6 py-4 font-bold hidden md:table-cell">Date</th>
                                <th class="px-6 py-4 font-bold hidden md:table-cell">Scope</th>
                                <th class="px-6 py-4 font-bold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="events-table-body" class="divide-y divide-gray-100">
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-400 italic">Loading events...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Modal -->
    <div id="eventModal"
        class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
            <h3 id="modal-title" class="text-xl font-bold mb-4">Add Event</h3>
            <form id="eventForm" onsubmit="saveEvent(event)" class="space-y-4">
                <input type="hidden" name="id" id="e-id">
                <input type="hidden" name="action" value="save_event">

                <!-- Form Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Event Title <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="title" id="e-title"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-theme-red focus:border-transparent outline-none transition"
                            placeholder="e.g. Annual Cricket Match" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Event Date <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="event_date" id="e-date"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-theme-red focus:border-transparent outline-none transition"
                            required>
                    </div>

                    <?php if ($_SESSION['role'] === 'super_admin' || $_SESSION['role'] === 'manager'): ?>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Assign to Country (Optional)</label>
                            <select name="country_id" id="e-country"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-theme-red focus:border-transparent outline-none transition">
                                <option value="">Global Event (All Countries)</option>
                                <?php
                                require_once 'db.php'; // Quick fetch
                                $cStmt = $pdo->query("SELECT id, name FROM countries ORDER BY name ASC");
                                while ($c = $cStmt->fetch()):
                                    ?>
                                    <option value="<?= $c['id'] ?>">
                                        <?= htmlspecialchars($c['name']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">If selected, this event will only appear on that
                                country's page.</p>
                        </div>
                    <?php endif; ?>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="e-desc" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-theme-red focus:border-transparent outline-none transition"
                            placeholder="Event details..."></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Event Image <span
                                class="text-red-500">*</span></label>
                        <input type="file" name="image" id="e-image" accept="image/*"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-theme-red focus:border-transparent outline-none transition file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-theme-red file:text-white hover:file:bg-red-800"
                            required>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                    <button type="button" onclick="closeModal()"
                        class="px-6 py-2 bg-gray-100 text-gray-700 font-bold rounded-lg hover:bg-gray-200 transition">Cancel</button>
                    <button type="submit"
                        class="px-6 py-2 bg-theme-red text-white font-bold rounded-lg hover:bg-red-800 transition shadow-lg shadow-red-200">Save
                        Event</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', loadEvents);

        async function loadEvents() {
            const container = document.getElementById('events-table-body'); // Changed to table body
            try {
                const events = await apiRequest('api/admin.php?action=list_events');
                if (events.length === 0) {
                    container.innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">No events found.</td></tr>';
                    return;
                }

                container.innerHTML = events.map(ev => `
                     <tr class="hover:bg-gray-50 transition border-b border-gray-50 last:border-0">
                         <td class="px-6 py-4">
                             <div class="flex items-center gap-3">
                                 <img src="${ev.image || 'assets/images/logo.jpg'}" class="h-10 w-10 rounded-lg object-cover border border-gray-200">
                                 <div>
                                     <div class="font-bold text-slate-800 text-sm">${ev.title}</div>
                                     <div class="flex flex-col gap-1 md:hidden mt-1">
                                         <span class="text-xs text-gray-400">${dateFormat(ev.event_date)}</span>
                                         ${ev.country_name
                        ? `<span class="inline-flex w-fit items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-black text-white">${ev.country_name}</span>`
                        : `<span class="inline-flex w-fit items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-theme-red text-white">Global</span>`}
                                     </div>
                                 </div>
                             </div>
                         </td>
                         <td class="px-6 py-4 text-sm text-gray-600 hidden md:table-cell whitespace-nowrap">
                             ${dateFormat(ev.event_date)}
                         </td>
                         <td class="px-6 py-4 hidden md:table-cell">
                             ${ev.country_name
                        ? `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-black text-white">${ev.country_name}</span>`
                        : `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-theme-red text-white">Global</span>`}
                         </td>
                         <td class="px-6 py-4 text-right">
                             <div class="flex items-center justify-end gap-2">
                                 <button onclick='editEvent(${JSON.stringify(ev)})' class="p-2 text-sky-600 hover:bg-sky-50 rounded-lg transition" title="Edit">
                                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                 </button>
                                 <button onclick='deleteEvent(this, ${ev.id})' class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                 </button>
                             </div>
                         </td>
                     </tr>
                 `).join('');

            } catch (err) {
                console.error(err);
                container.innerHTML = '<div class="col-span-full text-center text-red-400">Failed to load events</div>';
            }
        }

        const dateFormat = (dt) => {
            const [y, m, d] = dt.split('-');
            const date = new Date(y, m - 1, d);

            return date.toLocaleDateString('en-US');
        }

        function openModal() {
            document.getElementById('eventForm').reset();
            document.getElementById('e-id').value = '';
            document.getElementById('e-image').setAttribute('required', 'required');
            document.getElementById('modal-title').innerText = 'Add Event';
            document.getElementById('eventModal').classList.remove('hidden');
        }

        function editEvent(ev) {
            document.getElementById('eventForm').reset();
            document.getElementById('e-id').value = ev.id;
            document.getElementById('e-title').value = ev.title;
            document.getElementById('e-date').value = ev.event_date;
            document.getElementById('e-desc').value = ev.description;
            if (document.getElementById('e-country')) {
                document.getElementById('e-country').value = ev.country_id || '';
            }
            document.getElementById('e-image').removeAttribute('required'); // Optional on edit
            document.getElementById('modal-title').innerText = 'Edit Event';
            document.getElementById('eventModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('eventModal').classList.add('hidden');
        }

        async function saveEvent(e) {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            const originalText = btn.innerText;
            btn.disabled = true;
            btn.innerText = 'Saving...';

            try {
                await apiRequest('api/admin.php', {
                    method: 'POST',
                    body: new FormData(e.target)
                });
                closeModal();
                loadEvents();
                showAlert('Event saved successfully');
            } catch (err) {
                showAlert(err.message, 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = originalText;
            }
        }

        async function deleteEvent(btn, id) {
            if (!await showConfirm('Are you sure you want to delete this event?')) return;

            const originalText = btn.innerText;
            btn.innerText = '...';
            btn.disabled = true;

            try {
                const fd = new FormData();
                fd.append('action', 'delete_event');
                fd.append('id', id);

                await apiRequest('api/admin.php', {
                    method: 'POST',
                    body: fd
                });
                loadEvents();
                showAlert('Event deleted successfully');
            } catch (e) {
                showAlert(e.message, 'error');
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }
    </script>
</body>

</html>