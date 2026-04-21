@extends('layouts.app')
@section('title', 'Réunion — ' . ($user->isMentor() ? $session->mentee->name : $session->mentor->name))

@section('content')

{{-- En-tête de la réunion --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-extrabold text-oxford flex items-center gap-3">
            <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-video text-white text-sm"></i>
            </div>
            Réunion en cours
        </h1>
        <p class="text-gray-500 text-sm mt-1">
            Session du {{ $session->date->format('d/m/Y') }}
            de {{ $session->start_time }} à {{ $session->end_time }}
            — avec
            <span class="font-semibold text-oxford">
                {{ $user->isMentor() ? $session->mentee->name : $session->mentor->name }}
            </span>
        </p>
    </div>
    <a href="{{ route('sessions.index') }}"
       class="flex items-center gap-2 text-sm text-gray-500 hover:text-oxford transition border border-gray-200 px-4 py-2 rounded-xl">
        <i class="fa-solid fa-arrow-left text-xs"></i>
        Mes sessions
    </a>
</div>

{{-- Bandeau d'info --}}
<div class="bg-blue-50 border border-blue-200 rounded-2xl px-5 py-3 mb-6 flex items-center gap-3 text-sm text-blue-700">
    <i class="fa-solid fa-circle-info text-blue-400 flex-shrink-0"></i>
    <span>
        La réunion s'ouvre directement ci-dessous. Autorisez l'accès à votre caméra et microphone si demandé.
        Vous pouvez aussi
        <a href="{{ $roomUrl }}" target="_blank" rel="noopener noreferrer"
           class="font-semibold underline hover:text-blue-900">
            l'ouvrir dans un nouvel onglet
        </a>.
    </span>
</div>

{{-- Conteneur Jitsi --}}
<div class="bg-oxford rounded-2xl overflow-hidden shadow-xl" style="height: 620px;">
    <div id="jitsi-container" class="w-full h-full"></div>
</div>

{{-- Infos de la salle --}}
<div class="mt-4 flex items-center justify-between text-xs text-gray-400">
    <span>
        <i class="fa-solid fa-lock mr-1"></i>
        Salle privée — accès réservé aux participants de cette session
    </span>
    <span class="font-mono bg-gray-100 px-3 py-1 rounded-lg text-gray-500">
        {{ $session->meeting_room_id }}
    </span>
</div>

@endsection

@push('scripts')
<script src="{{ $serverUrl }}/external_api.js"></script>
<script>
    (function () {
        const serverUrl  = @json($serverUrl);
        const roomName   = @json($session->meeting_room_id);
        const userName   = @json($user->name);
        const userEmail  = @json($user->email);

        // Extraire le domaine depuis l'URL du serveur (ex: "meet.jit.si")
        const domain = serverUrl.replace(/^https?:\/\//, '');

        const options = {
            roomName:  roomName,
            width:     '100%',
            height:    '100%',
            parentNode: document.getElementById('jitsi-container'),
            lang: 'fr',
            userInfo: {
                displayName: userName,
                email:       userEmail,
            },
            configOverwrite: {
                startWithAudioMuted:  false,
                startWithVideoMuted:  false,
                disableDeepLinking:   true,
                enableWelcomePage:    false,
                prejoinPageEnabled:   false,
            },
            interfaceConfigOverwrite: {
                SHOW_JITSI_WATERMARK:       false,
                SHOW_WATERMARK_FOR_GUESTS:  false,
                TOOLBAR_BUTTONS: [
                    'microphone', 'camera', 'closedcaptions', 'desktop',
                    'fullscreen', 'fodeviceselection', 'hangup', 'chat',
                    'raisehand', 'videoquality', 'filmstrip', 'tileview',
                ],
            },
        };

        try {
            const api = new JitsiMeetExternalAPI(domain, options);

            // Quand l'utilisateur raccroche, rediriger vers ses sessions
            api.addEventListener('readyToClose', function () {
                window.location.href = @json(route('sessions.index'));
            });
        } catch (e) {
            document.getElementById('jitsi-container').innerHTML =
                '<div class="flex flex-col items-center justify-center h-full text-white gap-4">' +
                    '<i class="fa-solid fa-triangle-exclamation text-orange text-4xl"></i>' +
                    '<p class="font-semibold text-lg">Impossible de charger la réunion</p>' +
                    '<p class="text-vista text-sm">Vérifiez votre connexion ou ouvrez la réunion dans un nouvel onglet.</p>' +
                    '<a href="' + @json($roomUrl) + '" target="_blank" ' +
                       'class="bg-orange text-oxford font-bold px-6 py-2.5 rounded-xl hover:opacity-90 transition text-sm">' +
                        '<i class="fa-solid fa-arrow-up-right-from-square mr-2"></i>Ouvrir dans un nouvel onglet' +
                    '</a>' +
                '</div>';
        }
    })();
</script>
@endpush
