<?php 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/job-feed', function () {
    $response = Http::get('https://mrge-group-gmbh.jobs.personio.de/xml');
    return response($response->body(), 200)->header('Content-Type', 'application/xml');
});