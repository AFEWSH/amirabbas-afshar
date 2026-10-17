<?php

namespace App\Filament\Resources\Education;

use App\Filament\Resources\Education\Pages\ManageEducation;
use App\Models\Education;
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

class EducationResource extends Resource
{
    protected static ?string $model = Education::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'institution';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('institution')->required()->maxLength(255),
                TextInput::make('degree')->maxLength(255),
                TextInput::make('field_of_study')->maxLength(255),
                DatePicker::make('start_date'),
                DatePicker::make('end_date')->rule('after_or_equal:start_date'),
                Textarea::make('description')->rows(4),
                TextInput::make('institution_fa')->label('Institution (Persian)')->maxLength(255),
                TextInput::make('degree_fa')->label('Degree (Persian)')->maxLength(255),
                TextInput::make('field_of_study_fa')->label('Field Of Study (Persian)')->maxLength(255),
                Textarea::make('description_fa')->label('Description (Persian)')->rows(4),
                TextInput::make('sort_order')->numeric()->integer()->minValue(0)->default(0)->required(),
                Toggle::make('is_visible')->label('Visible on site')->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('institution')->searchable()->sortable(),
                TextColumn::make('degree'),
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
            'index' => ManageEducation::route('/'),
        ];
    }
}
