<?php

namespace App\CPU;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class Images
{
    public static function upload($folder, $image,$type='images')
    {
        $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

        $originalName = str_replace(' ', '-', $originalName);

        $fileName = $originalName . rand(1000, 99999) . '.' . $image->getClientOriginalExtension();

        $image->storeAs($type . '/' . $folder, $fileName, 'public'); // Specify 'public' disk
        $image->storeAs($type . '/' . $folder, $fileName, 'public');

        $productImage = 'storage/'. $type . '/' . $folder . '/' . $fileName;
        return $productImage;
    }

    public static function uploadImageFromUrl($url, $folder, $productName)
    {
        try {
            $imageContents = file_get_contents($url);

            // Get the original filename and extension
            $originalName = Str::slug($productName) . '-' . rand(1000, 9999);
            $originalName = str_replace(' ', '-', $originalName);
            $extension = pathinfo($url, PATHINFO_EXTENSION);

            // Generate a unique file name
            $fileName = $originalName . rand(1000, 99999) . '.' . $extension;
            $filePath = 'images/' . $folder . '/' . $fileName;

            // Store the file in the public disk
            Storage::disk('public')->put($filePath, $imageContents);

            // Return the file path
            return 'storage/' . $filePath;
        } catch (\Exception $e) {
            return null;
        }
    }


    public static function delete($image)
    {
        if (file_exists(public_path($image))) {
            unlink(public_path($image));
            return true;
        }
    }

    public static function show($path)
    {
        if ($path && file_exists(public_path($path))) {
            return '<img src="' . asset($path) . '" alt="Image ' . $path . '" style="width:70px;">';
        } else {
            $placeholder = 'pictures/placeholder.jpg';
            return '<img src="' . asset($placeholder) . '" alt="Placeholder Image" style="width:70px;">';
        }
    }


    public static function update($folder, $oldImagePath, $newImage)
    {
        self::delete($oldImagePath);

        $fileName = time() . rand(100, 999) . '.' . $newImage->getClientOriginalExtension();

        $newImage->storeAs('public/images/' . $folder, $fileName);

        $productImage = 'storage/images/' . $folder . '/' . $fileName;

        return $productImage;
    }

    public static function convertToWebP($sourceImagePath, $outputImagePath, $quality = 80)
    {
        // Check if the source image exists
        if (!file_exists($sourceImagePath)) {
            return false;
        }

        // Get the image information
        $info = getimagesize($sourceImagePath);
        $mime = $info['mime'];

        // Load the image based on mime type
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($sourceImagePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($sourceImagePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($sourceImagePath);
                break;
            default:
                return false; // Unsupported file type
        }

        // Convert to WebP and save
        if (imagewebp($image, $outputImagePath, $quality)) {
            imagedestroy($image); // Free up memory
            return true; // Successfully converted
        }

        // Free up memory in case of failure
        imagedestroy($image);
        return false;
    }

}
