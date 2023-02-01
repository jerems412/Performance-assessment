<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthentificationController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\RhController;
use App\Http\Controllers\EmployeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//domain
Route::post('/domainExist', [AuthentificationController::class, 'domainExist']);
Route::post('/login', [AuthentificationController::class, 'login']);
Route::post('/register', [AuthentificationController::class, 'register']);
Route::get('/space', [AuthentificationController::class, 'space']);


//manager
Route::get('/spaceManager', [ManagerController::class, 'spaceManager']);
Route::post('/persistEvaluation', [ManagerController::class, 'persist_evaluation']);
Route::post('/mnupdateEvaluation{id?}', [ManagerController::class, 'update_evaluation']);
Route::post('/mncloseEvaluation{id?}', [ManagerController::class, 'close_evaluation']);
Route::post('/mncloseObjectif{id?}', [ManagerController::class, 'close_objectif']);
Route::post('/mnupdateProfil', [ManagerController::class, 'update_profil']);
Route::post('/persistObjectif', [ManagerController::class, 'persist_objectif']);
Route::post('/mnupdateObjectif{id?}', [ManagerController::class, 'update_objectif']);
Route::post('/mnupdateProfil', [ManagerController::class, 'update_profil']);
Route::get('/mneditProfil', [ManagerController::class, 'editProfil']);
Route::get('/mnlisterEvaluations', [ManagerController::class, 'listerEvaluations']);
Route::get('/mnlistEmployeEvaluation{id?}', [ManagerController::class, 'listEmployeEvaluation']);
Route::get('/mnlistEmployeObjectif{id?}', [ManagerController::class, 'listEmployeObjectif']);
Route::get('/mnajouterEvaluation', [ManagerController::class, 'ajouterEvaluation']);
Route::get('/mnmodifierEvaluation{id?}', [ManagerController::class, 'modifierEvaluation']);
Route::get('/mnmodifierObjectif{id?}', [ManagerController::class, 'modifierObjectif']);
Route::get('/commentaireObjectif{id?}', [ManagerController::class, 'commentaireObjectif']);
Route::get('/mnajouterObjectif', [ManagerController::class, 'ajouterObjectif']);
Route::get('/mnlisterObjectifs', [ManagerController::class, 'listerObjectifs']);
Route::get('/mndetailsObjectifs{id?}', [ManagerController::class, 'detailsObjectifs']);
Route::get('/mndetailsEvaluations{id?}', [ManagerController::class, 'detailsEvaluations']);
Route::get('/mnfaireEvaluation{id?}', [ManagerController::class, 'faireEvaluation']);
Route::get('/mnfaireEvaluationEmploye{id?}/{idEmploye?}', [ManagerController::class, 'faireEvaluationEmploye']);
Route::get('/mnrapportEvaluation{id?}', [ManagerController::class, 'rapportEvaluation']);
Route::get('/mnrapportObjectif{id?}/{idEmploye?}', [ManagerController::class, 'rapportObjectif']);
Route::get('/mnrapportEvaluationEmploye{id?}/{idEmploye?}', [ManagerController::class, 'rapportEvaluationEmploye']);
Route::post('/mnpersistCommentaire{id?}/{form?}/{id1?}', [ManagerController::class, 'persist_commentaire']);
Route::post('/faire{idEv?}/{idEmp?}', [ManagerController::class, 'faire']);

//rh
Route::post('/persistEmploye', [RhController::class, 'persist_employe']);
Route::post('/rhupdateEmploye{id?}', [RhController::class, 'update_employe']);
Route::post('/updateDomain', [RhController::class, 'update_domain']);
Route::post('/rhupdateProfil', [RhController::class, 'update_profil']);
Route::get('/spaceRh', [RhController::class, 'spaceRh']);
Route::get('/rhlistEmployes', [RhController::class, 'listerEmployes']);
Route::get('/rhmodifierEmploye{id?}', [RhController::class, 'ModifierEmploye']);
Route::get('/rhmodifierDomain', [RhController::class, 'modifierDomain']);
Route::get('/rhajouterEmploye', [RhController::class, 'ajouterEmploye']);
Route::get('/rhlisterEvaluations', [RhController::class, 'listerEvaluations']);
Route::get('/rhlisterObjectifs', [RhController::class, 'listerObjectifs']);
Route::get('/rhdetailsObjectifs{id?}', [RhController::class, 'detailsObjectifs']);
Route::get('/rhdetailsEvaluations{id?}', [RhController::class, 'detailsEvaluations']);
Route::get('/rhfaireEvaluation{id?}', [RhController::class, 'faireEvaluation']);
Route::get('/rhrapportEvaluation{id?}', [RhController::class, 'rapportEvaluation']);
Route::get('/rheditProfil', [RhController::class, 'editProfil']);
Route::get('/rhbloquerEmploye{id?}', [RhController::class, 'bloquer_employe']);
Route::get('/rhdebloquerEmploye{id?}', [RhController::class, 'debloquer_employe']);

//employe
Route::get('/spaceEmploye', [EmployeController::class, 'spaceEmploye']);
Route::get('/emplisterEvaluations', [EmployeController::class, 'listerEvaluations']);
Route::get('/emplisterObjectifs', [EmployeController::class, 'listerObjectifs']);
Route::get('/empdetailsObjectifs{id?}', [EmployeController::class, 'detailsObjectifs']);
Route::get('/empdetailsEvaluations{id?}', [EmployeController::class, 'detailsEvaluations']);
Route::get('/empfaireEvaluation{id?}', [EmployeController::class, 'faireEvaluation']);
Route::get('/emprapportEvaluation{id?}', [EmployeController::class, 'rapportEvaluation']);
Route::get('/empeditProfil', [EmployeController::class, 'editProfil']);
Route::post('/empupdateProfil', [EmployeController::class, 'update_profil']);
Route::post('/emppersistCommentaire{id?}/{form?}/{id1?}', [EmployeController::class, 'persist_commentaire']);
Route::post('/valideEtape{id?}/{id2?}/{id3?}', [EmployeController::class, 'valider']);