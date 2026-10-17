<?php

namespace App\Filament\Resources\BlogPosts;

use App\Filament\Resources\BlogPosts\Pages\ManageBlogPosts;
use App\Models\BlogPost;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')->required()->maxLength(255),
                TextInput::make('slug')->required()->alphaDash()->unique(ignoreRecord: true)->maxLength(255),
                Select::make('blog_category_id')->label('Category')->relationship('category', 'name')->searchable()->preload(),
                Textarea::make('excerpt')->required()->rows(3),
                RichEditor::make('content')->required()->fileAttachments(false)->toolbarButtons([
                    ['bold', 'italic', 'link'],
                    ['h2', 'h3'],
                    ['bulletList', 'orderedList', 'blockquote', 'codeBlock'],
                ])->columnSpanFull(),
                FileUpload::make('featured_image_path')->label('Featured image')->disk('blog_images')->directory('featured')->visibility('private')
                    ->image()->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])->maxSize(2048)
                    ->rule('dimensions:min_width=320,min_height=180,max_width=4000,max_height=4000'),
                TextInput::make('featured_image_alt')->label('Featured image description')->maxLength(255)
                    ->required(fn (Get $get): bool => filled($get('featured_image_path'))),
                Select::make('status')->options(['draft' => 'Draft', 'published' => 'Published'])->default('draft')->required()->live(),
                DateTimePicker::make('published_at')->label('Publish at')->required(fn (Get $get): bool => $get('status') === 'published'),
                TextInput::make('seo_title')->maxLength(255),
                TextInput::make('seo_description')->maxLength(255),
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
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('published_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageBlogPosts::route('/'),
        ];
    }
}
