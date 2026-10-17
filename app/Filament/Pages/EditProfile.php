<?php

namespace App\Filament\Pages;

use App\Models\Profile;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class EditProfile extends Page
{
    protected static ?string $navigationLabel = 'Profile';

    protected static ?string $title = 'Edit profile';

    protected string $view = 'filament.pages.edit-profile';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Profile::find(1)?->attributesToArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->components([
            TextInput::make('full_name')->maxLength(255),
            TextInput::make('title')->maxLength(255),
            TextInput::make('specialization')->maxLength(255),
            Textarea::make('introduction')->rows(3),
            Textarea::make('background')->rows(5),
            Textarea::make('expertise')->rows(5),
            Textarea::make('interests')->rows(5),
            Textarea::make('approach')->rows(5),
            TextInput::make('contact_email')->email()->maxLength(255),
        ]);
    }

    public function save(): void
    {
        Profile::updateOrCreate(['id' => 1], $this->form->getState());

        Notification::make()->title('Profile saved')->success()->send();
    }
}
