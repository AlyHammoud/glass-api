<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;

class ProductsImagesController extends Controller
{
    public function storeEnglish(Request $request)
    {

        $path = public_path('tmpProductsEnglish/');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);
        $url = URL::to('/');
        return response([
            'name' => $name,
            'url' => $url
        ]);
    }
}
