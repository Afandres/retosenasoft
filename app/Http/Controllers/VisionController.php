<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
    
class VisionController extends Controller
{
    public function predict(Request $request)
    {
        $predictionUrl = "https://challengesenasoft.cognitiveservices.azure.com/";
        $predictionKey = "79f2f0f2970d491ba8bf6df46c53fde8";

        $imageUrl = $request->input('url');
        $imageFile = $request->file('file');

        if ($imageUrl) {
            $body = "{'url' : '$imageUrl'}";
        } elseif ($imageFile) {
            // Maneja la carga de imágenes locales aquí si es necesario
            // $body = ... (configura la carga de imágenes locales)
        } else {
            return response()->json(['error' => 'No se proporcionó una URL o archivo.'], 400);
        }

        $headers = [
            'Prediction-Key' => $predictionKey,
            'Content-Type' => 'application/json',
        ];

        $client = new \GuzzleHttp\Client();
        $response = $client->post($predictionUrl, [
            'headers' => $headers,
            'body' => $body,
        ]);

        return $response->getBody();
    }
}

