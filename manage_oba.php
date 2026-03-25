<?php
require_once 'session_init.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
// If Super Admin, they might need to select a country first? 
// For now, let's assume this page is primarily for Country Admins or Super Admin editing their OWN (none) or passed via GET?
// User requirement: "Update their OBA logo... show their log as well".
// Let's make it smart: if Super Admin, maybe show a dropdown or just "Edit OBA Info" from the Manage Countries list?
// For simpler UI, let's assume this page is for the logged-in user's assigned country.
// If Super Admin navigates here, maybe show a "Select Country" screen or just let them manage via the main Country Manager.
// Actually, for Super Admin, I'll add "Edit Info" button in `manage_countries.php`.
// This page `manage_oba.php` will be for the Country Admin to manage THEIR info.
$role = $_SESSION['role'] ?? 'admin';
$myCountryId = $_SESSION['country_id'] ?? null;

if (!$myCountryId && $role !== 'super_admin') {
    die("No OSA assigned to your account.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage OSA Info - J/Victoria College</title>
    <link rel="icon" type="image/jpeg" href="assets/images/logo.jpg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="assets/css/custom.css" rel="stylesheet">
    <script defer src="assets/js/main.js"></script>
</head>

<body class="bg-sky-50 min-h-screen p-4 md:p-8">

    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div
            class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50">
            <h1 class="text-xl md:text-2xl font-bold font-arima text-slate-800">My OSA Information</h1>
            <!-- <a href="dashboard.php" class="text-sm font-bold text-gray-400 hover:text-theme-red w-full md:w-auto text-center md:text-left">Dashboard &rarr;</a>
              -->
            <a href="dashboard.php"
                class="w-full md:w-auto text-center px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:text-theme-red transition shadow-sm font-bold text-sm flex items-center gap-2 justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="20" y1="4" x2="20" y2="20"></line>
                    <polyline points="10 17 5 12 10 7"></polyline>
                    <line x1="5" y1="12" x2="16" y2="12"></line>
                </svg>
                Dashboard
            </a>
        </div>

        <form id="obaForm" onsubmit="saveInfo(event)" class="p-6 md:p-8 space-y-8">
            <input type="hidden" name="action" value="save_oba_info">

            <!-- Logo Section -->
            <div class="p-4 bg-sky-50 rounded-xl border border-sky-100 flex flex-col md:flex-row items-center gap-6">
                <div
                    class="w-24 h-24 bg-white rounded-xl shadow-inner flex items-center justify-center overflow-hidden border border-sky-200">
                    <img id="current-logo" src="assets/images/logo.jpg" class="max-w-full max-h-full object-contain">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-bold text-slate-700 mb-1">Update OSA Logo</label>
                    <input type="file" name="logo" accept="image/*"
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-sky-100 file:text-sky-700 hover:file:bg-sky-200 cursor-pointer">
                    <p class="text-[10px] text-sky-600 mt-2 italic font-medium uppercase tracking-wider">Recommended:
                        Square image, PNG or JPG</p>
                </div>
            </div>

            <!-- Committee Group Photo Section -->
            <div
                class="p-4 bg-emerald-50 rounded-xl border border-emerald-100 flex flex-col md:flex-row items-center gap-6">
                <div
                    class="w-32 h-24 bg-white rounded-xl shadow-inner flex items-center justify-center overflow-hidden border border-emerald-200">
                    <img id="current-committee-photo" src="assets/images/logo.jpg"
                        class="max-w-full max-h-full object-cover">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-bold text-slate-700 mb-1">Committee Group Photo</label>
                    <input type="file" name="committee_photo" accept="image/*"
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200 cursor-pointer">
                    <p class="text-[10px] text-emerald-600 mt-2 italic font-medium uppercase tracking-wider">
                        Recommended:
                        Landscape image showing committee members</p>
                </div>
            </div>

            <!-- Leadership Roles -->
            <div class="space-y-6">
                <h3 class="font-bold text-lg text-theme-red border-b pb-2">Primary Leadership</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- President -->
                    <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">President</label>
                        <input type="text" name="president_name" id="p-name" placeholder="Name"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-sky-500 outline-none">
                        <input type="text" name="president_phone" id="p-phone" placeholder="Phone"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-sky-500 outline-none">
                    </div>
                    <!-- VP -->
                    <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Vice
                            President</label>
                        <input type="text" name="vp_name" id="vp-name" placeholder="Name"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-sky-500 outline-none">
                        <input type="text" name="vp_phone" id="vp-phone" placeholder="Phone"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-sky-500 outline-none">
                    </div>
                    <!-- Secretary -->
                    <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Secretary</label>
                        <input type="text" name="secretary_name" id="s-name" placeholder="Name"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-sky-500 outline-none">
                        <input type="text" name="secretary_phone" id="s-phone" placeholder="Phone"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-sky-500 outline-none">
                    </div>
                    <!-- Vice Secretary -->
                    <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Vice
                            Secretary</label>
                        <input type="text" name="vs_name" id="vs-name" placeholder="Name"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-sky-500 outline-none">
                        <input type="text" name="vs_phone" id="vs-phone" placeholder="Phone"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-sky-500 outline-none">
                    </div>
                    <!-- Treasurer -->
                    <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Treasurer</label>
                        <input type="text" name="treasurer_name" id="t-name" placeholder="Name"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-sky-500 outline-none">
                        <input type="text" name="treasurer_phone" id="t-phone" placeholder="Phone"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-sky-500 outline-none">
                    </div>
                    <!-- Vice Treasurer -->
                    <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Vice
                            Treasurer</label>
                        <input type="text" name="vt_name" id="vt-name" placeholder="Name"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-sky-500 outline-none">
                        <input type="text" name="vt_phone" id="vt-phone" placeholder="Phone"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-sky-500 outline-none">
                    </div>
                </div>
            </div>

            <!-- Dynamic Positions (Added later) -->
            <div class="space-y-4">
                <div class="flex justify-between items-center border-b pb-2">
                    <h3 class="font-bold text-lg text-theme-red">Additional Positions</h3>
                    <button type="button" onclick="addPosition()"
                        class="text-xs bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded text-gray-600 font-bold transition">
                        + Add Position Group
                    </button>
                </div>
                <input type="hidden" name="other_members" id="other-members-json">
                <div id="dynamic-positions-container" class="space-y-6">
                    <!-- JS Injected Positions -->
                </div>
            </div>

            <!-- Social Media -->
            <div class="space-y-4">
                <h3 class="font-bold text-lg text-theme-red border-b pb-2">Social Media Links</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Website URL</label>
                        <input type="url" name="social_web" id="soc-web"
                            class="w-full px-4 py-2 border rounded-lg outline-none focus:border-sky-500"
                            placeholder="https://...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Facebook URL</label>
                        <input type="url" name="social_facebook" id="soc-fb"
                            class="w-full px-4 py-2 border rounded-lg outline-none focus:border-sky-500"
                            placeholder="https://facebook.com/...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Instagram URL</label>
                        <input type="url" name="social_instagram" id="soc-insta"
                            class="w-full px-4 py-2 border rounded-lg outline-none focus:border-sky-500"
                            placeholder="https://instagram.com/...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Twitter / X URL</label>
                        <input type="url" name="social_twitter" id="soc-x"
                            class="w-full px-4 py-2 border rounded-lg outline-none focus:border-sky-500"
                            placeholder="https://twitter.com/...">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">YouTube / Live Stream
                            URL</label>
                        <input type="url" name="social_youtube" id="soc-yt"
                            class="w-full px-4 py-2 border rounded-lg outline-none focus:border-sky-500"
                            placeholder="https://youtube.com/...">
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit"
                    class="w-full md:w-auto px-8 py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-lg shadow-lg transition transform hover:-translate-y-0.5">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <script>
        // Store dynamic data in memory
        let dynamicPositions = [];

        document.addEventListener('DOMContentLoaded', loadInfo);

        function renderDynamicPositions() {
            const container = document.getElementById('dynamic-positions-container');

            if (dynamicPositions.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-400 py-8 italic bg-gray-50 rounded-lg mb-4">No additional committee groups added.</div>';
                return;
            }

            container.innerHTML = dynamicPositions.map((group, pIndex) => `
                <div class="bg-gray-50 rounded-xl p-4 md:p-5 border border-gray-200 relative group animate-fade-in mb-6">
                    <button type="button" onclick="removePosition(${pIndex})" 
                        class="absolute top-3 right-3 text-gray-400 hover:text-red-500 bg-white p-1 rounded-full shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>

                    <!-- Position Title -->
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Position Group Title</label>
                        <input type="text" value="${group.position}" onchange="updatePosTitle(${pIndex}, this.value)"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 bg-white"
                            placeholder="e.g. Executive Committee Members">
                    </div>

                    <!-- Members List -->
                    <div class="space-y-2 pl-0 md:pl-4 border-l-0 md:border-l-2 border-gray-200">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Members Names</label>
                        ${group.members.map((member, mIndex) => `
                            <div class="flex gap-2 mb-2">
                                <input type="text" value="${member}" onchange="updateMember(${pIndex}, ${mIndex}, this.value)"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-sky-500 text-sm bg-white"
                                    placeholder="Member Name">
                                <button type="button" onclick="removeMember(${pIndex}, ${mIndex})" 
                                    class="px-2 text-red-400 hover:text-red-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        `).join('')}
                        
                        <button type="button" onclick="addMember(${pIndex})"
                            class="text-sm font-bold text-sky-600 hover:text-sky-700 flex items-center gap-1 mt-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add Member
                        </button>
                    </div>
                </div>
            `).join('');
        }

        function addPosition() {
            dynamicPositions.push({ position: '', members: [''] });
            renderDynamicPositions();
        }

        function removePosition(index) {
            if (confirm('Remove this entire position group?')) {
                dynamicPositions.splice(index, 1);
                renderDynamicPositions();
            }
        }

        function updatePosTitle(index, val) {
            dynamicPositions[index].position = val;
        }

        function addMember(pIndex) {
            dynamicPositions[pIndex].members.push('');
            renderDynamicPositions(); // Re-render is easiest, though blurring focus. Ideally DOM append.
            // Optimization: Just append HTML
        }

        function removeMember(pIndex, mIndex) {
            dynamicPositions[pIndex].members.splice(mIndex, 1);
            renderDynamicPositions();
        }

        function updateMember(pIndex, mIndex, val) {
            dynamicPositions[pIndex].members[mIndex] = val;
        }

        async function loadInfo() {
            try {
                const data = await apiRequest('api/admin.php?action=get_oba_info');
                if (data) {

                    if (data.logo) document.getElementById('current-logo').src = data.logo;

                    if (data.committee_photo) document.getElementById('current-committee-photo').src = data.committee_photo;

                    const setVal = (id, val) => { if (document.getElementById(id)) document.getElementById(id).value = val || ''; }

                    setVal('p-name', data.president_name);
                    setVal('p-phone', data.president_phone);

                    setVal('vp-name', data.vp_name);
                    setVal('vp-phone', data.vp_phone);

                    setVal('s-name', data.secretary_name);
                    setVal('s-phone', data.secretary_phone);

                    setVal('vs-name', data.vs_name);
                    setVal('vs-phone', data.vs_phone);

                    setVal('t-name', data.treasurer_name);
                    setVal('t-phone', data.treasurer_phone);

                    setVal('vt-name', data.vt_name);
                    setVal('vt-phone', data.vt_phone);

                    if (data.other_members) {
                        try {
                            const parsed = JSON.parse(data.other_members);
                            // Fallback if old format (array of strings) -> Convert to new format
                            // New format: [{position: "X", members: ["A", "B"]}]
                            if (Array.isArray(parsed)) {
                                if (parsed.length > 0 && typeof parsed[0] === 'string') {
                                    // Legacy migration: Put all in "Committee Members"
                                    dynamicPositions = [{ position: "Committee Members", members: parsed }];
                                } else {
                                    dynamicPositions = parsed;
                                }
                            }
                        } catch (e) { }
                    }
                    renderDynamicPositions();

                    if (data.social_links) {
                        let links = data.social_links;
                        if (typeof links === 'string') links = JSON.parse(links);

                        setVal('soc-web', links.web);
                        setVal('soc-fb', links.facebook);
                        setVal('soc-insta', links.instagram);
                        setVal('soc-x', links.twitter);
                        setVal('soc-yt', links.youtube);
                    }
                }
            } catch (err) {
                console.error(err);
            }
        }

        async function saveInfo(e) {
            e.preventDefault();

            // Serialize dynamicPositions to the hidden input
            // Filter out empty pos titles or empty members
            const cleanData = dynamicPositions
                .filter(p => p.position.trim() !== '')
                .map(p => ({
                    position: p.position.trim(),
                    members: p.members.filter(m => m.trim() !== '')
                }))
                .filter(p => p.members.length > 0);

            document.getElementById('other-members-json').value = JSON.stringify(cleanData);

            const form = e.target;
            const btn = form.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerText = 'Saving...';

            try {
                await apiRequest('api/admin.php', {
                    method: 'POST',
                    body: new FormData(form)
                });
                showAlert('Information updated successfully!');
                loadInfo();
            } catch (err) {
                showAlert('Error: ' + err.message, 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Save Changes';
            }
        }
    </script>
</body>

</html>