$(document).ready(function () {
    $.ajax({
        beforeSend: function(){
            NProgress.start();
        },
        complete: function(){
            NProgress.done();
        }
    });

    const apiUrl = 'http://localhost/creative-notes/api/web/api/note';

    $.ajax({
        url: apiUrl,
        method: 'GET'
    }).then(function (response) {
        $('#notes').html(response);
    })
});