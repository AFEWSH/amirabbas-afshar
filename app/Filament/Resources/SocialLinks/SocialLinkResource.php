<?php

namespace App\Filament\Resources\SocialLinks;

use App\Filament\Resources\SocialLinks\Pages\ManageSocialLinks;
use App\Models\SocialLink;
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

class SocialLinkResource extends Resource
{
    protected static ?string $model = SocialLink::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')->required()->maxLength(255),
                TextInput::make('label_fa')->label('Label (Persian)')->maxLength(255),
                TextInput::make('url')->required()->url()->rule('url:http,https')->maxLength(2048),
                Select::make('icon_key')->label('Network icon')->options([
                    'github' => 'GitHub', 'linkedin' => 'LinkedIn', 'instagram' => 'Instagram',
                    'telegram' => 'Telegram', 'whatsapp' => 'WhatsApp', 'x' => 'X / Twitter',
                    'youtube' => 'YouTube', 'website' => 'Website',
                ])->placeholder('Detect automatically from URL')->helperText('Shown beside Send message and in the footer. Add a Persian label to display on Persian pages.'),
                TextInput::make('sort_order')->numeric()->integer()->minValue(0)->default(0)->required(),
                Toggle::make('is_visible')->label('Visible on site')->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')->searchable()->sortable(),
                TextColumn::make('url')->limit(50),
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
            'index' => ManageSocialLinks::route('/'),
        ];
    }
}
