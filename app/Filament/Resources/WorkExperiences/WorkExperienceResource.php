<?php

namespace App\Filament\Resources\WorkExperiences;

use App\Filament\Resources\WorkExperiences\Pages\ManageWorkExperiences;
use App\Models\WorkExperience;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WorkExperienceResource extends Resource
{
    protected static ?string $model = WorkExperience::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'job_title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company')->required()->maxLength(255),
                TextInput::make('job_title')->required()->maxLength(255),
                TextInput::make('employment_type')->maxLength(255),
                TextInput::make('location')->maxLength(255),
                DatePicker::make('start_date')->required(),
                DatePicker::make('end_date')->rule('after_or_equal:start_date'),
                Toggle::make('is_current')->label('Current role')->default(false),
                Textarea::make('description')->rows(3),
                Textarea::make('responsibilities')->rows(4)->helperText('Plain text; use one line per point.'),
                Textarea::make('achievements')->rows(4)->helperText('Plain text; use one line per point.'),
                TextInput::make('sort_order')->numeric()->integer()->minValue(0)->default(0)->required(),
                Toggle::make('is_visible')->label('Visible on site')->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('job_title')->searchable()->sortable(),
                TextColumn::make('company')->searchable(),
                TextColumn::make('start_date')->date()->sortable(),
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
            'index' => ManageWorkExperiences::route('/'),
        ];
    }
}
