<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use ApiResponse;
    public function index()
    {
        //
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
            'expense_category_id' => 'required',
            'amount' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_NOT_ACCEPTABLE);
        }
        try {
            $data = [
                'expense_category_id' => $request->expense_category_id,
                'amount' => $request->amount,
                'date' => $request->date,
                'note' => $request->note,
                'transaction_medium' => $request->transaction_medium,
            ];
            if ($request->image) {
                $path = Storage::putFile('ExpenseCategory', $request->image);
                $data['image'] = asset($path);
            }
            $expense_category = Expense::create($data);
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
            $expense = Expense::with('expenseCategory')->find($id);
            return $this->successResponse($expense, 'Fetched Successfully', Response::HTTP_OK);
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
            'expense_category_id' => 'required',
            'amount' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_NOT_ACCEPTABLE);
        }
        try {
            $data = [
                'expense_category_id' => $request->expense_category_id,
                'amount' => $request->amount,
                'date' => $request->date,
                'note' => $request->note,
                'transaction_medium' => $request->transaction_medium,
            ];
            if ($request->image) {
                $path = Storage::putFile('ExpenseCategory', $request->image);
                $data['image'] = asset($path);
            }
            $expense_category = Expense::where('id', $id)->update($data);
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
            $expense = Expense::where('id', $id)->delete();
            return $this->successResponse(null, 'Deleted Successfully', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
