<?php
$kategorije=config('mojconfig.kategorije');

?>
@props(['url'])



<div class="mx-auto max-w-screen-sm text-center lg:mb-6 mb-6 sm">
    <h2 class="mb-4 text-3xl lg:text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Materijali</h2>

    <x-dbfilter :categories="$kategorije" buttonText="Kategorija" :index="1" name="kategorija"/>
    <x-searchbar name="naziv"></x-searchbar>


    <button type="submit" class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Primijeni filtere</button>

</div>

