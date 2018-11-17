<?php

use Illuminate\Http\Request;

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
Route::group(['middleware' => ['role:admin']], function() {
    Route::get('participants/download', 'Api\V1\ParticipantController@download');
    Route::get('judges/download', 'Api\V1\JudgeController@download');
    Route::get('investors/download', 'Api\V1\InvestorController@download');
    Route::get('partners/download', 'Api\V1\PartnerController@download');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['role:admin|investor|partner']], function() {
    Route::get('investors/all', 'Api\V1\InvestorController@all');
    Route::resource('investors', 'Api\V1\InvestorController', ['except' => ['show']]);
    Route::get('judges/all', 'Api\V1\JudgeController@all');
    Route::resource('judges', 'Api\V1\JudgeController', ['except' => ['show']]);
    Route::get('participants/all', 'Api\V1\ParticipantController@all');
    Route::resource('participants', 'Api\V1\ParticipantController', ['except' => ['show']]);
    Route::resource('companies.scores', 'Api\V1\CompanyScoreController');
    Route::post('companies/{company}/auto-assign', 'Api\V1\CompanyController@autoAssign');
});

Route::group(['middleware' => ['role:admin|partner']], function() {
    Route::resource('partners', 'Api\V1\PartnerController', ['except' => ['show']]);
    Route::post('events', 'Api\V1\EventController@store');
    Route::put('events/{event}', 'Api\V1\EventController@update');
    Route::delete('events/{event}', 'Api\V1\EventController@destroy');
    Route::post('events/{event}/time-slots', 'Api\V1\EventTimeSlotController@store');
    Route::delete('events/{event}/time-slots/{time_slot}', 'Api\V1\EventTimeSlotController@destroy');
    Route::post('events/{event}/users', 'Api\V1\EventUserController@store');
    Route::get('competitions/{competition}', 'Api\V1\CompetitionController@show');
    Route::resource('competitions.companies', 'Api\V1\CompetitionParticipantController');
    Route::resource('competitions.judgings', 'Api\V1\CompetitionJudgeController');
    Route::get('companies/{company}/next-competitions', 'Api\V1\CompanyController@getNextCompetitions');
    Route::get('companies/{company}/selected-next-competition', 'Api\V1\CompanyController@getSelectedNextCompetition');
    Route::put('companies/{company}', 'Api\V1\CompanyController@update');
    Route::delete('events/{event}/delete-time-slots-by-day', 'Api\V1\EventTimeSlotController@deleteDay');
});

Route::get('participants/{participant}', 'Api\V1\ParticipantController@show');
Route::get('judges/{judge}', 'Api\V1\JudgeController@show');
Route::get('investors/{investor}', 'Api\V1\InvestorController@show');
Route::get('partners/{partner}', 'Api\V1\PartnerController@show');
Route::get('industries', 'Api\V1\IndustryController@index');
Route::get('competitions', 'Api\V1\CompetitionController@index');
Route::get('events', 'Api\V1\EventController@index');
Route::get('events/{event}', 'Api\V1\EventController@show');
Route::resource('events/{event}/schedules', 'Api\V1\EventScheduleController');
Route::resource('schedules', 'Api\V1\ScheduleController');
Route::resource('messages', 'Api\V1\MessageController');
Route::resource('threads', 'Api\V1\ThreadController');
Route::resource('threads.messages', 'Api\V1\ThreadMessageController');
Route::get('events/{event}/time-slots', 'Api\V1\EventTimeSlotController@index');
Route::get('events/{event}/time-slots-by-day', 'Api\V1\EventTimeSlotController@days');
Route::post('time-slots/{time_slot}/schedules', 'Api\V1\TimeSlotScheduleController@store');
Route::post('users/{user}/semifinal_forms/{semifinal_form}/file', 'Api\V1\UserSemifinalFormController@upload');
Route::resource('users.semifinal_forms', 'Api\V1\UserSemifinalFormController');
Route::resource('users.final_forms', 'Api\V1\UserFinalFormController');
Route::resource('files', 'Api\V1\FileController');
Route::post('semifinal_forms/{semifinal_form}/semifinal_form_persons', 'Api\V1\SemifinalFormSemifinalFormPersonController@store');
Route::delete('semifinal_forms/{semifinal_form}/semifinal_form_people/{semifinal_form_person}', 'Api\V1\SemifinalFormSemifinalFormPersonController@destroy');
Route::put('semifinal_form_people/{semifinal_form_person}', 'Api\V1\SemifinalFormPersonController@update');
