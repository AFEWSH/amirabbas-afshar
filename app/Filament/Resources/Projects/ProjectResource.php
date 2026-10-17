<?php

namespace App\Filament\Resources\Projects;

use App\Filament\Resources\Projects\Pages\ManageProjects;
use App\Models\Project;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')->required()->maxLength(255),
                TextInput::make('slug')->required()->alphaDash()->unique(ignoreRecord: true)->maxLength(255),
                Select::make('project_category_id')->label('Category')->relationship('category', 'name')->searchable()->preload(),
                Select::make('technologies')->relationship('technologies', 'name')->multiple()->searchable()->preload(),
                Textarea::make('summary')->required()->rows(3),
                Textarea::make('body')->rows(8)->helperText('Plain text. Paragraph breaks are preserved on the site.'),
                Textarea::make('role')->rows(3),
                Textarea::make('challenges')->rows(4),
                Textarea::make('solutions')->rows(4),
                TextInput::make('github_url')->label('GitHub URL')->url()->rule('url:http,https')->maxLength(2048),
                TextInput::make('live_url')->label('Live URL')->url()->rule('url:http,https')->maxLength(2048),
                Toggle::make('is_featured')->label('Featured on home')->default(false),
                Select::make('status')->options(['draft' => 'Draft', 'published' => 'Published'])->default('draft')->required()->live(),
                DateTimePicker::make('published_at')->label('Publish at')->required(fn (Get $get): bool => $get('status') === 'published'),
                TextInput::make('sort_order')->numeric()->integer()->minValue(0)->default(0)->required(),
                TextInput::make('seo_title')->maxLength(255),
                TextInput::make('seo_description')->maxLength(255),
                TextInput::make('title_fa')->label('Title (Persian)')->maxLength(255),
                Textarea::make('summary_fa')->label('Summary (Persian)')->rows(4),
                Textarea::make('body_fa')->label('Body (Persian)')->rows(4),
                Textarea::make('role_fa')->label('Role (Persian)')->rows(4),
                Textarea::make('challenges_fa')->label('Challenges (Persian)')->rows(4),
                Textarea::make('solutions_fa')->label('Solutions (Persian)')->rows(4),
                TextInput::make('seo_title_fa')->label('Seo Title (Persian)')->maxLength(255),
                TextInput::make('seo_description_fa')->label('Seo Description (Persian)')->maxLength(255),
                Repeater::make('images')->relationship()->orderColumn('sort_order')->schema([
                    FileUpload::make('path')->label('Image')->disk('project_images')->directory('projects')->visibility('private')
                        ->image()->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])->maxSize(2048)
                        ->rule('dimensions:min_width=320,min_height=180,max_width=4000,max_height=4000')->required(),
                    TextInput::make('alt_text')->required()->maxLength(255),
                    TextInput::make('alt_text_fa')->label('Alt text (Persian)')->maxLength(255),
                ])->label('Project images')->defaultItems(0)->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('category.name')->label('Category'),
                TextColumn::make('status')->badge(),
                TextColumn::make('published_at')->dateTime()->sortable(),
                IconColumn::make('is_featured')->boolean(),
                TextColumn::make('sort_order')->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageProjects::route('/'),
        ];
    }
}
