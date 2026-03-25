
// Custom Modal Logic
document.addEventListener('DOMContentLoaded', () => {
    const modalHTML = `
    <div id="globalModal" class="fixed inset-0 z-[200] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm border border-gray-100 transform scale-95 opacity-0 transition-all duration-300" id="globalModalContent">
            <div class="p-6 text-center">
                <div id="globalModalIcon" class="mx-auto mb-4 w-12 h-12 rounded-full flex items-center justify-center"></div>
                <h3 id="globalModalTitle" class="text-xl font-bold text-slate-800 mb-2"></h3>
                <p id="globalModalMessage" class="text-gray-500 text-sm mb-6"></p>
                <div id="globalModalButtons" class="flex gap-3 justify-center"></div>
            </div>
        </div>
    </div>`;
    document.body.insertAdjacentHTML('beforeend', modalHTML);
});

window.showAlert = function (message, type = 'success') {
    return new Promise((resolve) => {
        const modal = document.getElementById('globalModal');
        const content = document.getElementById('globalModalContent');
        const icon = document.getElementById('globalModalIcon');
        const title = document.getElementById('globalModalTitle');
        const msg = document.getElementById('globalModalMessage');
        const btns = document.getElementById('globalModalButtons');

        if (type === 'success') {
            icon.className = 'mx-auto mb-4 w-16 h-16 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center';
            icon.innerHTML = '<svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
            title.innerText = 'Success';
        } else {
            icon.className = 'mx-auto mb-4 w-16 h-16 rounded-full bg-red-50 text-red-500 flex items-center justify-center';
            icon.innerHTML = '<svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
            title.innerText = 'Error';
        }

        msg.innerText = message;
        btns.innerHTML = `<button onclick="closeGlobalModal()" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold rounded-lg transition-colors">OK</button>`;

        openGlobalModal();

        // Auto close after 2s for successes
        if (type === 'success') {
            setTimeout(() => {
                closeGlobalModal();
                resolve();
            }, 2000);
        } else {
            // Hijack the button to resolve
            btns.querySelector('button').onclick = () => {
                closeGlobalModal();
                resolve();
            };
        }
    });
};

window.showConfirm = function (message) {
    return new Promise((resolve) => {
        const modal = document.getElementById('globalModal');
        const content = document.getElementById('globalModalContent');
        const icon = document.getElementById('globalModalIcon');
        const title = document.getElementById('globalModalTitle');
        const msg = document.getElementById('globalModalMessage');
        const btns = document.getElementById('globalModalButtons');

        icon.className = 'mx-auto mb-4 w-16 h-16 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center';
        icon.innerHTML = '<svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
        title.innerText = 'Confirm Action';
        msg.innerText = message;

        btns.innerHTML = `
            <button id="modalDataCancel" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors">Cancel</button>
            <button id="modalDataConfirm" class="px-4 py-2 bg-theme-red hover:bg-red-800 text-white font-semibold rounded-lg transition-colors shadow-lg shadow-red-200">Confirm</button>
        `;

        document.getElementById('modalDataCancel').onclick = () => {
            closeGlobalModal();
            resolve(false);
        };
        document.getElementById('modalDataConfirm').onclick = () => {
            closeGlobalModal();
            resolve(true);
        };

        openGlobalModal();
    });
};

function openGlobalModal() {
    const modal = document.getElementById('globalModal');
    const content = document.getElementById('globalModalContent');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeGlobalModal() {
    const modal = document.getElementById('globalModal');
    const content = document.getElementById('globalModalContent');
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}

// API Helper
async function apiRequest(url, options = {}) {
    const defaultHeaders = {
        'X-Requested-With': 'XMLHttpRequest'
    };
    if (!(options.body instanceof FormData)) {
        defaultHeaders['Content-Type'] = 'application/json';
    }
    options.headers = { ...defaultHeaders, ...options.headers };

    try {
        const response = await fetch(url, options);
        const data = await response.json();
        if (!response.ok || data.error) {
            throw new Error(data.error || 'Server error');
        }
        return data;
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}
