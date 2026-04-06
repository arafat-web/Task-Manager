<?php
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ChecklistItemController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectFileController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Profile routes
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/password', [ProfileController::class, 'showPasswordForm'])->name('profile.password');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');

    Route::controller(MailController::class)->prefix('mail')->name('mail.')->group(function () {
        Route::get('/', 'index')->name('inbox');
    });
    Route::resource('projects', ProjectController::class);
    Route::post('project/team', [ProjectController::class, 'addMember'])->name('projects.addMember');
    Route::get('projects/{project}/tasks', [TaskController::class, 'index'])->name('projects.tasks.index');
    Route::post('projects/{project}/tasks', [TaskController::class, 'store'])->name('projects.tasks.store');

    Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::post('tasks/{task}/update-status', [TaskController::class, 'updateStatus']);

    Route::resource('routines', RoutineController::class)->except(['show']);
    Route::get('routines/showAll', [RoutineController::class, 'showAll'])->name('routines.showAll');
    Route::get('routines/daily', [RoutineController::class, 'showDaily'])->name('routines.showDaily');
    Route::get('routines/weekly', [RoutineController::class, 'showWeekly'])->name('routines.showWeekly');
    Route::get('routines/monthly', [RoutineController::class, 'showMonthly'])->name('routines.showMonthly');
    Route::resource('files', FileController::class);
    Route::resource('notes', NoteController::class);
    Route::patch('notes/{note}/toggle-favorite', [NoteController::class, 'toggleFavorite'])->name('notes.toggle-favorite');
    Route::post('notes/{note}/duplicate', [NoteController::class, 'duplicate'])->name('notes.duplicate');
    Route::resource('reminders', ReminderController::class);
    Route::post('reminders/{reminder}/toggle-complete', [ReminderController::class, 'toggleComplete'])->name('reminders.toggle-complete');
    Route::post('reminders/{reminder}/snooze', [ReminderController::class, 'snooze'])->name('reminders.snooze');
    Route::post('reminders/{reminder}/duplicate', [ReminderController::class, 'duplicate'])->name('reminders.duplicate');
    Route::get('reminders-calendar', [ReminderController::class, 'calendar'])->name('reminders.calendar');
    Route::resource('checklist-items', ChecklistItemController::class);
    Route::get('checklist-items/{checklistItem}/update-status', [ChecklistItemController::class, 'updateStatus'])->name('checklist-items.update-status');

    // AI Chat
    Route::post('/ai/chat', [AiChatController::class, 'chat'])->name('ai.chat');

    // Dashboard routes
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/productivity-data', [DashboardController::class, 'getProductivityData'])->name('dashboard.productivity-data');
});
