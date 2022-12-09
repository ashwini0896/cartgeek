<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('products.index');
    }

    /**
     * Get the data for listing in yajra.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getProducts(Request $request, Product $product)
    {
        $data = $product->getData();
        
        return \DataTables::of($data)
            ->addColumn('Actions', function($data) {
                $editUrl = route('products.edit', $data->id);
                return '<a href="'.$editUrl.'" class="btn btn-success btn-sm">Edit</a>
                    <button type="submit" data-id="'.$data->id.'" data-toggle="modal" data-target="#DeleteProductModal" class="btn btn-danger btn-sm btn-delete" id="getDeleteId">Delete</button>';
            })
            ->rawColumns(['Actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'product_name' => 'required',
            'product_price' => 'required',
            'product_description' => 'required',
            'images' => 'required',
            'images.*' => 'mimes:jpeg,png,jpg|max:2048'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $product =new Product([
                "product_name" =>$request->product_name,
                "product_price" =>$request->product_price,
                "product_description" =>$request->product_description,
            ]);
            $product->save();
    
            if($request->hasFile("images")){
                $files=$request->file("images");
                foreach($files as $file){
                    $imageName=time().'_'.$file->getClientOriginalName();
                    $request['product_id']=$product->id;
                    $request['image']=$imageName;
                    $file->move(\public_path("/images"),$imageName);
                    Image::create($request->all());
                }
            }
            return redirect()->route('products.index')->with('message','Product added successfully');
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
        $products=Product::findOrFail($id);
        return view('products.edit')->with('products',$products);
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
        $product = Product::findOrFail($id);

        $product->update([
            "product_name" =>$request->product_name,
            "product_price"=>$request->product_price,
            "product_description"=>$request->product_description,
        ]);

        if($request->hasFile("images")){
            $files=$request->file("images");
            foreach($files as $file){
                $imageName=time().'_'.$file->getClientOriginalName();
                $request["product_id"] = $id;
                $request["image"] = $imageName;
                $file->move(\public_path("images"),$imageName);
                Image::create($request->all());
            }
        }

        return redirect()->route('products.index')->with('message','Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $products=Product::findOrFail($id);

        $images=Image::where("product_id",$products->id)->get();
        foreach($images as $image){
            if (File::exists("images/".$image->image)) {
                File::delete("images/".$image->image);
                $image->delete();
            }
        }
        $products->delete();     
        return "delete success";    
    }

    public function deleteImage($id){
        $images = Image::findOrFail($id);
        if (File::exists("images/".$images->image)) {
            File::delete("images/".$images->image);
        }
        Image::find($id)->delete();
        return back()->with('message','Image deleted successfully');
    }
}