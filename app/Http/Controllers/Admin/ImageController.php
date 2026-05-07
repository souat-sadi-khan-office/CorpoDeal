<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $baseFolder = 'global';
        $currentFolder = $request->get('folder');

        $folderPath = $currentFolder ? $currentFolder : $baseFolder;

        $folders = Storage::disk('public')->directories($folderPath);
        $images = Storage::disk('public')->files($folderPath);

        $parentFolder = null;
        if ($currentFolder) {
            $parts = explode('/', $currentFolder);
            array_pop($parts);
            $parentFolder = implode('/', $parts); 
        }

        return view('backend.images.index', compact('folders', 'images', 'currentFolder', 'parentFolder'));
    }

    public function createFolder(Request $request)
    {
        $request->validate([
            'folder_name' => ['required', 'string', 'max:50'],
        ]);

        $folderName = str_replace(' ', '-', $request->folder_name);
        $folderName = strtolower($folderName);

        $parentFolder = $request->input('parent_folder');
        $base = 'global'; 
        $path = $parentFolder ?  $parentFolder . '/' . $folderName : $base . '/' . $folderName;

        if (!Storage::disk('public')->exists($path)) {
            Storage::disk('public')->makeDirectory($path);
        }

        return redirect()->back()->with('success', 'Folder created successfully!');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'images.*' => 'required|file|mimes:jpeg,jpg,png,webp,gif,svg|max:512',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if (!$file->isValid()) {
                    continue; 
                }

                $originalName = $file->getClientOriginalName();
                $extension = strtolower($file->getClientOriginalExtension());
                $filename = strtolower(preg_replace('/[^a-z0-9\-]+/', '-', pathinfo($originalName, PATHINFO_FILENAME)));
                $filename = substr($filename, 0, 50);

                if ($request->filled('parent_folder')) {
                    $parentFolder = trim($request->input('parent_folder'), '/');
                    $parts = explode('/', $parentFolder);
                    $cleanParts = array_map(fn($part) => strtolower(preg_replace('/[^a-z0-9\-]+/', '-', $part)), $parts);
                    $folder = implode('/', $cleanParts);
                } else {
                    $folder = 'global';
                }

                Storage::disk('public')->makeDirectory($folder);
                $file->storeAs($folder, $filename . '.' . $extension, 'public');
            }
        }


        return back()->with('success', 'Files uploaded successfully!');
    }
}