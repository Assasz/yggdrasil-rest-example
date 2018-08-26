let currentNoteId;

/**
 * List notes action
 */
app.register('listNotes', function () {
    app.use('yjax').get('API:Note:items', [], function (response) {
        $('#notes').html(response);
    });
}).run();

/**
 * Search notes action
 */
app.register('searchNotes', function () {
    const delay = (function(){
        let timer = 0;

        return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();

    $('[data-action="search-notes"]').on('keyup propertychange', function () {
        let data = {searchTerm: $(this).val()};

        delay(function () {
           app.use('yjax').post('API:Note:search', data, [], function (response) {
               $('#notes').html(response);
           });
        }, 500);
    });
}).run();

/**
 * Create note action
 */
app.register('createNote', function () {
    $('[data-action="create-note"]').click(function () {
        if($("#create_form").valid()){
            let data = {
                title: $('#create_form #title').val(),
                content: $('#create_form #content').val()
            };

            app.use('yjax').post('API:Note:create', data, [], function (response) {
                $('#notes .card-columns').prepend(response);
                $('#create_modal').modal('hide');
            });
        }
    });
}).run();

/**
 * Edit note action
 */
app.register('editNote', function () {
    $(document).on('click', '[data-action="edit-note"]', function () {
        if($('#edit_form').valid()) {
            let data = {
                title: $('#title_edit').val(),
                content: $('#content_edit').val()
            };

            app.use('yjax').put('API:Note:edit', data, [currentNoteId], function (response) {
                $('#' + currentNoteId).replaceWith(response);
                $('#edit_modal').modal('hide');
            });
        }
    });
}).run();

/**
 * Delete note action
 */
app.register('deleteNote', function () {
    $(document).on('click', '[data-action="delete-note"]', function () {
        app.use('yjax').delete('API:Note:item', [currentNoteId], function () {
            $('#' + currentNoteId).remove();
            $('#delete_modal').modal('hide');
        });
    });
}).run();

/**
 * Toggle modal action
 */
app.register('toggleModal', function () {
    $(document).on('click', '[data-action="toggle-modal"]', function () {
        let target = $(this).data('target');

        $('#' + target).modal();
        $('.form-control').removeClass('is-valid is-invalid');

        if($(this).is("[data-note-id]")){
            currentNoteId = $(this).data('note-id');

            if(target === 'edit_modal'){
                app.use('yjax').get('API:Note:item', [currentNoteId], function (response) {
                    $('#title_edit').val(response[0].title);
                    $('#content_edit').val(response[0].content);
                });
            }
        }
    });
}).run();

/**
 * Validate forms action
 */
app.register('validateForms', function () {
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

    $("#create_form").validate(validationOptions);
    $("#edit_form").validate(validationOptions);
}).run();

/**
 * Error action
 */
app.register('error', function () {
    const debug = true;

    app.use('yjax').onError(function(event, jqXHR) {
        let response = JSON.parse(jqXHR.responseText);

        if(jqXHR.status === 500){
            if (debug) {
                console.error(response.error.message);
            }

            response =
                '<div id="app_error" class="alert alert-dismissible alert-primary mt-4">' +
                    '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                    '<p id="error_message" class="mb-0">Internal server error.</p>' +
                '</div>';
        }

        if(!$('#app_error').length) {
            $('#app_header').after(response);
        } else {
            $('#app_error').replaceWith(response);
        }
    });
}).run();