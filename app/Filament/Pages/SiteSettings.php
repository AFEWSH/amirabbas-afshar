<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class SiteSettings extends Page
{
    protected static ?string $navigationLabel = 'Site settings';

    protected static ?string $title = 'Site settings';

    protected string $view = 'filament.pages.site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(SiteSetting::find(1)?->attributesToArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->components([
            TextInput::make('site_name')->maxLength(255),
            Textarea::make('default_seo_description')->maxLength(255)->rows(3),
            FileUpload::make('og_image_path')->label('Default sharing image')->disk('site_assets')->directory('og')->visibility('private')
                ->image()->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])->maxSize(2048)
                ->rule('dimensions:min_width=320,min_height=180,max_width=4000,max_height=4000'),
        ]);
    }

    public function save(): void
    {
        SiteSetting::updateOrCreate(['id' => 1], $this->form->getState());

        Notification::make()->title('Site settings saved')->success()->send();
    }
}
