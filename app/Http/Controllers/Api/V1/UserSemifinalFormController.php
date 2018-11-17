<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\SemifinalForm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class UserSemifinalFormController extends Controller
{
    public function show(User $user, SemifinalForm $semifinal_form)
    {
        return $semifinal_form->load([
            'flight_ticket_receipt',
            'visa',
            'consent_form',
            'registration_form',
            'executive_summary',
            'pitch_deck',
            'semifinal_form_people.passport',
        ]);
    }

    public function update(User $user, SemifinalForm $semifinal_form, Request $request)
    {
        if (($file = $request->input('file')) && ($relationship = $request->get('relationship'))) {
            $semifinal_form->update([$relationship . '_id' => $file['id']]);
        }

        return response('', Response::HTTP_NO_CONTENT);
    }
}
