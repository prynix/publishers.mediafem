jQuery.validator.addMethod('passwordCheck', function(password) {
    var parametros = {
        "login": $('#login').val(),
        "password": password
    };
    $.ajax({
        cache: false,
        async: false,
        type: "POST",
        data: parametros,
        url: 'php/check_password.php',
        success: function(msg) {
            result = (msg == 'true') ? true : false;
        }
    });
    return result;
}, '');

jQuery.validator.addMethod('userCheck', function(password) {
    var parametros = {
        "login": $('#email').val()
    };
    $.ajax({
        cache: false,
        async: false,
        type: "POST",
        data: parametros,
        url: 'php/check_user.php',
        success: function(msg) {
            result = (msg == 'true') ? true : false;
        }
    });
    return result;
}, '');

$(document).ready(function() {
    var altura = screen.height - 389;
    $('#index_advertisers').css('height', altura + 'px');
    $('#index_publishers').css('height', altura + 'px');
    $('#index_coffee').css('height', altura + 'px');


    var posSlider = 1;

    setInterval(function(){
        if(posSlider == 1){
            posSlider = 2;

            $('#index_advertisers').fadeOut(500, function(){
                $('#index_publishers').fadeIn(500);
            });
        }else if(posSlider == 2){
            posSlider = 3;

            $('#index_publishers').fadeOut(500, function(){
                $('#index_coffee').fadeIn(500);
            });
        }else if(posSlider == 3){
            posSlider = 1;

            $('#index_coffee').fadeOut(500, function(){
                $('#index_advertisers').fadeIn(500);
            });
        }
    }, 4000);



    Placeholdem( document.querySelectorAll( '[placeholder]' ) );

    var options = {
        useEasing : false,
        useGrouping : false,
        separator : ',',
        decimal : '.'
    }

    var count1 = new countUp("count1", 0, 90, 0, 2.5, options);
    var count2 = new countUp("count2", 0, 100, 0, 2.5, options);
    var count3 = new countUp("count3", 0, 230, 0, 2.5, options);

    $(window).scroll(function() {
        if ($(this).scrollTop() > 150) {
            count1.start();
            count2.start();
            count3.start();
        }
    });


    $("#form_acceso").validate({
        rules: {
            'login': {
                required: true
            },
            'password': {
                required: true,
                passwordCheck: true
            }
        },
        messages: {
            'login': {
                required: "*Enter your username"
            },
            'password': {
                required: "*Enter your password",
                passwordCheck: " Wrong username or password"
            }
        },
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        debug: true,
        errorElement: "label",
        submitHandler: function(form) {
            form.submit();
        }
    });


    $("#form_forgot").validate({
        rules: {
            'email': {
                required: true,
                email: true,
                //userCheck: true
            }
        },
        messages: {
            'login': {
                required: "* Enter your username"
            },
            'password': {
                required: "* Enter your password",
                passwordCheck: "* Wrong username or password"
            }
        },
        debug: true,
        errorElement: "label",
        submitHandler: function(form) {
			form.submit();
            /*var email = $("#email").val();
            var form_data = {
                email: email
            };
            $.ajax({
                cache: false,
                async: true,
                type: "POST",
                data: form_data,
                url: 'http://publishers.adtomatik.com/forgot_password',
                success: function(msg){
					
					console.log(msg);
					
                    $('#form_forgot').fadeOut('500', function(){
                        $('#form_forgot_Ok').fadeIn('500');
                    });
                }
            });*/
        }
    });

    $('a[data-reveal-id="forgot_password"]').click(function(){
        $('#form_forgot_Ok').fadeOut('500', function(){
            $('#form_forgot').fadeIn('500');
        });
    });


    function getVarsUrl(){
        var url= location.search.replace("?", "");
        var arrUrl = url.split("&");
        var urlObj={};
        for(var i=0; i<arrUrl.length; i++){
            var x= arrUrl[i].split("=");
            urlObj[x[0]]=x[1]
        }
        return urlObj;
    }

    var get = getVarsUrl();

    switch(get.a) {
        case 'A':
        case 'R':
        case 'P':
            $('a[data-reveal-id="get_Ok"]').click();
            break;
        case 'I':
        case 'new_password_failed':
        case 'ban':
            $('a[data-reveal-id="get_Error"]').click();
            break;
    }
});