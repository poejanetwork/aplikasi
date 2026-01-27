(function ($) {
    "use strict";

    $(document).on('submit', '#formLogin', function (e) {
        e.preventDefault();
        let form = $(this);
        $.ajax({
            url: window.location.origin + '/login',
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.status === true) {
                    showToast('success', res.message);
                    // swalSuccess(res.message).then(() => {
                        window.location.href = window.location.origin + '/admin';
                    // });
                } else {
                    // swalError(res.message, 'Tutup')
                    showToast('error', res.message);
                }
            },
            error: function (xhr) {
                let msg = 'Terjadi kesalahan server';
                try {
                    let res = JSON.parse(xhr.responseText);
                    if (res.message) msg = res.message;
                } catch (e) {}

                showToast('error', msg);
                console.error(xhr.responseText);
            }
        });
    });

})(jQuery);