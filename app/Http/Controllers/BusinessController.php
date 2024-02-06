<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Http\Requests\Business\StoreRequest;
use App\Http\Requests\Business\UpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class BusinessController extends Controller
{
    public function index()
    {
        $businesses = Business::all();
        return response()->json(['businesses' => $businesses], 200);
    }

    public function show($id)
    {
        try {
            $business = Business::findOrFail($id);

            return response()->json(['business' => $business], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Empresa no encontrada'], 404);
        }
    }

    public function store(StoreRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $logoPath = $this->storeLogo($request->file('logo'));

            $business = new Business($validatedData);
            $business->logo = $logoPath;
            $business->save();

            return response()->json(['message' => 'Empresa creada con éxito', 'business' => $business], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $business = Business::findOrFail($id);

            $validatedData = $request->validated();

            if ($request->hasFile('logo')) {
                $this->deleteLogo($business->logo);
                $business->logo = $this->storeLogo($request->file('logo'));
            }

            unset($validatedData['logo']);

            $business->fill($validatedData);
            $business->save();

            return response()->json(['message' => 'Empresa actualizada con éxito', 'business' => $business], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Empresa no encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $business = Business::findOrFail($id);
            $this->deleteLogo($business->logo);
            $business->delete();

            return response()->json(['message' => 'Empresa eliminada con éxito'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Empresa no encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function storeLogo($file)
    {
        $imageName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('logo_images'), $imageName);
        return 'logo_images/' . $imageName;
    }

    private function deleteLogo($logoPath)
    {
        if ($logoPath && file_exists(public_path($logoPath))) {
            unlink(public_path($logoPath));
        }
    }
}
