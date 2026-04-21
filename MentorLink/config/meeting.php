<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Serveur Jitsi Meet
    |--------------------------------------------------------------------------
    | URL du serveur Jitsi à utiliser. Par défaut : serveur public meet.jit.si
    | Pour un serveur auto-hébergé, définir JITSI_SERVER dans le fichier .env
    |
    */
    'jitsi_server' => env('JITSI_SERVER', 'https://meet.jit.si'),
];
