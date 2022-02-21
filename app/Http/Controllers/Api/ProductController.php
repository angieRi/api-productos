<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Funcion que permite agregar datos de un nuevo producto
     * @param Request $request recibe datos de un producto
     * @return \Illuminate\Http\JsonResponse retorna el producto creado
     */
    public function create(Request $request)
    {

        $validator =Validator::make($request->all(),[
            'name' => 'required',
            'image' => 'required',
            'brand' => 'required',
            'price' => 'required|numeric',
            'category' => 'required',
            'stock' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $product = new Product;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->image = $request->image;
            $product->brand = $request->brand;
            $product->price = $request->price;
            $product->price_sale = $request->price_sale;
            $product->category = $request->category;
            $product->stock = $request->stock;
            $product->save();

            DB::commit();

            return response()->json([
                'status'=>200,
                'data'=>$product
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'statu' =>400,
                'message' => 'Unexpected error !',
                'error'=> [$validator->getMessageBag()->getMessages(),$e->getPrevious()->getMessage()]]);
        }
    }

    /**
     * Función que permite obtener los datos de un producto creado, retorna los primeros 15 cuadros
     * @return \Illuminate\Http\JsonResponse retorna los productos paginados de 15
     */
    public function getAll()
    {
        $product = Product::paginate(15);
        if($product) {
            return response()->json([
                $product,
                200]);
        }else {
            return response()->json([
                'statu' => 404,
                'message' => 'transaction error']);
        }
    }


    /**
     * función que devuelve los datos de un producto por $id
     * @param $id /del producto a obtener
     * @return \Illuminate\Http\JsonResponse
     */
    public function getById($id)
    {
        $product = Product::find($id);

        if($product) {
            return response()->json([
                'data' =>$product,
                'statu' =>200]);
        }
        return response()->json([
            'statu' => 404,
            'message' => 'transaction error']);
    }


    /***
     * Función que permite editar los datos del un producto
     * @param Request $request datos de un producto a editar
     * @param Producto $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request,Product $product)
    {
        $validator =Validator::make($request->all(),[
            'name' => 'required',
            'image' => 'required',
            'brand' => 'required',
            'price' => 'required',
            'category' => 'required',
            'stock' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $product->fill($request->all());
            DB::commit();
            return response()->json([
                'status'=>200,
                'producto'=> $product
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'statu' => 400,
                'message' => 'Update error !',
                'error' => $validator->fails()]);
        }
    }

    /**
     * Funcion que elimina un producto por $id
     * @param $id /de un producto a borrar
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        try{
            DB::beginTransaction();

            Product::destroy($id);

            DB::commit();

            return response()->json([
                'statu' => 200,
                'message' => 'Product removed successfully']);
        }
        catch (\Exception $e) {

            DB::rollback();
            return response()->json([
                'statu' =>400,
                'message' => 'update error']);
        }
    }

    /**
     * Función que devuelve datos de un producto por campos solicitados
     * @param Request $request data de campos a buscar y data de campos a mostrar
     * @return \Illuminate\Http\JsonResponse con datos de campos pedidos
     */
    public function search(Request $request)
    {
        $product=[];
        if($request->name) {
            if ($request->fields) {
                $product = Product::where('name', 'like', '%' . trim($request->name).'%')->paginate(1,explode(',', $request->fields));
            } else {
                $product = Product::where('name', 'like', '%' . trim($request->name))->paginate(20);
            }
        }

        if($request->category){
            if ($request->fields) {
                $product = Product::where('category', 'like', '%' . trim($request->category))->paginate(20,explode(',', $request->fields));
            } else {
                $product = Product::where('category', 'like', '%' . trim($request->category))->paginate(20);
            }
        }
        if($request->price){
            if ($request->fields) {
                $product = Product::where('price', 'like', '%' . trim($request->price))->paginate(20,explode(',', $request->fields));
            } else {
                $product = Product::where('price', 'like', '%' . trim($request->price))->paginate(20);
            }
        }
        //if($request->year){dd($request->name);}
        if($request->brand){
            if ($request->fields) {
                $product = Product::where('brand', 'like', '%' . trim($request->brand))->paginate(20,explode(',', $request->fields));
            } else {
                $product = Product::where('brand', 'like', '%' . trim($request->brand))->paginate(20);
            }
        }
        if(!empty($product->all())){
            return response()->json(['data' => $product,200]);
        }else{
            return response()->json(['message' => "no results found",400]);
        }
    }
}
