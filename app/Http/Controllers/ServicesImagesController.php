<?php

namespace App\Http\Controllers;

use App\Models\MainInfo;
use App\Models\Service;
use Illuminate\Http\Request;

class ServicesImagesController extends Controller
{
    public function store(Request $request){
        
        $path = public_path('tmpServices/');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('image');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response([
            'name' => $name,
        ]);
    }

    public function getImage(Service $service){
        $images = $service->servicesImages;

        return response([
            'images' => $images
        ]);
    }
}
