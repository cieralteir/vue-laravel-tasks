<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\DeleteFileAction;
use App\Http\Controllers\Controller;
use App\Models\Files\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Display file
     *
     * @param File $file
     */
    public function show(File $file)
    {
        return Storage::download($file->filename);
    }

    /**
     * Delete file
     *
     * @param File $file
     */
    public function destroy(File $file)
    {
        DeleteFileAction::execute($file);

        return response()->json([], 204);
    }
}
