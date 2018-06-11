$(document).ajaxStart(function() {
    NProgress.start();
});

$(document).ajaxComplete(function() {
    NProgress.done();
});

$(document).ajaxError(function(event, jqXHR) {
    var error = jqXHR.responseText;

    if(jqXHR.status == 500){
        error = '<div id="app_error" class="alert alert-dismissible alert-primary mt-4">' +
            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
            '<p id="error_message" class="mb-0">Internal server error.</p>' +
            '</div>';
    }

    if(!$('#app_error').length) {
        $(error).insertAfter($('#app_header'));
    } else {
        $('#app_error').replaceWith(error);
    }
});

$(document).ready(function () {
    const apiUrl = 'http://localhost/creative-notes/api/web/api/note',
          validationOptions = {
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

    var currentNoteId,
        delay = (function(){
            var timer = 0;

            return function(callback, ms){
                clearTimeout (timer);
                timer = setTimeout(callback, ms);
            };
        })();;

    $("#create_form").validate(validationOptions);

    $.ajax({
        url: apiUrl,
        method: 'GET'
    }).then(function (response) {
        $('#notes').html(response);
    });

    $('[data-action="search-notes"]').on('keyup propertychange', function () {
        var data = {
            searchTerm: $(this).val()
        };

        delay(function () {
            $.ajax({
                url: apiUrl + '/search',
                method: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json'
            }).then(function (response) {
                $('#notes').html(response);
            });
        }, 500);
    });

    $(document).on('click', '[data-action="toggle-modal"]', function () {
       $('#' + $(this).data('target')).modal();

       if($(this).is("[data-noteid]")){
           currentNoteId = $(this).data('noteid');
       }
    });

    $('[data-action="create-note"]').click(function () {
        if($("#create_form").valid()){
            var data = {
                title: $('#create_form #title').val(),
                content: $('#create_form #content').val()
            };

            $.ajax({
                url: apiUrl + '/create',
                method: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json'
            }).then(function (response) {
                $('#notes .card-columns').prepend(response);
                $('#create_modal').modal('hide');
            });
        }
    });

    $(document).on('click', '[data-action="delete-note"]', function () {
        $.ajax({
            url: apiUrl + '/remove/' + currentNoteId,
            method: 'DELETE'
        }).then(function (response) {
            $('#' + currentNoteId).remove();
            $('#delete_modal').modal('hide');
        });
    });
});