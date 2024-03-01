<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Gallery\UpdateRequest;
use App\Http\Requests\Gallery\StoreRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;



use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{


    public function index()
    {
        $galleries = Gallery::all();
        return response()->json(['galleries' => $galleries], 200);
    }

    // public function show($id)
    // {
    //     try {
    //         $gallery = Gallery::findOrFail($id);
    //         return response()->json(['gallery' => $gallery], 200);
    //     } catch (ModelNotFoundException $e) {
    //         return response()->json(['error' => 'Galería no encontrada'], 404);
    //     }
    // }

    public function store(StoreRequest $request)
    {
        try {

            $validatedData = $request->validated();

            $galleryPath = $this->storeGallery($request->file('image'));

            $gallery = new Gallery($validatedData);
            $gallery->image = $galleryPath;
            $gallery->save();

            return response()->json(['message' => 'Galería creada con éxito', 'gallery' => $gallery], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateRequest $request, $id)
{
    try {
        $gallery = Gallery::findOrFail($id);

        $validatedData = $request->all();

        $gallery->fill($validatedData);

        if ($request->hasFile('image')) {
            $this->deleteImage($gallery->image);

            $galleryPath = $this->storeGallery($request->file('image'));
            $gallery->image = $galleryPath;

        }
        $gallery->save();

        $gallery->refresh();



        return response()->json(['message' => 'Galería actualizada con éxito', 'gallery' => $gallery], 200);
    } catch (ModelNotFoundException $e) {
        return response()->json(['error' => 'Galería no encontrada'], 404);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    public function destroy($id)
    {
        try {
            $gallery = Gallery::findOrFail($id);
            $this->deleteImage($gallery->image);
            $gallery->delete();

            return response()->json(['message' => 'Galería eliminada con éxito'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Galería no encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function storeGallery($file)
    {
        $galleryName = time() . '_' . $file->getClientOriginalName();

        $path = $file->storeAs('', $galleryName, 'gallerys');

        $url = Storage::disk('gallerys')->url($path);

        return $url;
    }

    private function deleteImage($galleryPath)
    {
        if ($galleryPath && Storage::disk('gallerys')->exists($galleryPath)) {
            Storage::disk('gallerys')->delete($galleryPath);
        }

    }


}
