<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use App\Models\Category;
use App\Models\Movie;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Intervention\Image\Facades\Image ;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movies = Movie::paginate(10) ;
        return Inertia::render('Movie/IndexMovie',compact('movies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::get();
        return Inertia::render('Movie/CreateMovie',compact('categories'));
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
            'title'=> 'required|string|max:250|min:5',
            'description'=> 'required|string|min:5',
            'rate'=> 'required|integer|min:1|max:5',
            'category_id'=> 'required|integer|exists:categories,id',
            'image'=> 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        try {
            $imageName = uniqid(). '.' . $request->file('image')->getClientOriginalExtension();
            $img =Image::make($request->file('image'));
            $img->save('images/'.$imageName);
            $data['image'] ='/images/' . $imageName ;
            Movie::create($data) ;
            return redirect()->route('movie.index');
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
        $categories = Category::get();
        $movie = new MovieResource( Movie::findOrFail($id));
        return Inertia::render('Movie/EditMovie',compact('movie','categories'));
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
            'title'=> 'required|string|max:250|min:5',
            'description'=> 'required|string|min:5',
            'rate'=> 'required|integer|min:1|max:5',
            'category_id'=> 'required|integer|exists:categories,id',
            'image'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        try {
            $movie = Movie::findOrFail($id);
            if($request->file('image')){
                unlink($movie->image);
                $imageName = uniqid(). '.' . $request->file('image')->getClientOriginalExtension();
                $img =Image::make($request->file('image'));
                $img->save('images/'.$imageName);
                $data['image'] ='/images/' . $imageName ;
            }else{
                unset($data['image']);
            }
            $movie->update($data);
            return redirect()->route('movie.index');
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
        $category = Movie::findOrFail($id);
        $category->delete();
        return response()->json(['message'=>'success'],200) ;
    }
    public function search(Request $request)
    {
        $movies = Movie::
        when($request->search,function($query) use($request){
            $query->where('title','like','%'.$request->search.'%')
            ->orWhere('description','like','%'.$request->search.'%')
            ->orWhereHas('category',function($q) use($request){
                $q->where('title','like','%'.$request->search.'%');
            });
        })->
        paginate(10) ;
        return response()->json(['data'=>$movies],200);
    }
}
