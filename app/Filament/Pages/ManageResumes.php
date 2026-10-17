<?php

namespace App\Filament\Pages;

use App\Models\ResumeFile;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Validation\ValidationException;

class ManageResumes extends Page
{
    protected static ?string $navigationLabel = 'Resumes';

    protected static ?string $title = 'Resume files';

    protected string $view = 'filament.pages.manage-resumes';

    public ?array $data = [];

    public function mount(): void
    {
        $resumes = ResumeFile::whereIn('language', ['en', 'fa'])->get()->keyBy('language');

        $this->form->fill([
            'en_path' => $resumes->get('en')?->path,
            'en_original_name' => $resumes->get('en')?->original_name,
            'fa_path' => $resumes->get('fa')?->path,
            'fa_original_name' => $resumes->get('fa')?->original_name,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->components([
            FileUpload::make('en_path')->label('English PDF')->disk('resumes')->directory('en')->visibility('private')
                ->acceptedFileTypes(['application/pdf'])->rule('mimes:pdf')->maxSize(5120)->storeFileNamesIn('en_original_name'),
            FileUpload::make('fa_path')->label('Persian PDF')->disk('resumes')->directory('fa')->visibility('private')
                ->acceptedFileTypes(['application/pdf'])->rule('mimes:pdf')->maxSize(5120)->storeFileNamesIn('fa_original_name'),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $updates = [];

        foreach (['en', 'fa'] as $language) {
            if ($path = $data["{$language}_path"] ?? null) {
                $updates[$language] = [
                    'path' => $path,
                    'original_name' => $data["{$language}_original_name"] ?? basename($path),
                ];

                if (! (new ResumeFile(['language' => $language, ...$updates[$language]]))->isAvailable()) {
                    throw ValidationException::withMessages(["data.{$language}_path" => 'Upload a valid PDF file.']);
                }
            }
        }

        foreach ($updates as $language => $values) {
            ResumeFile::updateOrCreate(['language' => $language], $values);
        }

        Notification::make()->title('Resume files saved')->success()->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('removeEnglish')->label('Remove English PDF')->color('danger')
                ->visible(fn (): bool => ResumeFile::where('language', 'en')->exists())
                ->requiresConfirmation()->action(fn () => $this->remove('en')),
            Action::make('removePersian')->label('Remove Persian PDF')->color('danger')
                ->visible(fn (): bool => ResumeFile::where('language', 'fa')->exists())
                ->requiresConfirmation()->action(fn () => $this->remove('fa')),
        ];
    }

    public function remove(string $language): void
    {
        abort_unless(in_array($language, ['en', 'fa'], true), 404);

        ResumeFile::where('language', $language)->delete();
        $this->mount();

        Notification::make()->title('Resume removed from site')->success()->send();
    }
}
