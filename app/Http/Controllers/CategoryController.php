<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::paginate(10);
        return Inertia::render('Category/IndexCategory',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Category/CreateCategory');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate( $request ,[
            'title'=> 'required|string|max:250|min:5'
        ]);
        try {
            Category::create($data) ;
            return redirect()->route('category.index');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['title'=>'Cant be created']) ;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return Inertia::render('Category/EditCategory',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $this->validate( $request ,[
            'title'=> 'required|string|max:250|min:5'
        ]);
        try {
            $category = Category::findOrFail($id);
            $category->update($data);
            return redirect()->route('category.index');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['title'=>'Cant be created']) ;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $category = Category::findOrFail($id);
         $category->delete();
         return response()->json(['message'=>'success'],200) ;
    }
}
