<?php

namespace App\Http\Controllers;

use App\Http\Requests\Banner\StoreRequest;
use App\Http\Requests\Banner\UpdateRequest;
use App\Models\Banner;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;


class BannerController extends Controller
{

    public function index()
    {
        $banner = Banner::all();
        return response()->json(['banner' => $banner], 200);
    }

    public function show($id)
    {
        try {
            $banner = Banner::findOrFail($id);

            return response()->json(['banner' => $banner], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Banner no encontrado'], 404);
        }
    }

    public function store(StoreRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $bannerPath = $this->storeBanner($request->file('image'));

            $banners = new Banner($validatedData);
            $banners->image = $bannerPath;
            $banners->save();

            return response()->json(['message' => 'Banner creado con éxito', 'banner' => $banners], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateRequest $request, $id)
{
    try {
        $banner = Banner::findOrFail($id);

        $validatedData = $request->validated();

        $banner->fill($validatedData);
        if ($request->hasFile('image')) {
            $this->deleteImage($banner->image);

            $bannerPath = $this->storeBanner($request->file('image'));
            $banner->image = $bannerPath;
        }

        $banner->save();


        $banner->refresh();

        return response()->json(['message' => 'Banner actualizado con éxito', 'banner' => $banner], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
    public function destroy($id)
    {
        try {
            $banners = Banner::findOrFail($id);
            $this->deleteImage($banners->image);
            $banners->delete();

            return response()->json(['message' => 'Banner eliminado con éxito'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Banner no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function storeBanner($file)
{

    $bannerName = time() . '_' . $file->getClientOriginalName();


    $path = $file->storeAs('', $bannerName, 'banners');


    $url = Storage::disk('banners')->url($path);

    return $url;
}

private function deleteImage($bannerPath)
{
    if ($bannerPath && Storage::disk('banners')->exists($bannerPath)) {
        Storage::disk('banners')->delete($bannerPath);
    }
}
}
