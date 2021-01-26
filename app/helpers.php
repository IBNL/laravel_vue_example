<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

function successResponse($data, $code)
{
    return response()->json($data, $code);
}

function errorResponse($message, $code)
{
       return response()->json(['error' => $message],$code);
}

function showAll(Collection $collection, $code = 200)
{
    return successResponse($collection, $code);
}
function showOne(Model $model, $code = 200)
{
    return successResponse(['data' => $model], $code);
}