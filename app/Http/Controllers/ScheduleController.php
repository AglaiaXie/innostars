<?php namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\File;
use App\Models\Industry;
use App\Models\InvestorCompetition;
use App\Models\SubIndustry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class ScheduleController extends Controller
{
    public function index()
    {
        return view('admin.page.schedule-list');
    }

    public function list()
    {
        return view('admin.page.schedule-admin');
    }
}
