<?php
session_start();
// Basic validation: user must provide an ID, otherwise redirect/show 404
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$countryId = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSA - J/Victoria College</title>
    <link rel="icon" type="image/jpeg" href="assets/images/logo.jpg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="assets/css/custom.css" rel="stylesheet">
    <script defer src="assets/js/main.js"></script>
</head>

<body class="flex flex-col min-h-screen bg-slate-50">

    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="index.php" class="flex items-center gap-4 group">
                    <img src="assets/images/logo.jpg" alt="Logo"
                        class="h-12 w-12 rounded-full border-2 border-theme-red object-cover grayscale group-hover:grayscale-0 transition">
                    <div>
                        <span
                            class="block text-2xl font-bold text-slate-800 group-hover:text-theme-red font-arima tracking-wide transition">J/Victoria
                            College</span>
                        <span class="block text-xs text-gray-400 font-sans tracking-widest uppercase">Global OSA
                            Network</span>
                    </div>
                </a>
                <a href="index.php"
                    class="text-sm font-bold text-gray-500 hover:text-theme-red transition flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Home
                </a>
            </div>
        </div>
    </nav>

    <div id="loading" class="fixed inset-0 bg-white z-40 flex items-center justify-center">
        <div class="w-16 h-16 border-4 border-gray-200 border-t-theme-red rounded-full animate-spin"></div>
    </div>

    <!-- Country Hero -->
    <header class="relative bg-slate-900 text-white min-h-[300px] flex items-end overflow-hidden">
        <div class="absolute inset-0 z-0">
            <!-- Static Banner -->
            <div class="absolute inset-0 bg-[url('assets/images/school_banner.jpg')] bg-cover bg-center opacity-30">
            </div>
            <div class="absolute inset-0 bg-gradient-to-br to-[#800000] from-transparent"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pb-12 flex  items-end gap-8">
            <div class="relative">
                <img id="oba-logo" src="assets/images/logo.jpg"
                    class="w-32 h-32 md:w-36 md:h-36 rounded-full border-4 border-white shadow-2xl bg-white object-contain">
            </div>
            <div class="mb-2">
                <h1 class="text-3xl md:text-4xl font-bold font-arima mb-2">OSA <span id="country-name">Loading...</span>
                </h1>
                <div id="social-links" class="flex gap-4 opacity-80">
                    <!-- JS Injected -->
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-12 grid grid-cols-1 lg:grid-cols-3 gap-12">

        <!-- Sidebar: Leadership -->
        <aside class="lg:col-span-1 space-y-8">
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 sticky top-24">
                <h3
                    class="text-lg font-bold font-arima mb-6 text-slate-800 border-b pb-2 border-gray-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-theme-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    Administration
                </h3>

                <div class="space-y-6" id="leadership-container">
                    <!-- Standard Positions -->
                    <div id="leadership-fixed">
                        <!-- Filled by JS -->
                    </div>

                    <!-- Dynamic Positions -->
                    <div id="leadership-dynamic" class="border-t border-gray-100 pt-4 space-y-6">
                        <!-- Filled by JS -->
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-12">

            <!-- Events -->
            <section>
                <h2 class="text-lg font-bold font-arima mb-4 text-slate-800">
                    Upcoming Events
                </h2>
                <!-- Changed to Grid Layout -->
                <div id="events-container" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- JS Injected -->
                </div>
            </section>

            <!-- News Section -->
            <section>
                <h2 class="text-lg font-bold font-arima mb-4 text-slate-800">News & Updates</h2>
                <div id="country-news-container" class="flex flex-col gap-6">
                    <!-- News will be loaded here -->
                </div>
                <!-- Pagination -->
                <div id="country-news-pagination" class="flex justify-end gap-2 mt-8"></div>
            </section>

            <!-- Donations -->
            <section>
                <h2 class="text-lg font-bold font-arima mb-4 text-slate-800">
                    Recent Donations
                </h2>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr
                                    class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500">
                                    <th class="px-6 py-4 font-semibold hidden md:table-cell">Date</th>
                                    <th class="px-6 py-4 font-semibold">Donor</th>
                                    <th class="px-6 py-4 font-semibold">Purpose</th>
                                    <th class="px-6 py-4 font-semibold text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody id="donations-body" class="divide-y divide-gray-100"></tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div id="donation-pagination"
                        class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between items-center text-sm">
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-[#800000] text-slate-200 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <img src="assets/images/logo.jpg" alt="Logo"
                class="h-16 w-16 rounded-full mx-auto mb-6 opacity-80 grayscale hover:grayscale-0 transition duration-500">
            <p class="mb-4">&copy;
                <?= date('Y') ?> J/Victoria College. All rights reserved.
            </p>
            <p class="text-sm font-medium text-slate-200">Developed by <a class="font-semibold text-white"
                    href="https://masspro.ca/en/" target="_blank">
                    Mass Production</a></p>
        </div>
    </footer>

    <script>
        const COUNTRY_ID = <?= $countryId ?>;

        document.addEventListener('DOMContentLoaded', () => {
            loadCountryData();
        });

        async function loadCountryData() {
            try {
                const data = await apiRequest(`api/public.php?action=country_details&id=${COUNTRY_ID}`);

                // Hide Loader
                document.getElementById('loading').style.display = 'none';

                if (!data.info) {
                    alert("Country information not found");
                    window.location = 'index.php';
                    return;
                }

                // Header
                document.title = `OSA ${data.info.country_name} - J/Victoria College`;
                document.getElementById('country-name').innerText = data.info.country_name;

                // Flag image removed in favor of static school banner
                // const flagImg = document.getElementById('header-flag');
                // if (data.info.flag_image) flagImg.src = data.info.flag_image;
                // else flagImg.style.display = 'none';

                if (data.info.logo) document.getElementById('oba-logo').src = data.info.logo;

                // Socials
                if (data.info.social_links) {
                    // Try parsing if string, or use directly if object
                    // Note: In PHP fetchAll/fetch, JSON columns might be returned as strings depending on driver options
                    // We'll safely parse logic here or assume string
                    let links = data.info.social_links;
                    if (typeof links === 'string') {
                        try { links = JSON.parse(links); } catch (e) { links = {}; }
                    }

                    const container = document.getElementById('social-links');
                    // Simple logic for common platforms
                    const map = {
                        'facebook': '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>',
                        'twitter': '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/></svg>',
                        'instagram': '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke-width="2"></rect><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z" stroke-width="2"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke-width="2"></line></svg>',
                        'web': '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"></circle><line x1="2" y1="12" x2="22" y2="12" stroke-width="2"></line><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z" stroke-width="2"></path></svg>',
                        'youtube': '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="6" width="18" height="12" rx="3" ry="3" stroke-width="2"></rect><polygon points="10 9 16 12 10 15" stroke-width="2"></polygon></svg>'
                    };

                    let html = '';
                    console.log(links);

                    for (const [key, val] of Object.entries(links)) {
                        if (val && map[key.toLowerCase()]) { // match vaguely
                            html += `<a href="${val}" target="_blank" class="hover:text-theme-red transition">${map[key.toLowerCase()]}</a>`;
                        } else if (val && key === 'web') {
                            html += `<a href="${val}" target="_blank" class="hover:text-theme-red transition">${map['web']}</a>`;
                        }
                    }
                    container.innerHTML = html;
                }

                // Info
                // Render Fixed Leadership
                const fixedContainer = document.getElementById('leadership-fixed');
                fixedContainer.innerHTML = '';

                const addFixedRole = (title, name, phone) => {
                    if (!name) return;
                    fixedContainer.innerHTML += `
                        <div class="group mb-6">
                            <span class="text-xs font-bold text-theme-red uppercase tracking-wider block mb-1">${title}</span>
                            <div class="font-bold text-base text-slate-900">${name}</div>
                            <div class="text-sm text-gray-500 flex flex-col gap-1 mt-1">
                                ${phone ? `
                                <a href="tel:${phone}" class="hover:text-theme-red transition flex items-center gap-2">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    <span>${phone}</span>
                                </a>` : ''}
                            </div>
                        </div>
                    `;
                };

                addFixedRole('President', data.info.president_name, data.info.president_phone);
                addFixedRole('Vice President', data.info.vp_name, data.info.vp_phone);
                addFixedRole('Secretary', data.info.secretary_name, data.info.secretary_phone);
                addFixedRole('Vice Secretary', data.info.vs_name, data.info.vs_phone);
                addFixedRole('Treasurer', data.info.treasurer_name, data.info.treasurer_phone);
                addFixedRole('Vice Treasurer', data.info.vt_name, data.info.vt_phone);

                // Render Dynamic Positions
                const dynamicContainer = document.getElementById('leadership-dynamic');
                dynamicContainer.innerHTML = '';

               

                if (data.info.other_members) {
                    try {
                        const others = JSON.parse(data.info.other_members);
                        // Handle both old format (array of strings) and new (array of objects)
                        if (Array.isArray(others)) {
                            if (others.length > 0 && typeof others[0] === 'string') {
                                // Legacy Format
                                dynamicContainer.innerHTML = `
                                    <div>
                                        <h4 class="font-bold text-sm text-theme-red mb-3">Committee Members</h4>
                                        <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside">
                                            ${others.map(m => `<li>${m}</li>`).join('')}
                                        </ul>
                                    </div>
                                `;
                            } else {
                                // New Format
                                others.forEach(group => {
                                    if (group.members && group.members.length > 0) {
                                        dynamicContainer.innerHTML += `
                                            <div>
                                                <h4 class="font-bold text-sm text-theme-red mb-3 border-b pb-1 border-gray-100">${group.position}</h4>
                                                <ul class="text-sm text-gray-600 space-y-2">
                                                    ${group.members.map(m => `<li class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div> ${m}</li>`).join('')}
                                                </ul>
                                            </div>
                                        `;
                                    }
                                });
                            }
                        }
                    } catch (e) { console.error("Parse error", e); }
                }
                
                 // Committee Photo
                if (data.info.committee_photo) {
                    dynamicContainer.innerHTML += `
                        <div class="mb-6">
                            <h4 class="text-sm font-bold text-gray-600 uppercase tracking-wider mb-3">Committee Members</h4>
                            <div class="cursor-pointer group relative overflow-hidden rounded-xl" onclick="openImageModal('${data.info.committee_photo}')">
                                <img src="${data.info.committee_photo}" 
                                     class="w-full h-40 object-cover rounded-xl shadow-md group-hover:scale-105 transition duration-500">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition transform scale-75 group-hover:scale-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    `;
                }

                // Events
                renderEvents(data.events);

                // Donations
                renderDonations(data.donations);

            } catch (err) {
                console.error(err);
                document.getElementById('loading').innerHTML = '<p class="text-red-500">Failed to load content.</p>';
            }
        }

        function updateText(id, val, hideIfEmpty = false) {
            const el = document.getElementById(id);
            if (val) {
                el.innerText = val;
                if (hideIfEmpty) el.closest('.flex').style.display = 'flex'; // Reset default
            } else {
                el.innerText = 'Not Available';
                if (hideIfEmpty) el.closest('.flex').style.display = 'none'; // Assuming structure
            }
        }
        function updateUrl(id, url) {
            document.getElementById(id).href = url;
        }

        // Helper for thumbnails
        function getThumb(src, w = 200, q = 60) {
            if (!src) return 'assets/images/logo.jpg';
            return `thumb.php?src=${encodeURIComponent(src)}&w=${w}&q=${q}`;
        }

        function renderEvents(events) {
            const container = document.getElementById('events-container');
            if (events.length === 0) {
                container.innerHTML = '<div class="col-span-full p-6 bg-gray-50 rounded-xl border border-dashed border-gray-200 text-center text-gray-400">No upcoming events scheduled.</div>';
                return;
            }
            container.innerHTML = events.map(ev => `
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden border border-gray-100 group cursor-pointer" onclick='openEventModal(${JSON.stringify(ev)})'>
                        <div class="h-40 bg-gray-100 relative p-2 flex items-center justify-center">
                            <img src="${getThumb(ev.image, 400, 60)}" alt="${ev.title}" class="h-full w-full object-contain group-hover:scale-105 transition duration-500">
                        </div>
                        <div class="p-4">
                            <div class="flex items-center gap-2 text-xs text-theme-red font-semibold mb-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                ${dateShortFormat(ev.event_date)}
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 mb-1 line-clamp-2 leading-tight">${ev.title}</h3>
                            <p class="text-gray-500 text-xs line-clamp-2 mt-1">${ev.description || ''}</p>
                        </div>
                    </div>
             `).join('');
        }

        const dateShortFormat = (dt) => {
            const [y, m, d] = dt.split('-');
            const date = new Date(y, m - 1, d);

            return date.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric'
            });
        }

        const dateFormat = (dt) => {
            const [y, m, d] = dt.split('-');
            const date = new Date(y, m - 1, d);

            return date.toLocaleDateString('en-US');
        }
        let currentDonationPage = 1;

        function renderDonations(data) {
            const tbody = document.getElementById('donations-body');
            if (!data.data || data.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center py-8 text-gray-400">No donation records found.</td></tr>';
                document.getElementById('donation-pagination').innerHTML = ''; // Clear pagination
                return;
            }

            tbody.innerHTML = data.data.map(d => `
                 <tr class="hover:bg-gray-50 transition border-b border-gray-50 last:border-0">
                    <td class="px-6 py-4 text-sm text-gray-500 hidden md:table-cell">${dateFormat(d.date)}</td>
                    <td class="px-6 py-4 flex flex-col md:table-cell">
                        <span class="font-bold text-slate-800">${d.received_from}</span>
                        <span class="text-xs text-gray-400 md:hidden">${dateFormat(d.date)}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">${d.purpose_name || d.payment_purpose || 'General'}</td>
                    <td class="px-6 py-4 text-right font-bold text-emerald-600">$${parseFloat(d.amount).toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
                 </tr>
             `).join('');

            renderPagination(data);
        }

        function renderPagination(data) {
            const container = document.getElementById('donation-pagination');
            const totalPages = data.pages;
            const curr = data.page;

            if (totalPages <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = `
                <div class="text-gray-500 text-xs md:text-sm">Page <span class="font-bold text-slate-800">${curr}</span> of ${totalPages}</div>
                <div class="flex gap-1 md:gap-2">
            `;

            // First
            if (curr > 1) {
                html += `<button onclick="loadDonations(1)" class="px-2 py-1 md:px-3 border rounded hover:bg-gray-100 text-slate-600 text-xs md:text-sm" title="First">&laquo;</button>`;
                html += `<button onclick="loadDonations(${curr - 1})" class="px-2 py-1 md:px-3 border rounded hover:bg-gray-100 text-slate-600 text-xs md:text-sm">Prev</button>`;
            } else {
                html += `<button disabled class="px-2 py-1 md:px-3 border rounded bg-gray-50 text-gray-300 text-xs md:text-sm">&laquo;</button>`;
                html += `<button disabled class="px-2 py-1 md:px-3 border rounded bg-gray-50 text-gray-300 text-xs md:text-sm">Prev</button>`;
            }

            // Next
            if (curr < totalPages) {
                html += `<button onclick="loadDonations(${curr + 1})" class="px-2 py-1 md:px-3 border rounded hover:bg-gray-100 text-slate-600 text-xs md:text-sm">Next</button>`;
                html += `<button onclick="loadDonations(${totalPages})" class="px-2 py-1 md:px-3 border rounded hover:bg-gray-100 text-slate-600 text-xs md:text-sm" title="Last">&raquo;</button>`;
            } else {
                html += `<button disabled class="px-2 py-1 md:px-3 border rounded bg-gray-50 text-gray-300 text-xs md:text-sm">Next</button>`;
                html += `<button disabled class="px-2 py-1 md:px-3 border rounded bg-gray-50 text-gray-300 text-xs md:text-sm">&raquo;</button>`;
            }

            html += `</div>`;
            container.innerHTML = html;
        }

        async function loadDonations(page) {
            const tbody = document.getElementById('donations-body');
            tbody.innerHTML = '<tr><td colspan="4" class="text-center py-8 text-gray-400">Loading...</td></tr>';

            try {
                const data = await apiRequest(`api/public.php?action=country_donations&id=${COUNTRY_ID}&page=${page}`);
                renderDonations(data);
                currentDonationPage = page;
                // Scroll to table top if needed, but maybe not disrupting user experience
            } catch (e) {
                console.error(e);
                tbody.innerHTML = '<tr><td colspan="4" class="text-center py-8 text-red-400">Error loading donations.</td></tr>';
            }
        }
    </script>
    <!-- Event Popup Modal (Reused) -->
    <div id="eventModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="closeEventModal()">
        </div>
        <div
            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl p-4 h-full md:h-auto flex items-center justify-center pointer-events-none">
            <div
                class="bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in relative pointer-events-auto max-h-[90vh] flex flex-col w-full max-w-3xl overflow-y-auto">
                <button onclick="closeEventModal()"
                    class="absolute top-4 right-4 z-50 bg-gray-100/50 text-gray-500 p-2 rounded-full hover:bg-gray-200 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Info (Top) -->
                <div class="w-full p-8 pb-4">
                    <h2 id="modalEventTitle"
                        class="text-2xl md:text-3xl font-bold text-slate-800 mb-2 font-arima leading-tight"></h2>
                    <div class="flex items-center gap-2 text-theme-red font-semibold mb-6">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span id="modalEventDate"></span>
                    </div>
                    <p id="modalEventDesc" class="text-gray-600 leading-relaxed text-sm md:text-base"></p>
                </div>

                <!-- Image (Bottom) -->
                <div class="w-full bg-gray-50 flex items-center justify-center p-4 border-t border-gray-100">
                    <img id="modalEventImage" src="" alt="Event"
                        class="max-h-[50vh] w-auto object-contain rounded-lg shadow-sm" onclick="toggleZoom(this)">
                </div>
            </div>
        </div>
    </div>

    <script>
        const modelDateFormat = (dt) => {
            const [y, m, d] = dt.split('-');
            const date = new Date(y, m - 1, d);

            return date.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
        function openEventModal(ev) {
            document.getElementById('modalEventTitle').innerText = ev.title;
            document.getElementById('modalEventDate').innerText = modelDateFormat(ev.event_date);
            document.getElementById('modalEventDesc').innerText = ev.description || 'No description available.';
            document.getElementById('modalEventImage').src = ev.image || 'assets/images/logo.jpg';

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

        // Image Modal for Committee Photo
        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
            document.getElementById('imageModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.getElementById('imageModal').classList.remove('flex');
            document.body.style.overflow = '';
        }

        // --- Helper: Escape HTML ---
        function escapeHtml(text) {
            if (!text) return '';
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        let currentCountryNewsPage = 1;

        // Load country-specific news
        async function loadCountryNews(page = 1) {
            currentCountryNewsPage = page;
            const countryId = <?= $countryId ?>;
            try {
                const response = await fetch(`api/public.php?action=news&country_id=${countryId}&page=${page}&limit=10`);
                const result = await response.json();

                let news = [];
                let totalPages = 1;

                if (result.data) {
                    news = result.data;
                    totalPages = result.pages;
                } else if (Array.isArray(result)) {
                    news = result;
                }

                const container = document.getElementById('country-news-container');

                if (!news || news.length === 0) {
                    if (page === 1) container.innerHTML = '<p class="text-center text-gray-400 py-8">No news available</p>';
                    else container.innerHTML = '<p class="text-center text-gray-400 py-8">No more news</p>';
                    document.getElementById('country-news-pagination').innerHTML = '';
                    return;
                }

                container.innerHTML = news.map(item => {
                    const images = item.images ? JSON.parse(item.images) : [];
                    const firstImage = images.length > 0 ? images[0] : null;
                    const safeTitle = escapeHtml(item.title);
                    const safeContent = escapeHtml(item.content).substring(0, 200);
                    const safeDate = new Date(item.created_at).toLocaleDateString();

                    return `
                    <div class="bg-white border border-gray-100 rounded-xl overflow-hidden hover:shadow-lg transition-all flex flex-col sm:flex-row group cursor-pointer min-h-[140px]" onclick='openCountryNewsPopup(${JSON.stringify(item).replace(/'/g, "&#39;")})'>
                        ${firstImage ? `
                        <div class="w-full sm:w-48 h-48 sm:h-auto flex-shrink-0 relative overflow-hidden">
                            <img src="${firstImage}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                        </div>
                        ` : ''}
                        
                        <div class="p-6 flex flex-col flex-1 justify-center">
                            <div class="flex items-center gap-2 mb-2">
                                ${item.is_global == 1 ? '<span class="px-2 py-0.5 text-[10px] font-bold bg-red-50 text-red-700 rounded-full">Global</span>' : ''}
                                <span class="text-xs text-gray-400">${safeDate}</span>
                            </div>
                            <h3 class="font-bold text-lg text-slate-800 mb-2 group-hover:text-theme-red transition line-clamp-2 leading-snug">${safeTitle}</h3>
                            <p class="text-sm text-gray-600 line-clamp-2 mb-4 leading-relaxed">${safeContent}...</p>
                            <span class="text-xs text-theme-red font-bold mt-auto flex items-center gap-1 group-hover:translate-x-1 transition uppercase tracking-wide">
                                Read More
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </span>
                        </div>
                    </div>
                `;
                }).join('');

                renderCountryNewsPagination(page, totalPages);

            } catch (error) {
                console.error('Error loading news:', error);
            }
        }

        function renderCountryNewsPagination(currentPage, totalPages) {
            const container = document.getElementById('country-news-pagination');
            if (totalPages <= 1) { container.innerHTML = ''; return; }

            let html = '';

            html += `<button onclick="loadCountryNews(1)" class="px-3 py-1 rounded border border-gray-200 hover:bg-gray-50 text-sm ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}" ${currentPage === 1 ? 'disabled' : ''}>First</button>`;
            html += `<button onclick="loadCountryNews(${currentPage - 1})" class="px-3 py-1 rounded border border-gray-200 hover:bg-gray-50 text-sm ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}" ${currentPage === 1 ? 'disabled' : ''}>Prev</button>`;
            html += `<span class="px-4 py-1 text-sm font-semibold text-gray-600 flex items-center">Page ${currentPage} of ${totalPages}</span>`;
            html += `<button onclick="loadCountryNews(${currentPage + 1})" class="px-3 py-1 rounded border border-gray-200 hover:bg-gray-50 text-sm ${currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''}" ${currentPage === totalPages ? 'disabled' : ''}>Next</button>`;
            html += `<button onclick="loadCountryNews(${totalPages})" class="px-3 py-1 rounded border border-gray-200 hover:bg-gray-50 text-sm ${currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''}" ${currentPage === totalPages ? 'disabled' : ''}>Last</button>`;

            container.innerHTML = html;
        }

        function openCountryNewsPopup(news) {
            const images = news.images ? JSON.parse(news.images) : [];
            const imagesHtml = images.length > 0 ?
                `<div class="grid grid-cols-2 gap-2 mb-4">
                    ${images.map(img => `<img src="${img}" class="w-full h-48 object-cover rounded-lg cursor-pointer hover:opacity-90 transition" onclick="openImageModal('${img}')">`).join('')}
                </div>` : '';

            document.getElementById('countryNewsPopupTitle').innerText = news.title;
            document.getElementById('countryNewsPopupDate').innerText = new Date(news.created_at).toLocaleDateString();
            document.getElementById('countryNewsPopupImages').innerHTML = imagesHtml;
            document.getElementById('countryNewsPopupContent').innerText = news.content;

            const badge = document.getElementById('countryNewsPopupBadge');
            if (news.is_global == 1) {
                badge.innerText = 'Global';
                badge.className = 'px-3 py-1 bg-red-50 text-red-700 text-xs font-bold rounded-full';
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }

            document.getElementById('countryNewsPopup').classList.remove('hidden');
            document.getElementById('countryNewsPopup').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeCountryNewsPopup() {
            document.getElementById('countryNewsPopup').classList.add('hidden');
            document.getElementById('countryNewsPopup').classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Load news on page load
        loadCountryNews();
    </script>

    <!-- Image Modal for Committee Photo -->
    <div id="imageModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-[130] p-4">


        <div class="max-w-4xl max-h-full relative">
            <button onclick="closeImageModal()"
                class="absolute top-4 right-4 z-50 bg-gray-100/50 text-gray-500 p-2 rounded-full hover:bg-gray-200 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
            <img id="modalImage" src="" class="max-w-full max-h-screen object-contain rounded-lg">
        </div>
    </div>

    <!-- News Popup Modal -->
    <div id="countryNewsPopup" class="fixed inset-0 bg-black/90 hidden items-center justify-center z-[120] p-4">

        <div class="bg-white rounded-2xl w-full md:max-w-5xl max-h-[90vh] overflow-y-auto relative"
            onclick="event.stopPropagation()">
            <button onclick="closeCountryNewsPopup()"
                class="absolute top-4 right-4 w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center z-[100]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
            <div class="p-8">
                <span id="countryNewsPopupBadge"
                    class="px-3 py-1 bg-sky-50 text-sky-700 text-xs font-bold rounded-full mb-4 inline-block"></span>
                <h2 id="countryNewsPopupTitle" class="text-lg font-bold text-slate-800 mb-2"></h2>
                <p id="countryNewsPopupDate" class="text-sm text-gray-500 mb-6"></p>
                <div id="countryNewsPopupImages" class="mb-6"></div>
                <p id="countryNewsPopupContent" class="text-gray-700 leading-relaxed whitespace-pre-wrap"></p>
            </div>
        </div>
    </div>
</body>

</html>