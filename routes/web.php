<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');

Auth::routes();

Route::group(['prefix' => 'admin', 'middleware' => ['role:admin|investor|partner']], function() {
    Route::resource('participants', 'ParticipantController');
    Route::resource('judges', 'JudgeController');
    Route::resource('areas', 'AreaController');
    Route::resource('industries', 'IndustryController');
    Route::get('competitions/{competition}/assign', 'CompetitionController@assign');
    Route::get('competitions/{competition}/downloadParticipants', 'CompetitionController@downloadParticipants');
    Route::get('competitions/{competition}/downloadJudges', 'CompetitionController@downloadJudges');
    Route::resource('competitions', 'CompetitionController');
    Route::resource('competitions.companies', 'CompetitionCompanyController');
    Route::resource('messages', 'MessageController');
    Route::resource('scores', 'ScoreController');
    Route::get('masquerade/{user}', 'MasqueradeController@go');
    Route::get('investors', 'InvestorController@index');
    Route::get('partners', 'PartnerController@index');
    Route::get('schedules/list', 'ScheduleController@list');
});
Route::group(['prefix' => 'admin', 'middleware' => ['role:judge|participant|partner|investor|admin']], function() {
    Route::get('events', 'EventController@index');
    Route::get('schedules', 'ScheduleController@index');
});

Route::group(['prefix' => 'common', 'middleware' => ['role:judge|participant']], function() {
    Route::get('messages/admins', 'CommonMessageController@admins');
    Route::get('messages/participants', 'CommonMessageController@participants');
    Route::get('messages/judges', 'CommonMessageController@judges');
    Route::post('messages/participants', 'CommonMessageController@store');
    Route::post('messages/judges', 'CommonMessageController@store');
    Route::post('messages/admins', 'CommonMessageController@store');
    Route::resource('messages', 'CommonMessageController');
});

Route::group(['prefix' => 'participant', 'middleware' => ['role:participant']], function() {
    Route::get('', 'ParticipantDashboardController@index');
    Route::resource('competitions', 'ParticipantCompetitionController');
    Route::resource('competitions.companies', 'ParticipantCompetitionCompanyController');
    Route::resource('threads', 'ParticipantMessageController');
    Route::resource('judge-profiles', 'ParticipantJudgeController');
    Route::get('profile/company', 'ParticipantProfileController@editCompany');
    Route::post('profile/company', 'ParticipantProfileController@saveCompany');
    Route::get('profile/contact', 'ParticipantProfileController@editContact');
    Route::post('profile/contact', 'ParticipantProfileController@saveContact');
    Route::get('profile/project', 'ParticipantProfileController@editProject');
    Route::post('profile/project', 'ParticipantProfileController@saveProject');
    Route::get('profile/addition', 'ParticipantProfileController@editAddition');
    Route::post('profile/addition', 'ParticipantProfileController@saveAddition');
    Route::get('profile/file', 'ParticipantProfileController@editFile');
    Route::post('profile/file', 'ParticipantProfileController@saveFile');
    Route::get('profile/submit', 'ParticipantProfileController@editSubmit');
    Route::post('profile/submit', 'ParticipantProfileController@saveSubmit');
    Route::get('profile/information', function() {
        return Redirect::to('participant/profile/company');
    });
});

Route::group(['prefix' => 'judge', 'middleware' => ['role:judge']], function() {
    Route::get('', 'JudgeDashboardController@index');
    Route::resource('competitions', 'JudgeCompetitionController');
    Route::resource('competitions.companies', 'JudgeCompetitionCompanyController');
    Route::resource('threads', 'JudgeMessageController');
    Route::resource('companies', 'JudgeCompanyController');
    Route::get('profile/information', 'JudgeProfileController@editInformation');
    Route::post('profile/information', 'JudgeProfileController@saveInformation');
    Route::get('profile/preference', 'JudgeProfileController@editPreference');
    Route::post('profile/preference', 'JudgeProfileController@savePreference');
    Route::get('profile/addition', 'JudgeProfileController@editAddition');
    Route::post('profile/addition', 'JudgeProfileController@saveAddition');
    Route::get('profile/file', 'JudgeProfileController@editFile');
    Route::post('profile/file', 'JudgeProfileController@saveFile');
    Route::get('profile/submit', 'JudgeProfileController@editSubmit');
    Route::post('profile/submit', 'JudgeProfileController@saveSubmit');
});

Route::group(['prefix' => 'investor', 'middleware' => ['role:investor|new_investor']], function() {
    Route::get('profile/information', 'InvestorProfileController@editInformation');
    Route::post('profile/information', 'InvestorProfileController@saveInformation');
    Route::get('profile/preference', 'InvestorProfileController@editPreference');
    Route::post('profile/preference', 'InvestorProfileController@savePreference');
    Route::get('profile/file', 'InvestorProfileController@editFile');
    Route::post('profile/file', 'InvestorProfileController@saveFile');
    Route::get('profile/submit', 'InvestorProfileController@editSubmit');
    Route::post('profile/submit', 'InvestorProfileController@saveSubmit');
});


Route::group(['prefix' => 'partner', 'middleware' => ['role:partner|new_partner']], function() {
    Route::get('profile/information', 'PartnerProfileController@editInformation');
    Route::post('profile/information', 'PartnerProfileController@saveInformation');
    Route::get('profile/preference', 'PartnerProfileController@editPreference');
    Route::post('profile/preference', 'PartnerProfileController@savePreference');
    Route::get('profile/file', 'PartnerProfileController@editFile');
    Route::post('profile/file', 'PartnerProfileController@saveFile');
    Route::get('profile/submit', 'PartnerProfileController@editSubmit');
    Route::post('profile/submit', 'PartnerProfileController@saveSubmit');
});

Route::get('file/{hash}', 'FileController@show');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('file', 'FileController', ['except' => 'show']);
});
