<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use function PHPUnit\Framework\isNull;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Admin;
use App\Models\Employe;
use App\Models\Domain;
use PhpParser\Node\Expr\BinaryOp\Spaceship;

class AuthentificationController extends Controller
{

    //spaceEmploye
    public function space(Request $request)
    {
        return response()->json([
            'domain' => session('domainConnect'),
        ]);
    }

    //domain connexion
    public function domainExist(Request $request)
    {
        $trouve = false;
        $domain = 0;
        $listDomain = Domain::all();
        foreach ($listDomain as $value) 
        {
            if($value-> nomDomain == $request -> nom && $value -> statut == 0) 
            {
                $trouve = true;
                $request->session()->put('domainConnect', $value);
                $domain = session('domainConnect');
            }
        }
        return response()->json([
            'success' => $trouve,
            'domain' => $domain
        ]);
    }

    //login
    public function login(Request $request)
    {
        $success = false;
        $trouve = false;
        $user = 0;
        $employe = 0;
        $listUser = User::all();
        foreach ($listUser as $value) {
            if($value -> login == $request -> login && $value -> password == $request -> password && $value -> statut == false)
            {
                $trouve = true;
                $user = $value;
            }
        }

        if($trouve == true)
        {
            $employe = Employe::find($user-> roleusertable_id);
            if($employe -> domain_id == session('domainConnect')-> id && $employe -> statut == false)
            {
                $success = true;
                $request->session()->put('userConnect', $user);
                $request->session()->put('employeConnect', $employe);
                return response()->json([
                    'success' => $success,
                    'user' => $user,
                    'employe' => $employe,
                    'domain' => session('domainConnect'),
                ]);
            }
        }else{
            return response()->json([
                'success' => $success,
                'login' => $request -> login,
                'password' => $request -> password,
            ]);
        }
        
    }

    //register
    public function register(Request $request)
    {
        $trouve = false;
        $listDomain = Domain::all();
        foreach ($listDomain as $value) {
            if($value -> nomDomain == $request -> nomDomain)
            {
                $trouve = true;
            }
        }

        if($trouve == true)
        {
            return response()->json([
                'success' => false,
                'error' => "Domain name already used !",
                'nomDomain' => $request -> nomDomain,
                'nomEntreprise' => $request -> nomEntreprise,
                'paysEntreprise' => $request -> paysEntreprise,
                'villeEntreprise' => $request -> villeEntreprise,
                'adresseEntreprise' => $request -> adresseEntreprise,
                'nationalite' => $request -> nationalite,
                'telProfessionnel' => $request -> telProfessionnel,
                'emailProfessionnel' => $request -> emailProfessionnel,
                'genre' => $request -> genre,
                'dateNaissance' => $request -> dateNaissance,
            ]);
        }else{
            $listUser = User::all();
            $trouve1 = false;
            foreach ($listUser as $value) {
                if($value -> login == $request -> emailProfessionnel)
                {
                    $trouve1 = true;
                }
            }
            if($trouve1 == true)
            {
                return response()->json(false);
            }else{
                //domain
                $domain = new Domain();
                $domain -> nomDomain = $request -> nomDomain;
                $domain -> nomEntreprise = $request -> nomEntreprise;
                $domain -> paysEntreprise = $request -> paysEntreprise;
                $domain -> villeEntreprise = $request -> villeEntreprise;
                $domain -> adresseEntreprise = $request -> adresseEntreprise;
                $domain -> dateAjout = date('d-m-Y');
                $domain -> statut = false;
                $domain -> save();
                //$domain1 = Domain::select('id')->where('nomDomain', $request -> nomDomain)->get();
                //employe
                $employe = new Employe();
                $employe -> prenom = $request -> prenom;
                $employe -> nom = $request -> nom;
                $employe -> nationalite = $request -> nationalite;
                $employe -> telProfessionnel = $request -> telProfessionnel;
                $employe -> emailProfessionnel = $request -> emailProfessionnel;
                $employe -> genre = $request -> genre;
                $employe -> dateNaissance = $request -> dateNaissance;
                $employe -> statut = false;
                $employe -> domain_id = $domain->id;
                $employe -> role = "RH";
                $employe -> situationMatrimoniale = "";
                $employe -> adresse = "";
                $employe -> ville = "";
                $employe -> pays = "";
                $employe -> telPersonnel = "";
                $employe -> emailPersonnel = "";
                $employe -> experience = "";
                $employe -> formation = "";
                $employe -> langue = "";
                $employe -> dateEmbauche = "";
                $employe -> emploi = "";
                $employe -> matricule = "EMP".date('dmY')."".$employe->id;
                $employe -> save();
                //$employe1 = Employe::select('id')->where('emailProfessionnel', $request -> emailProfessionnel)->get();
                //user
                $user = new User();
                $user -> login = $request -> emailProfessionnel;
                $user -> password = $request -> password;
                $user -> statut = false;
                $user -> roleusertable_id = $employe->id;
                $user -> roleusertable_type = "employes";
                $user -> save();
                return response()->json(true);
            }
        }
    }

}
