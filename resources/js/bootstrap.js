/**
 * JavaScript Bootstrap File — Sets up frontend libraries.
 *
 * This file initializes:
 * 1. Alpine.js — A lightweight JS framework for interactive UI
 *    (modals, toggles, dynamic forms) without building a full SPA.
 * 2. Alpine Focus plugin — Traps keyboard focus inside modals (accessibility).
 * 3. Axios — HTTP client for making AJAX requests (not heavily used here,
 *    but available as window.axios for any future AJAX needs).
 */
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus'
import axios from 'axios';

// Make Axios available globally and set the X-Requested-With header
// so Laravel can identify AJAX requests
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Make Alpine available globally (useful for debugging in browser console)
window.Alpine = Alpine

// Register the Focus plugin (enables x-trap directive for modal focus trapping)
Alpine.plugin(focus)

// Start Alpine.js — this activates all x-data, x-show, @click etc. on the page
Alpine.start()
 
