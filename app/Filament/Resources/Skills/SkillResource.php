<?php

namespace App\Filament\Resources\Skills;

use App\Filament\Resources\Skills\Pages\ManageSkills;
use App\Models\Skill;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SkillResource extends Resource
{
    protected static ?string $model = Skill::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('skill_category_id')->label('Category')->relationship('category', 'name')->searchable()->preload()->required(),
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('icon_key')->maxLength(255)->helperText('Optional icon identifier for later use.'),
                Select::make('proficiency')->options([
                    'familiar' => 'Familiar',
                    'intermediate' => 'Intermediate',
                    'advanced' => 'Advanced',
                ])->required(),
                TextInput::make('sort_order')->numeric()->integer()->minValue(0)->default(0)->required(),
                Toggle::make('is_visible')->label('Visible on site')->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('category.name')->label('Category')->sortable(),
                TextColumn::make('proficiency')->badge()->formatStateUsing(fn (string $state): string => ucfirst($state)),
                TextColumn::make('sort_order')->sortable(),
                IconColumn::make('is_visible')->boolean(),
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
            'index' => ManageSkills::route('/'),
        ];
    }
}
