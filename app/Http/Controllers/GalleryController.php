<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Gallery\UpdateRequest;
use App\Http\Requests\Gallery\StoreRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;



use App\Models\Gallery;

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

            $imageName = $this->storeImage($request->file('image'));

            $gallery = new Gallery($validatedData);
            $gallery->image = $imageName;
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

        if ($request->hasFile('image')) {
            $this->deleteImage($gallery->image);
            $gallery->image = $this->storeImage($request->file('image'));
        }

        unset($validatedData['image']);

        $gallery->fill($validatedData);
        $gallery->save();

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

    private function storeImage($file)
    {
        $imageName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('gallery_images'), $imageName);
        return 'gallery_images/' . $imageName;
    }

    private function deleteImage($imagePath)
    {
        if ($imagePath && file_exists(public_path($imagePath))) {
            unlink(public_path($imagePath));
        }
    }


}
