<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AdminFeedbackController;
use App\Http\Controllers\Admin\AdminNewsController;
use App\Http\Controllers\Admin\AdminSliderController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminVideoController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FileManagerController;
use App\Http\Controllers\Admin\FolderController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\GroupItemController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Route::get('/deploy', function(){
//     return view('deploy');
// });

// Route::get('/who', function(){
//     return Auth::user();
// });

// Route::get('/phpinfo', function () {
//     phpinfo();
// });

Route::get('/test-role', function () {
    return Auth::user()->role;
})->middleware(['auth','checkRole:admin']);

Route::get('/', [HomeController::class, 'index']);
Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/authenticate', [AuthenticatedSessionController::class, 'store'])->name('authenticate');

Route::get('/feedback', function () {
    return view('feedback.feedback');
})->name('feedback.feedback');

Route::post('/send-feedback', [FeedbackController::class, 'store'])->name('feedback.send');

Route::get('/about', [AboutController::class, 'about']);
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.detail');
Route::get('{slug}', [PageController::class, 'menu'])->name('page.menu');
Route::get('/file/{id}', [PageController::class, 'fileShow'])->name('file.show');
Route::get('/department/{id}', [PageController::class, 'departmentShow'])
    ->name('department.show');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware(['auth', 'checkRole:admin'])
    ->as('admin.')
    ->group(function () {

    // Feedback
    Route::resource('feedback', AdminFeedbackController::class);
    // Route::delete('feedback/{id}', [AdminFeedbackController::class, 'destroy'])->name('feedback.destroy');

    // Menus
    Route::resource('menus', MenuController::class);

    // Department
    Route::resource('department', DepartmentController::class);
    Route::post('department/upload', [DepartmentController::class, 'upload'])->name('department.upload');
    Route::get('department/{id}/json', [DepartmentController::class, 'json'])->name('department.json');

    // Employee
    Route::post('employee', [DepartmentController::class, 'employee'])->name('employee.store');
    Route::get('employee/{employee}/json', [DepartmentController::class, 'employeeJson']);
    Route::put('employee/{employee}', [DepartmentController::class, 'employeeUpdate'])->name('employee.update');
    Route::delete('employee/{employee}', [DepartmentController::class, 'employeeDestroy'])->name('employee.destroy');

    // Video
    Route::resource('video', AdminVideoController::class);

    // FAQ
    Route::get('faq', [FaqController::class, 'index']);
    Route::post('faq', [FaqController::class, 'store']);
    Route::put('faq/{faq}', [FaqController::class, 'update']);
    Route::delete('faq/{faq}', [FaqController::class, 'destroy']);

    Route::get('faq/{faq}/json', [FaqController::class, 'show']);

    // Slider
    Route::resource('slider', AdminSliderController::class);
    Route::post('slider/upload', [AdminSliderController::class, 'upload'])->name('slider.upload');

    // File Manager
    Route::resource('folders', FolderController::class)->only(['index', 'create', 'store']);
    Route::delete('folders/{folder}', [FolderController::class, 'destroy'])->name('folders.destroy');
    
    Route::resource('files', FileManagerController::class)
    ->only(['index','create','store','show','edit','update','destroy']);
    Route::patch('files/{file}/move', [FileManagerController::class, 'moveFile'])->name('files.move');
    // Route::patch('files/{file}/move', [FileManagerController::class, 'moveFile']);

    Route::get('folders/{folder}/files', [FolderController::class, 'files'])->name('folders.files');

    // Group
    Route::resource('groups', GroupController::class);
    Route::get('groups/{group}/json', [GroupController::class, 'getGroup']);
    Route::get('files', [FolderController::class, 'allFiles']); // бүх файлын жагсаалт

    // Group Items
    Route::get('group-items/create/{group}', [GroupController::class, 'createItem'])->name('group-items.create');
    Route::post('group-items', [GroupController::class, 'storeItem'])->name('group-items.store');
    Route::get('group-items/{groupItem}/json', [GroupController::class, 'showItem']);
    Route::get('group-items/{groupItem}/edit', [GroupController::class, 'editItem'])->name('group-items.edit');
    Route::put('group-items/{groupItem}', [GroupController::class, 'updateItem'])->name('group-items.update');
    Route::delete('group-items/{groupItem}', [GroupController::class, 'destroyItem'])->name('group-items.destroy');

    // sort
    Route::post('group-items/sort', [GroupController::class, 'sort'])->name('group-items.sort');

    Route::resource('users', AdminUserController::class);
    Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [AdminUserController::class, 'update'])->name('users.update');

});

Route::prefix('admin')
    ->middleware(['auth', 'checkRole:admin,publisher,editor'])
    ->as('admin.')
    ->group(function () {

     // News
     Route::resource('news', AdminNewsController::class);
     Route::post('news/upload', [AdminNewsController::class, 'upload'])->name('news.upload');
     Route::post('news/{news}/publish', [AdminNewsController::class, 'publish'])->name('news.publish');
     Route::post('news/{news}/submit', [AdminNewsController::class,'submit'])->name('news.submit');
});

// Route::prefix('admin')
//     ->middleware(['auth', 'checkRole:editor'])
//     ->as('admin.')
//     ->group(function () {

//      // News
//      Route::resource('news', AdminNewsController::class);
//      Route::post('news/upload', [AdminNewsController::class, 'upload'])->name('news.upload');
// });

/*
|--------------------------------------------------------------------------
| Fallback
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    return redirect('/');
});

require __DIR__.'/auth.php';
