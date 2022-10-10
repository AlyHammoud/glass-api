<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\URL;
use App\Models\CustomersReview;
use App\Models\MainImage;
use App\Models\MainInfo;
use App\Models\Service;
use App\Models\ServicesImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ServicesController extends Controller
{
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'nameArabic' => 'required'
        ]);
        // return response($request->nameArabic);
        $services = $request->user()->services()->create([
            'name' => $request->name,
            'extraDetails' => $request->extraDetails,
            'extraDetailsArabic' => $request->extraDetailsArabic,
        ]);

        $request->user()->services()->where('id', $services->id)->update(['nameArabic' => $request->nameArabic]);

        if ($request->images) {
            foreach ($request->images as $image) {
                $from = public_path('tmpServices/' . $image['name']);
                $to = public_path('services_images/' . $image['name']);
                File::move($from, $to);

                $services->servicesImages()->create([
                    'name' => $image['name'],
                ]);
            }
        }

        // $mainInfos = MainInfo::orderBy('id', 'desc')->get()->first();
        $services = $request->user()->services()->orderBy('id', 'desc')->get()->first();
        $servicesImages = ServicesImage::where('service_id', $services->id)->get();

        return response([
            'services' => $services,
            'servicesImages' => $servicesImages
        ]);
    }

    public function allServices(Service $service)
    {
        $services = $service->get();


        if (!empty($services)) {
            foreach ($services as $service) {
                $service['images'] = ServicesImage::where('service_id', $service->id)->get();
                foreach ($service['images'] as $key => $serviceImage) {
                    $service['images'][$key]['name'] = URL::to('services_images/' . $serviceImage->name);
                }
            }
        }

        return response([
            'allServices' => $services,
        ]);
    }

    public function allServicesImages(ServicesImage $serviceImage)
    {
        $images = $serviceImage->paginate(20);

        foreach ($images as $key => $image) {
            $images[$key]['name'] = URL::to('services_images/' . $image->name);
        }


        return response([
            'allServicesImages' => $images
        ]);
    }

    public function singleService($serviceName)
    {
        $service = Service::where('name', $serviceName)->get();
        $images = [];
        if (count($service) > 0) {
            $service = $service[0];
            $images = ServicesImage::where('service_id', $service->id)->paginate(15);
            foreach ($images as $key => $image) {
                $images[$key]['name'] = URL::to('services_images/' . $image->name);
            }
        }

        return response([
            'service' => $service,
            'images' => $images
        ]);
    }

    public function update(Request $request, Service $service)
    {
        $validate = $request->validate([
            'name' => 'required'
        ]);

        if ($request->user()->id != $service->user_id) {
            return response(['error' => "not allowed"], 405);
        }

        $service->update([
            'name' => $request->name,
            'nameArabic' => $request->nameArabic,
            'extraDetails' => $request->extraDetails,
            'extraDetailsArabic' => $request->extraDetailsArabic,
        ]);

        //update delete backgrounds
        if (isset($request->images[0]['added_media'])) {
            foreach ($request->images[0]['added_media'] as $image) {
                $from = public_path('tmpServices/' . $image['name']);
                $to = public_path('services_images/' . $image['name']);

                File::move($from, $to);

                $service->servicesImages()->create([
                    'name' => $image['name'],
                ]);
            }
        }

        if (isset($request->images[1]['deleted_media'])) {
            foreach ($request->images[1]['deleted_media'] as $deleted_media) {
                File::delete(public_path('services_images/' . $deleted_media['name']));
                ServicesImage::where('name', $deleted_media['name'])->delete();
            }
        }

        return response([
            'status' => true
        ]);
    }
}
