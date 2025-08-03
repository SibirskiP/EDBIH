<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chat</title>
    @vite('resources/css/app.css')
    @vite(['resources/js/app.js'])
    @livewireStyles
    {{-- Dodatni stilovi ako su potrebni, ali neka budu minimalni --}}
    <style>
        body {
            font-family: 'Inter', sans-serif;
            /* Osigurava da body zauzima cijelu visinu viewporta i nema margina */
            min-height: 100vh;
            margin: 0;
            /* Postavite pozadinu ovdje da bude globalna, ako želite */
            background: linear-gradient(to bottom right, #EFF6FF, #EEF2FF); /* Suptilan gradijent */
        }
    </style>
</head>
<body class="h-screen overflow-hidden"> {{-- h-screen i overflow-hidden su ključni ovdje --}}
{{ $slot }}
@livewireScripts
</body>
</html>
