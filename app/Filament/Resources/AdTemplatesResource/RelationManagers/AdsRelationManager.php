<?php

namespace App\Filament\Resources\AdTemplatesResource\RelationManagers;

use App\Enums\AdStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdsRelationManager extends RelationManager
{
    protected static string $relationship = 'ad';

    protected static string $header = 'Ads';
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Ad details')->schema([
                        Forms\Components\TextInput::make('title')
                            ->maxLength(255)->required(),
                        Forms\Components\RichEditor::make('description')
                            ->extraInputAttributes(['style' => 'max-height: 300px; overflow-y: scroll'])->required(),
                        Forms\Components\TextInput::make('url')
                            ->maxLength(255)->required(),
                    ])
                ])->columnSpan(2),
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Status')->schema([
                        Forms\Components\Select::make('status')
                            ->options(fn () => collect(AdStatus::cases())->mapWithKeys(fn (AdStatus $status) => [
                                $status->value => $status->getLabel(),
                            ]))
                            ->default(AdStatus::PENDING->value)
                            ->required()
                    ])
                ])->columnSpan(1),
            ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

                ]),
            ]);
    }
}
