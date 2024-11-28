<?php

namespace App\Filament\Resources;

use App\Enums\AdTemplateStatus;
use App\Filament\Resources\AdTemplatesResource\Pages;
use App\Filament\Resources\AdTemplatesResource\RelationManagers;
use App\Models\AdTemplates;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdTemplatesResource extends Resource
{
    protected static ?string $model = AdTemplates::class;

    protected static ?string $navigationGroup = 'Ads';
    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Ad Template details')->schema([
                        Forms\Components\TextInput::make('title')
                            ->maxLength(255)->required(),
                        Forms\Components\RichEditor::make('description'),
                        Forms\Components\TextInput::make('canva_url')
                            ->label('Canva URL')
                            ->rules([
                                'required',
                                'regex:/^https:\/\/(www\.)?canva\.com\//',
                            ])
                            ->helperText('Provide a valid Canva link, e.g., https://www.canva.com/design/...'),
                    ])
                ])->columnSpan(2),
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Status and relations')->schema([
                        Forms\Components\Select::make('status')
                            ->options(fn () => collect(AdTemplateStatus::cases())->mapWithKeys(fn (AdTemplateStatus $status) => [
                                $status->value => $status->getLabel(),
                            ]))
                            ->default(AdTemplateStatus::DRAFT->value)
                            ->required(),
                        Forms\Components\Select::make('ad_id')
                            ->label('Ad')
                            ->required()
                            ->relationship('ad', 'title'),
                    ])
                ])
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('ad.title')->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(fn () => collect(AdTemplateStatus::cases())->mapWithKeys(fn (AdTemplateStatus $status) => [
                        $status->value => $status->getLabel(),
                    ]))
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            'ad' => RelationManagers\AdsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdTemplates::route('/'),
            'create' => Pages\CreateAdTemplates::route('/create'),
            'edit' => Pages\EditAdTemplates::route('/{record}/edit'),
        ];
    }

    public static function messages(): array
    {
        return [
            'canva_url.regex' => 'The Canva URL must start with https://www.canva.com/',
        ];
    }
}
