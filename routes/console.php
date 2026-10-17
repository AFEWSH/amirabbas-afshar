<?php
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
Artisan::command('inspire', fn () => $this->comment('Build something great.'));

Artisan::command('portfolio:create-admin', function (): void {
    if (User::query()->where('is_admin', true)->exists()) {
        $this->error('An administrator already exists.');
        return;
    }

    $name = $this->ask('Administrator name');
    $email = $this->ask('Administrator email');
    $password = $this->secret('Administrator password (at least 12 characters)');

    $validated = Validator::make(compact('name', 'email', 'password'), [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'string', 'min:12'],
    ])->validate();

    User::query()->create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => $validated['password'],
    ])->forceFill(['is_admin' => true])->save();

    $this->info('Administrator created.');
})->purpose('Create the single Filament administrator');
