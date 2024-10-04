 <?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TaskController;

Route::controller(AuthController::class)->group(function(){
    Route::post('/register','register');
    Route::post('/login','login');
});



Route::controller(ProjectController::class)->group(function(){
    Route::post('/projects','store');
    Route::put('/projects/{id}','update');
    Route::get('/projects','index');
    Route::post('/projects/pinned','pinnedProject');
    Route::get('/projects/{slug}','getProject');
    Route::get('count/projects','countProject');
});

Route::controller(MemberController::class)->group(function(){
    Route::post('/members','store');
    Route::put('/members','update');
    Route::get('/members','index');
});

Route::controller(TaskController::class)->group(function(){
    Route::post('/tasks','createTask');
    Route::post('/tasks/not_started_to_pending','TaskToNotStartedToPending');
    Route::post('/tasks/not_started_to_completed','TaskToNotStartedToCompleted');
    Route::post('/tasks/pending_to_completed','TaskToPendingToCompleted');
    Route::post('/tasks/pending_to_not_started','TaskToPendingToNotStarted');
    Route::post('/tasks/completed_to_pending','TaskToCompletedToPending');
    Route::post('/tasks/completed_to_not_started','TaskToCompletedToNotStarted');

});




