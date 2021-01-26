<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Exception;
use Validator;

class ProductRepository extends AbstractRepository implements ProductRepositoryInterface
{
    protected $model = Product::class;

    public static function uploadFile($request)
    {
        $validator = Validator::make($request->only('file'), [
            'file' => 'required|mimes:json',
        ]);

        if ($validator->fails()) {
            throw new Exception($validator->errors());
        }

        $path = $request->file->store('private');

        return $path;
    }

    public static function checkContentFile($path)
    {
        $contents = Storage::get($path);

        $data = json_decode($contents, true);

        $validator = Validator::make($data, [
            '*.title' => 'required',
            '*.type' => 'required',
            '*.description' => 'required',
            '*.filename' => 'required',
            '*.height' => 'required|numeric',
            '*.width' => 'required|numeric',
            '*.price' => 'required|numeric',
            '*.rating' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            Storage::delete($path);
            throw new Exception($validator->errors());
        }


        return $data;
    }

    public static function insertFileNameInData($data, $path)
    {

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['path_file_upload'] = $path;
        }

        return $data;
    }
}
