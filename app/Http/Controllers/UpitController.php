<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class UpitController extends Controller
{
    //

    public function index(){

        $sql1 = 'SELECT u.id, u.username, COUNT(i.id) AS broj_instrukcija
FROM users u
LEFT JOIN instrukcije i ON u.id = i.user_id
GROUP BY u.id, u.username
ORDER BY broj_instrukcija DESC
LIMIT 10;
';

        $rez1= DB::select($sql1);


        $sql2='SELECT o.id AS objava_id, o.naziv AS naslov_objave, COUNT(k.id) AS broj_komentara FROM objave o
LEFT JOIN komentari k ON o.id = k.objava_id GROUP BY o.id, o.naziv
ORDER BY broj_komentara DESC
LIMIT 10';

        $rez2= DB::select($sql2);



        //sql3

        $sql3='SELECT u.id, u.username,
       (COUNT(DISTINCT i.id) + COUNT(DISTINCT o.id) + COUNT(DISTINCT m.id)) AS ukupno_aktivnosti
FROM users u
LEFT JOIN instrukcije i ON u.id = i.user_id
LEFT JOIN objave o ON u.id = o.user_id
LEFT JOIN materijali m ON u.id = m.user_id
GROUP BY u.id, u.username
ORDER BY ukupno_aktivnosti DESC
LIMIT 10
';


        $rez3= DB::select($sql3);

//upit 4


        $sql4='SELECT AVG(broj_komentara) AS prosjecan_broj_komentara
FROM (
    SELECT COUNT(k.id) AS broj_komentara
    FROM objave o
    LEFT JOIN komentari k ON o.id = k.objava_id
    GROUP BY o.id
) AS subquery
';
        $rez4= DB::select($sql4);


        //upit 5
        $sql5 = 'SELECT COUNT(id) / COUNT(DISTINCT DATE(created_at)) AS prosjecan_broj_objava_po_danu FROM objave';
        $rez5 = DB::select($sql5);



        return view('upiti',['rez1'=>$rez1,'rez2'=>$rez2,'rez3'=>$rez3,'rez4'=>$rez4,'rez5'=>$rez5]);
    }







}
