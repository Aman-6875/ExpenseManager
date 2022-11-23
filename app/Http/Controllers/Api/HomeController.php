<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    use ApiResponse;
    public function homeContent()
    {
        try {
            $expense = Expense::select('expense_category_id', DB::raw('SUM(amount) AS total_amount'))
                ->groupBy('expense_category_id')
                ->with(['expenseCategory'])
                ->orderBy('total_amount', 'desc')
                ->get();
            $total_expense = Expense::sum('amount');
            $data = [];
            foreach ($expense as $item) {
                $data['expense_categories'][] = [
                    'expense_category_id' => $item->expenseCategory->id,
                    'expense_category_title' => $item->expenseCategory->title,
                    'total_amount' => $item->total_amount,
                    'percent' => ceil((100 * $item->total_amount) / $total_expense),
                ];
            }
            $data['total_expense'] = $total_expense;
            return $this->successResponse($data, 'Deleted Successfully', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // filter data for current day data
    public function expenseListByCurrentDate($filter)
    {
        try {
            $query = Expense::select('expense_category_id', DB::raw('SUM(amount) AS total_amount'))
                ->groupBy('expense_category_id')
                ->with(['expenseCategory'])
                ->where('date', Carbon::now()->format('Y-m-d'));

            if ($filter == "expense_high_to_low") {
                $expense = $query->orderBy('total_amount', 'desc')->get();
            } else if ($filter == "expense_low_to_high") {
                $expense = $query->orderBy('total_amount', 'asc')->get();
            } else if ($filter == "time_old_to_new") {
                $expense = $query->orderBy('date', 'asc')->get();
            } else {
                $expense = $query->orderBy('date', 'desc')->get();
            }
            $total_expense = Expense::where('date', Carbon::now()->format('Y-m-d'))->sum('amount');
            $data = [];
            foreach ($expense as $item) {
                $data['expense_categories'][] = [
                    'expense_category_id' => $item->expenseCategory->id,
                    'expense_category_title' => $item->expenseCategory->title,
                    'total_amount' => $item->total_amount,
                ];
            }
            $data['total_expense'] = $total_expense;
            return $this->successResponse($data, 'Fetched Successfully', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function expenseListByCurrentWeek()
    {
        try {
            $expense = Expense::select('expense_category_id', DB::raw('SUM(amount) AS total_amount'))
                ->groupBy('expense_category_id')
                ->with(['expenseCategory'])
                ->orderBy('total_amount', 'desc')
                ->whereBetween(
                    'date',
                    [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
                )
                ->get();

            $total_expense = Expense::whereBetween(
                'date',
                [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
            )->sum('amount');
            $data = [];
            foreach ($expense as $item) {
                $data['expense_categories'][] = [
                    'expense_category_id' => $item->expenseCategory->id,
                    'expense_category_title' => $item->expenseCategory->title,
                    'total_amount' => $item->total_amount,
                ];
            }
            $data['total_expense'] = $total_expense;
            return $this->successResponse($data, 'Fetched Successfully', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function expenseListByCurrentMonth()
    {
        try {

            $expense = Expense::select('expense_category_id', DB::raw('SUM(amount) AS total_amount'))
                ->groupBy('expense_category_id')
                ->with(['expenseCategory'])
                ->orderBy('total_amount', 'desc')
                ->whereBetween(
                    'date',
                    [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]
                )
                ->get();

            $total_expense = Expense::whereBetween(
                'date',
                [Carbon::now()->startOfMonth(), Carbon::now()->startOfMonth()]
            )->sum('amount');
            $data = [];
            foreach ($expense as $item) {
                $data['expense_categories'][] = [
                    'expense_category_id' => $item->expenseCategory->id,
                    'expense_category_title' => $item->expenseCategory->title,
                    'total_amount' => $item->total_amount,
                ];
            }
            $data['total_expense'] = $total_expense;
            return $this->successResponse($data, 'Fetched Successfully', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function expenseListByCurrentYear()
    {
        try {

            $expense = Expense::select('expense_category_id', DB::raw('SUM(amount) AS total_amount'))
                ->groupBy('expense_category_id')
                ->with(['expenseCategory'])
                ->orderBy('total_amount', 'desc')
                ->whereBetween(
                    'date',
                    [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]
                )
                ->get();

            $total_expense = Expense::whereBetween(
                'date',
                [Carbon::now()->startOfYear(), Carbon::now()->startOfYear()]
            )->sum('amount');
            $data = [];
            foreach ($expense as $item) {
                $data['expense_categories'][] = [
                    'expense_category_id' => $item->expenseCategory->id,
                    'expense_category_title' => $item->expenseCategory->title,
                    'total_amount' => $item->total_amount,
                ];
            }
            $data['total_expense'] = $total_expense;
            return $this->successResponse($data, 'Fetched Successfully', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function expenseListByCurrentDateByCategory($id)
    {
        try {
            $expense = Expense::where('expense_category_id', $id)
                ->with(['expenseCategory'])
                ->orderBy('id', 'desc')
                ->where('date', Carbon::now()->format('Y-m-d'))
                ->get();
            return $this->successResponse($expense, 'Fetched Successfully', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function expenseListByCurrentWeekByCategory($id)
    {
        try {
            $expense = Expense::where('expense_category_id', $id)
                ->with(['expenseCategory'])
                ->orderBy('id', 'desc')
                ->whereBetween(
                    'date',
                    [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
                )
                ->get();
            return $this->successResponse($expense, 'Fetched Successfully', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function expenseListByCurrentMonthByCategory($id)
    {
        try {
            $expense = Expense::where('expense_category_id', $id)
                ->with(['expenseCategory'])
                ->orderBy('id', 'desc')
                ->whereBetween(
                    'date',
                    [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]
                )
                ->get();
            return $this->successResponse($expense, 'Fetched Successfully', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function expenseListByCurrentYearByCategory($id)
    {
        try {

            $expense = Expense::where('expense_category_id', $id)
                ->with(['expenseCategory'])
                ->orderBy('id', 'desc')
                ->whereBetween(
                    'date',
                    [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]
                )
                ->get();


            return $this->successResponse($expense, 'Fetched Successfully', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
