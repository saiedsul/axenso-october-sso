window.customfiscal = function customfiscal(){
    if ($('#formCheck-customfiscal').is(':checked')) {
        $('#custom_fiscal').val(1)
    } 
    else {
        $('#custom_fiscal').val(0)

    }
}
$(document).ready(function() {
  
    $("#loginForm").validate({
        rules: {

            email: {
                required: true,
                email : true
            },
            password : {
                required : true,

            },
        },
        messages: {
            email : {
                'email' : 'Inserire un indirizzo email valido',
            },
        },
    });

    $('#resetForm').validate({
        rules: {
            email: {
                required: true,
                email : true
            }
        },
        messages: {
            email : {
                'email' : 'Inserire un indirizzo email valido',
            },
        },
    });
    $('#PasswordResetForm').validate({
        rules: {
            password: {
                required: true,
                minlength: 8,
            },
            password_confirmation : {
                required: true,
                equalTo : '[name="password"]'
            }
        },
        messages: {
            password_confirmation : {
                equalTo : 'La password non corrisponde'
            }
        },
    });
});
