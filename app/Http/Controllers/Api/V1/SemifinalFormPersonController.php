<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\SemifinalForm;
use App\Models\SemifinalFormPerson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class SemifinalFormPersonController extends Controller
{
    public function update(SemifinalFormPerson $semifinal_form_person, Request $request)
    {
        if (($file = $request->input('file'))) {
            $semifinal_form_person->update(['passport_id' => $file['id']]);

            return  response('', Response::HTTP_NO_CONTENT);
        }

        return response('', Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
