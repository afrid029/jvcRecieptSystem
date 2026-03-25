<?php
require_once 'session_init.php';
// Auth Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Countries & Purposes - Admin</title>
    <link rel="icon" type="image/jpeg" href="assets/images/logo.jpg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="assets/css/custom.css" rel="stylesheet">
    <script defer src="assets/js/main.js"></script>
</head>

<body class="bg-sky-50 min-h-screen p-4 md:p-6">


    <div class="max-w-6xl mx-auto">
        <div
            class="flex flex-col md:flex-row justify-between items-center bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-xl border border-sky-200 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900">OSA Management</h1>
                <p class="text-slate-500 mt-1">Manage OSAs and Donation Purposes</p>
            </div>
            <div class="flex gap-4 mt-4 md:mt-0">
                <a href="dashboard.php"
                    class="px-6 py-2 bg-sky-100 hover:bg-sky-200 text-slate-700 flex gap-2 items-center rounded-lg font-semibold transition-all">
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




        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">



            <!-- Countries Management -->
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-sky-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-slate-800">Countries / OSAs</h2>
                        <button onclick="openCountryModal()"
                            class="px-3 py-1 bg-sky-50 text-sky-600 rounded-lg hover:bg-sky-100 font-bold text-sm">+ Add
                            New</button>
                    </div>

                    <div id="countries-list" class="space-y-3 max-h-[500px] overflow-y-auto pr-2">
                        <!-- JS Loaded -->
                        <div class="animate-pulse flex space-x-4">
                            <div class="flex-1 space-y-4 py-1">
                                <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                                <div class="space-y-2">
                                    <div class="h-4 bg-gray-200 rounded"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Purposes Management -->
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-sky-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-slate-800">Donation Purposes</h2>
                        <button onclick="openPurposeModal()"
                            class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-100 font-bold text-sm">+
                            Add New</button>
                    </div>

                    <div class="overflow-hidden rounded-lg border border-gray-100">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 text-gray-500 uppercase font-bold text-xs">
                                <tr>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3 text-center">Home?</th>
                                    <th class="px-4 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody id="purposes-list" class="divide-y divide-gray-100">
                                <!-- JS Loaded -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Country Modal -->
    <div id="countryModal"
        class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <h3 id="c-modal-title" class="text-xl font-bold mb-4">Add Country</h3>
            <form id="countryForm" onsubmit="saveCountry(event)">
                <input type="hidden" name="id" id="c-id">
                <input type="hidden" name="action" value="save_country">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-500 mb-1">Country Name</label>
                        <input type="text" name="name" id="c-name"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-sky-500 outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-500 mb-1">Flag Image</label>
                        <input type="file" name="flag_image" accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100">
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('countryModal')"
                        class="px-4 py-2 text-gray-500 hover:bg-gray-100 rounded-lg font-bold">Cancel</button>
                    <button type="submit"
                        class="px-6 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 font-bold">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Purpose Modal -->
    <div id="purposeModal"
        class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
            <h3 id="p-modal-title" class="text-xl font-bold mb-4">Add Purpose</h3>
            <form id="purposeForm" onsubmit="savePurpose(event)">
                <input type="hidden" name="id" id="p-id">
                <input type="hidden" name="action" value="save_purpose">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-500 mb-1">Purpose Name</label>
                        <input type="text" name="name" id="p-name"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none"
                            required>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="show_on_homepage" id="p-show"
                            class="w-5 h-5 rounded text-emerald-600 focus:ring-emerald-500">
                        <label for="p-show" class="font-bold text-gray-700">Show on Homepage List</label>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('purposeModal')"
                        class="px-4 py-2 text-gray-500 hover:bg-gray-100 rounded-lg font-bold">Cancel</button>
                    <button type="submit"
                        class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-bold">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            loadCountries();
            loadPurposes();
        });

        // --- Countries ---
        async function loadCountries() {
            const list = document.getElementById('countries-list');
            try {
                const data = await apiRequest('api/public.php?action=countries');
                if (data.length === 0) {
                    list.innerHTML = `<div class="text-center text-gray-400 py-4">No countries added yet.</div>`;
                    return;
                }
                list.innerHTML = data.map(c => `
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-sky-50 transition group border border-gray-100">
                        <div class="flex items-center gap-3">
                            <img src="${c.flag_image}" class="w-8 h-8 rounded-full object-cover border border-gray-200">
                            <div>
                                <div class="font-bold text-slate-700">${c.name}</div>
                            </div>
                        </div>
                         <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition">
                            <button onclick='editCountry(${JSON.stringify(c)})' class="p-1.5 text-sky-600 hover:bg-sky-100 rounded-lg" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button onclick="deleteCountry(${c.id})" class="p-1.5 text-red-500 hover:bg-red-100 rounded-lg" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                `).join('');
            } catch (e) { console.error(e); }
        }

        function openCountryModal() {
            document.getElementById('countryForm').reset();
            document.getElementById('c-id').value = '';
            document.getElementById('c-modal-title').innerText = 'Add Country';
            document.getElementById('countryModal').classList.remove('hidden');
        }

        function editCountry(c) {
            document.getElementById('countryForm').reset();
            document.getElementById('c-id').value = c.id;
            document.getElementById('c-name').value = c.name;
            document.getElementById('c-modal-title').innerText = 'Edit Country';
            document.getElementById('countryModal').classList.remove('hidden');
        }

        async function saveCountry(e) {
            e.preventDefault();
            const form = e.target;
            const btn = form.querySelector('button[type="submit"]');
            btn.disabled = true;

            try {
                await apiRequest('api/admin.php', {
                    method: 'POST',
                    body: new FormData(form) // Send as FormData for file upload
                });
                closeModal('countryModal');
                loadCountries();
            } catch (err) {
                alert(err.message);
            } finally {
                btn.disabled = false;
            }
        }

        async function deleteCountry(id) {
            if (!await showConfirm('Are you sure? This will delete all associated events and info!')) return;
            // Loader
            const originalText = document.activeElement.innerText;
            document.activeElement.innerText = '...';
            document.activeElement.disabled = true;

            try {
                const fd = new FormData();
                fd.append('action', 'delete_country');
                fd.append('id', id);
                await apiRequest('api/admin.php', { method: 'POST', body: fd });
                loadCountries();
                showAlert('Country deleted successfully');
            } catch (err) {
                showAlert(err.message, 'error');
                // document.activeElement.innerText = originalText; // might be lost re-render
            }
        }

        // --- Purposes ---
        async function loadPurposes() {
            const list = document.getElementById('purposes-list');
            try {
                const data = await apiRequest('api/admin.php?action=list_purposes');
                list.innerHTML = data.map(p => {
                    const isOther = p.name.toLowerCase() === 'other';
                    return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-slate-700">${p.name}</td>
                        <td class="px-4 py-3 text-center">
                            ${p.show_on_homepage == 1 ? '<span class="text-emerald-500 font-bold">Yes</span>' : '<span class="text-gray-300">No</span>'}
                        </td>
                        <td class="px-4 py-3 text-right">
                             ${isOther ?
                            '<span class="text-xs text-gray-400 italic">System Protected</span>' :
                            `<button onclick='editPurpose(${JSON.stringify(p)})' class="text-xs font-bold text-sky-600 hover:text-sky-800 bg-sky-50 px-2 py-1 rounded">Edit</button>
                                  <button onclick="deletePurpose(this, ${p.id})" class="text-xs font-bold text-red-500 hover:text-red-700 bg-red-50 px-2 py-1 rounded ml-2">Delete</button>`
                        }
                        </td>
                    </tr>
                `}).join('');
            } catch (e) {
                console.error(e);
            }
        }

        async function deletePurpose(btn, id) {
            if (!await showConfirm('Are you sure you want to delete this purpose?')) return;

            const originalText = btn.innerText;
            btn.innerText = '...';
            btn.disabled = true;

            try {
                const fd = new FormData();
                fd.append('action', 'delete_purpose');
                fd.append('id', id);
                await apiRequest('api/admin.php', { method: 'POST', body: fd });
                loadPurposes();
                showAlert('Purpose deleted successfully');
            } catch (err) {
                showAlert(err.message, 'error');
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }

        function openPurposeModal() {
            document.getElementById('purposeForm').reset();
            document.getElementById('p-id').value = '';
            document.getElementById('p-modal-title').innerText = 'Add Purpose';
            document.getElementById('purposeModal').classList.remove('hidden');
        }

        function editPurpose(p) {
            document.getElementById('purposeForm').reset();
            document.getElementById('p-id').value = p.id;
            document.getElementById('p-name').value = p.name;
            document.getElementById('p-show').checked = (p.show_on_homepage == 1);
            document.getElementById('p-modal-title').innerText = 'Edit Purpose';
            document.getElementById('purposeModal').classList.remove('hidden');
        }

        async function savePurpose(e) {
            e.preventDefault();
            const form = e.target;
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.innerText;

            btn.disabled = true;
            btn.innerText = 'Saving...';

            try {
                await apiRequest('api/admin.php', {
                    method: 'POST',
                    body: new FormData(form)
                });
                closeModal('purposeModal');
                loadPurposes();
                showAlert('Purpose saved successfully');
            } catch (err) {
                showAlert(err.message, 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = originalText;
            }
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
    </script>
</body>

</html>