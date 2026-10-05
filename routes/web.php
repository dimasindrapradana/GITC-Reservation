<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\BuildingCoordinatorDashboardController;
use App\Http\Controllers\BuildingCoordinatorReportController;
use App\Http\Controllers\BuildingCoordinatorReservationController;
use App\Http\Controllers\CoordinatorDashboardController;
use App\Http\Controllers\CoordinatorReportController;
use App\Http\Controllers\CoordinatorReservationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TrainingOfficerCartController;
use App\Http\Controllers\TrainingOfficerController;
use App\Http\Controllers\TrainingOfficerReservationController;
use App\Http\Controllers\TrainingRoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\SecurityDisplayController;
use App\Http\Controllers\TrainingOfficerClassroomController;
use App\Http\Controllers\TrainingOfficerClassroomCartController;
use App\Http\Controllers\TrainingOfficerClassroomReservationController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Public Landing Page
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [LandingController::class, 'index']
)->name('landing');

Route::get(
    '/landing/reservations',
    [LandingController::class, 'reservations']
)->name('landing.reservations');


/*
|--------------------------------------------------------------------------
| Public Display Routes
|--------------------------------------------------------------------------
*/

Route::get(
    '/display/{buildingName}',
    [DisplayController::class, 'index']
)->name('display');

Route::get(
    '/display/{buildingName}/data',
    [DisplayController::class, 'data']
)->name('display.data');
Route::get(
    '/display/{buildingName}/detail',
    [DisplayController::class, 'detail']
)->name('display.detail');

Route::get(
    '/display/{buildingName}/upcoming',
    [DisplayController::class, 'upcoming']
)->name('display.upcoming');

Route::get(
    '/display/{buildingName}/upcoming/data',
    [DisplayController::class, 'upcomingData']
)->name('display.upcoming.data');

/*
|--------------------------------------------------------------------------
| Public Security Display Routes
|--------------------------------------------------------------------------
*/

Route::get(
    '/security',
    [SecurityDisplayController::class, 'index']
)->name('security.display');

Route::get(
    '/security/data',
    [SecurityDisplayController::class, 'data']
)->name('security.display.data');


/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get(
        '/login',
        [AuthController::class, 'showLogin']
    )->name('login');

    Route::post(
        '/login',
        [AuthController::class, 'login']
    )->name('login.store');

});


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');

    Route::post(
        '/logout',
        [AuthController::class, 'logout']
    )->name('logout');


    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/notifications',
        [NotificationController::class, 'index']
    )->name('notifications.index');

    Route::post(
        '/notifications/{notification}/read',
        [NotificationController::class, 'read']
    )->name('notifications.read');

    Route::post(
        '/notifications/read-all',
        [NotificationController::class, 'readAll']
    )->name('notifications.read-all');

});


    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware([
        'auth',
        'role:Admin',
    ])->group(function () {

        Route::get(
            '/admin',
            [DashboardController::class, 'index']
        )->name('admin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/reports/reservations',
        [
            AdminReportController::class,
            'reservationReport'
        ]
    )->name('admin.reports.reservations');

    Route::get(
        '/admin/reports/reservations/export',
        [
            AdminReportController::class,
            'exportReservationReport'
        ]
    )->name('admin.reports.reservations.export');

    Route::get(
        '/admin/reports/reservations/{reservation}',
        [
            AdminReportController::class,
            'showReservationReport'
        ]
    )->name('admin.reports.reservations.show');


    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'users',
        UserController::class
    )->except([
        'show',
    ]);


    /*
    |--------------------------------------------------------------------------
    | Buildings
    |--------------------------------------------------------------------------
    */

    Route::delete(
        '/buildings/{building}/images/{image}',
        [BuildingController::class, 'destroyImage']
    )->name('buildings.images.destroy');

    Route::resource(
        'buildings',
        BuildingController::class
    )->except([
        'show',
    ]);


    /*
    |--------------------------------------------------------------------------
    | Rooms
    |--------------------------------------------------------------------------
    */

    Route::delete(
        '/rooms/{room}/images/{image}',
        [RoomController::class, 'destroyImage']
    )->name('rooms.images.destroy');

    Route::resource(
        'rooms',
        RoomController::class
    )->except([
        'show',
    ]);

    Route::get(
        '/rooms/{room}',
        [RoomController::class, 'show']
    )->name('rooms.show');


    /*
    |--------------------------------------------------------------------------
    | Training Rooms
    |--------------------------------------------------------------------------
    */

    Route::delete(
        '/training-rooms/{trainingRoom}/images/{image}',
        [TrainingRoomController::class, 'destroyImage']
    )->name('training-rooms.images.destroy');

    Route::resource(
        'training-rooms',
        TrainingRoomController::class
    )->except([
        'show',
    ]);

    Route::get(
        '/training-rooms/{trainingRoom}',
        [TrainingRoomController::class, 'show']
    )->name('training-rooms.show');


    /*
    |--------------------------------------------------------------------------
    | Fields
    |--------------------------------------------------------------------------
    */

    Route::delete(
        '/fields/{field}/images/{image}',
        [FieldController::class, 'destroyImage']
    )->name('fields.images.destroy');

    Route::resource(
        'fields',
        FieldController::class
    )->except([
        'show',
    ]);

    Route::get(
        '/fields/{field}',
        [FieldController::class, 'show']
    )->name('fields.show');


    /*
    |--------------------------------------------------------------------------
    | Reservations
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/reservations/{reservation}/edit',
        [ReservationController::class, 'edit']
    )->name('reservations.edit');

    Route::put(
        '/reservations/{reservation}',
        [ReservationController::class, 'update']
    )->name('reservations.update');

    Route::delete(
        '/reservations/{reservation}',
        [ReservationController::class, 'destroy']
    )->name('reservations.destroy');

    Route::resource(
        'reservations',
        ReservationController::class
    )->only([
        'index',
        'create',
        'store',
        'show',
    ]);


    /*
    |--------------------------------------------------------------------------
    | News
    |--------------------------------------------------------------------------
    */

    Route::delete(
        '/news/{news}/images/{image}',
        [NewsController::class, 'destroyImage']
    )->name('news.images.destroy');

    Route::put(
        '/news/{news}',
        [NewsController::class, 'update']
    )->name('news.update');

    Route::delete(
        '/news/{news}',
        [NewsController::class, 'destroy']
    )->name('news.destroy');

    Route::resource(
        'news',
        NewsController::class
    )->only([
        'index',
        'create',
        'store',
        'show',
        'edit',
    ]);


    /*
    |--------------------------------------------------------------------------
    | Audit Logs
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/audit-logs',
        [AuditLogController::class, 'index']
    )->name('audit-logs.index');

    Route::get(
        '/audit-logs/{auditLog}',
        [AuditLogController::class, 'show']
    )->name('audit-logs.show');

});


/*
|--------------------------------------------------------------------------
| Building Coordinator Routes
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'building.coordinator',
])
    ->prefix('building-coordinator')
    ->name('building-coordinator.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [BuildingCoordinatorDashboardController::class, 'index']
        )->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Reservations
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reservations',
            [BuildingCoordinatorReservationController::class, 'index']
        )->name('reservations.index');

        Route::get(
            '/reservations/{reservation}/edit',
            [BuildingCoordinatorReservationController::class, 'edit']
        )->name('reservations.edit');

        Route::get(
            '/reservations/{reservation}',
            [BuildingCoordinatorReservationController::class, 'show']
        )->name('reservations.show');

        Route::put(
            '/reservations/{reservation}',
            [BuildingCoordinatorReservationController::class, 'update']
        )->name('reservations.update');

        Route::post(
            '/reservations/{reservation}/approve',
            [BuildingCoordinatorReservationController::class, 'approve']
        )->name('reservations.approve');

        Route::post(
            '/reservations/{reservation}/reject',
            [BuildingCoordinatorReservationController::class, 'reject']
        )->name('reservations.reject');

        Route::post(
            '/reservations/{reservation}/cancel',
            [BuildingCoordinatorReservationController::class, 'cancel']
        )->name('reservations.cancel');


        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reports/reservations',
            [BuildingCoordinatorReportController::class, 'reservationReport']
        )->name('reports.reservations');

        Route::get(
            '/reports/reservations/export',
            [BuildingCoordinatorReportController::class, 'exportReservationReport']
        )->name('reports.reservations.export');

        Route::get(
            '/reports/reservations/{reservation}',
            [BuildingCoordinatorReportController::class, 'showReservationReport']
        )->name('reports.reservations.show');

    });


/*
|--------------------------------------------------------------------------
| Coordinator Routes
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:Coordinator',
])
    ->prefix('coordinator')
    ->name('coordinator.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [CoordinatorDashboardController::class, 'index']
        )->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Reservations
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reservations',
            [CoordinatorReservationController::class, 'index']
        )->name('reservations.index');

        Route::get(
            '/reservations/{reservation}/edit',
            [CoordinatorReservationController::class, 'edit']
        )->name('reservations.edit');

        Route::get(
            '/reservations/{reservation}',
            [CoordinatorReservationController::class, 'show']
        )->name('reservations.show');

        Route::put(
            '/reservations/{reservation}',
            [CoordinatorReservationController::class, 'update']
        )->name('reservations.update');

        Route::post(
            '/reservations/{reservation}/approve',
            [CoordinatorReservationController::class, 'approve']
        )->name('reservations.approve');

        Route::post(
            '/reservations/{reservation}/reject',
            [CoordinatorReservationController::class, 'reject']
        )->name('reservations.reject');

        Route::post(
            '/reservations/{reservation}/cancel',
            [CoordinatorReservationController::class, 'cancel']
        )->name('reservations.cancel');


        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reports/reservations',
            [CoordinatorReportController::class, 'reservationReport']
        )->name('reports.reservations');

        Route::get(
            '/reports/reservations/export',
            [CoordinatorReportController::class, 'exportReservationReport']
        )->name('reports.reservations.export');

        Route::get(
            '/reports/reservations/{reservation}',
            [CoordinatorReportController::class, 'showReservationReport']
        )->name('reports.reservations.show');

    });


/*
|--------------------------------------------------------------------------
| Field Coordinator Routes
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:Field Coordinator',
])
    ->prefix('field-coordinator')
    ->name('field-coordinator.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Reservations
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reservations',
            [CoordinatorReservationController::class, 'index']
        )->name('reservations.index');

        Route::get(
            '/reservations/{reservation}/edit',
            [CoordinatorReservationController::class, 'edit']
        )->name('reservations.edit');

        Route::get(
            '/reservations/{reservation}',
            [CoordinatorReservationController::class, 'show']
        )->name('reservations.show');

        Route::put(
            '/reservations/{reservation}',
            [CoordinatorReservationController::class, 'update']
        )->name('reservations.update');

        Route::post(
            '/reservations/{reservation}/approve',
            [CoordinatorReservationController::class, 'approve']
        )->name('reservations.approve');

        Route::post(
            '/reservations/{reservation}/reject',
            [CoordinatorReservationController::class, 'reject']
        )->name('reservations.reject');

        Route::post(
            '/reservations/{reservation}/cancel',
            [CoordinatorReservationController::class, 'cancel']
        )->name('reservations.cancel');


        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reports/reservations',
            [CoordinatorReportController::class, 'reservationReport']
        )->name('reports.reservations');

        Route::get(
            '/reports/reservations/export',
            [CoordinatorReportController::class, 'exportReservationReport']
        )->name('reports.reservations.export');

        Route::get(
            '/reports/reservations/{reservation}',
            [CoordinatorReportController::class, 'showReservationReport']
        )->name('reports.reservations.show');

    });


/*
|--------------------------------------------------------------------------
| Training Officer Routes
|--------------------------------------------------------------------------
|
| HANYA untuk role:
| Training Officer
|
*/

Route::middleware([
    'auth',
    'role:Training Officer',
])
    ->prefix('training-officer')
    ->name('training-officer.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Home
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [TrainingOfficerController::class, 'index']
        )->name('home');


         /*
    |--------------------------------------------------------------------------
    | My Reservations
    |--------------------------------------------------------------------------
    */

       Route::get(
            '/my-reservations',
            [ReservationController::class, 'myReservations']
        )->name('my-reservations.index');

        Route::get(
            '/my-reservations/{reservation}',
            [ReservationController::class, 'myShow']
        )->name('my-reservations.show');


        /*
        |--------------------------------------------------------------------------
        | Cart / Booking List
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/cart',
            [TrainingOfficerCartController::class, 'index']
        )->name('cart');

        Route::post(
            '/cart/add',
            [TrainingOfficerCartController::class, 'add']
        )->name('cart.add');

        Route::delete(
            '/cart/remove',
            [TrainingOfficerCartController::class, 'remove']
        )->name('cart.remove');


        /*
        |--------------------------------------------------------------------------
        | Reservation - Start
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reservation/create',
            [TrainingOfficerReservationController::class, 'create']
        )->name('reservation.create');


        /*
        |--------------------------------------------------------------------------
        | Reservation - Multi Resource Step
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reservation/step/{step}',
            [TrainingOfficerReservationController::class, 'create']
        )->name('reservation.step');

        Route::post(
            '/reservation/step/{step}',
            [TrainingOfficerReservationController::class, 'store']
        )->name('reservation.step.store');


        /*
        |--------------------------------------------------------------------------
        | Reservation - Individual Resource
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reservation/{type}/{resource}/create',
            [TrainingOfficerReservationController::class, 'createResource']
        )->name('reservation.resource.create');

        Route::post(
            '/reservation/{type}/{resource}',
            [TrainingOfficerReservationController::class, 'storeResource']
        )->name('reservation.resource.store');


        /*
        |--------------------------------------------------------------------------
        | Reservation - Review
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reservation/review',
            [TrainingOfficerReservationController::class, 'review']
        )->name('reservation.review');


        /*
        |--------------------------------------------------------------------------
        | Reservation - Final Submission
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/reservation/submit',
            [TrainingOfficerReservationController::class, 'submit']
        )->name('reservation.submit');

    });


/*
|--------------------------------------------------------------------------
| Training Officer Classroom Routes
|--------------------------------------------------------------------------
|
| HANYA untuk role:
| Training Officer Classroom
|
*/

Route::middleware([
    'auth',
    'role:Training Officer Classroom',
])
    ->prefix('training-officer/classroom')
    ->name('training-officer.classroom.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Home
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [TrainingOfficerClassroomController::class, 'index']
        )->name('home');


                /*
        |--------------------------------------------------------------------------
        | My Reservations
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/my-reservations',
            [
                TrainingOfficerClassroomReservationController::class,
                'myReservations'
            ]
        )->name('my-reservations');

        Route::get(
            '/my-reservations/{reservation}',
            [TrainingOfficerClassroomReservationController::class, 'show']
        )->name('my-reservations.show');


        /*
        |--------------------------------------------------------------------------
        | Booking List / Cart
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/cart',
            [TrainingOfficerClassroomCartController::class, 'index']
        )->name('cart');

        Route::post(
            '/cart/add',
            [TrainingOfficerClassroomCartController::class, 'add']
        )->name('cart.add');

        Route::delete(
            '/cart/remove',
            [TrainingOfficerClassroomCartController::class, 'remove']
        )->name('cart.remove');

        Route::delete(
            '/cart/clear',
            [TrainingOfficerClassroomCartController::class, 'clear']
        )->name('cart.clear');


        /*
        |--------------------------------------------------------------------------
        | Reservation - Start
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reservation/create',
            [TrainingOfficerClassroomReservationController::class, 'create']
        )->name('reservation.create');


        /*
        |--------------------------------------------------------------------------
        | Reservation - Room
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reservation/room/{room}/create',
            [TrainingOfficerClassroomReservationController::class, 'createResource']
        )->name('reservation.resource.create');

        Route::post(
            '/reservation/room/{room}',
            [TrainingOfficerClassroomReservationController::class, 'storeResource']
        )->name('reservation.resource.store');


        /*
        |--------------------------------------------------------------------------
        | Reservation - Review
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reservation/review',
            [TrainingOfficerClassroomReservationController::class, 'review']
        )->name('reservation.review');


        /*
        |--------------------------------------------------------------------------
        | Reservation - Final Submission
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/reservation/submit',
            [TrainingOfficerClassroomReservationController::class, 'submit']
        )->name('reservation.submit');

    });