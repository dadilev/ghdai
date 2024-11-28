<?php

namespace App\Filament\Resources;

use App\Enums\AdStatus;
use App\Enums\AdTemplateStatus;
use App\Filament\Resources\AdsResource\Pages;
use App\Filament\Resources\AdsResource\RelationManagers;
use App\Models\Ads;
use App\Models\AdTemplates;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdsResource extends Resource
{
    protected static ?string $model = Ads::class;

    protected static ?string $navigationGroup = 'Ads';
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    public static function form(Form $form): Form
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(fn () => collect(AdStatus::cases())->mapWithKeys(fn (AdStatus $status) => [
                        $status->value => $status->getLabel(),
                    ]))
            ])
            ->actions([
                Tables\Actions\Action::make('generateAdTemplate')
                    ->label('Generate Template')
                    ->action(function (Ads $record, array $data): void {
                        self::processAdTemplate($record, $data);
                    })
                    ->form([
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


                        Forms\Components\Select::make('status')
                            ->options(fn () => collect(AdTemplateStatus::cases())->mapWithKeys(fn (AdTemplateStatus $status) => [
                                $status->value => $status->getLabel(),
                            ]))
                            ->default(AdTemplateStatus::DRAFT->value)
                            ->required(),

                    ])
                    ->modalHeading('Generate Ad Template')
                    ->modalButton('Generate'),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAds::route('/'),
            'create' => Pages\CreateAds::route('/create'),
            'edit' => Pages\EditAds::route('/{record}/edit'),
        ];
    }

    protected static function processAdTemplate(Ads $ad, array $data)
    {
        // Create a new ad template
        $template = AdTemplates::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'canva_url' => $data['canva_url'],
            'ad_id' => $ad->id,
            'status' => $data['status']
        ]);

        // Update the Ad status to 'completed'
        $ad->update(['status' => 'completed']);

        //show notification for user
        return Notification::make()
            ->title('Ad Template Generated!')
            ->success()
            ->send();
    }

}
