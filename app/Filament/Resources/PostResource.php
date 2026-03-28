<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $modelLabel = 'Haber';
    protected static ?string $pluralModelLabel = 'Haberler';
    protected static ?string $navigationGroup = 'Haber Yönetimi';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Haber İçeriği')->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Haber Başlığı')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),
                        Forms\Components\TextInput::make('slug')
                            ->label('URL Uzantısı')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\Textarea::make('spot')
                            ->label('Opsiyonel Spot (Özet) Metni')
                            ->columnSpanFull()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('content')
                            ->label('Haber İçeriği')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),
                    
                    Forms\Components\Section::make('Seo Ayarları')->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('SEO Başlığı'),
                        Forms\Components\Textarea::make('meta_description')
                            ->label('SEO Açıklaması')
                            ->columnSpanFull(),
                    ])->columns(2)->collapsed(),
                ])->columnSpan(['lg' => fn (?Post $record) => $record === null ? 3 : 2]),

                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Durum & Görünürlük')->schema([
                        Forms\Components\Select::make('status')
                            ->label('Yayın Durumu')
                            ->options([
                                'draft' => 'Taslak',
                                'published' => 'Yayında',
                                'archived' => 'Arşiv',
                            ])
                            ->required()
                            ->default('draft'),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Yayın Tarihi')
                            ->default(now()),
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('user_id')
                            ->label('Yazar')
                            ->relationship('author', 'name')
                            ->required()
                            ->default(fn () => auth()->id()),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Manşet Haber'),
                        Forms\Components\Toggle::make('is_narrow_featured')
                            ->label('Dar Manşet'),
                        Forms\Components\Toggle::make('is_breaking')
                            ->label('Son Dakika'),
                    ]),
                    Forms\Components\Section::make('Görsel')->schema([
                        Forms\Components\FileUpload::make('image_url')
                            ->label('Haber Görseli')
                            ->disk('public')
                            ->directory(function () {
                                $now = now();
                                return $now->format('Y') . '/' . $now->format('m');
                            })
                            ->image()
                            ->imageEditor()
                            ->maxSize(5120)
                            ->required(),
                    ]),
                ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Görsel')
                    ->disk('public')
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Post $record): ?string => $record->spot ? \Illuminate\Support\Str::limit($record->spot, 50) : null),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label('Yazar')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Taslak',
                        'published' => 'Yayında',
                        'archived' => 'Arşiv',
                        default => $state,
                    })
                    ->colors([
                        'danger' => 'draft',
                        'success' => 'published',
                        'warning' => 'archived',
                    ]),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Manşet'),
                Tables\Columns\IconColumn::make('is_narrow_featured')
                    ->boolean()
                    ->label('Dar Manşet'),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Yayın Tarihi')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('view_count')
                    ->label('Okunma')
                    ->sortable()
                    ->numeric(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori Seç')
                    ->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Durum Seç')
                    ->options([
                        'draft' => 'Taslak',
                        'published' => 'Yayında',
                        'archived' => 'Arşiv'
                    ]),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Sadece Manşetler'),
                Tables\Filters\TernaryFilter::make('is_narrow_featured')
                    ->label('Sadece Dar Manşetler'),
                Tables\Filters\TernaryFilter::make('is_breaking')
                    ->label('Sadece Son Dakika'),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
