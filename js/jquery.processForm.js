var resultCallback = null;

(function($){
    $.fn.extend({
        processForm: function(callBack, errorCallBack){
            var $this = $(this)[0];
            var $form = $( '#' + $this.id )[0];

            var l = Ladda.create(document.querySelector( '#' + $form.id + ' button[type="submit"]' ));
            l.start();

            $('span.help-block').html('');
            $('.has-error').removeClass('has-error');

            $.ajax({
                data:  $('#' + $form.id).serialize(),
                url:   $form.action,
                type:  'post',
                dataType: 'json',
                success:  function (result) {
                    
                    resultCallback = result;

                    l.stop();

                    if(result.error == 1 || result.error == 2){
                        for(var indice in result.messages){
                            $('#' + indice).addClass('has-error');
                            $('#' + indice + ' .help-block').html(result.messages[indice]);
                        }
                        
                        if(errorCallBack != undefined)
                            return errorCallBack.call();
                        
                        return false;
                    }

                    if (typeof callBack == 'function')
                        return callBack.call();
                }
            });
        }
    });
})( jQuery );