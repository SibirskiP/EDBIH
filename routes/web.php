<?php

use App\Http\Controllers\InstrukcijaController;
use App\Http\Controllers\KomentarController;
use App\Http\Controllers\ObjavaController;
use App\Http\Controllers\OdgovorController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UpitController;
use App\Http\Controllers\UserController;
use App\Mail\InstrukcijaMade;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/test2', function () {
    return view('test');
});





Route::get('test', function () {


//    Mail::to('kenandurakovic.tm87@gmail.com')->send(new InstrukcijaMade());
//    return 'Radi poslano';

    $instrukcija=\App\Models\Instrukcija::first();

    \App\Jobs\TranslateInstrukcija::dispatch($instrukcija);
});

use App\Http\Controllers\ChatController;

//Route::get('/chat', [ChatController::class, 'show']);
Route::post('/send-message', [ChatController::class, 'sendMessage']);

Route::get('/broadcast',function(){


    broadcast(new \App\Events\Example(User::find(1)));

});


Route::get('/email/verify', function () {
    if (auth()->user()->hasVerifiedEmail()) {
        return redirect('/'); // Preusmjeri na početnu stranicu ili neku drugu stranicu
    }
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');


Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');



Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('welcome');
});

Route::get('/chat',[ChatController::class,'index']);

Route::get('/instrukcije',[\App\Http\Controllers\InstrukcijaController::class,'index'])->middleware(['auth', 'verified']);
Route::get('/instrukcije/{instrukcija}', [InstrukcijaController::class, 'show'])->middleware(['auth', 'verified']);
Route::get('/instrukcije/{instrukcija}', [InstrukcijaController::class, 'show'])->middleware(['auth', 'verified']);
Route::post('/instrukcije', [InstrukcijaController::class, 'store'])->middleware(['auth', 'verified']);
Route::patch('instrukcije/{instrukcija}', [InstrukcijaController::class, 'update'])->middleware(['auth', 'verified']);
Route::delete('instrukcije/{instrukcija}', [InstrukcijaController::class, 'destroy'])->middleware(['auth', 'verified']);


Route::get('instruktori',[UserController::class,'index'])->middleware(['auth', 'verified']);
Route::get('instruktori/{user}',[UserController::class,'show'])->middleware(['auth', 'verified']);
Route::patch('instruktori/{user}',[UserController::class,'update'])->middleware(['auth', 'verified']);


//novo dodano za materijale

use App\Http\Controllers\MaterijalController;

Route::get('/materijali', [MaterijalController::class, 'index'])->name('materijali.index')->middleware(['auth', 'verified']);
Route::post('/materijali', [MaterijalController::class, 'store'])->name('materijali.store')->middleware(['auth', 'verified']);
Route::get('/materijali/create', [MaterijalController::class, 'create'])->name('materijali.create')->middleware(['auth', 'verified']);
Route::get('/materijali/{materijal}/download', [MaterijalController::class, 'download'])->name('materijali.download')->middleware(['auth', 'verified']);
Route::delete('/materijali/{id}', [MaterijalController::class, 'destroy'])->middleware(['auth', 'verified']);

//novo dodano za objave

Route::get('objave',[ObjavaController::class,'index'])->name('objave.index')->middleware(['auth', 'verified']);

Route::get('/objave/create', [ObjavaController::class, 'create'])->name('objave.create')->middleware(['auth', 'verified']);

Route::post('/objave', [ObjavaController::class, 'store'])->name('objave.store')->middleware(['auth', 'verified']);
Route::get('/objave/{objava}', [ObjavaController::class, 'show'])->name('objave.show')->middleware(['auth', 'verified']);

Route::delete('/objave/{objava}', [ObjavaController::class, 'destroy'])->middleware(['auth', 'verified']);
Route::patch('/objave/{objava}', [ObjavaController::class, 'update'])->name('objave.update')->middleware(['auth', 'verified']);


//novo dodano za komentare

Route::post('/komentari', [KomentarController::class, 'store'])->name('komentari.store')->middleware(['auth', 'verified']);
Route::delete('/komentari/{komentar}',[KomentarController::class,'destroy'])->name('komentari.destroy')->middleware(['auth', 'verified']);



Route::post('/odgovori',[OdgovorController::class,'store'])->name('odgovori.store')->middleware(['auth', 'verified']);
Route::delete('/odgovori/{odgovor}',[OdgovorController::class,'destroy'])->middleware(['auth', 'verified']);





Route::get('/register', [RegisteredUserController::class, 'create'])->name('login')->middleware('guest');
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::get('/login',[SessionController::class, 'create'])->name('login')->middleware('guest');;
Route::post('/login',[SessionController::class, 'store']);
Route::post('/logout',[SessionController::class, 'destroy']);



//upiti

Route::get('/upiti',[UpitController::class,'index'])->name('upit.index')->middleware(['auth', 'verified']);


//resetiranje passworda

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');




//novo dodano za sobe

Route::get('sobe',[\App\Http\Controllers\RoomController::class,'index'])->name('sobe.index')->middleware(['auth', 'verified']);
Route::get('sobe/{soba}',[\App\Http\Controllers\RoomController::class,'show'])->middleware(['auth', 'verified']);



use Illuminate\Support\Facades\Password;

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');


Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');


use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');




//02.08

Route::post('/video-call/start', [App\Http\Controllers\VideoController::class, 'start'])
    ->middleware('auth');

use App\Http\Controllers\DailyWebhookController;

Route::post('/daily/webhook', [DailyWebhookController::class, 'handleWebhook']);
// U routes/web.php


Route::get('/video-call', function () {
    // Ovo će biti vaša stranica za video poziv
    return view('video-call');
})->name('video-call');
