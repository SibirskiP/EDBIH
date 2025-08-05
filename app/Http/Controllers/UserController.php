<?php

namespace App\Http\Controllers;

use App\Models\Instrukcija;
use App\Models\Materijal;
use App\Models\Obavijest;
use App\Models\Objava;
use App\Models\RoomInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request) {

        $query = User::query();

        // Filtriranje po kategoriji
        if ($request->has('kategorije') && !empty($request->kategorije)) {
            $query->whereHas('instrukcije', function($q) use ($request) {
                $q->whereIn('kategorija', $request->kategorije);
            });
        }


        // Filtriranje po lokaciji
        if ($request->has('lokacije') && !empty($request->lokacije)) {
            $query->whereIn('lokacija', $request->lokacije);
        }

        // Pretraga po nazivu (username)
        if ($request->has('naziv') && $request->naziv != '') {
            $query->where('username', 'like', '%' . $request->naziv . '%');
        }

        $instruktori = $query->latest()->simplePaginate(6)->appends($request->all());


        return view('instruktori/index', [
            'instruktori' => $instruktori,
            'kategorije' => config('mojconfig.kategorije'), // Za prikaz filtera
            'lokacije' => config('mojconfig.lokacije') // Za prikaz filtera
        ]);
    }


    public function show(User $user) {
        if (Auth::guest()) {
            return redirect('/login');
        }

        $instrukcije = Instrukcija::where('user_id', $user->id)->with('user')->paginate(10);
        $materijali = Materijal::where('user_id', $user->id)->with('user')->paginate(10);
        $objave = Objava::where('user_id', $user->id)->with('user')->paginate(10);

        $roomInvitations = collect();
        $notifications = collect();
        $totalUnreadCount = 0; // Nova varijabla za ukupan broj nepročitanih

        if (Auth::id() === $user->id) {
            $roomInvitations = RoomInvitation::with(['room.creator'])
                ->where('user_id', Auth::id())
                ->where('status', 'pending')
                ->get();

            $notifications = Obavijest::where('korisnik_id', Auth::id())->latest()->get(); // Koristimo Obavijest model

            // Izračunaj ukupan broj nepročitanih
            $unreadNotificationsCount = Obavijest::where('korisnik_id', Auth::id())->where('procitano', false)->count();
            $pendingInvitationsCount = RoomInvitation::where('user_id', Auth::id())->where('status', 'pending')->count();
            $totalUnreadCount = $unreadNotificationsCount + $pendingInvitationsCount;
        }

        return view('instruktori/show', [
            'instruktor' => $user,
            'instrukcije' => $instrukcije,
            'materijali' => $materijali,
            'objave' => $objave,
            'roomInvitations' => $roomInvitations,
            'notifications' => $notifications,
            'totalUnreadCount' => $totalUnreadCount, // Proslijedi ukupan broj u view
        ]);
    }

    public function update(User $user) {

        if(\Auth::guest()){
            return redirect('/login');
        }
        try {
            $att = request()->validate([

                'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
                'profilna_slika' => ['image', 'mimes:jpeg,png,jpg', 'max:1024'],

                'titula' => ['required', 'string', 'max:255'],
                'kontakt' => ['required', 'string', 'max:255'],
                'opis' => ['required', 'string', 'max:255'],

            ]);
        }
        catch (\Exception $e) {
            session()->flash('failure', $e->getMessage());

            return redirect('instruktori/' . $user->id);
        }

        $user->update($att);

        return redirect('instruktori/' . $user->id);

    }

    //

}
