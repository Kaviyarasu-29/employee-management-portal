<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('employees.index');
});

Route::get('employees/export', [EmployeeController::class, 'export'])->name('employees.export');
Route::post('employees/import', [EmployeeController::class, 'import'])->name('employees.import');

Route::resource('employees', EmployeeController::class);
