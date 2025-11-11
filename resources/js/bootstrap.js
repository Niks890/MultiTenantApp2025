import $ from 'jquery';
window.$ = window.jQuery = $;

const csrfToken = $('meta[name="csrf-token"]').attr('content');

$.ajaxSetup({
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken 
    },
    
    xhrFields: {
        withCredentials: true
    },
});