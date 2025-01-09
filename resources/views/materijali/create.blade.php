<?php

$kategorije=config('mojconfig.kategorije');
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<div class="container">
    <h1>Dodaj Materijal</h1>
    <form action="{{ route('materijali.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="opis">Opis</label>
            <input type="text" name="opis" class="form-control" placeholder="Unesi opis">
        </div>

        <select name="kategorija" id="kategorija" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
            <option value="" disabled selected>Izaberi kategoriju</option>
            @foreach($kategorije as $kategorija)
                <option value="{{ $kategorija }}">{{ $kategorija }}</option>
            @endforeach
        </select>


        <div class="form-group">
            <label for="materijal">Fajl</label>
            <input type="file" name="materijal" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Sačuvaj</button>
    </form>
</div>

</body>
</html>

