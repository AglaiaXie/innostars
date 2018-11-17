<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\SemifinalForm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function destroy(File $file)
    {
        if (!$file) {
            return response('', Response::HTTP_NOT_FOUND);
        }

        if ($file->user_id !== Auth::id()) {
            return response('', Response::HTTP_UNAUTHORIZED);
        }

        Storage::disk('files')->delete($file->disk_name);

        $file->delete();

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function store(Request $request)
    {
        if ($file = $request->file('file')) {
            $filename = md5(uniqid(rand(), true));
            Storage::disk('files')->put($filename, file_get_contents($file->path()));

            return File::create([
                'filename' => $file->getClientOriginalName(),
                'disk_name' => $filename,
                'user_id' => Auth::id(),
            ]);
        }

        return response('', Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
