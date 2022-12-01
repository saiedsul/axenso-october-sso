<?php return [
    "plugin" => [
        "name" => "Axen SSO",
        "description" => "Provides SSO functionality for users"
    ],
    "messages" => [
        "login" => [
            "incorrect" => "Ops, le informazioni inserite non risultano corrette <br><br> La invitamo a riprovare oppure a contattarci all`indirizzo support@axen.com",
            "inactive" => "Il suo account non risulta essere attivo <br><br> La preghiamo di verificare nella sua casella di posta e di cliccare sul link che trova nell'email di conferma registrazione. <br> Per eventuali chiarimenti non esiti a contattarci all'indirizzo support@axen.com",
            "underverification" => "Account in fase di verifica",
        ],
        "register" => [
            "emailtaken" => "L'email inserita risulta già registrata. La preghiamo di effettuare il login con le sue credenziali oppure di recuperare la password.",
            "emailrequired" => "Gli indirizzi email inseriti non coincidono, la preghiamo di tornare indietro e verificare.",
            "passwordrequired" => "Gli password inseriti non coincidono, la preghiamo di tornare indietro e verificare",

        ],
        "validation" => [
            "required" => [
                "email" => "Il campo Email è richiesto.",
                "password" => "Il campo Password è richiesto",
                "name" => "Nome non valido",
                "lastname" => "Cognome non valido",
                "profession" => "Professione non valida",
                "specialization" => "Specializzazione non valida",
                "board_number" => "N. Iscrizione Ordine non valido"
            ],
            "confirmed" => [
                "email" => "",
                "password" => "Conferma della password non valida, la preghiamo di tornare indietro e verificare"
            ]
        ],
        "reset_password" => [
            "invalid_link" => "Il link non è più valido, ti preghiamo di <a style='color:#9f0247' href='/recupera-password'>provare di nuovo</a>",
            "ok" => "<h1 class='msg' >Password aggiornata con successo</h1><p><a href='/login' class='btn btn-primary btn-lg d-block w-100 btn-orange' >Vai al login</a></p>"
        ],

        "generic" => "ops, qualcosa è andato storto :( ti preghiamo di riprovare più tardi",
    ]
];
