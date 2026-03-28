<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $modelLabel = 'Kategori';
    
    protected static ?string $pluralModelLabel = 'Kategoriler';
    
    protected static ?string $navigationGroup = 'Haber Yönetimi';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Kategori Adı')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),
                    Forms\Components\TextInput::make('slug')
                        ->label('URL Uzantısı')
                        ->required()
                        ->unique(ignoreRecord: true),
                    Forms\Components\Select::make('parent_id')
                        ->label('Üst Kategori')
                        ->relationship('parent', 'name')
                        ->searchable()
                        ->preload(),
                    Forms\Components\Textarea::make('description')
                        ->label('Açıklama')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('meta_title')
                        ->label('SEO Başlığı'),
                    Forms\Components\Textarea::make('meta_description')
                        ->label('SEO Açıklaması')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('order')
                        ->label('Sıralama')
                        ->numeric()
                        ->default(0),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktif mi?')
                        ->default(true),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Adı')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Üst Kategori')
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Uzantı')
                    ->searchable(),
                Tables\Columns\TextColumn::make('order')
                    ->label('Sıra')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Durum')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
