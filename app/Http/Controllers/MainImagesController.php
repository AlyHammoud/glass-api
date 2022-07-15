<?php

namespace App\Http\Controllers;

use App\Models\MainInfo;
use Illuminate\Http\Request;

class MainImagesController extends Controller
{
    public function store(Request $request){
        
        $path = public_path('tmp/uploads');

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

    public function getImages(MainInfo $mainInfo){
        $images = $mainInfo->mainImages;

        return response([
            'images' => $images
        ]);
    }
}
