<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\SemifinalForm;
use App\Models\SemifinalFormPerson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class SemifinalFormSemifinalFormPersonController extends Controller
{
    public function store(SemifinalForm $semifinal_form, Request $request)
    {
        $semifinal_form->semifinal_form_people()->create(['name' => $request->get('name')]);

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function destroy(SemifinalForm $semifinal_form, SemifinalFormPerson $semifinal_form_person)
    {
        $semifinal_form_person->delete();

        return response('', Response::HTTP_NO_CONTENT);
    }
}
