<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use App\Http\Resources\ProductsResource;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\ProductsImage;
use App\Models\Service;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $servicesNames = explode(',', $request->query('servicesNames')[0]);
        
        $services = Service::select('id')->whereIn('name', $servicesNames )->get();

        $servicesId = collect($services)->pluck('id')->toArray();

        $products = Product::orderBy('id', 'desc')->whereIn('service_id', $servicesId)->paginate(15);
        foreach ($products as $product) {
            if ($product->cover !== null)
                $product->cover = URL::to($product->cover);
            $product['service'] = Service::where('id', $product->service_id)->get();
            $product['created_at1'] = $product['created_at']->diffForHumans();
            
            $images = ProductsImage::where('product_id', $product->id)->get();

            foreach($images as $image){
                if(Str::contains($product['fullDetails'], $image['name'])){
                    $product['fullDetails'] = str_replace($image['name'], URL::to('ProductsImages/'.$image['name']), $product['fullDetails']);
                }
                if(Str::contains($product['fullDetailsArabic'], $image['name'])){
                    $product['fullDetailsArabic'] = str_replace($image['name'], URL::to('ProductsImages/'.$image['name']), $product['fullDetailsArabic']);
                }
            }
        }

        return response([
            'paginateProducts' => $products
        ]);
    }

    public function allProducts(){
        $products = Product::orderBy('id', 'desc')->get();
        foreach ($products as $product) {
            if ($product->cover !== null)
                $product->cover = URL::to($product->cover);
            $product['service'] = Service::where('id', $product->service_id)->get();
            $product['created_at1'] = $product['created_at']->diffForHumans();

            $images = ProductsImage::where('product_id', $product->id)->get();

            foreach ($images as $image) {
                if (Str::contains($product['fullDetails'], $image['name'])) {
                    $product['fullDetails'] = str_replace($image['name'], URL::to('ProductsImages/' . $image['name']), $product['fullDetails']);
                }
                if (Str::contains($product['fullDetailsArabic'], $image['name'])) {
                    $product['fullDetailsArabic'] = str_replace($image['name'], URL::to('ProductsImages/' . $image['name']), $product['fullDetailsArabic']);
                }
            }
        }

        // $products['service_name'] = Product::with('services')->get();

        return response([
            'allProducts' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        if (isset($data['cover'])) {
            $relativePath = $this->saveImage($data['cover']); //create saveImage function by own
            $data['cover'] = $relativePath;
        }


        if (count($data['imagesEnglish']) > 0) {
            foreach ($data['imagesEnglish'] as $imageEnglish) {
                if (File::exists(public_path('tmpProductsEnglish/' . $imageEnglish[1]))) {
                    if (Str::contains($data['fullDetails'], $imageEnglish[0])) {
                        $data['fullDetails'] = str_replace($imageEnglish[0], $imageEnglish[1], $data['fullDetails']);
                    }
                }
            }
        }

        if (count($data['imagesArabic']) > 0) {
            foreach ($data['imagesArabic'] as $imageEnglish) {
                if (File::exists(public_path('tmpProductsEnglish/' . $imageEnglish[1]))) {
                    if (Str::contains($data['fullDetailsArabic'], $imageEnglish[0])) {
                        $data['fullDetailsArabic'] = str_replace($imageEnglish[0], $imageEnglish[1], $data['fullDetailsArabic']);
                    }
                }
            }
        }

        $product = $request->user()->products()->create([
            'title' => $data['title'],
            'titleArabic' => $data['titleArabic'],
            'cover' => $data['cover'],
            'briefDetails' => $data['briefDetails'],
            'briefDetailsArabic' => $data['briefDetailsArabic'],
            'fullDetails' => $data['fullDetails'],
            'fullDetailsArabic' => $data['fullDetailsArabic'],
            'service_id' => $data['service_id'],
        ]);


        if (count($data['imagesEnglish']) > 0) {
            foreach ($data['imagesEnglish'] as $imageEnglish) {
                if (File::exists(public_path('tmpProductsEnglish/' . $imageEnglish[1]))) {
                    $from = public_path('tmpProductsEnglish/' . $imageEnglish[1]);
                    $to = public_path('ProductsImages/' . $imageEnglish[1]);
                    File::move($from, $to);

                    $product->productImages()->create([
                        'name' => $imageEnglish[1]
                    ]);
                }
            }
        }

        if (count($data['imagesArabic']) > 0) {
            foreach ($data['imagesArabic'] as $imageArabic) {
                if (File::exists(public_path('tmpProductsEnglish/' . $imageArabic[1]))) {
                    $from = public_path('tmpProductsEnglish/' . $imageArabic[1]);
                    $to = public_path('ProductsImages/' . $imageArabic[1]);
                    File::move($from, $to);

                    $product->productImages()->create([
                        'name' => $imageArabic[1]
                    ]);
                }
            }
        }

        return new ProductsResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $Product)
    {

        $product = $Product;

        $images = $product->productImages()->get();

        if ($product['cover'] != null) {

            $product['cover'] = URL::to($product['cover']);
        }

        if (count($images) > 0) {
            foreach ($images as $image) {
                $product['fullDetails'] = str_replace($image['name'], URL::to("ProductsImages/" . $image['name']), $product['fullDetails']);
                $product['fullDetailsArabic'] = str_replace($image['name'], URL::to("ProductsImages/" . $image['name']), $product['fullDetailsArabic']);
            }
        }

        return response([
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $Product)
    {

        $validatedProduct = $request->validated();
        
        $images = $Product->productImages()->get();

        if (!Str::contains($validatedProduct['cover'], $Product->cover) && $Product->cover) { //replace cover image if updated and delete old one
            $relativePath = $this->saveImage($validatedProduct['cover']); //create saveImage function by own
            $validatedProduct['cover'] = $relativePath;

            File::delete(public_path($Product['cover']));
        } else {
            $validatedProduct['cover'] = $Product->cover; //if not changed keep it the same with no URL::to
        }

        foreach ($images as $image) { //delete images after being deleted from rich text editor,
            if (!Str::contains($validatedProduct['fullDetails'], $image['name']) && !Str::contains($validatedProduct['fullDetailsArabic'], $image['name'])) {
                File::delete(public_path("ProductsImages/" . $image['name']));
                $Product->productImages()->where('name', $image['name'])->delete();
            }else if(Str::contains($validatedProduct['fullDetails'], $image['name'])){ //since the comming img src comes with full url, we need to implement it in db as image name only
                 $validatedProduct['fullDetails'] = str_replace(URL::to("ProductsImages/".$image['name']), $image['name'], $validatedProduct['fullDetails']);
            }else if(Str::contains($validatedProduct['fullDetailsArabic'], $image['name'])){
                 $validatedProduct['fullDetailsArabic'] = str_replace(URL::to("ProductsImages/".$image['name']), $image['name'], $validatedProduct['fullDetailsArabic']);
            }
        }

        if (count($validatedProduct['imagesEnglish']) > 0) { //upload new uploaded images for update
            foreach ($validatedProduct['imagesEnglish'] as $imageEnglish) {
                if (File::exists(public_path('tmpProductsEnglish/' . $imageEnglish[1]))) {
                    if (Str::contains($validatedProduct['fullDetails'], $imageEnglish[0])) {
                        $validatedProduct['fullDetails'] = str_replace($imageEnglish[0], $imageEnglish[1], $validatedProduct['fullDetails']); /// change data to store in db to be absolute path by trimming www.example.com/image
                    }
                }
            }
        }

        if (count($validatedProduct['imagesArabic']) > 0) {
            foreach ($validatedProduct['imagesArabic'] as $imageEnglish) {
                if (File::exists(public_path('tmpProductsEnglish/' . $imageEnglish[1]))) {
                    if (Str::contains($validatedProduct['fullDetailsArabic'], $imageEnglish[0])) {
                        $validatedProduct['fullDetailsArabic'] = str_replace($imageEnglish[0], $imageEnglish[1], $validatedProduct['fullDetailsArabic']);
                    }
                }
            }
        }

        $Product->update($validatedProduct);

        if (count($validatedProduct['imagesEnglish']) > 0) { //move new uploaded images from tmp into the folder
            foreach ($validatedProduct['imagesEnglish'] as $imageEnglish) {
                if (File::exists(public_path('tmpProductsEnglish/' . $imageEnglish[1]))) {
                    $from = public_path('tmpProductsEnglish/' . $imageEnglish[1]);
                    $to = public_path('ProductsImages/' . $imageEnglish[1]);
                    File::move($from, $to);

                    $Product->productImages()->create([
                        'name' => $imageEnglish[1]
                    ]);
                }
            }
        }

        if (count($validatedProduct['imagesArabic']) > 0) {
            foreach ($validatedProduct['imagesArabic'] as $imageArabic) {
                if (File::exists(public_path('tmpProductsEnglish/' . $imageArabic[1]))) {
                    $from = public_path('tmpProductsEnglish/' . $imageArabic[1]);
                    $to = public_path('ProductsImages/' . $imageArabic[1]);
                    File::move($from, $to);

                    $Product->productImages()->create([
                        'name' => $imageArabic[1]
                    ]);
                }
            }
        }


        return response([
            'product' => $Product,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $Product, Request $request)
    {
        if ($request->user()->id != $Product->user_id) {
            return abort(403, 'Unothorized action, try again!');
        }

        $productImages =  $Product->productImages()->get();

        $Product->productImages()->delete();
        $Product->delete();

        //if there is an old image, remove it
        if ($Product->cover) {
            $absolutePath = public_path($Product->cover);
            File::delete($absolutePath);
        }

        if (count($productImages) > 0) {
            foreach ($productImages as $image) {
                $absolutePath = public_path("ProductsImages/" . $image->name);
                File::delete($absolutePath);
            }
        }

        return response([
            'dete' => 'deleted'
        ]);
    }

    private function saveImage($image)
    {
        //image is base64 encoded, so we need to decode it.

        //check if image is valid base64 string 
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            //take out the base64 encoded text without mime type
            $image = substr($image, strpos($image, ',') + 1); //remove befrore ,  data:image/png;base64,iVBORw0KGg....

            //Get file extension
            $type = strtolower($type[1]); //jpg, png, gif

            //check if file is an image 
            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png', 'svg'])) {
                throw new \Exception('invalid image type');
            }

            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);

            if ($image === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('did not match data URI with image data');
        }

        $dir = 'ProductsImages/';
        $file = Str::random() . '.' . $type;

        $absolutePath = public_path($dir);
        $relativePath = $dir . $file;

        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0755, true);
        }

        file_put_contents($relativePath, $image);

        return $relativePath;
    }
}
