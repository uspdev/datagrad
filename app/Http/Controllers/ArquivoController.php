<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

class ArquivoController extends Controller
{
    /**
     * Permite realizar o download de arquivo com assinatura de tempo
     */
    public function download(Request $request)
    {
        if (!URL::signatureHasNotExpired($request)) {
            return response('A URL expirou.');
        }

        if (!URL::hasCorrectSignature($request)) {
            return response('A URL fornecida é inválida.');
        }

        return response()->file(Storage::path($request->get('path')));
    }
}
