/**
 * App.js — Global scripts
 * Mobile menu toggle & Toast notification system
 */

document.addEventListener('DOMContentLoaded', function () {
    // --- Mobile Menu Toggle ---
    var menuBtn = document.getElementById('mobileMenuBtn');
    var mobileMenu = document.getElementById('mobileMenu');
    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');
        });
    }
});

/**
 * Lightweight toast notification
 * Usage: showToast('Pesan', 'success') | showToast('Error', 'error') | showToast('Info', 'warning')
 */
function showToast(message, type, duration) {
    type = type || 'info';
    duration = duration || 4000;

    var container = document.getElementById('toastContainer');
    if (!container) return;

    var colors = {
        success: 'bg-green-50 border-green-200 text-green-800',
        error: 'bg-red-50 border-red-200 text-red-800',
        warning: 'bg-amber-50 border-amber-200 text-amber-800',
        info: 'bg-blue-50 border-blue-200 text-blue-800'
    };

    var toast = document.createElement('div');
    toast.className = 'toast-enter px-4 py-3 rounded-lg border text-sm font-medium shadow-sm max-w-sm ' + (colors[type] || colors.info);
    toast.textContent = message;

    container.appendChild(toast);

    setTimeout(function () {
        toast.className = toast.className.replace('toast-enter', 'toast-exit');
        setTimeout(function () {
            toast.remove();
        }, 300);
    }, duration);
}
