<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/app.css')
</head>
<body>
<div class="">
    <x-navigacija></x-navigacija>
<div class="pb-20">
    {{$slot}}
</div>

</div>
<footer class="bg-white rounded-lg  m-4">
    <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">

<hr class="my-6 sm:mx-auto  lg:my-8" />
<span class="block text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2024 Kenan Durakovic All Rights Reserved.</span>
</div>
</footer>
</body>
</html>
