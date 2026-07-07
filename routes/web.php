<?php

use Illuminate\Support\Facades\Route;
use App\Models\Project;
use App\Http\Controllers\ProjectCodeController;

Route::get('/', function () {
    // Na razie pobieramy projekty z bazy danych
    $projects = App\Models\Project::latest()->get();

    return view('welcome', compact('projects'));
});
Route::get('/projects/{project}/code', [ProjectCodeController::class, 'tree']);
Route::get('/projects/{project}/code/file', [ProjectCodeController::class, 'file']);