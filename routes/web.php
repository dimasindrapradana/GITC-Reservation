<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\CoordinatorDashboardController;
use App\Http\Controllers\CoordinatorReportController;
use App\Http\Controllers\CoordinatorReservationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TrainingRoomController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth')->group(function () {

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');

    Route::post(
        '/logout',
        [AuthController::class, 'logout']
    )->name('logout');

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

Route::middleware(['auth', 'role:Admin'])->group(function () {

    Route::get(
        '/admin',
        [DashboardController::class, 'index']
    )->name('admin.dashboard');

    Route::resource(
        'buildings',
        BuildingController::class
    )->except([
        'show',
    ]);

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

    Route::get(
        '/audit-logs',
        [AuditLogController::class, 'index']
    )->name('audit-logs.index');

    Route::get(
        '/audit-logs/{auditLog}',
        [AuditLogController::class, 'show']
    )->name('audit-logs.show');

});

Route::middleware(['auth', 'role:Coordinator'])
    ->prefix('coordinator')
    ->name('coordinator.')
    ->group(function () {

        Route::get(
            '/',
            [CoordinatorDashboardController::class, 'index']
        )->name('dashboard');

        Route::get(
            '/reservations',
            [CoordinatorReservationController::class, 'index']
        )->name('reservations.index');

        Route::get(
            '/reservations/{reservation}',
            [CoordinatorReservationController::class, 'show']
        )->name('reservations.show');

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

        Route::get(
            '/reports/reservations',
            [CoordinatorReportController::class, 'reservationReport']
        )->name('reports.reservations');

        Route::get(
            '/reports/reservations/export',
            [CoordinatorReportController::class, 'exportReservationReport']
        )->name('reports.reservations.export');
    });