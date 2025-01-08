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

class ProductVariations extends EditRecord
{
    // Link this page to the ProductResource
    protected static string $resource = ProductResource::class;

    // Optional page title and navigation icon
    protected static ?string $title = 'Variation Types';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    // Define the form structure for editing records
    public function form(Form $form): Form
    {
        // Load variation types and their options
        $this->record->loadMissing('variationTypes.options');
        $types = $this->record->variationTypes;
        $fields = [];

        // Create fields dynamically for each variation type
        foreach ($types as $type) {
            $fields[] = TextInput::make("variation_type_{$type->id}.id")
                ->hidden()
                ->default($type->id);

            $fields[] = TextInput::make("variation_type_{$type->id}.name")
                ->label($type->name)
                ->default($type->name);
        }

        // Return the form schema
        return $form->schema([
            Repeater::make('variations') // Repeater for variations
                ->label(false)
                ->collapsible()
                ->addable(false)
                ->defaultItems(1)
                ->schema([
                    Section::make() // Section for variation details
                        ->schema($fields)
                        ->columns(3),
                    TextInput::make('quantity') // Quantity input field
                        ->label("Quantity")
                        ->numeric(),
                    TextInput::make('price') // Price input field
                        ->label('Price')
                        ->numeric(),
                ])
                ->columns(2)
                ->columnSpan(2),
        ]);
    }

    // Define actions in the page header
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(), // Action to delete a record
        ];
    }

    // Prepare data before filling the form
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->record->loadMissing(['variations', 'variationTypes.options']); // Load relationships
        $variations = $this->record->variations->toArray();
        $variationTypes = $this->record->variationTypes;

        // Merge existing variations with new combinations
        $data['variations'] = $this->mergeCartesianWithExisting($variationTypes, $variations);

        return $data;
    }

    // Create cartesian product of variations and merge with existing data
    private function mergeCartesianWithExisting($variationTypes, $existingData)
    {
        $defaultQuantity = $this->record->quantity;
        $defaultPrice = $this->record->price;

        // Generate all possible combinations
        $cartesianProduct = $this->cartesianProduct($variationTypes, $defaultQuantity, $defaultPrice);

        $mergedResult = [];
        foreach ($cartesianProduct as $product) {
            // Match combinations with existing data or set defaults
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

    // Generate all possible combinations of variation types
    private function cartesianProduct($variationTypes, $defaultQuantity = null, $defaultPrice = null)
    {
        $result = [[]];

        foreach ($variationTypes as $variationType) {
            $temp = [];
            foreach ($variationType->options as $option) {
                foreach ($result as $combination) {
                    $temp[] = $combination + [
                        'variation_type_' . $variationType->id => [
                            'id' => $option->id,
                            'name' => $option->name,
                            'label' => $variationType->name,
                        ],
                    ];
                }
            }
            $result = $temp;
        }

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

        // Ensure 'variations' key exists in the data
        if (isset($data['variations']) && is_array($data['variations'])) {
            foreach ($data['variations'] as $option) {
                $variationTypeOptionIds = [];

                // Loop through each variation type and collect the relevant option IDs
                foreach ($this->record->variationTypes as $variationType) {
                    $key = 'variation_type_' . $variationType->id;
                    if (isset($option[$key])) {
                        $variationTypeOptionIds[] = $option[$key]['id'];
                    } else {
                        $variationTypeOptionIds[] = null; // Handle missing keys gracefully
                    }
                }

                // Ensure quantity and price have default values
                $quantity = $option['quantity'] ?? 0;
                $price = $option['price'] ?? 0.00;

                // Add formatted data
                $formattedData[] = [
                    'products_variation_options' => $variationTypeOptionIds, // Match the database column name
                    'quantity' => $quantity,
                    'price' => $price,
                ];
            }
        }

        // Update 'variations' key with formatted data
        $data['variations'] = $formattedData;

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Extract variations from data
        $variations = $data['variations'] ?? [];
        unset($data['variations']); // Remove 'variations' to avoid conflict with other columns

        // Update main record (e.g., product)
        $record->update($data);

        // Handle variations
        if (!empty($variations)) {
            try {
                // Delete existing variations
                $record->variations()->delete();

                // Format 'products_variation_options' as JSON for insertion
                foreach ($variations as &$variation) {
                    // Ensure 'products_variation_options' is an array
                    $variation['products_variation_options'] = json_encode($variation['products_variation_options']);
                }

                // Insert or update variations
                $record->variations()->upsert(
                    $variations,
                    ['id'], // Detect updates by unique 'id'
                    ['products_variation_options', 'quantity', 'price'] // Fields to update
                );
            } catch (\Exception $e) {
                // Log error if something goes wrong
                logger()->error('Failed to update variations: ' . $e->getMessage());
                throw $e;
            }
        }

        return $record;
    }
}
