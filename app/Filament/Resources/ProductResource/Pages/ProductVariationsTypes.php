<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Enum\ProductStatusEnum;
use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class ProductVariationsTypes extends EditRecord
{
    protected static string $resource = ProductResource::class;
    protected static ?string $navigationIcon='heroicon-c-photo';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
               Repeater::make('variationsTypes')
               ->relationship()
               ->collapsible()
               ->defaultItems(1)
               ->addActionLabel('Add variations Type')
               ->columns(2)
               ->columnSpan(2)
               ->schema([
                TextInput::make('name')->required(),
                Select::make('type')
                ->options([ProductStatusEnum::labels()])

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
