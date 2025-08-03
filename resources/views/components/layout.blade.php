<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/app.css')
    @vite(['resources/js/app.js'])

    <!-- Dodaj Livewire stilove -->
    @livewireStyles
</head>
<body class="">
<div class="">
    <x-navigacija></x-navigacija>
    <div class=" ">
        {{$slot}}
    </div>
</div>

<footer class="bg-slate-100 py-6 border-t border-slate-200 text-center text-slate-600 text-sm shadow-inner"> {{-- Promijenjena pozadina, jači border, dodana shadow-inner --}}
    <div class="max-w-screen-xl mx-auto px-4">
        <p>&copy; {{ date('Y') }} Kenan Durakovic. Sva prava pridržana.</p>
    </div>
</footer>

<!-- Dodaj Livewire skripte prije zatvaranja body taga -->
@livewireScripts
</body>
</html>
