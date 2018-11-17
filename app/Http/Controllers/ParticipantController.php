<?php namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Industry;
use App\Models\JoinedCompetition;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use League\Csv\Writer;
use PragmaRX\Countries\Facade;
use SplTempFileObject;

class ParticipantController extends Controller
{
    public function index()
    {
        return view('admin.page.participant-list');
    }
}
