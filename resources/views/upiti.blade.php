<x-layout>
    <!-- tvoj_view.blade.php -->

    <br>
    <br>
    <br>
    <div class="py-10 bg-gray-100">
        <div class="max-w-7xl mx-auto px-6">

            <div class="mb-12">
                <h1 class="text-3xl font-bold text-gray-800 text-center">Top 10 Korisnici s Najviše Instrukcija</h1>
                <table class="mt-10 table-auto w-full border-collapse border border-gray-300 shadow-md rounded-lg">
                    <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600 border border-gray-300">ID</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600 border border-gray-300">Username</th>
                        <th class="px-4 py-2 text-right font-semibold text-gray-600 border border-gray-300">Broj Instrukcija</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rez1 as $user)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 border border-gray-300">{{ $user->id }}</td>
                            <td class="px-4 py-2 border border-gray-300">{{ $user->username }}</td>
                            <td class="px-4 py-2 border border-gray-300 text-right">{{ $user->broj_instrukcija }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div>
                <h1 class="text-3xl font-bold text-gray-800 text-center">Top 10 Objave s Najviše Komentara</h1>
                <table class="mt-10 table-auto w-full border-collapse border border-gray-300 shadow-md rounded-lg">
                    <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600 border border-gray-300">#</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600 border border-gray-300">Naslov Objave</th>
                        <th class="px-4 py-2 text-right font-semibold text-gray-600 border border-gray-300">Broj Komentara</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rez2 as $index => $objava)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 border border-gray-300">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border border-gray-300">{{ $objava->naslov_objave }}</td>
                            <td class="px-4 py-2 border border-gray-300 text-right">{{ $objava->broj_komentara }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-12">
                <h1 class="text-3xl font-bold text-gray-800 text-center">Top 10 Korisnika s Najviše Aktivnosti</h1>
                <table class="mt-10 table-auto w-full border-collapse border border-gray-300 shadow-md rounded-lg">
                    <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600 border border-gray-300">ID</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600 border border-gray-300">Username</th>
                        <th class="px-4 py-2 text-right font-semibold text-gray-600 border border-gray-300">Ukupno Aktivnosti</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rez3 as $user)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 border border-gray-300">{{ $user->id }}</td>
                            <td class="px-4 py-2 border border-gray-300">{{ $user->username }}</td>
                            <td class="px-4 py-2 border border-gray-300 text-right">{{ $user->ukupno_aktivnosti }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-center">


                <div class="mt-12">
                    <h1 class="text-3xl font-bold text-gray-800 text-center">Prosječan Broj Komentara po Objavi</h1>
                    <table class="mt-10 table-auto w-full border-collapse border border-gray-300 shadow-md rounded-lg">
                        <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 border border-gray-300">#</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 border border-gray-300">Prosječan Broj Komentara</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 border border-gray-300">1</td>
                            <td class="px-4 py-2 border border-gray-300 text-right">
                                {{ number_format($rez4[0]->prosjecan_broj_komentara, 2) }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>


                <div class="mt-12">
                    <h1 class="text-3xl font-bold text-gray-800 text-center">Prosječan Broj Objava po Danu</h1>
                    <table class="mt-10 table-auto w-full border-collapse border border-gray-300 shadow-md rounded-lg">
                        <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 border border-gray-300">#</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 border border-gray-300">Prosječan Broj Objava po Danu</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 border border-gray-300">1</td>
                            <td class="px-4 py-2 border border-gray-300 text-right">
                                {{ number_format($rez5[0]->prosjecan_broj_objava_po_danu, 0) }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>




            </div>
    </div>

</x-layout>
