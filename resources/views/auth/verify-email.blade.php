
 @vite('resources/css/app.css')

 <section class="bg-white dark:bg-gray-900">
     <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
         <div class="mx-auto max-w-screen-sm text-center">
             <h1 class="mb-4 text-3xl tracking-tight font-extrabold lg:text-3xl text-primary-600 dark:text-primary-500">Potvrdi svoj mail</h1>
             <p class="mb-4 text-xl tracking-tight font-bold text-gray-900 md:text-xl dark:text-white">Novi verifikacijski link je poslan na tvoj email.</p>
         </div>

         <form method="POST" action="/email/verification-notification" class="flex justify-center">
             @csrf
             <button class="rounded-md bg-blue-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600" type="submit">Pošalji ponovo verifikacijski email</button>
         </form>
     </div>
 </section>
