<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Admin;
use App\Models\AlerteObjectif;
use App\Models\AlertEvaluation;
use App\Models\Commentaire;
use App\Models\Domain;
use App\Models\Employe;
use App\Models\EmployeEtape;
use App\Models\EmployeEvaluation;
use App\Models\EmployeObjectif;
use App\Models\Etape;
use App\Models\Evaluation;
use App\Models\Objectif;
use App\Models\Critere;
use App\Models\Reponse;
use App\Models\User;

class EmployeController extends Controller
{
    //getters

    public function spaceEmploye(Request $request)
    {
        $listAlertObjectif = AlerteObjectif::select('*')->where('employe_id', session('employeConnect')->id)->get();
        $listAlertEvaluation = AlertEvaluation::select('*')->where('employe_id', session('employeConnect')->id)->get();
        //$listEmploye = Employe::select('*')->where('domain_id', session('domainConnect')->id)->get();
        $listEmploye = DB::select("SELECT * FROM employes WHERE domain_id = :id ORDER BY created_at DESC",['id'=> session('domainConnect')->id]);
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
            'listAlertObjectif' => $listAlertObjectif,
            'listAlertEvaluation' => $listAlertEvaluation,
            'listEmploye' => $listEmploye,
        ]);
    }

    public function listerObjectifs(Request $request)
    {
        $listObjectif = DB::select('SELECT t1.id id,t1.dateDebut dateDebut,t1.dateFin dateFin,t1.titre titre,jt.progression progression FROM objectifs t1 INNER JOIN employe_objectif jt ON t1.id = jt.objectifs_id INNER JOIN employes t2 ON t2.id = jt.employes_id WHERE t2.id = :id', ['id' => session('employeConnect')->id]);
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
            //'listAlertObjectif' => session('listAlertObjectif'),
            //'listAlertEvaluation' => session('listAlertEvaluation'),
            'listObjectif' => $listObjectif,
        ]);
    }

    public function listerEvaluations(Request $request)
    {
        $listEvaluation = DB::select('SELECT t1.id id,t1.dateDebut,t1.dateFin,t1.titre,t1.statut statut1,jt.statut statut2,t1.type types FROM evaluations t1 INNER JOIN employe_evaluation jt ON t1.id = jt.evaluations_id INNER JOIN employes t2 ON t2.id = jt.employes_id WHERE t2.id = :id', ['id' => session('employeConnect')->id]);
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
            //'listAlertEvaluation' => session('listAlertEvaluation'),
            //'listAlertEvaluation' => session('listAlertEvaluation'),
            'listEvaluation' => $listEvaluation,
        ]);
    }

    public function detailsEvaluations(Request $request,$id)
    {
        $evaluation = Evaluation::find($id);
        $criteres = Critere::where('evaluation_id',$id);
        $nbParticipant = DB::select('SELECT count(*) nb FROM employe_evaluation WHERE evaluations_id = :id',['id'=>$id]);
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
            'evaluation' => $evaluation,
            'criteres' => $criteres,
            'nombreDeParticipant' => $nbParticipant[0]->nb,
        ]);
    }

    public function detailsObjectifs(Request $request,$id)
    {
        $objectif = Objectif::find($id);
        $etape = DB::select('SELECT t1.id id,t1.libelle libelle,jt.statut statut,jt.id idJt FROM etapes t1 INNER JOIN employe_etape jt ON t1.id = jt.etapes_id INNER JOIN employes t2 ON t2.id = jt.employes_id WHERE t1.objectif_id = :id AND t2.id = :id2',['id'=>$objectif->id,'id2'=>session('employeConnect')->id]);
        $commentaires = DB::select('SELECT c.id id,c.libelle libelle,c.statut statut,c.created_at datePost,e.nom nom,e.prenom prenom,e.id employe_id FROM commentaires c INNER JOIN employes e WHERE c.objectif_id = :id AND c.employe_id=e.id',['id'=>$objectif->id]);
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
            'objectif' => $objectif,
            'etapes' => $etape,
            'commentaires' => $commentaires,
        ]);
    }

    public function editProfil(Request $request)
    {
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
        ]);
    }

    public function valider(Request $request,$id,$id2,$id3)
    {
        $et = EmployeEtape::find($id);
        if($et-> statut == 0){
            $et -> statut = 1;
        }else{
            $et -> statut = 0;
        }
        $save = $et ->etapes_id;
        $et -> save();
        $nbTotal = DB::select('SELECT count(*) nb FROM etapes WHERE objectif_id = :id',['id'=>$id2]);
        $nbT = intval($nbTotal[0]->nb);
        $ob = DB::select('SELECT COUNT(*) nb FROM employe_etape ee JOIN etapes e ON ee.etapes_id = e.id WHERE ee.statut = 1 AND e.objectif_id = :id AND ee.employes_id=:id2',['id'=>$id2,'id2'=>$id3]);
        $progression = intval($ob[0]->nb)*100 / intval($nbT);
        $up = DB::select('UPDATE employe_objectif SET progression = :p WHERE objectifs_id = :ob AND employes_id = :emp',['p'=>$progression,'ob'=>$id2,'emp'=>$id3]);
    }

    public function faireEvaluation(Request $request,$id)
    {
        $evaluation = Evaluation::find($id);
        $criteres = Critere::where('evaluation_id',$id)->get();
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
            'evaluation' => $evaluation,
            'criteres' => $criteres,
        ]);
    }

    public function rapportEvaluation(Request $request,$id)
    {
        $evaluation = DB::select('SELECT t1.dateDebut,t1.dateFin,t1.titre,t1.objectif,t1.contexte,jt.bilan,jt.statut s1,t1.statut s2 FROM evaluations t1 INNER JOIN employe_evaluation jt ON t1.id = jt.evaluations_id INNER JOIN employes t2 ON t2.id = jt.employes_id WHERE t2.id = :id AND t1.id = :ev AND t2.id = :em', ['id' => session('employeConnect')->id,'ev'=>$id,'em'=>session('employeConnect')->id]);
        $criteresReponses = DB::select('SELECT c.libelle indicateur,r.libelle avis FROM criteres c,reponses r WHERE c.evaluation_id = :id AND r.critere_id=c.id AND r.participant_id=:idEmp',['id'=>$id,'idEmp'=>session('employeConnect')->id]);
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
            'evaluation' => $evaluation[0],
            'criteresReponses' => $criteresReponses,
        ]);
    }

    //setter
    // modifeir prfil
    public function update_profil(Request $request)
    {
        $employe = Employe::find(session('employeConnect')->id);
        ($request->prenom)?$employe -> prenom = $request -> prenom: null;
        ($request->nom)?$employe -> nom = $request -> nom: null;
        ($request->nationalite)?$employe -> nationalite = $request -> nationalite: null;
        ($request->telProfessionnel)?$employe -> telProfessionnel = $request -> telProfessionnel: null;
        ($request->genre)?$employe -> genre = $request -> genre: null;
        ($request->dateNaissance)?$employe -> dateNaissance = $request -> dateNaissance: null;
        ($request->role)?$employe -> role = $request -> role: null;
        ($request->situationMatrimoniale)?$employe -> situationMatrimoniale = $request -> situationMatrimoniale: null;
        ($request->adresse)?$employe -> adresse = $request -> adresse: null;
        ($request->ville)?$employe -> ville = $request -> ville: null;
        ($request->pays)?$employe -> pays = $request -> pays: null;
        ($request->telPersonnel)?$employe -> telPersonnel = $request -> telPersonnel: null;
        ($request->emailPersonnel)?$employe -> emailPersonnel = $request -> emailPersonnel: null;
        ($request->experience)?$employe -> experience = $request -> experience: null;
        ($request->formation)?$employe -> formation = $request -> formation: null;
        ($request->langue)?$employe -> langue = $request -> langue: null;
        ($request->dateEmbauche)?$employe -> dateEmbauche = $request -> dateEmbauche: null;
        ($request->emploi)?$employe -> emploi = $request -> emploi: null;
        $employe -> save();
        $request->session()->put('employeConnect',$employe);
        //user
        $user = User::find(session('userConnect')->id);
        ($request->password)?$user -> password = $request -> password:null;
        $user -> save();
        $request->session()->put('userConnect',$user);
        return response()->json(true);
    }

    public function persist_commentaire($id,$form,$id1)
    {
        $com = new Commentaire();
        $com -> libelle = $form;
        $com -> datePost= $form;
        $com -> statut= false;
        $com -> employe_id= $id;
        $com -> objectif_id= $id1;
        $com -> save();
        $commentaires = DB::select('SELECT c.id id,c.libelle libelle,c.statut statut,c.created_at datePost,e.nom nom,e.prenom prenom,e.id employe_id FROM commentaires c INNER JOIN employes e WHERE c.objectif_id = :id AND c.employe_id=e.id',['id'=>$id1]);
        return response()->json($commentaires);
    }

}
