function showPassword() {

    $('.show-pass-btn').each(function() {
        const toggleButton = $(this);
        const input = toggleButton.closest('.input-group').find('input');

        if (input.prop('disabled')) {
            toggleButton.prop('disabled', true);
        }
    });

    $('.show-pass-btn').on('click', function() {
        const toggle = $(this);

        const input = toggle.closest('.input-group').find('input');

        if (input.prop('disabled')) {
            return;
        }

        const icon = toggle.find('i');
        const isPassword = input.attr('type') === 'password';

        input.attr('type', isPassword ? 'text' : 'password');

        icon.toggleClass('fa-eye', !isPassword);
        icon.toggleClass('fa-eye-slash', isPassword);
    })
}

$(function() {
    showPassword();
});

// Example usage:

// Example html structure:
// <div class="input-group">
//     <input type="password" class="form-control" />
//     <button type="button" class="btn btn-secondary show-pass-btn">
//         <i class="fa fa-eye"></i>
//     </button>
// </div>

// Initialize show password functionality
// showPassword();