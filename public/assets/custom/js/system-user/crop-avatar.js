$(document).ready(function () {
    let croppieInstance = null;
    let activeForm = null;
    let selectedImage = null;
    const defaultAvatar = $('#avatarPreview').data('default-avatar');
    $(document).on('change', '#avatar_url, #edit_avatar_url', function () {
        const file = this.files[0];
        if (!file) return;
        activeForm = this.id === 'avatar_url' ? 'create' : 'edit';
        const reader = new FileReader();
        reader.onload = function (e) {
            selectedImage = e.target.result;
            $('#cropModal').modal('show');
        };
        reader.readAsDataURL(file);
    });

    $('#cropModal').on('shown.bs.modal', function () {
        if (!selectedImage) return;

        if (croppieInstance) croppieInstance.croppie('destroy');

        croppieInstance = $('#cropperArea').croppie({
            viewport: { width: 200, height: 200, type: 'circle' },
            boundary: { width: 300, height: 300 },
            enableExif: true,
            enableZoom: true
        });

        croppieInstance.croppie('bind', {
            url: selectedImage
        });
    });

    $('#cropImageBtn').on('click', function () {
        if (!croppieInstance) return;

        croppieInstance.croppie('result', {
            type: 'base64',
            size: 'viewport',
            format: 'png',
            quality: 1
        }).then(function (base64) {
            if (activeForm === 'create') {
                $('#avatarPreview').attr('src', base64);
                if (!$('#cropped_avatar').length) {
                    $('<input>', {
                        type: 'hidden',
                        id: 'cropped_avatar',
                        name: 'cropped_avatar'
                    }).appendTo('#createUserForm');
                }
                $('#cropped_avatar').val(base64);
            } else {
                $('#editAvatarPreview').attr('src', base64);
                if (!$('#edit_cropped_avatar').length) {
                    $('<input>', {
                        type: 'hidden',
                        id: 'edit_cropped_avatar',
                        name: 'edit_cropped_avatar'
                    }).appendTo('#editUserForm');
                }
                $('#edit_cropped_avatar').val(base64);
            }

            $('#cropModal').modal('hide');
        });
    });

    $('#cropModal').on('hidden.bs.modal', function () {
        if (croppieInstance) croppieInstance.croppie('destroy');
        croppieInstance = null;
        selectedImage = null;
        $('#cropperArea').empty();
        if (activeForm === 'create') {
            if (!$('#cropped_avatar').length || !$('#cropped_avatar').val()) {
                $('#avatar_url').val('');
            }
        } else {
            if (!$('#edit_cropped_avatar').length || !$('#edit_cropped_avatar').val()) {
                $('#edit_avatar_url').val('');
            }
        }
        activeForm = null;
    });
    $('#inlineForm, #editModal').on('hidden.bs.modal', function () {
        const preview = $(this).find('img[id$="AvatarPreview"]');
        const fileInput = $(this).find('input[type="file"]');
        const hiddenInput = $(this).find('input[id$="cropped_avatar"]');
        preview.attr('src', defaultAvatar);
        fileInput.val('');
        hiddenInput.remove();
    });
});
