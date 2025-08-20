<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FileImportController extends Controller
{
    public function import(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|file|mimes:csv,xls,xlsx',
        ]);

        // Store the uploaded file temporarily
        $filePath = $request->file('file')->store('uploads');
        $fullPath = storage_path('app/' . $filePath);

        // Determine the file extension
        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);

        if ($extension === 'csv') {
            return $this->importCsv($fullPath);
        } elseif (in_array($extension, ['xls', 'xlsx'])) {
            return $this->importExcel($fullPath);
        }

        return redirect()->back()->withErrors(['file' => 'Invalid file type.']);
    }

    private function importCsv($fullPath)
    {
        if (($handle = fopen($fullPath, 'r')) !== false) {
            $header = fgetcsv($handle); // Get the header row

            while (($data = fgetcsv($handle)) !== false) {
                // Combine header with data
                $row = array_combine($header, $data);
                $this->processRow($row); // Process each row
            }

            fclose($handle);
        }

        return redirect()->back()->with('success', 'CSV imported successfully!');
    }

    private function importExcel($fullPath)
    {
        // Open the Excel file
        $file = fopen($fullPath, 'rb');
        $data = fread($file, filesize($fullPath));
        fclose($file);

        // Check the first few bytes to determine the file format
        if (substr($data, 0, 3) === "\x50\x4b\x03") { // Check for .xlsx
            // Extract content from the .xlsx file
            // This is a simplified example; parsing .xlsx files manually can be complex
            return redirect()->back()->withErrors(['file' => 'Excel .xlsx files require a library for parsing.']);
        } elseif (substr($data, 0, 2) === "\xD0\xCF") { // Check for .xls
            // Implement parsing logic for .xls files if needed (complex)
            return redirect()->back()->withErrors(['file' => 'Excel .xls files require a library for parsing.']);
        }

        return redirect()->back()->withErrors(['file' => 'Unsupported file format.']);
    }

    private function processRow($row)
    {
        // Process image upload if an image link exists
        if (isset($row['image'])) {
            $imageData = file_get_contents($row['image']);
            $imagePath = $this->upload($imageData); // Call your upload function
        }

        // Create a new model instance (replace with your model)
        // User::create([
        //     'name' => $row['name'],
        //     'email' => $row['email'],
        //     'password' => bcrypt($row['password']),
        //     'image' => $imagePath ?? null,
        // ]);
    }

    private function upload($imageData)
    {
        // Logic to upload the image (similar to the previous example)
        $imageName = uniqid() . '.jpg'; // Generate a unique name
        $path = 'images/' . $imageName; // Define the path

        // Save the image to storage
        file_put_contents(storage_path('app/' . $path), $imageData);

        return $path; // Return the relative path to the image
    }
}

