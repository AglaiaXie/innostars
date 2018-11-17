<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FinalForm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserFinalFormController extends Controller
{
    public function show(User $user, FinalForm $final_form)
    {
        return $final_form->load([
            'flight_ticket_receipt',
            'pitch_deck',
        ]);
    }

    public function update(User $user, FinalForm $final_form, Request $request)
    {
        if (($file = $request->input('file')) && ($relationship = $request->get('relationship'))) {
            $final_form->update([$relationship . '_id' => $file['id']]);
        }

        return response('', Response::HTTP_NO_CONTENT);
    }
}
