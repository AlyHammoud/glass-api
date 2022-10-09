<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\URL;
use App\Models\CustomersReview;
use App\Models\MainImage;
use App\Models\MainInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\App;

class MainInfosController extends Controller
{
    public function store(Request $request)
    {
        $validate = $request->validate([
            'aboutUsEnglish' => 'required'
        ]);

        $mainInfo = $request->user()->MainInfo()->create([
            'whatsapp' => $request->whatsapp,
            'facebook' => $request->facebook,
            'youtube' => $request->youtube,
            'instagram' => $request->instagram,
            'aboutUsEnglish' => $request->aboutUsEnglish,
            'aboutUsArabic' => $request->aboutUsArabic,
            'totalProjects' => $request->totalProjects,
            'totalCustomers' => $request->totalCustomers,
            'totalExperience' => $request->totalExperience,
        ]);

        if (count($request->backgroundImages) > 0) {
            foreach ($request->backgroundImages as $image) {
                $from = public_path('tmp/uploads/' . $image['name']);
                $to = public_path('main_images/' . $image['name']);
                File::move($from, $to);

                $mainInfo->mainImages()->create([
                    'name' => $image['name'],
                ]);
            }
        }
        if (count($request->customersReviewsImages) > 0) {
            foreach ($request->customersReviewsImages as $image) {
                $from = public_path('tmp2/uploads/' . $image['name']);
                $to = public_path('customers_images/' . $image['name']);
                File::move($from, $to);

                $mainInfo->CustomersReviews()->create([
                    'name' => $image['name'],
                ]);
            }
        }


        // $mainInfos = MainInfo::orderBy('id', 'desc')->get()->first();
        $mainInfos = $request->user()->MainInfo()->orderBy('id', 'desc')->get()->first();
        $backgrounds = MainImage::where('main_infos_id', $mainInfos->id)->get();
        $customers_reviews = CustomersReview::where('main_infos_id', $mainInfos->id)->get();

        return response([
            'mainInfos' => $mainInfos,
            'customers_reviews' => $customers_reviews,
            'backgrounds' => $backgrounds
        ]);
    }

    public function getMainInfo()
    {
        
        $lang = Request()->server('HTTP_ACCEPT_LANGUAGE');
        $differLanguage = $lang == "English" ? "Arabic" : "English";

        $backgrounds = [];
        $customers_reviews = [];

        $mainInfos = MainInfo::orderBy('id', 'desc')->get()->first();
        if (!empty($mainInfos)) {

            foreach(collect($mainInfos) as $key => $mainInfo){
                if(Str::contains($key, $differLanguage)){
                    unset($mainInfos->$key);
                    continue;
                }
            }
            
            $backgroundsFirst = MainImage::where('main_infos_id', $mainInfos->id)->get();

            if (count($backgroundsFirst) > 0) {
                foreach ($backgroundsFirst as $key => $background) {
                    $backgrounds[] = URL::to('/main_images/' . $background->name);
                }
            }



            $customers_reviewsFirst = CustomersReview::where('main_infos_id', $mainInfos->id)->get();
            if (count($customers_reviewsFirst) > 0) {
                foreach ($customers_reviewsFirst as $key => $customers_review) {
                    $customers_reviews[] = URL::to('/customers_images/' . $customers_review->name);
                }
            }
        } else {
            $backgrounds = null;
            $customers_reviews = null;
        }


        return response([
            'mainInfos' => $mainInfos,
            'customers_reviews' => $customers_reviews,
            'backgrounds' => $backgrounds
        ]);
    }


    public function update(Request $request, MainInfo $mainInfo)
    {
        $validate = $request->validate([
            'aboutUsEnglish' => 'required'
        ]);

        if ($request->user()->id != $mainInfo->user_id) {
            return response(['error' => "not allowed"], 405);
        }

        $mainInfo->update([
            'whatsapp' => $request->whatsapp,
            'facebook' => $request->facebook,
            'youtube' => $request->youtube,
            'instagram' => $request->instagram,
            'aboutUsEnglish' => $request->aboutUsEnglish,
            'aboutUsArabic' => $request->aboutUsArabic,
            'totalProjects' => $request->totalProjects,
            'totalCustomers' => $request->totalCustomers,
            'totalExperience' => $request->totalExperience,
        ]);

        //update delete backgrounds
        if (isset($request->backgroundImages[0]['added_media'])) {
            foreach ($request->backgroundImages[0]['added_media'] as $image) {
                $from = public_path('tmp/uploads/' . $image['name']);
                $to = public_path('main_images/' . $image['name']);

                File::move($from, $to);

                $mainInfo->mainImages()->create([
                    'name' => $image['name'],
                ]);
            }
        }

        if (isset($request->backgroundImages[1]['deleted_media'])) {
            foreach ($request->backgroundImages[1]['deleted_media'] as $deleted_media) {
                File::delete(public_path('main_images/' . $deleted_media['name']));
                MainImage::where('name', $deleted_media['name'])->delete();
            }
        }

        //update delete customers reviews
        if (isset($request->customersReviewsImages[0]['added_media'])) {
            foreach ($request->customersReviewsImages[0]['added_media'] as $image) {
                $from = public_path('tmp2/uploads/' . $image['name']);
                $to = public_path('customers_images/' . $image['name']);

                File::move($from, $to);

                $mainInfo->CustomersReviews()->create([
                    'name' => $image['name'],
                ]);
            }
        }

        if (isset($request->customersReviewsImages[1]['deleted_media'])) {
            foreach ($request->customersReviewsImages[1]['deleted_media'] as $deleted_media) {
                File::delete(public_path('customers_images/' .  $deleted_media['name']));
                CustomersReview::where('name',  $deleted_media['name'])->delete();
            }
        }


        // response the data
        // $mainInfos = MainInfo::orderBy('id', 'desc')->get()->first();
        $mainInfos = $request->user()->MainInfo()->orderBy('id', 'desc')->get()->first();
        $backgrounds = MainImage::where('main_infos_id', $mainInfos->id)->get();
        $customers_reviews = CustomersReview::where('main_infos_id', $mainInfos->id)->get();

        return response([
            'mainInfos' => $mainInfos,
            'customers_reviews' => $customers_reviews,
            'backgrounds' => $backgrounds
        ]);
    }
}
