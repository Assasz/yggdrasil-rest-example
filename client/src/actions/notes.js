/**
 * Init action
 */
app.register('init', 'no-event', function () {
    $(document).tooltip({ selector: '[data-toggle="tooltip"]'});

    const normalizer = function(value) {
        return $.trim(value);
    };

    $("#create_form, #edit_form").validate({
        rules: {
            title: {
                required: true,
                maxlength: 255,
                normalizer: normalizer
            },
            content: {
                required: true,
                maxlength: 1000,
                normalizer: normalizer
            }
        },
        onkeyup: false,
        errorClass: "is-invalid",
        validClass: "is-valid",
        errorElement: "p",
        errorPlacement: function(error, element) {
            error.appendTo(element.parent());
        }
    });
}).run();

/**
 * List notes action
 */
app.register('listNotes', 'no-event', function () {
    app.use('yjax').get({
        action: 'Note:all',
        success: function (response) {
            $('#notes').html(response.html);
        }
    });
}).run();

/**
 * Search notes action
 */
app.register('searchNotes', 'keyup propertychange', function () {
    let delay = app.retrieve('delay');

    if (null === delay) {
        delay = (function(){
            let timer = 0;

            return function(callback, ms){
                clearTimeout (timer);
                timer = setTimeout(callback, ms);
            };
        })();

        app.store('delay', delay);
    }

    let data = {searchTerm: $(this).val()};

    delay(function () {
       app.use('yjax').post({
           action: 'Note:search',
           data: data,
           success: function (response) {
               $('#notes').html(response.html);
           }
       });
    }, 500);
}).run();

/**
 * Create note action
 */
app.register('createNote', 'click', function () {
    if($("#create_form").valid()){
        let data = {
            title: $('#create_form #title').val(),
            content: $('#create_form #content').val()
        };

        app.use('yjax').post({
            action: 'Note:create',
            data: data,
            success: function (response) {
                $('#notes .card-columns').prepend(response.html);
                $('#create_modal').modal('hide');
            }
        });
    }
}).run();

/**
 * Edit note action
 */
app.register('editNote', 'click', function () {
    if($('#edit_form').valid()) {
        let data = {
            title: $('#title_edit').val(),
            content: $('#content_edit').val()
        };

        app.use('yjax').put({
            action: 'Note:edit',
            data: data,
            params: [app.retrieve('noteId')],
            success: function (response) {
                $('#' + app.retrieve('noteId')).replaceWith(response.html);
                $('#edit_modal').modal('hide');
            }
        });
    }
}).run();

/**
 * Delete note action
 */
app.register('deleteNote', 'click', function () {
    app.use('yjax').delete({
        action: 'Note:delete',
        params: [app.retrieve('noteId')],
        success: function () {
            $('#' + app.retrieve('noteId')).remove();
            $('#delete_modal').modal('hide');
        }
    });
}).run();

/**
 * Toggle modal action
 */
app.register('toggleModal', 'click', function () {
    let target = $(this).data('target');

    $('#' + target).modal();
    $('.form-control').removeClass('is-valid is-invalid');

    if($(this).is("[data-note-id]")){
        app.store('noteId', $(this).data('note-id'));

        if(target === 'edit_modal'){
            app.use('yjax').get({
                action: 'Note:get',
                params: [app.retrieve('noteId')],
                success: function (response) {
                    $('#title_edit').val(response.note.title);
                    $('#content_edit').val(response.note.content);
                }
            });
        }
    }
}).run();

/**
 * Error action
 */
app.register('error', 'no-event', function () {
    app.use('yjax').onError(function(event, jqXHR) {
        let response = JSON.parse(jqXHR.responseText);

        if (typeof response.error !== 'undefined') {
            console.error(response.error.message);

            return;
        }

        if(!$('#app_error').length) {
            $('#app_header').after(response.html);
        } else {
            $('#app_error').replaceWith(response.html);
        }
    });
}).run();