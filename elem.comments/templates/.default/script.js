document.addEventListener('DOMContentLoaded', () => {

    $('#comments_form').on('submit', function (event) {
        event.stopPropagation();

        var data = $(this).serializeArray();

        BX.ajax.runComponentAction('dev:elem.comments',
            'sendMessage', {
                mode: 'class',
                signedParameters: params.signedParameters,
                data: {post: data},
            })
            .then(function(response) {
                console.log(response)
                if (response.status === 'success' && response.data.STATUS) {
                   alert("Комментарий добавлен")
                }
            });

        return false;
    });



});

