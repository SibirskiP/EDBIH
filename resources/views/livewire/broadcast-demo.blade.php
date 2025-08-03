<div class="p-4 border">
    <button wire:click="send" class="bg-blue-500 text-white px-4 py-2">Pošalji event</button>

    <ul class="mt-4">
        @foreach($messages as $msg)
            <li class="text-green-600">{{ $msg }}</li>
        @endforeach
    </ul>
</div>
