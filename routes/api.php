<?php

use App\Http\Controllers\Api\ExpenseCategoryController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResource('expense-category', ExpenseCategoryController::class);
Route::apiResource('expense', ExpenseController::class);
Route::get('home-content', [HomeController::class, 'homeContent']);
Route::get('expense-list-by-today/{filter}', [HomeController::class, 'expenseListByCurrentDate']);
Route::get('expense-list-by-week', [HomeController::class, 'expenseListByCurrentWeek']);
Route::get('expense-list-by-month', [HomeController::class, 'expenseListByCurrentMonth']);
Route::get('expense-list-by-year', [HomeController::class, 'expenseListByCurrentYear']);
Route::get('expense-list-by-today/expense-category/{id}', [HomeController::class, 'expenseListByCurrentDateByCategory']);
Route::get('expense-list-by-week/expense-category/{id}', [HomeController::class, 'expenseListByCurrentWeekByCategory']);
Route::get('expense-list-by-month/expense-category/{id}', [HomeController::class, 'expenseListByCurrentMonthByCategory']);
Route::get('expense-list-by-year/expense-category/{id}', [HomeController::class, 'expenseListByCurrentYearByCategory']);
