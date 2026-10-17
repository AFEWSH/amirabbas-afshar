<?php

namespace App\Filament\Resources\BlogCategories;

use App\Filament\Resources\BlogCategories\Pages\ManageBlogCategories;
use App\Models\BlogCategory;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BlogCategoryResource extends Resource
{
    protected static ?string $model = BlogCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('name_fa')->label('Name (Persian)')->maxLength(255),
                TextInput::make('slug')->required()->alphaDash()->unique(ignoreRecord: true)->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('slug'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->disabled(fn (BlogCategory $record): bool => $record->posts()->exists()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageBlogCategories::route('/'),
        ];
    }
}
