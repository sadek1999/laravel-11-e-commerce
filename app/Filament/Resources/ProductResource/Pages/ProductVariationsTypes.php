<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Enum\ProductStatusEnum;
use App\Enum\ProductVariationTypeEnum;
use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class ProductVariationsTypes extends EditRecord
{
    protected static string $resource = ProductResource::class;
    protected static ?string $title='Variation Types';
    protected static ?string $navigationIcon = 'heroicon-m-list-bullet';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('variationTypes')
                    ->label(false)
                    ->relationship('variationTypes') // Define the relationship in the Product model
                    ->collapsible()
                    ->defaultItems(1)
                    ->addActionLabel('Add Variation Type')
                    ->columns(2)
                    ->columnSpan(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Variation Name')
                            ->required(),

                        Select::make('type')
                            ->label('Variation Type')
                            ->options(ProductVariationTypeEnum::labels()),

                        Repeater::make('options')
                            ->relationship('options') // Define the relationship in VariationType model
                            ->collapsible()
                            ->defaultItems(1)
                            ->addActionLabel('Add Option')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Option Name')
                                    ->columnSpan(2)
                                    ->required(),

                                SpatieMediaLibraryFileUpload::make('image')
                                    ->label('Option Image')
                                    ->image()
                                    ->openable()
                                    ->multiple()
                                    ->panelLayout("grid")
                                    ->collection('images') // Ensure collection name matches your logic
                                    ->reorderable()
                                    ->appendFiles()
                                    ->preserveFilenames(),
                            ])
                            ->columnSpan(2)

                    ])
            ]);
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
