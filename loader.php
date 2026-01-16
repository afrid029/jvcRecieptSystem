<!-- Global Loader Component -->
<div id="global-loader"
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-900/40 backdrop-blur-[2px] hidden transition-opacity duration-300 opacity-0">
    <div
        class="bg-white p-6 rounded-2xl shadow-2xl border border-sky-100 flex flex-col items-center gap-4 transform scale-95 transition-transform duration-300">
        <div class="relative w-16 h-16">
            <!-- Sleek Animated Spinner -->
            <div class="absolute inset-0 border-4 border-sky-100 rounded-full"></div>
            <div
                class="absolute inset-0 border-4 border-t-sky-600 border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin">
            </div>
        </div>
        <div class="flex flex-col items-center text-center">
            <span class="text-slate-900 font-bold text-lg">Loading...</span>
            <p class="text-slate-500 text-sm">Please wait a moment</p>
        </div>
    </div>
</div>

<style>
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }
</style>

<script>
    const loader = {
        el: document.getElementById('global-loader'),
        container: document.getElementById('global-loader').querySelector('div'),

        show: function () {
            this.el.classList.remove('hidden');
            // Force reflow
            this.el.offsetHeight;
            this.el.classList.add('opacity-100');
            this.el.classList.remove('opacity-0');
            this.container.classList.remove('scale-95');
            this.container.classList.add('scale-100');
        },

        hide: function () {
            this.el.classList.remove('opacity-100');
            this.el.classList.add('opacity-0');
            this.container.classList.remove('scale-100');
            this.container.classList.add('scale-95');
            setTimeout(() => {
                this.el.classList.add('hidden');
            }, 300);
        }
    };

    // Show on page transition
    window.addEventListener('beforeunload', () => {
        loader.show();
    });

    // Show on form submissions
    document.addEventListener('submit', (e) => {
        // If it's a standard form submit, show loader
        if (!e.defaultPrevented) {
            loader.show();
        }
    });

    // Handle back/forward cache
    window.addEventListener('pageshow', (event) => {
        if (event.persisted) {
            loader.hide();
        }
    });

    // Export to global scope
    window.loading = loader;
</script>