<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J/Victoria College - Old Students Association</title>
    <link rel="icon" type="image/jpeg" href="assets/images/logo.jpg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="assets/css/custom.css" rel="stylesheet">
    <script defer src="assets/js/main.js"></script>
</head>

<body class="flex flex-col min-h-screen">

    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo Area -->
                <div class="flex items-center gap-4">
                    <img src="assets/images/logo.jpg" alt="Logo"
                        class="h-10 w-10 md:h-12 md:w-12 rounded-full border-2 border-theme-red object-cover">
                    <div>
                        <span
                            class="block text-lg md:text-xl font-bold text-theme-red font-arima tracking-wide">J/Victoria
                            College</span>
                        <span
                            class="block text-[10px] md:text-xs text-gray-500 font-sans tracking-widest uppercase">Global
                            OSA
                            Network</span>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-4">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span class="text-gray-600 text-sm">Hello,
                            <b class="capitalize"><?= htmlspecialchars($_SESSION['username']) ?></b></span>
                        <!-- <a href="dashboard.php"
                            class="px-5 py-2 rounded-full bg-gray-100 text-gray-700 hover:bg-gray-200 transition font-medium text-sm">Dashboard</a> -->

                        <button onclick="window.location.href='dashboard.php'"
                            class="px-6 py-2.5 rounded-full bg-gray-50 text-gray-600 hover:bg-gray-100 transition font-medium shadow-md shadow-gray-200 flex items-center gap-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <line x1="4" y1="20" x2="4" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="20" y1="20" x2="20" y2="14"></line>
                            </svg>
                            Dashboard
                        </button>
                        <button onclick="window.location.href='logout.php'"
                            class="px-6 py-2.5 rounded-full bg-[#800000] text-white hover:bg-red-500 transition font-medium shadow-md shadow-red-200 flex items-center gap-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="20" y1="4" x2="20" y2="20"></line>
                                <polyline points="10 17 5 12 10 7"></polyline>
                                <line x1="5" y1="12" x2="16" y2="12"></line>
                            </svg>
                            Logout
                        </button>
                    <?php else: ?>
                        <button onclick="openLoginModal()"
                            class="px-6 py-2.5 rounded-full bg-[#800000] text-white hover:bg-[#e23b3b] transition font-medium shadow-lg shadow-gray-500/75 flex items-center gap-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Login
                        </button>
                    <?php endif; ?>
                </div>




                <!-- Mob     ile Menu Button -->
                <button onclick="document.getElementById('mobileMenu').classList.toggle('hidden')"
                    class="md:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobileMenu" class="hidden md:hidden border-t border-gray-100 bg-white">
            <div class="px-4 py-4 space-y-3">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="px-4 py-2 bg-gray-50 rounded-lg text-center">
                        <span class="text-gray-600 text-sm block mb-1">Signed in as</span>
                        <span
                            class="font-bold text-slate-800 capitalize"><?= htmlspecialchars($_SESSION['username']) ?></span>
                    </div>
                    <a href="dashboard.php"
                        class="block w-full text-center px-4 py-3 rounded-lg bg-gray-100 text-gray-700 font-bold flex items-center gap-4 justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <line x1="4" y1="20" x2="4" y2="10"></line>
                            <line x1="12" y1="20" x2="12" y2="4"></line>
                            <line x1="20" y1="20" x2="20" y2="14"></line>
                        </svg>
                        Dashboard</a>
                    <a href="logout.php"
                        class="block w-full text-center px-4 py-3 rounded-lg bg-theme-red text-white font-bold flex items-center gap-4 justify-center ">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="20" y1="4" x2="20" y2="20"></line>
                            <polyline points="10 17 5 12 10 7"></polyline>
                            <line x1="5" y1="12" x2="16" y2="12"></line>
                        </svg>
                        Logout</a>
                <?php else: ?>
                    <button onclick="openLoginModal(); document.getElementById('mobileMenu').classList.add('hidden')"
                        class="w-full py-3 rounded-lg bg-[#c51d1d] text-white font-bold shadow-lg flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Login
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative bg-slate-900 text-white py-24 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <!-- Background Banner with Low Opacity -->
            <div class="absolute inset-0 bg-[url('assets/images/school_banner.jpg')] bg-cover bg-center opacity-70">
            </div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#c51d1d47] to-[#800000]"></div>
            <!-- Animated blobs -->
            <div
                class="absolute -top-24 -left-24 w-96 h-96 bg-theme-red rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob">
            </div>
            <div
                class="absolute top-24 right-0 w-96 h-96 bg-blue-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000">
            </div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto text-center px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-6 font-arima leading-tight">Connect with Alma Mater</h1>
            <p class="text-lg md:text-xl text-gray-300 mb-8 leading-relaxed max-w-2xl mx-auto">
                Welcome to the official hub of J/Victoria College Old Students Associations.
                We are a global community dedicated to supporting our school's legacy, fostering brotherhood,
                and contributing to the development of our alma mater through events, donations, and continuous
                engagement.
            </p>
            <div class="flex justify-center gap-4">
                <a href="#events"
                    class="px-8 py-3 bg-white text-slate-900 rounded-full font-bold hover:bg-gray-100 transition">Upcoming
                    Events</a>
                <a href="#donations"
                    class="px-8 py-3 border border-white/30 text-white rounded-full font-bold hover:bg-white/10 transition backdrop-blur-sm">Our
                    Donors</a>
            </div>
        </div>
    </header>

    <!-- OBA List Carousel -->
    <section class="py-16 bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!--<h2 class="text-2xl font-bold text-center mb-12 text-slate-800 font-arima">Our Global Presence</h2>-->
            <!--  <p class="text-gray-500 mt-2">Join us in our upcoming gatherings around the world.</p>-->
              
                <div class="flex flex-col items-center mb-8">
                    <h2 class="text-2xl font-bold text-slate-800 font-arima">Our Global Presence</h2>
                    <p class="text-gray-500 mt-2">Empowering Students Across Continents</p>
                </div>

            <!-- Animated Container -->
            <div class="relative overflow-hidden">
                <div id="oba-container" class="flex flex-wrap justify-center gap-8 py-4 overflow-x-auto scrollbar-hide"
                    style="scroll-behavior: auto;">
                    <!-- Loading State -->
                    <div class="w-full text-center text-gray-400 py-8">Loading OSAs...</div>
                </div>
                <!-- Gradient Overlays -->
                <div
                    class="absolute inset-y-0 left-0 w-8 bg-gradient-to-r from-white to-transparent pointer-events-none z-10">
                </div>
                <div
                    class="absolute inset-y-0 right-0 w-8 bg-gradient-to-l from-white to-transparent pointer-events-none z-10">
                </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Events -->
    <section id="events" class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-center items-center mb-12">
                <div class="flex flex-col items-center">
                    <h2 class="text-2xl font-bold text-slate-800 font-arima">Upcoming Events</h2>
                    <p class="text-gray-500 mt-2 text-center">Join us in our upcoming gatherings around the world.</p>
                </div>
            </div>

            <div id="events-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Loading State -->
                <div class="col-span-full text-center py-12">
                    <div
                        class="inline-block w-8 h-8 border-4 border-gray-300 border-t-theme-red rounded-full animate-spin">
                    </div>
                </div>
            </div>
        </div>
    </section>
    
        <!-- News Section -->
    <section id="news-section" class="py-16 bg-white">
        <div class="container mx-auto px-4">
               <div class="flex flex-col items-center mb-8">
                    <h2 class="text-2xl font-bold text-slate-800 font-arima">Latest News</h2>
                    <p class="text-gray-500 mt-2 text-center">Discover what’s happening across our school community.</p>
                </div>
            <div id="news-container" class="flex flex-col gap-6 max-w-5xl mx-auto">
                <!-- News will be loaded here -->
            </div>
            <!-- Pagination -->
            <div id="news-pagination" class="flex justify-end gap-2 mt-10"></div>
        </div>
    </section>

    <!-- Donations -->
    <section id="donations" class="py-16 bg-gray-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-center mb-4 text-slate-800 font-arima">Recent Contributions</h2>
            <p class="text-center text-gray-500 mb-12 max-w-2xl mx-auto">Thank you to our generous donors for supporting
                our vision.</p>

            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500">
                                <th class="px-6 py-4 font-semibold">Donor</th>
                                <th class="px-6 py-4 font-semibold text-start">Location</th>
                                <th class="px-6 py-4 font-semibold text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody id="donations-body" class="divide-y divide-gray-100">
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-400">Loading records...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div id="donation-pagination"
                    class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between items-center text-sm">
                    <!-- JS Injected -->
                </div>
            </div>
        </div>
    </section>



    <!-- Posters Section -->
    <section id="posters-section" class="py-16 bg-gradient-to-br from-purple-50 to-pink-50">
        <div class="container mx-auto px-4">
             <div class="flex flex-col items-center mb-8">
                    <h2 class="text-2xl font-bold text-slate-800 font-arima">Announcements & Posters</h2>
                    <p class="text-gray-500 mt-2 text-center">Stay updated with important notices and official communications.</p>
                </div>
            <div id="posters-container" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <!-- Posters will be loaded here -->
            </div>
        </div>
    </section>

    <!-- Advertisements Carousel -->
    <section id="ads-section" class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
             <div class="flex flex-col items-center mb-8">
                    <h2 class="text-2xl font-bold text-slate-800 font-arima">Advertisements</h2>
                    <p class="text-gray-500 mt-2">Sponsored content from our trusted partners.</p>
                </div>
            <div id="ads-scroller" class="overflow-visible lg:overflow-x-auto scrollbar-hide"
                onmouseenter="this.isPaused=true" onmouseleave="this.isPaused=false" ontouchstart="this.isPaused=true"
                ontouchend="this.isPaused=false">
                <div id="ads-container" class="grid grid-cols-2 gap-4 lg:flex lg:gap-6 pb-4">
                    <!-- Ads will be loaded here -->
                </div>
            </div>
        </div>
    </section>

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <!-- News Popup Modal -->
    <div id="newsPopup" class="fixed inset-0 bg-black/90 hidden items-center justify-center z-[120] p-4"
        onclick="closeNewsPopup()">
        <div class="bg-white rounded-2xl w-full md:max-w-5xl max-h-[90vh] overflow-y-auto relative"
            onclick="event.stopPropagation()">
            <button onclick="closeNewsPopup()"
                class="absolute top-4 right-4 w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
            <div class="p-8">
                <span id="newsPopupBadge"
                    class="px-3 py-1 bg-sky-50 text-sky-700 text-xs font-bold rounded-full mb-4 inline-block"></span>
                <h2 id="newsPopupTitle" class="text-2xl font-bold text-slate-800 mb-2"></h2>
                <p id="newsPopupDate" class="text-sm text-gray-500 mb-6"></p>
                <div id="newsPopupImages" class="mb-6"></div>
                <p id="newsPopupContent" class="text-gray-700 leading-relaxed whitespace-pre-wrap"></p>
            </div>
        </div>
    </div>

    <!-- Image Popup Modal -->
    <div id="imagePopup" class="fixed inset-0 bg-black/90 hidden items-center justify-center z-[130] p-4"
        onclick="closeImagePopup()">
        <div class="w-full max-h-full relative">
            <img id="imagePopupImg" src="" class="w-full max-h-screen object-contain rounded-lg">
            <button onclick="closeImagePopup()"
                class="absolute top-4 right-4 w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
    </div>

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

    <!-- Event Popup Modal -->
    <div id="eventModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="closeEventModal()">
        </div>
        <div
            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl p-4 h-full md:h-auto flex items-center justify-center pointer-events-none">
            <div
                class="bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in relative pointer-events-auto max-h-[90vh] flex flex-col w-full max-w-3xl overflow-y-auto">
                <!-- Close Button -->
                <button onclick="closeEventModal()"
                    class="absolute top-4 right-4 z-50 bg-gray-100/50 text-gray-500 p-2 rounded-full hover:bg-gray-200 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Info Section (Top) -->
                <div class="w-full p-8 pb-4">
                    <span id="modalEventCountry"
                        class="inline-block px-3 py-1 bg-theme-red text-white text-xs font-bold rounded-full w-fit mb-4">Global</span>
                    <h2 id="modalEventTitle"
                        class="text-2xl md:text-2xl font-bold text-slate-800 mb-2 font-arima leading-tight">Event Title
                    </h2>
                    <div class="flex items-center gap-2 text-theme-red font-semibold mb-6">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span id="modalEventDate">Date</span>
                    </div>
                    <p id="modalEventDesc" class="text-gray-600 leading-relaxed text-sm md:text-base">Description...</p>
                </div>

                <!-- Image Section (Bottom, Centered) -->
                <div class="w-full bg-gray-50 flex items-center justify-center p-4 border-t border-gray-100">
                    <img id="modalEventImage" src="" alt="Event"
                        class="max-h-[50vh] w-auto object-contain rounded-lg shadow-sm" onclick="toggleZoom(this)">
                </div>
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <div id="loginModal" class="fixed inset-0 z-[100] hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeLoginModal()">
        </div>

        <!-- Modal -->
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in relative">
                <button onclick="closeLoginModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="p-8 text-center">
                    <img src="assets/images/logo.jpg" alt="Logo" class="w-20 h-20 rounded-full mx-auto mb-4 shadow-md">
                    <h2 class="text-2xl font-bold text-slate-800 font-arima">Admin Login</h2>
                    <p class="text-gray-500 text-sm mt-1">Access the administration portal</p>

                    <form id="loginForm" onsubmit="handleLogin(event)" class="mt-8 space-y-4 text-left">
                        <div id="loginError"
                            class="hidden p-3 bg-red-50 text-red-600 text-xs rounded-lg border border-red-100 flex items-center gap-2">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Invalid credentials</span>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Username</label>
                            <input type="text" name="username"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-theme-red focus:border-transparent outline-none transition"
                                required placeholder="Enter username">
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Password</label>
                            <input type="password" name="password"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-theme-red focus:border-transparent outline-none transition"
                                required placeholder="••••••••">
                        </div>

                        <button type="submit"
                            class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                            Sign In
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Logic -->
    <script>
        // --- Modal Logic ---
        function openLoginModal() {
            document.getElementById('loginModal').classList.remove('hidden');
        }
        function closeLoginModal() {
            document.getElementById('loginModal').classList.add('hidden');
        }

        // --- Auth Logic ---
        async function handleLogin(e) {
            e.preventDefault();
            const btn = e.target.querySelector('button');
            const originalText = btn.innerText;
            btn.disabled = true;
            btn.innerHTML = '<span class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>';

            const formData = new FormData(e.target);

            try {
                // Should point to login.php
                const response = await fetch('login.php', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    showError(data.message || 'Login failed');
                }
            } catch (err) {
                console.error(err);
                showError('System error. Please try again.');
            } finally {
                btn.disabled = false;
                btn.innerText = originalText;
            }
        }

        function showError(msg) {
            const errEl = document.getElementById('loginError');
            errEl.querySelector('span').innerText = msg;
            errEl.classList.remove('hidden');
        }

        // --- Data Fetching ---
        document.addEventListener('DOMContentLoaded', () => {
            loadOBAs();
            loadEvents();
            loadDonations(1);
        });

        // Helper for thumbnails
        function getThumb(src, w = 200, q = 60) {
            if (!src) return 'assets/images/logo.jpg'; // fallback
            // If already absolute or external, return as is (unless we want to proxy external too)
            // Assuming local paths like 'assets/images/...'
            return `thumb.php?src=${encodeURIComponent(src)}&w=${w}&q=${q}`;
        }

        async function loadOBAs() {
            const container = document.getElementById('oba-container');
            try {
                const obas = await apiRequest('api/public.php?action=oba_list');

                if (obas.length === 0) {
                    container.innerHTML = '<div class="text-center text-gray-400 py-8">No OBAs found yet.</div>';
                    return;
                }

                // Render badges
                container.innerHTML = obas.map(oba => `
                    <a href="country.php?id=${oba.id}" target="_blank" class="group flex-shrink-0 w-40 flex flex-col items-center p-4 bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-100 transition-all transform hover:-translate-y-1 text-center cursor-pointer">
                        <div class="relative w-16 h-16 mb-3 rounded-full overflow-hidden border-2 border-gray-50 group-hover:border-theme-red transition-colors shadow-inner">
                            <img src="${getThumb(oba.flag_image, 100, 60)}" alt="${oba.name} Flag" class="w-full h-full object-cover">
                        </div>
                        <h3 class="font-bold text-sm text-slate-800 group-hover:text-theme-red transition-colors leading-tight">${oba.name}</h3>
                    </a>
                `).join('');


            } catch (err) {
                container.innerHTML = '<div class="text-red-500 w-full text-center">Failed to load OSAs</div>';
            }
        }

        async function loadEvents() {
            const container = document.getElementById('events-container');
            try {
                const events = await apiRequest('api/public.php?action=upcoming_events');

                if (events.length === 0) {
                    container.innerHTML = '<div class="col-span-full py-12 text-center text-gray-400 bg-gray-50 rounded-lg border border-dashed border-gray-200">No upcoming events at the moment.</div>';
                    return;
                }

                container.innerHTML = events.map(ev => `
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden border border-gray-100 group cursor-pointer" onclick='openEventModal(${JSON.stringify(ev)})'>
                        <div class="h-40 bg-gray-100 relative p-2 flex items-center justify-center">
                            <img src="${getThumb(ev.image, 400, 60)}" alt="${ev.title}" class="h-full w-full object-contain group-hover:scale-105 transition duration-500">
                             ${ev.country_name ? `<span class="absolute top-2 right-2 px-2 py-0.5 bg-black/60 backdrop-blur-sm text-white text-[10px] font-bold rounded-full">${ev.country_name}</span>` : '<span class="absolute top-2 right-2 px-2 py-0.5 bg-theme-red text-white text-[10px] font-bold rounded-full">Global</span>'}
                        </div>
                        <div class="p-4">
                            <div class="flex items-center gap-2 text-xs text-theme-red font-semibold mb-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                ${dateShortFormat(ev.event_date)}
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 mb-1 line-clamp-2 leading-tight">${ev.title}</h3>
                        </div>
                    </div>
                `).join('');

            } catch (err) {
                container.innerHTML = '<div class="col-span-full text-center text-red-500">Failed to load events</div>';
            }
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

        async function loadDonations(page) {
            const tbody = document.getElementById('donations-body');
            const paginator = document.getElementById('donation-pagination');

            // Fade out
            tbody.classList.add('opacity-50');

            try {
                const res = await apiRequest(`api/public.php?action=donations&page=${page}&_t=${Date.now()}`);
                const donations = res.data;

                if (donations.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center py-8 text-gray-400">No donations to display yet.</td></tr>';
                    paginator.innerHTML = '';
                    tbody.classList.remove('opacity-50');
                    return;
                }

                tbody.innerHTML = donations.map(d => `
                    <tr class="hover:bg-gray-50 transition border-b border-gray-50 last:border-0 group">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">${d.received_from}</div>
                            <div class="text-xs text-gray-400">${dateFormat(d.date)}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-start gap-2">
                                ${d.country_name ? `<img src="${getThumb(d.flag_image, 50, 60)}" class="w-5 h-5 rounded-full object-cover border border-gray-200" title="${d.country_name}">` : ''}
                                <span class="text-sm text-gray-600">${d.city || 'N/A'}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-emerald-600 group-hover:scale-110 inline-block transition transform">$${parseFloat(d.amount).toLocaleString('en-US', { minimumFractionDigits: 2 })}</span>
                        </td>
                    </tr>
                `).join('');

                // Pagination UI
                // Matches country.php style
                let html = `<div class="text-gray-500 text-xs md:text-sm">Page <span class="font-bold text-slate-800">${res.page}</span> of ${res.pages}</div>`;

                html += '<div class="flex gap-1 md:gap-2">';

                // First & Prev
                if (res.page > 1) {
                    html += `<button onclick="loadDonations(1)" class="px-2 py-1 md:px-3 bg-white border border-gray-300 rounded hover:bg-gray-50 text-slate-600 text-xs md:text-sm" title="First">&laquo;</button>`;
                    html += `<button onclick="loadDonations(${res.page - 1})" class="px-2 py-1 md:px-3 bg-white border border-gray-300 rounded hover:bg-gray-50 text-slate-600 text-xs md:text-sm">Prev</button>`;
                } else {
                    html += `<button disabled class="px-2 py-1 md:px-3 bg-gray-50 border border-gray-200 rounded text-gray-300 text-xs md:text-sm">&laquo;</button>`;
                    html += `<button disabled class="px-2 py-1 md:px-3 bg-gray-50 border border-gray-200 rounded text-gray-300 text-xs md:text-sm">Prev</button>`;
                }

                // Next & Last
                if (res.page < res.pages) {
                    html += `<button onclick="loadDonations(${res.page + 1})" class="px-2 py-1 md:px-3 bg-white border border-gray-300 rounded hover:bg-gray-50 text-slate-600 text-xs md:text-sm">Next</button>`;
                    html += `<button onclick="loadDonations(${res.pages})" class="px-2 py-1 md:px-3 bg-white border border-gray-300 rounded hover:bg-gray-50 text-slate-600 text-xs md:text-sm" title="Last">&raquo;</button>`;
                } else {
                    html += `<button disabled class="px-2 py-1 md:px-3 bg-gray-50 border border-gray-200 rounded text-gray-300 text-xs md:text-sm">Next</button>`;
                    html += `<button disabled class="px-2 py-1 md:px-3 bg-gray-50 border border-gray-200 rounded text-gray-300 text-xs md:text-sm">&raquo;</button>`;
                }

                html += '</div>';

                paginator.innerHTML = html;

            } catch (err) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center py-8 text-red-400">Failed to load donations.</td></tr>';
            } finally {
                tbody.classList.remove('opacity-50');
            }
        }

        // --- Event Modal Logic ---
        function openEventModal(ev) {
            document.getElementById('modalEventTitle').innerText = ev.title;
            document.getElementById('modalEventDate').innerText = modelDateFormat(ev.event_date);
            document.getElementById('modalEventDesc').innerText = ev.description || 'No description available.';
            // Full Quality for Modal
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

        let currentNewsPage = 1;

        // --- News Loading ---
        async function loadNews(page = 1) {
            currentNewsPage = page;
            try {
                const response = await fetch(`api/public.php?action=news&page=${page}&limit=10`);
                const result = await response.json();

                // Extract data depending on API response format
                let news = [];
                let totalPages = 1;

                if (result.data) {
                    news = result.data;
                    totalPages = result.pages;
                } else if (Array.isArray(result)) {
                    news = result; // Fallback
                }

                const container = document.getElementById('news-container');

                if (!news || news.length === 0) {
                    if (page === 1) {
                        document.getElementById('news-section').style.display = 'none';
                    } else {
                        container.innerHTML = '<div class="text-center text-gray-500 py-8">No more news articles found.</div>';
                    }
                    return;
                }

                document.getElementById('news-section').style.display = 'block';

                container.innerHTML = news.map(item => {
                    const images = item.images ? JSON.parse(item.images) : [];
                    const firstImage = images.length > 0 ? images[0] : null;
                    const safeTitle = escapeHtml(item.title);
                    const safeContent = escapeHtml(item.content).substring(0, 250);
                    const safeDate = new Date(item.created_at).toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' });
                    const safeCountry = escapeHtml(item.country_name || 'Global');

                    return `
                    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col md:flex-row group cursor-pointer h-auto min-h-[160px]" onclick='openNewsPopup(${JSON.stringify(item).replace(/'/g, "&#39;")})'>
                        ${firstImage ? `
                        <div class="w-full md:w-48 h-48 md:h-auto flex-shrink-0 relative overflow-hidden">
                            <img src="${firstImage}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                            <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition"></div>
                        </div>
                        ` : ''}
                        
                        <div class="p-6 flex flex-col flex-1 justify-center">
                            <div class="flex items-center gap-3 mb-2 text-xs font-semibold uppercase tracking-wider">
                                ${item.is_global == 1
                            ? `<span class="text-theme-red bg-red-50 px-2 py-1 rounded">Global News</span>`
                            : `<span class="text-sky-600 bg-sky-50 px-2 py-1 rounded">${safeCountry}</span>`
                        }
                                <span class="text-gray-400">&bull;</span>
                                <span class="text-gray-400">${safeDate}</span>
                            </div>
                            
                            <h3 class="font-bold text-xl text-slate-800 mb-2 group-hover:text-theme-red transition line-clamp-2">${safeTitle}</h3>
                            <p class="text-slate-600 text-sm leading-relaxed line-clamp-2 mb-4">${safeContent}...</p>
                            
                            <div class="mt-auto flex items-center text-theme-red font-semibold text-sm group-hover:translate-x-1 transition">
                                Read Full Story
                                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    `;
                }).join('');

                renderNewsPagination(page, totalPages);

            } catch (error) {
                console.error('Error loading news:', error);
                document.getElementById('news-section').style.display = 'none';
            }
        }

        function renderNewsPagination(currentPage, totalPages) {
            const container = document.getElementById('news-pagination');
            if (totalPages <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '';

            html += `<button onclick="loadNews(1)" class="px-3 py-1 rounded border border-gray-200 hover:bg-gray-50 text-sm ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}" ${currentPage === 1 ? 'disabled' : ''}>First</button>`;
            html += `<button onclick="loadNews(${currentPage - 1})" class="px-3 py-1 rounded border border-gray-200 hover:bg-gray-50 text-sm ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}" ${currentPage === 1 ? 'disabled' : ''}>Prev</button>`;
            html += `<span class="px-4 py-1 text-sm font-semibold text-gray-600 flex items-center">Page ${currentPage} of ${totalPages}</span>`;
            html += `<button onclick="loadNews(${currentPage + 1})" class="px-3 py-1 rounded border border-gray-200 hover:bg-gray-50 text-sm ${currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''}" ${currentPage === totalPages ? 'disabled' : ''}>Next</button>`;
            html += `<button onclick="loadNews(${totalPages})" class="px-3 py-1 rounded border border-gray-200 hover:bg-gray-50 text-sm ${currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''}" ${currentPage === totalPages ? 'disabled' : ''}>Last</button>`;

            container.innerHTML = html;
        }

        // --- News Popup ---
        function openNewsPopup(news) {
            const images = news.images ? JSON.parse(news.images) : [];
            const imagesHtml = images.length > 0 ?
                `<div class="grid grid-cols-2 gap-2 mb-4">
                    ${images.map(img => `<img src="${img}" class="w-full h-48 object-cover rounded-lg cursor-pointer hover:opacity-90 transition" onclick="openImagePopup('${img}')">`).join('')}
                </div>` : '';

            document.getElementById('newsPopupTitle').innerText = news.title;
            document.getElementById('newsPopupDate').innerText = new Date(news.created_at).toLocaleDateString();
            document.getElementById('newsPopupImages').innerHTML = imagesHtml;
            document.getElementById('newsPopupContent').innerText = news.content;

            const badge = document.getElementById('newsPopupBadge');
            if (news.is_global == 1) {
                badge.innerText = 'Global';
                badge.className = 'px-3 py-1 bg-red-50 text-red-700 text-xs font-bold rounded-full';
            } else {
                badge.innerText = news.country_name || 'Country';
                badge.className = 'px-3 py-1 bg-sky-50 text-sky-700 text-xs font-bold rounded-full';
            }

            document.getElementById('newsPopup').classList.remove('hidden');
            document.getElementById('newsPopup').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeNewsPopup() {
            document.getElementById('newsPopup').classList.add('hidden');
            document.getElementById('newsPopup').classList.remove('flex');
            document.body.style.overflow = '';
        }

        // --- Advertisements Loading ---
        async function loadAdvertisements() {
            try {
                const response = await fetch('api/public.php?action=advertisements');
                const ads = await response.json();
                const container = document.getElementById('ads-container');

                if (!ads || ads.length === 0) {
                    document.getElementById('ads-section').style.display = 'none';
                    return;
                }

                container.innerHTML = ads.map(ad => `
                    <div class="w-full lg:w-64 lg:flex-shrink-0 cursor-pointer" onclick="openImagePopup('${ad.image}')">
                        <img src="${ad.image}" class="w-full h-auto  object-cover rounded-xl shadow-md hover:shadow-xl transition ">
                    </div>
                `).join('');
            } catch (error) {
                console.error('Error loading advertisements:', error);
                document.getElementById('ads-section').style.display = 'none';
            }
        }

        // --- Auto Scroll Logic ---
        // --- Auto Scroll Logic ---
        function startAutoScroll() {
            // Only run on larger screens (Tailwind lg breakpoint is 1024px)
            if (window.innerWidth < 1024) return;

            const scroller = document.getElementById('ads-scroller');
            const container = document.getElementById('ads-container');
            if (!scroller || !container) return;

            // Wait one frame to ensure DOM is rendered and clientWidth is available
            requestAnimationFrame(() => {
                const items = container.children;
                const itemCount = items.length;
                if (itemCount === 0) return;

                // Initialize state
                scroller.isPaused = false;

                // Calculate width of one set
                // Each item is w-48 (192px) + gap-6 (24px)
                const itemWidth = 192;
                const gap = 24;
                const totalContentWidth = (itemCount * itemWidth) + ((itemCount - 1) * gap);

                // Step 1: Check if content fits within the visible container
                if (totalContentWidth <= scroller.clientWidth) {
                    container.classList.add('justify-center');
                    return;
                } else {
                    container.classList.remove('justify-center');
                }

                // Duplicate content for infinite loop effect
                container.innerHTML += '<div class="hidden lg:block w-px h-1 flex-shrink-0" style="margin-left:' + gap + 'px"></div>' + container.innerHTML;

                const speed = 0.8;

                function step() {
                    if (!scroller.isPaused) {
                        scroller.scrollLeft += speed;

                        // Reset when we've scrolled past the first set
                        // We added a small spacer to handle the gap between sets
                        if (scroller.scrollLeft >= totalContentWidth + gap) {
                            scroller.scrollLeft = 0;
                        }
                    }
                    requestAnimationFrame(step);
                }
                requestAnimationFrame(step);
            });
        }

        // --- Posters Loading ---
        async function loadPosters() {
            try {
                const response = await fetch('api/public.php?action=posters');
                const posters = await response.json();
                const container = document.getElementById('posters-container');

                if (!posters || posters.length === 0) {
                    document.getElementById('posters-section').style.display = 'none';
                    return;
                }

                container.innerHTML = posters.map(poster => `
                    <div class="cursor-pointer transform hover:scale-105 transition" onclick="openImagePopup('${poster.image}')">
                        <img src="${poster.image}" class="w-full h-auto md:h-64 object-cover rounded-xl shadow-md hover:shadow-xl transition">
                    </div>
                `).join('');
            } catch (error) {
                console.error('Error loading posters:', error);
                document.getElementById('posters-section').style.display = 'none';
            }
        }

        // --- Image Popup ---
        function openImagePopup(src) {
            document.getElementById('imagePopupImg').src = src;
            document.getElementById('imagePopup').classList.remove('hidden');
            document.getElementById('imagePopup').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeImagePopup() {
            document.getElementById('imagePopup').classList.add('hidden');
            document.getElementById('imagePopup').classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Load all on page load
        loadNews();
        loadAdvertisements().then(() => startAutoScroll());
        loadPosters();
    </script>
</body>

</html>