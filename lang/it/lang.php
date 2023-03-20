<?php return [
    'plugin' => [
        'name' => 'Axen SSO',
        'description' => 'Provides SSO functionality for users..',
    ],
    'messages' => [
        'resetpassword' => [
            'email-not-found' => 'Ops, l\'indirizzo indicato non è presente, ti invitiamo a <a href="/register">registrarti</a> come nuovo utente', //done
            'email-required' => 'Il campo dell\'indirizzo email è obbligatorio',//done
        ],
        'login' => [
            'email-not-found' => 'Ops, l\'indirizzo indicato non è presente, ti invitiamo a <a href="/register">registrarti</a> come nuovo utente', //done
            'incorrect-login' => 'Ops, le informazioni inserite non risultano corrette <br><br> La invitiamo a riprovare oppure a contattarci all\'indirizzo <a href="mailto:xgate@axenso.com">xgate@axenso.com</a> ', //done
            'user-inactive' => 'Il suo account non risulta essere attivo <br><br> La preghiamo di verificare nella sua casella di posta e di cliccare sul link che trova nell\'email di conferma registrazione.<br><br/> Per eventuali chiarimenti non esiti a contattarci all\'indirizzo <a href="mailto:xgate@axenso.com">xgate@axenso.com</a>',//done
            'user-unverfied' => 'Account in fase di verifica',//dpne
        ],
        'register' => [
            'emailtaken' => '<p class="mb-0">L\'email inserita risulta già registrata</p><p class="mb-0">La preghiamo di effettuare il login con le sue credenziali oppure di recuperare la password </p>',
            'emailrequired' => '<p class="mb-0">Gli indirizzi email inseriti non coincidono, la preghiamo di tornare indietro e verificare </p>',
            'passwordrequired' => '<p class="mb-0">Gli password inseriti non coincidono, la preghiamo di tornare indietro e verificare </p>',
        ],
        'validation' => [
            'required' => [
                'email' => '<p class="mb-0">Il campo Email è richiesto</p>',
                'password' => '<p class="mb-0">Il campo Password è richiesto</p>',
                'name' => '<p class="mb-0">Nome non valido</p>',
                'lastname' => '<p class="mb-0">Cognome non valido</p>',
                'profession' => '<p class="mb-0">Seleziona "Professione"</p>',
                'specialization' => '<p class="mb-0">Seleziona specializzazione</p>',
                'board_number' => '<p class="mb-0">N. Iscrizione Ordine non valido</p>',
            ],
            'confirmed' => [
                'email' => '',
                'password' => '<p class="mb-0">Le password non coincidono, la preghiamo di verificare </p>',
            ],
        ],
        'reset_password' => [
            'invalid_link' => 'Il link non è più valido, ti preghiamo di <a href=\'/recupera-password\'>provare di nuovo</a></p>', // done
        ],
        'generic' => 'ops, qualcosa è andato storto :( ti preghiamo di riprovare più tardi',
    ],
];
