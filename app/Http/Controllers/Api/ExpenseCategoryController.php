<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ExpenseCategoryRequest;
use App\Models\ExpenseCategory;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use ApiResponse;
    public function index()
    {
        try {
            $expense_categories = ExpenseCategory::orderBy('id', 'desc')->get();
            return $this->successResponse($expense_categories, 'Fetched Successfully', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_NOT_ACCEPTABLE);
        }
        try {
            $data = [
                'title' => $request->title
            ];
            if ($request->image) {
                $path = Storage::putFile('ExpenseCategory', $request->image);
                $data['image'] = asset($path);
            }
            $expense_category = ExpenseCategory::create($data);
            return $this->successResponse($data, 'Successfully Created', Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
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
        try {
            $expense_category = ExpenseCategory::find($id);
            return $this->successResponse($expense_category, 'Fetched Successfully', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_NOT_ACCEPTABLE);
        }
        try {
            $data = [
                'title' => $request->title
            ];
            if ($request->image) {
                $path = Storage::putFile('ExpenseCategory', $request->image);
                $data['image'] = asset($path);
            }
            $expense_category = ExpenseCategory::where('id', $id)->update($data);
            return $this->successResponse($data, 'Successfully Updated', Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
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
        try {
            $expense_category = ExpenseCategory::where('id', $id)->delete();
            return $this->successResponse(null, 'Deleted Successfully', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
