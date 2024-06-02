<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Status;
use App\Models\Suplier;
use App\Models\Terminal;
use App\Models\Type;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function indexTour()
    {
        return view('admin.tour.index');
    }

    public function createTour()
    {
        $tour = new Product();

        $parentCategories = Category::whereNull('parent_id')->get();

        $supliers = Suplier::all();
        
        return view('admin.tour.form', [
            'tour' => $tour,
            'parentCategories' => $parentCategories,
            'supliers' => $supliers,
            'new' => true,
        ]);
    }

    public function storeTour(Request $request)
    {
        $validatedFields = $request->validate([
            'name' => 'required|min:3',
            'slug' => 'required|min:3',
            'category_id' => 'required|integer',
            'suplier_id' => 'required|integer',
        ], [
            'name.required' => 'El campo nombre es requerido',
            'category_id' => 'Tienes que seleccionar una categoría válida',
        ]); // añadir la validación

        $validatedFields['type_id'] = Type::TOUR;
        $validatedFields['status_id'] = Status::TOUR_DRAFT;
        $validatedFields['created_user_id'] = 1; // set default value

        $tour = Product::create($validatedFields);

        //return redirect()->route('admin.tour.index')->with('success', 'Tour created successfully.');
        return redirect()->route('admin.tour.show', $tour->id);
    }

    public function showTour($id)
    {
        $tour = Product::findOrFail($id);

        $parentCategories = Category::whereNull('parent_id')->get();

        $supliers = Suplier::all();

        $terminals = Terminal::all();

        return view('admin.tour.form', [
            'tour' => $tour,
            'parentCategories' => $parentCategories,
            'supliers' => $supliers,
            'terminals' => $terminals,
            'show' => true,
        ]);
    }

    public function editTour($id)
    {
        $tour = Product::findOrFail($id);

        $parentCategories = Category::whereNull('parent_id')->get();

        $supliers = Suplier::all();
        
        return view('admin.tour.form', [
            'tour' => $tour,
            'parentCategories' => $parentCategories,
            'supliers' => $supliers,
            'edit' => true,
        ]);
    }

    public function updateTour(Request $request, $id)
    {
        $validatedFields = $request->validate([
            'name' => 'required|min:3',
            'slug' => 'required|min:3',
            'category_id' => 'required|integer',
            'suplier_id' => 'required|integer',
        ], [
            'name.required' => 'El campo nombre es requerido',
            'category_id' => 'Tienes que seleccionar una categoría válida',
        ]); // añadir la validación

        //$validatedFields['type_id'] = Type::TOUR;
        //$validatedFields['status_id'] = Status::TOUR_DRAFT;
        //$validatedFields['created_user_id'] = 1; // set default value

        $tour = Product::findOrFail($id);
        $tour->update($validatedFields);

        return redirect()->route('admin.tour.show', $tour->id)
            ->with('success', 'Tour updated successfully.');
    }
}
