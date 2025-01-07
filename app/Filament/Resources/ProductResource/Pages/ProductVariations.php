<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Parent_;

class ProductVariations extends EditRecord
{
    protected static string $resource = ProductResource::class;
    protected static ?string $title = 'Variation Types';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public function form(Form $form): Form
    {
        // Load relationships to prevent lazy loading errors
        $this->record->loadMissing('variationTypes.options');

        $types = $this->record->variationTypes;
        $fields = [];

        // Generate form fields for each variation type
        foreach ($types as $type) {
            $fields[] = TextInput::make("variation_type_{$type->id}.id")
                ->hidden()
                ->default($type->id);
            $fields[] = TextInput::make("variation_type_{$type->id}.name")
                ->label($type->name)
                ->default($type->name);
        }

        return $form->schema([
            Repeater::make('variations')
                ->label(false)
                ->collapsible()
                ->addable(false)
                ->defaultItems(1)
                ->schema([
                    Section::make()
                        ->schema($fields)
                        ->columns(3),
                    TextInput::make('quantity')
                        ->label("Quantity")
                        ->numeric(),
                    TextInput::make('price')
                        ->label('Price')
                        ->numeric(),
                ])
                ->columns(2)
                ->columnSpan(2),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load necessary relationships for existing variations
        $this->record->loadMissing(['variations', 'variationTypes.options']);

        $variations = $this->record->variations->toArray();
        $variationTypes = $this->record->variationTypes;

        // Merge existing variation data with cartesian product
        $data['variations'] = $this->mergeCartesianWithExisting($variationTypes, $variations);

        return $data;
    }

    private function mergeCartesianWithExisting($variationTypes, $existingData)
    {
        $defaultQuantity = $this->record->quantity;
        $defaultPrice = $this->record->price;

        // Create cartesian product of variation types
        $cartesianProduct = $this->cartesianProduct($variationTypes, $defaultQuantity, $defaultPrice);

        $mergedResult = [];
        foreach ($cartesianProduct as $product) {
            // Match existing data with new product options
            $optionIds = collect($product)
                ->filter(fn($value, $key) => str_starts_with($key, 'variation_type'))
                ->map(fn($option) => $option['id'])
                ->values()
                ->toArray();

            $match = array_filter($existingData, fn($existingOptions) => $existingOptions['products_variation_options'] === $optionIds);

            if (!empty($match)) {
                $existingEntry = reset($match);
                $product['quantity'] = $existingEntry['quantity'];
                $product['price'] = $existingEntry['price'];
            } else {
                $product['quantity'] = $defaultQuantity;
                $product['price'] = $defaultPrice;
            }

            $mergedResult[] = $product;
        }

        return $mergedResult;
    }

    private function cartesianProduct($variationTypes, $defaultQuantity = null, $defaultPrice = null)
    {
        $result = [[]];

        // Generate combinations of variation types and options
        foreach ($variationTypes as $variationType) {
            $temp = [];
            foreach ($variationType->options as $option) {
                foreach ($result as $combination) {
                    $newCombination = $combination + [
                        'variation_type_' . $variationType->id => [
                            'id' => $option->id,
                            'name' => $option->name,
                            'label' => $variationType->name,
                        ],
                    ];
                    $temp[] = $newCombination;
                }
            }
            $result = $temp;
        }

        // Set default quantity and price for each combination
        foreach ($result as &$combination) {
            if (count($combination) === count($variationTypes)) {
                $combination['quantity'] = $defaultQuantity ?? 0;
                $combination['price'] = $defaultPrice ?? 0;
            }
        }

        return $result;
    }
    protected function mutateFormDataBeforeSave(array $data): array
{
    $formattedData = [];

    // Ensure that the 'variations' key exists in the data
    if (isset($data['variations']) && is_array($data['variations'])) {
        foreach ($data['variations'] as $option) {
            $variationTypeOptionIds = [];

            // Loop through each variation type and check if the relevant keys exist in the $option
            foreach ($this->record->variationTypes as $variationType) {
                $key = 'variationType' . $variationType->id;
                // Check if the key exists in the option array before trying to access it
                if (isset($option[$key])) {
                    $variationTypeOptionIds[] = $option[$key]['id'];
                } else {
                    // Optionally, log a message or handle missing keys
                    // Example: Log::warning("Missing variation type option for variation type ID {$variationType->id}");
                    $variationTypeOptionIds[] = null; // Or you can decide to skip this variation type
                }
            }

            // Ensure that the quantity and price are present, set default values if missing
            $quantity = $option['quantity'] ?? 0;
            $price = $option['price'] ?? 0.00;

            // Add the formatted variation data to the result array
            $formattedData[] = [
                'variation_type_option' => $variationTypeOptionIds,
                'quantity' => $quantity,
                'price' => $price,
            ];
        }
    }

    // Update the variations data with the newly formatted data
    $data['variations'] = $formattedData;

    return $data;
}

   protected function handleRecordUpdate(Model $record, array $data): Model
   {
    $variation=$data['variation'];
    return $record;
   }
}
