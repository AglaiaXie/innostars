<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Industry;

class IndustryController extends Controller
{
    public function index()
    {
        return Industry::orderBy('name')->get();
    }
}
