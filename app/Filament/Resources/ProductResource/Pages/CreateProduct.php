<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Container\Attributes\Auth;
use Pest\Preset;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
    public function mutateFromDataBeforeCreate($data)
    {
        // $data['created_by']=auth()->id();
        // $data['updated_by']=auth()->id();
        $data['created_by'] = auth()->id(); // Add authenticated user ID
        $data['updated_by'] = auth()->id();

    return parent::mutateFormDataBeforeCreate($data);
    }
}
