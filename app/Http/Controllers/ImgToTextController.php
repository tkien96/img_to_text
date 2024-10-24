<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Illuminate\Http\Request;

class ImgToTextController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __invoke()
    {
        // ...
    }

    public function index(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480', // Validate multiple images
        ]);
        $client = new ImageAnnotatorClient([
            'credentials' => storage_path('app/google/convert-img-to-text-e904e692d968.json')
        ]);

        // Process each uploaded image
        $results = [];
        foreach ($request->file('images') as $image) {
            $text = '';
            $img_name = $image->getClientOriginalName();
            $imageContent = file_get_contents($image->getRealPath());
            // Call the Vision API for text detection
            $response = $client->textDetection($imageContent);
            $annotations = $response->getTextAnnotations();
            if ($annotations) $text = $annotations[0]->getDescription();

            $text = trim(str_replace("\n", " ", $text));
            // Use regular expressions to extract the required fields
            preg_match('/^(.*?)©.*?Đã xem bởi (.*?) - (\d{2}:\d{2})/', $text, $matches);
            if(empty($matches)) preg_match('/^(.*?)\s*[\*\©]\s*Đã xem bởi\s*(.*?)\s*-\s*(\d{2}:\d{2})/', $text, $matches);
            if(empty($matches)) preg_match('/^(.*?)\s*Đã xem bởi\s*(.*?)\s*-\s*(\d{2}:\d{2})/', $text, $matches);
            if(empty($matches)) preg_match('/^(.*?)\s*đã xem bởi\s*(.*?)\s*-\s*(\d{2}:\d{2})/', $text, $matches);
            preg_match('/(\d{9,11})/', $text, $phoneMatches);
            preg_match('/(https?:\/\/[^\s]+)/', $text, $linkMatches);

            // Structure the result
            $results[] = [
                'customer' => !empty($matches[1]) ? trim($matches[1]) : '',
                'phone'    => isset($phoneMatches[1]) ? trim($phoneMatches[1]) : '',
                'link'     => isset($linkMatches[0]) ? trim($linkMatches[0]) : '',
                'sale'     => isset($matches[2]) ? trim($matches[2]) : '',
                'time'     => isset($matches[3]) ? trim($matches[3]) : '',
                'content'  => $text,
                'file'     => $img_name
            ];
        }

        $client->close();
        return view('index', ['data' => $results]);
    }
}
