<?php return [
    'plugin' => [
        'name' => 'Axen SSO',
        'description' => 'Provides SSO functionality for users.',
    ],
    'messages' => [
        'login' => [
            'incorrect' => '<p class="mb-0"><strong>Ops, le informazioni inserite non risultano corrette </strong> </p><p class="mb-0">La invitiamo a riprovare oppure a contattarci all\'indirizzo <a href="mailto:support@axen.com">support@axen.com</a> </p>',
            'inactive' => '<p class="mb-0">Il suo account non risulta essere attivo <p class="mb-0"> La preghiamo di verificare nella sua casella di posta e di cliccare sul link che trova nell\'email di conferma registrazione.</p><p class="mb-0"> Per eventuali chiarimenti non esiti a contattarci all\'indirizzo <a href="mailto:support@axen.com">support@axen.com</a></p>',
            'underverification' => '<p class="mb-0">Account in fase di verifica </p>',
        ],
        'resetpassword' => [
            'incorrect' => '<p class="mb-0">Ops, l\'indirizzo indicato non è presente, ti invitiamo a <a href="/register">registrarti</a> come nuovo utente</p>',
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
            'invalid_link' => '<p class="mb-0">Il link non è più valido, ti preghiamo di <a style=\'color:#9f0247\' href=\'/recupera-password\'>provare di nuovo</a></p>',
            'ok' => '<h1 class=\'msg\' >Password aggiornata con successo</h1><p><a href=\'/login\' class=\'btn btn-primary btn-lg d-block w-100 btn-orange\' >Vai al login</a></p>',
        ],
        'generic' => '<p class="mb-0">ops, qualcosa è andato storto :( ti preghiamo di riprovare più tardi</p>',
    ],
];
