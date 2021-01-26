<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;



class ProductController extends Controller
{
    
    public function index(ProductRepositoryInterface $model)
    {

        $products = $model->all();

        return showAll($products);
    }

    public function store(ProductRepositoryInterface $model, Request $request)
    {
        try {
            $path = ProductRepository::uploadFile($request);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 400);
        }

        try {
            $data = ProductRepository::checkContentFile($path);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 400);
        }

        try {
            $data = ProductRepository::insertFileNameInData($data,$path);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 400);
        }
       
        $model->create($data);  
        return successResponse($path,200);
    }

    public function show(ProductRepositoryInterface $model, $id)
    { 

        $product = $model->show($id);

        return showOne($product);
    }

    public function update(ProductRepositoryInterface $model, Request $request, $id)
    {
        $data = $request->only(['title', 'type', 'price']);


        $validator = Validator::make($data, [
            'title' => 'required',
            'type' => 'required',
            'price' => 'required',
        ]);

        if ($validator->fails()) {
            return errorResponse($validator->errors(), 400);
        }

        $model->update($data, $id);


         $model->show($id);
         return response()->json(['success' => 'Produto Atualizado com Sucesso.']);
    }

    public function destroy(ProductRepositoryInterface $model, $id)
    {
        try {
          $model->show($id);
        } catch (Exception $e) {
            return errorResponse('Produto não existe', 400);
        }
        
        $model->delete($id);
        return response()->json(['success' => 'Produto Deletado com Sucesso.']);
    }

    public function getDataFromFile(Request $request)
    {
        $path_file_upload = $request->only('path_file_upload');

        $products = DB::table('products')
        ->where('path_file_upload', '=', $path_file_upload)->get();

        return showAll($products);
    }
}
