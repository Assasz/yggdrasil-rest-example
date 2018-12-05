/**
 * List notes action
 */
app.register('listNotes', 'no-event', function () {
    app.use('yjax').get('Note:all', [], function (response) {
        $('#notes').html(response);
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
       app.use('yjax').post('Note:search', data, [], function (response) {
           $('#notes').html(response);
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

        app.use('yjax').post('Note:create', data, [], function (response) {
            $('#notes .card-columns').prepend(response);
            $('#create_modal').modal('hide');
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

        app.use('yjax').put('Note:edit', data, [app.retrieve('noteId')], function (response) {
            $('#' + app.retrieve('noteId')).replaceWith(response);
            $('#edit_modal').modal('hide');
        });
    }
}).run();

/**
 * Delete note action
 */
app.register('deleteNote', 'click', function () {
    app.use('yjax').delete('Note:delete', [app.retrieve('noteId')], function () {
        $('#' + app.retrieve('noteId')).remove();
        $('#delete_modal').modal('hide');
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
            app.use('yjax').get('Note:get', [app.retrieve('noteId')], function (response) {
                $('#title_edit').val(response[0].title);
                $('#content_edit').val(response[0].content);
            });
        }
    }
}).run();

/**
 * Validate forms action
 */
app.register('validateForms', 'no-event', function () {
    const validationOptions = {
        rules: {
            title: {
                required: true,
                maxlength: 255,
                normalizer: function(value) {
                    return $.trim(value);
                }
            },
            content: {
                required: true,
                maxlength: 1000,
                normalizer: function(value) {
                    return $.trim(value);
                }
            }
        },
        onkeyup: false,
        errorClass: "is-invalid",
        validClass: "is-valid",
        errorElement: "p",
        errorPlacement: function(error, element) {
            error.appendTo( element.parent() );
        }
    };

    $("#create_form, #edit_form").validate(validationOptions);
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
            $('#app_header').after(response);
        } else {
            $('#app_error').replaceWith(response);
        }
    });
}).run();