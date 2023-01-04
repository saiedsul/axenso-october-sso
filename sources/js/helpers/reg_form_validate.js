window.goNext = function goNext() {
    const nextTabLinkEl = $('.nav-tabs .active').closest('li').next('li').find('a')[0];
    const nextTab = new bootstrap.Tab(nextTabLinkEl);
    const isValid = $("#regForm").valid();
    if (isValid) {
        nextTab.show();
    }
}
$(document).ready(function() {

    $.validator.addMethod("notEqualTo", function(value, element, param){
        return this.optional(element) || value != param;
    },'Disciplina già selezionata');


    $('.btnNext').click(function() {
        const nextTabLinkEl = $('.nav-tabs .active').closest('li').next('li').find('a')[0];
        const nextTab = new bootstrap.Tab(nextTabLinkEl);
        const isValid = $("#regForm").valid();
        if (isValid) {
            nextTab.show();
        }
      });
      $('.btnPrevious').click(function() {
        const prevTabLinkEl = $('.nav-tabs .active').closest('li').prev('li').find('a')[0];
        const prevTab = new bootstrap.Tab(prevTabLinkEl);
        prevTab.show();
      });



    $("#regForm").validate({
        rules: {
            title: "required",
            first_name: "required",
            last_name: "required",
            email: {
                required: true,
                email : true
            },
            email_confirmation : {
                required : true,
                email : true,
                equalTo : '[name="email"]'
            },

            password : {
                required : true,
                minlength: 8,
            },
            password_confirmation : {
                required : true,
                equalTo : '[name="password"]'
            },
            profession : {
                required : true,
            },
            specialization : {
                required : true,
                notEqualTo: function() {return $('select[name="specialization_2"]').val();},
                notEqualTo : function() {return $('select[name="specialization_3"]').val();},
            },
            sub_specialization : {
                required : true,
            },
            specialization_2 : {
                notEqualTo : function() {return $('select[name="specialization"]').val();},
            },
            specialization_3 : {
                notEqualTo : function() {return $('select[name="specialization_2"]').val();},
            },
            employment : {
                required : true,
            },
            province_enployment : {
                required : true,
            },
            board_number : {
                required : true,
                minlength : 3
            },
            board_member : {
                required:true,
            },
            date_of_birth : {
                required : true,
            },
            address_number : "required"
        },
        messages: {
            first_name: "Campo obbligatorio",
            last_name : "Campo obbligatorio",
            title : "Campo obbligatorio",
            address_number : "Campo obbligatorio",
            password : {
                "minlength" : "La password deve contenere almeno 8 caratteri"
            },
            board_number : {
                "minlength" : "Inserire almeno 3 caratteri"
            },
            email : {
                'email' : 'Inserire un indirizzo email valido',
            },
            email_confirmation : {
                'equalTo' : "L'indirizzo email non corrisponde",
            },
            password_confirmation : {
                'equalTo' : 'La password non corrisponde'
            },
            specialization : {
                'notEqualTo' : 'Disciplina già selezionata'
            },
            specialization_2 : {
                'notEqualTo' : 'Disciplina già selezionata'
            },
            specialization_2 : {
                'notEqualTo' : 'Disciplina già selezionata'
            }
        },
        errorPlacement: function(error,element) {
         //   return false;
            if (element.attr("name") == "address_number")
                {
                    //
                }
                else
                {
                    error.insertAfter(element);
                }
          }
    });




});
