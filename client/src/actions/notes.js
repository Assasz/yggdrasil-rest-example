/**
 * Init action
 */
app.register({
    name: 'init',
    callback: function () {
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
    }
}).run();

/**
 * List notes action
 */
app.register({
    name: 'listNotes',
    callback: function () {
        app.use('yjax').get({
            action: 'Note:all',
            success: function (response) {
                $.each(response.notes, function (index, note) {
                    $('#notes').append(app.use('noteHelper').render(note));
                });
            }
        });
    }
}).run();

/**
 * Search notes action
 */
app.register({
    name: 'searchNotes',
    event: 'keyup propertychange',
    callback: function () {
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
                    $('#notes').empty();

                    $.each(response.notes, function (index, note) {
                        $('#notes').append(app.use('noteHelper').render(note));
                    });
                }
            });
        }, 500);
    }
}).run();

/**
 * Create note action
 */
app.register({
    name: 'createNote',
    event: 'click',
    callback: function () {
        if($("#create_form").valid()){
            let data = {
                title: $('#create_form #title').val(),
                content: $('#create_form #content').val()
            };

            app.use('yjax').post({
                action: 'Note:create',
                data: data,
                success: function (response) {
                    $('#notes').prepend(app.use('noteHelper').render(response.note));
                    $('#create_modal').modal('hide');
                }
            });
        }
    }
}).run();

/**
 * Edit note action
 */
app.register({
    name: 'editNote',
    event: 'click',
    callback: function () {
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
                    $('#note_' + app.retrieve('noteId')).replaceWith(app.use('noteHelper').render(response.note));
                    $('#edit_modal').modal('hide');
                }
            });
        }
    }
}).run();

/**
 * Delete note action
 */
app.register({
    name: 'deleteNote',
    event: 'click',
    callback: function () {
        app.use('yjax').delete({
            action: 'Note:delete',
            params: [app.retrieve('noteId')],
            success: function () {
                $('#note_' + app.retrieve('noteId')).remove();
                $('#delete_modal').modal('hide');
            }
        });
    }
}).run();

/**
 * Toggle modal action
 */
app.register({
    name: 'toggleModal',
    event: 'click',
    callback: function () {
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
    }
}).run();

/**
 * Error action
 */
app.register({
    name: 'error',
    callback: function () {
        app.use('yjax').onError(function(event, jqXHR) {
            let response = JSON.parse(jqXHR.responseText);

            if (typeof response.error !== 'undefined') {
                console.error(response.error.message);

                return;
            }

            if(!$('#app_error').length) {
                $('#app_header').after(`
                    <div id="app_error" class="alert alert-dismissible alert-primary mt-4">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <p id="error_message" class="mb-0">${response.message}</p>
                    </div>
                `);
            } else {
                $('#error_message').html(response.message);
            }
        });
    }
}).run();