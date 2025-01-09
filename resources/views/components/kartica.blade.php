<div



    class="mx-3 flex flex-col rounded-lg bg-white text-surface shadow-secondary-1 dark:bg-surface-dark dark:text-white sm:shrink-0 sm:grow sm:basis-0">
    <a href="#!">
        <img
            class="rounded-t-lg"
            src="{{asset($slika)}}"
            alt="Skyscrapers" />
    </a>
    <div class="p-6">
        <h5 class="mb-2 text-xl font-medium leading-tight">{{$naslov}}</h5>
        {{ $sadrzaj }} <!-- Ovdje uključuješ slot koji ćeš definirati u komponenti -->
    </div>
</div>
