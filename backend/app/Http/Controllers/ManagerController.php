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

class ManagerController extends Controller
{
    //setters

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

    // ajouter une evaluation
    public function persist_evaluation(Request $request)
    {
        $evaluation = new Evaluation();
        $evaluation -> dateDebut = $request -> dateDebut;
        $evaluation -> dateFin = $request -> dateFin;
        $evaluation -> titre = $request -> titre;
        $evaluation -> contexte = $request -> contexte;
        $evaluation -> objectif = $request -> objectif;
        $evaluation -> type = $request -> type;
        $evaluation -> statut = false;
        $evaluation -> employe_id = session('employeConnect')->id;
        $evaluation -> save();
        $participants = $request -> criteres;
        foreach ($participants as $value) {
            $critere = new Critere();
            $critere -> statut = false;
            $critere -> libelle = $value['critere'];
            $critere -> evaluation_id = $evaluation->id;
            $critere ->save();
        }
        $participants = $request -> employes;
        foreach ($participants as $value) {
            $emp = new EmployeEvaluation();
            $emp -> statut = false;
            $emp -> dateParticipation = date('d-m-Y');
            $emp -> bilan = 0;
            $emp -> employes_id = $value;
            $emp -> evaluations_id = $evaluation->id;
            $emp ->save();
            $alert = new AlertEvaluation();
            $alert -> libelle = session('employeConnect')->prenom." ".session('employeConnect')->nom." a démarré une évaluation pour vous ! Cliquez pour plus de détails.";
            $alert -> employe_id = $value;
            $alert -> statut = false;
            $alert -> evaluation_id = $evaluation->id;
            $alert -> save();
        }
        return response()->json();
    }

    public function faire(Request $request,$idEv,$idEmp)
    {
        $note = 0;
        $nb = 0;
        if($request->feedbacks){
            foreach ($request->feedbacks as $value) {
                $nb++;
                $reponse = new Reponse();
                $reponse -> libelle = $value['feedback'];
                $reponse -> participant_id = $idEmp;
                $reponse -> statut = false;
                $reponse -> note = $value['note'];
                $reponse -> critere_id = $value['idCritere'];
                $reponse -> employe_id = session('employeConnect')->id;
                $reponse -> datePost = date('d-m-Y');
                $reponse -> save();
                $note+= intval($value['note']);
            }
            //$note = $note*20/6*$nb;
            $num = $note*20;
            $denom = 6*$nb;
            $global = $num/ $denom;
            $up = DB::select('UPDATE employe_evaluation SET bilan = :p,statut=1 WHERE evaluations_id = :ob AND employes_id = :emp',['p'=>$global,'ob'=>$idEv,'emp'=>$idEmp]);
        }
    }

    // ajouter un Objectif
    public function persist_objectif(Request $request)
    {
        $objectif = new Objectif();
        $objectif -> dateDebut = $request -> dateDebut;
        $objectif -> dateFin = $request -> dateFin;
        $objectif -> titre = $request -> titre;
        $objectif -> description = $request -> description;
        $objectif -> statut = false;
        $objectif -> employe_id = session('employeConnect')->id;
        $objectif -> save();
        $participants = $request -> etapes;
        foreach ($participants as $value) {
            $etape = new Etape();
            $etape -> statut = false;
            $etape -> libelle = $value['etape'];
            $etape -> objectif_id = $objectif->id;
            $etape ->save();
            $emps = $request -> employes;
            foreach ($emps as $value1) {
                $ce = new EmployeEtape();
                $ce -> statut = false;
                $ce -> employes_id = $value1;
                $ce -> etapes_id = $etape -> id;
                $ce -> save();
            }
        }
        $participants = $request -> employes;
        foreach ($participants as $value) {
            $emp = new EmployeObjectif();
            $emp -> statut = false;
            $emp -> dateDepot = date('d-m-Y');
            $emp -> progression = 0;
            $emp -> employes_id = $value;
            $emp -> objectifs_id = $objectif->id;
            $emp ->save();
        }

        foreach ($request -> employes as $value) {
            $alert = new AlerteObjectif();
            $alert -> libelle = session('employeConnect')->prenom." ".session('employeConnect')->nom." a démarré un objectif pour vous ! Cliquez pour plus de détails.";
            $alert -> employe_id = $value;
            $alert -> statut = false;
            $alert -> objectif_id = $objectif ->id;
            $alert -> save();
        }
        return response()->json();
    }

    // modifier une evaluation
    public function update_evaluation(Request $request,$id)
    {
        $evaluation = Evaluation::find($id);
        ($request -> dateDebut)?$evaluation -> dateDebut = $request -> dateDebut:null;
        ($request -> dateFin)?$evaluation -> dateFin = $request -> dateFin:null;
        ($request -> titre)?$evaluation -> titre = $request -> titre:null;
        ($request -> contexte)?$evaluation -> contexte = $request -> contexte:null;
        ($request -> objectif)?$evaluation -> objectif = $request -> objectif:null;
        $evaluation -> save();
        if($request -> criteres)
        {
            $participants = $request -> criteres;
            foreach ($participants as $value) {
                $critere = new Critere();
                $critere -> statut = false;
                $critere -> libelle = $value['critere'];
                $critere -> evaluation_id = $evaluation->id;
                $critere ->save();
            }
        }
        if($request -> employes)
        {
            $participants = $request -> employes;
            foreach ($participants as $value) {
                $emp = new EmployeEvaluation();
                $emp -> statut = false;
                $emp -> dateParticipation = date('d-m-Y');
                $emp -> bilan = 0;
                $emp -> employes_id = $value;
                $emp -> evaluations_id = $evaluation->id;
                $emp ->save();
            }
        }
        
        return response()->json(true);
    }

    // modifier un Objectif
    public function update_objectif(Request $request,$id)
    {
        $objectif = Objectif::find($id);
        ($request -> dateDebut)?$objectif -> dateDebut = $request -> dateDebut:null;
        ($request -> dateFin)?$objectif -> dateFin = $request -> dateFin:null;
        ($request -> dateFin)?$objectif -> titre = $request -> titre:null;
        ($request -> description)?$objectif -> description = $request -> description:null;
        $objectif -> save();
        if($request -> etapes)
        {
            $participants = $request -> etapes;
            foreach ($participants as $value) {
                $etape = new Etape();
                $etape -> statut = false;
                $etape -> libelle = $value['etape'];
                $etape -> objectif_id = $objectif->id;
                $etape ->save();
                $emps = $request -> employes;
                foreach ($emps as $value1) {
                    $ce = new EmployeEtape();
                    $ce -> statut = false;
                    $ce -> employes_id = $value1;
                    $ce -> etapes_id = $etape -> id;
                    $ce -> save();
                }
            }
        }
        if($request -> employes)
        {
            $participants = $request -> employes;
            foreach ($participants as $value) {
                $emp = new EmployeObjectif();
                $emp -> statut = false;
                $emp -> dateDepot = date('d-m-Y');
                $emp -> progression = 0;
                $emp -> employes_id = $value;
                $emp -> objectifs_id = $objectif->id;
                $emp ->save();
            }
        }
        return response()->json();
    }

    // close un Objectif
    public function close_objectif($id)
    {
        $objectif = Objectif::find($id);
        $objectif -> statut = true;
        $objectif -> save();
        return response()->json(true);
    }

    // close un evaluation
    public function close_evaluation($id)
    {
        $evaluation = Evaluation::find($id);
        $evaluation -> statut = true;
        $evaluation -> save();
        return response()->json(true);
    }

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

    //getters

    public function spaceManager(Request $request)
    {
        //$listAlertObjectif = AlerteObjectif::select('*')->where('employe_id', session('userConnect')->id)->get();
        //$listAlertEvaluation = AlertEvaluation::select('*')->where('employe_id', session('userConnect')->id)->get();
        $listEmploye = DB::select("SELECT * FROM employes WHERE domain_id = :id ORDER BY created_at DESC",['id'=> session('domainConnect')->id]);
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
            //'listAlertObjectif' => session('listAlertObjectif'),
            //'listAlertEvaluation' => session('listAlertEvaluation'),
            'listEmploye' => $listEmploye,
        ]);
    }

    public function listerObjectifs(Request $request)
    {
        $listObjectif = DB::select('SELECT t1.id id,t1.dateDebut dateDebut,t1.dateFin dateFin,t1.titre titre,jt.progression progression FROM objectifs t1 INNER JOIN employe_objectif jt ON t1.id = jt.objectifs_id INNER JOIN employes t2 ON t2.id = jt.employes_id WHERE t2.id = :id', ['id' => session('employeConnect')->id]);
        $listObjectifCree = DB::select('SELECT * FROM objectifs WHERE employe_id = :id', ['id' => session('employeConnect')->id]);
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
            //'listAlertObjectif' => session('listAlertObjectif'),
            //'listAlertEvaluation' => session('listAlertEvaluation'),
            'listObjectif' => $listObjectif,
            'listObjectifCree' => $listObjectifCree,
        ]);
    }

    public function listerEvaluations(Request $request)
    {
        $listEvaluation = DB::select('SELECT t1.id id,t1.dateDebut,t1.dateFin,t1.titre,t1.statut statut1,jt.statut statut2,t1.type types FROM evaluations t1 INNER JOIN employe_evaluation jt ON t1.id = jt.evaluations_id INNER JOIN employes t2 ON t2.id = jt.employes_id WHERE t2.id = :id', ['id' => session('employeConnect')->id]);
        $listEvaluationCree = DB::select('SELECT * FROM evaluations WHERE employe_id = :id', ['id' => session('employeConnect')->id]);
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
            //'listAlertEvaluation' => session('listAlertEvaluation'),
            //'listAlertEvaluation' => session('listAlertEvaluation'),
            'listEvaluation' => $listEvaluation,
            'listEvaluationCree' => $listEvaluationCree,
        ]);
    }

    public function listEmployeEvaluation($id)
    {
        $listEmploye = DB::select('SELECT t1.id id,t1.matricule matricule,t1.nom nom,t1.prenom prenom, t1.emploi job,jt.statut statut FROM employes t1 INNER JOIN employe_evaluation jt ON t1.id = jt.employes_id WHERE jt.evaluations_id = :id', ['id' => $id]);
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
            //'listAlertEvaluation' => session('listAlertEvaluation'),
            //'listAlertEvaluation' => session('listAlertEvaluation'),
            'listEmploye' => $listEmploye,
        ]);
    }

    public function listEmployeObjectif($id)
    {
        $listEmploye = DB::select('SELECT t1.id id,t1.matricule matricule,t1.nom nom,t1.prenom prenom, t1.emploi job,jt.statut statut FROM employes t1 INNER JOIN employe_objectif jt ON t1.id = jt.employes_id INNER JOIN objectifs t2 ON t2.id = jt.objectifs_id WHERE t2.id = :id', ['id' => $id]);
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
            //'listAlertEvaluation' => session('listAlertEvaluation'),
            //'listAlertEvaluation' => session('listAlertEvaluation'),
            'listEmploye' => $listEmploye,
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
        $etape = DB::select('SELECT t1.id id,t1.libelle libelle,jt.statut statut FROM etapes t1 INNER JOIN employe_etape jt ON t1.id = jt.etapes_id INNER JOIN employes t2 ON t2.id = jt.employes_id WHERE t1.objectif_id = :id AND t2.id = :id2',['id'=>$objectif->id,'id2'=>session('employeConnect')->id]);
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

    public function faireEvaluation(Request $request,$id)
    {
        $evaluation = Evaluation::find($id);
        $criteres = Critere::where('evaluation_id',$id)->get();
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
            'employeC' => session('employeConnect'),
            'evaluation' => $evaluation,
            'criteres' => $criteres,
        ]);
    }

    public function faireEvaluationEmploye(Request $request,$id,$idEmploye)
    {
        $employeC= Employe::find($idEmploye);
        $evaluation = Evaluation::find($id);
        $criteres = Critere::where('evaluation_id',$id)->get();
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
            'employeC' => $employeC,
            'evaluation' => $evaluation,
            'criteres' => $criteres,
        ]);
    }

    public function rapportEvaluation(Request $request,$id)
    {
        $employeC = Employe::find(session('employeConnect')->id);
        $evaluation = DB::select('SELECT t1.dateDebut,t1.dateFin,t1.titre,t1.objectif,t1.contexte,jt.bilan FROM evaluations t1 INNER JOIN employe_evaluation jt ON t1.id = jt.evaluations_id INNER JOIN employes t2 ON t2.id = jt.employes_id WHERE t2.id = :id AND t1.id = :ev AND t2.id = :em', ['id' => session('employeConnect')->id,'ev'=>$id,'em'=>session('employeConnect')->id]);
        $criteresReponses = DB::select('SELECT c.libelle indicateur,r.libelle avis FROM criteres c,reponses r WHERE c.evaluation_id = :id AND r.critere_id=c.id AND r.participant_id=:idEmp',['id'=>$id,'idEmp'=>session('employeConnect')->id]);
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
            'evaluation' => $evaluation[0],
            'criteresReponses' => $criteresReponses,
            'employeC' => $employeC,
        ]);
    }

    public function rapportObjectif(Request $request,$id,$idEmploye)
    {
        $employeC = Employe::find($idEmploye);
        $objectif = DB::select('SELECT t1.dateDebut,t1.dateFin,t1.titre,t1.description,jt.progression FROM objectifs t1 INNER JOIN employe_objectif jt ON t1.id = jt.objectifs_id INNER JOIN employes t2 ON t2.id = jt.employes_id WHERE t2.id = :id AND t1.id = :ev AND t2.id = :em', ['id' => $idEmploye,'ev'=>$id,'em'=>$idEmploye]);
        $etape = DB::select('SELECT t1.libelle,jt.statut FROM etapes t1 INNER JOIN employe_etape jt ON t1.id = jt.etapes_id INNER JOIN employes t2 ON t2.id = jt.employes_id WHERE t2.id = :id AND t1.objectif_id = :ev', ['id' => $idEmploye,'ev'=>$id]);
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
            'objectif' => $objectif[0],
            'etapes' => $etape,
            'employeC' => $employeC,
        ]);
    }

    public function commentaireObjectif($id)
    {
        $commentaires = DB::select('SELECT c.id id,c.libelle libelle,c.statut statut,c.created_at datePost,e.nom nom,e.prenom prenom,e.id employe_id FROM commentaires c INNER JOIN employes e WHERE c.objectif_id = :id AND c.employe_id=e.id',['id'=>$id]);
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
            'commentaires' => $commentaires,
        ]);
    }

    public function rapportEvaluationEmploye(Request $request,$id,$idEmploye)
    {
        $employeC = Employe::find($idEmploye);
        $evaluation = DB::select('SELECT t1.dateDebut,t1.dateFin,t1.titre,t1.objectif,t1.contexte,jt.bilan FROM evaluations t1 INNER JOIN employe_evaluation jt ON t1.id = jt.evaluations_id INNER JOIN employes t2 ON t2.id = jt.employes_id WHERE t2.id = :id AND t1.id = :ev AND t2.id = :em', ['id' => $idEmploye,'ev'=>$id,'em'=>$idEmploye]);
        $criteresReponses = DB::select('SELECT c.libelle indicateur,r.libelle avis FROM criteres c,reponses r WHERE c.evaluation_id = :id AND r.critere_id=c.id AND r.employe_id = :emp AND r.participant_id=:idEmp',['id'=>$id,'emp'=>session('employeConnect')->id,'idEmp'=>$idEmploye]);
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
            'employeC' => $employeC,
            'evaluation' => $evaluation[0],
            'criteresReponses' => $criteresReponses,
        ]);
    }

    public function ajouterEvaluation(Request $request)
    {
        $listEmploye = DB::select("SELECT * FROM employes WHERE domain_id = :id AND id!=:id1",['id'=> session('domainConnect')->id,'id1'=>session('employeConnect')->id]);
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
            'listEmploye' => $listEmploye,
        ]);
    }

    public function modifierEvaluation(Request $request,$id)
    {
        $listEmploye = DB::select("SELECT * FROM employes WHERE domain_id = :id AND id!=:id1",['id'=> session('domainConnect')->id,'id1'=>session('employeConnect')->id]);
        $evaluation = Evaluation::find($id);
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
            'evaluation' => $evaluation,
            'listEmploye' => $listEmploye,
        ]);
    }

    public function modifierObjectif(Request $request,$id)
    {
        $listEmploye = DB::select("SELECT * FROM employes WHERE domain_id = :id AND id!=:id1",['id'=> session('domainConnect')->id,'id1'=>session('employeConnect')->id]);
        $objectif = Objectif::find($id);
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
            'objectif' => $objectif,
            'listEmploye' => $listEmploye,
        ]);
    }

    public function ajouterObjectif(Request $request)
    {
        $listEmploye = DB::select("SELECT * FROM employes WHERE domain_id = :id AND id!=:id1",['id'=> session('domainConnect')->id,'id1'=>session('employeConnect')->id]);
        return response()->json([
            'user' => session('userConnect'),
            'domain' => session('domainConnect'),
            'employe' => session('employeConnect'),
            'listEmploye' => $listEmploye,
        ]);
    }
}
