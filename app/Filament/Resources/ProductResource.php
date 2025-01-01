<?php

namespace App\Filament\Resources;

use App\Enum\ProductStatusEnum;
use App\Enum\RolesEnum;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Doctrine\DBAL\Query\From;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Livewire\Features\SupportConsoleCommands\Commands\FormCommand;
use function Laravel\Prompts\select;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                Forms\Components\Grid::make()
                ->schema([
                    TextInput::make('title')
                    ->live(onBlur: true)->required()
                    ->afterStateUpdated(function (string $operation, $state, callable $set) {
                        $set('slug', str::slug($state));
                    }),
                TextInput::make('slug')->required(),
                Select::make('department_id')
                    ->relationship('department', 'name')
                    ->label(__('Department'))
                    ->searchable()
                    ->reactive()
                    ->preload()
                    ->afterStateUpdated(function(callable $set){
                        $set('category_id',null);
                    }),
                Select::make('category_id')
                ->relationship(
                   'category',
                   'name',
                   function(Builder $query ,callable $get){
                    $departmentId=$get('department_id');
                    if($departmentId){
                        $query->where('department_id',$departmentId);
                    }
                   }
                )
                ->label(__('Category'))
                ->preload()
                ->searchable()
                ->required()
                ,

                ]),
                Forms\Components\RichEditor::make('description')
                ->required()
                ->toolbarButtons([
                    'blockquote',   // Blockquote button
                    'bold',         // Bold text button
                    'bulletList',   // Unordered (bullet) list button
                    'h2',           // Heading level 2 button
                    'h3',           // Heading level 3 button
                    'italic',       // Italic text button
                    'link',         // Hyperlink button
                    'orderedList',  // Ordered (numbered) list button
                    'redo',         // Redo button
                    'strike',       // Strikethrough text button
                    'underline',    // Underline text button
                    'undo',         // Undo button
                    'table',        // Insert table button
                ])
                ->columnSpan(2),
                TextInput::make('price')->required()->nullable(),
                TextInput::make('quantity')->integer(),
                Select::make('status')
                ->options(ProductStatusEnum::labels())
                ->default(ProductStatusEnum::Draft->value)
                ->required(),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('title')->searchable()->words(10)->sortable(),
                TextColumn::make('status')->badge()->color(fn ($state) => ProductStatusEnum::colors()[$state] ?? 'default'),

                TextColumn::make('department.name'),
                TextColumn::make('category.name'),
                TextColumn::make('created_at')->dateTime(),

            ])
            ->filters([
                SelectFilter::make('status')->options(ProductStatusEnum::labels()),
                SelectFilter::make('department_id')->relationship('department','name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
    // public static function canViewAny(): bool
    // {
    //     $user=Filament::auth()->user();
    //     return $user&& $user->hasAnyRole([RolesEnum::Admin,RolesEnum::Vendor]);
    // }
}
