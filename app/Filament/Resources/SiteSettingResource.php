<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Filament\Resources\SiteSettingResource\RelationManagers;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    use \App\Traits\HasNavigationBadge;

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $pluralModelLabel = 'Manajamen Site Setting';

    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('app_name')
                    ->label('App Name')
                    ->required()
                    ->maxLength(100)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('brand_name')
                    ->label('Brand Name')
                    ->maxLength(150)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('brand_logo')
                    ->label('Brand Logo')
                    ->image()
                    ->directory('brands')
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '1:1', '4:3', '16:9'
                    ])
                    ->maxSize(2048)
                    ->openable()
                    ->downloadable()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('locale')
                    ->label('Locale')
                    ->required()
                    ->default('en')
                    ->maxLength(10),
                Forms\Components\TextInput::make('fallback_locale')
                    ->label('Fallback Locale')
                    ->required()
                    ->default('en')
                    ->maxLength(10),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\ImageColumn::make('brand_logo')
                    ->label('Logo')
                    ->circular()
                    ->height(40)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('brand_name')
                    ->label('Brand Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('app_name')
                    ->label('App Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('locale')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fallback_locale')
                    ->label('Fallback Locale')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSiteSettings::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
