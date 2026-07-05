<?php

use Illuminate\Support\Facades\Route;
use App\Models\Project;

Route::get('/', function () {
    // Na razie pobieramy projekty z bazy danych
    $projects = App\Models\Project::latest()->get();

    return view('welcome', compact('projects'));
});
