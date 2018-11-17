<?php namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function show($hash, Request $request)
    {
        if (!$file = File::where('disk_name', '=', $hash)->first()) {
            return response('', Response::HTTP_NOT_FOUND);
        }

        if (!file_exists(storage_path('files/' . $file->disk_name))) {
            return  response('', Response::HTTP_NOT_FOUND);
        }

        if ($request->has('preview')) {
            return response()->download(storage_path('files/' . $file->disk_name), $file->filename, [], 'inline');
        }

        return response()->download(storage_path('files/' . $file->disk_name), $file->filename);
    }

    public function destroy(File $file)
    {
        if (!$file) {
            return response('', Response::HTTP_NOT_FOUND);
        }

        Storage::disk('files')->delete($file->disk_name);

        $file->delete();

        return response('', Response::HTTP_NO_CONTENT);
    }
}
