function postForm( $form, callback, error ){

    var values = {};
    $.each( $form.serializeArray(), function(i, field) {
        // If a same name is encountered several times,
        // an array that contains all data is created instead
        if (typeof values[field.name] !== 'undefined') {
            if (values[field.name].constructor === Array) {
                values[field.name].push(field.value);
            } else {
                values[field.name] = [values[field.name], field.value];
            }
        } else {
            values[field.name] = field.value;
        }
    });

    $.ajax({
        type        : $form.attr( 'method' ),
        url         : $form.attr( 'action' ),
        data        : values,
        dataType    : 'HTML',
        success     : function(data) {
            callback( data );
        },
        error       : function(XMLHttpRequest, textStatus, errorThrown) {
            error(XMLHttpRequest, textStatus, errorThrown);
        }
    });
}

function alertDanger(text) {
    if (!$('#alert_danger_js').length) { console.log('err: cant find alert_danger'); }
    $('#alert_danger_js').text(text);
    $('#alert_danger_js').delay(250).fadeIn('normal', function() {
        $(this).delay(2000).fadeOut();
    });
}

function alertSuccess(text) {
    if (!$('#alert_success_js').length) { console.log('err: cant find alert_success'); }
    $('#alert_success_js').text(text);
    $('#alert_success_js').delay(250).fadeIn('normal', function() {
        $(this).delay(2000).fadeOut();
    });
}

// parameter route =
// {
//      name: ROUTE_NAME
//      parameters:
//      {
//          PARAMETER_NAME: PARAMETER_VALUE,
//          ..: ...
//      }
// }
ajaxForm = function(route, $modal, callback, formInit) {
    if (!route) {
        console.log("err: no route given");
        return;
    }

    if (!route.name) {
        console.log("err: no route name given");
        return;
    }

    if (!$modal.length) {
        console.log("err: no modal given");
        return;
    }

    var loadingOverlay = "<div id=\"modal_overlay\" class=\"overlay\"> <i class=\"fa fa-refresh fa-spin\"></i> </div>";

    $formEdit = null;

    var showModalAndFillWithData = function(data) {
        $modal.modal('show');
        $modal.find('.modal-body').empty();
        $modal.find('.modal-body').html(data);
        $formEdit = $modal.find('form');

        if (!$formEdit.length) {
            console.log("err: ajax call returned correctly but no form was found");
            return;
        }
    };

    // Post $formEdit in ajax, call the callback with json updatedData returned
    // if it returns a 200 with proper json data, call the callback
    // if it returns a 400, refresh the returned form in the modal. Also binds the new form submit event
    var onSubmit = function(e) {
        e.preventDefault();
        $('#div_modal_content').append(loadingOverlay);
        $modal.find('form').find('input[type=submit]').attr('disabled', 'disabled');

        postForm($formEdit,
            function(response) {
                $modal.find('form').find('input[type=submit]').attr('disabled', 'enabled');
                $('#modal_overlay').remove();
                $modal.modal('hide');

                updatedData = response;

                callback(updatedData);
            },
            function(XMLHttpRequest, textStatus, errorThrown) {
                $modal.find('form').find('input[type=submit]').attr('disabled', 'enabled');
                $('#loading_overlay').remove();
                console.log(XMLHttpRequest);
                console.log(textStatus);
                console.log(errorThrown);

                if (XMLHttpRequest.status === 400) {
                    var newForm = XMLHttpRequest.responseText
                        .replace("HTTP/1.0 200 OK", "")
                        .replace("Cache-Control: no-cache", "");

                    showModalAndFillWithData(newForm);
                    $('#modal_overlay').remove();
                    $formEdit.submit(onSubmit);

                    return;
                }

                alertDanger("Une erreur " + XMLHttpRequest.status + " est survenue sur le serveur");
                $('#modal_overlay').remove();
                $modal.modal('hide');
            }
        );
    };

    $.ajax({
        type: "GET",
        url: Routing.generate(route.name, route.parameters),
        data: { },
        dataType: 'HTML',
        success: function(data, dataType) {
            showModalAndFillWithData(data);

            if (formInit) {
                formInit();
            }

            $formEdit.submit(onSubmit);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            console.log("err: " + textStatus);
            console.log(errorThrown);
            console.log(XMLHttpRequest);
            console.log(route.name);
            console.log(route.parameters);
            alertDanger("Une erreur serveur est survenue lors de la récupération du formulaire");
        }
    });
};
