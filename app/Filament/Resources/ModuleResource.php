<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModuleResource\Pages;
use App\Models\Module;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;

    protected static ?string $modelLabel = 'Eklenti';
    protected static ?string $pluralModelLabel = 'Eklentiler';
    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';
    protected static ?string $navigationGroup = 'Sistem Yönetimi';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Eklenti Durumu')
                    ->schema([
                        Forms\Components\Placeholder::make('label_display')
                            ->label('Eklenti Adı')
                            ->content(fn ($record) => $record?->label),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Eklenti Aktif Mi?')
                            ->required()
                            ->live(),
                        
                        // Dinamik URL Bilgilendirmesi (Sadece Okunabilir)
                        Forms\Components\Placeholder::make('slug_info')
                            ->label('Erişim Adresi')
                            ->content(function ($record) {
                                if (!$record || $record->name !== 'pharmacy') return '-';
                                $city = $record->settings['city'] ?? 'il';
                                $dist = $record->settings['district'] ?? '';
                                $slug = Str::slug($city) . ($dist ? '-' . Str::slug($dist) : '') . '-nobetci-eczaneler';
                                return '/' . $slug;
                            })
                            ->visible(fn (Forms\Get $get) => $get('is_active')),
                    ])->columns(3),

                Forms\Components\Section::make('Eklenti Konfigürasyonu')
                    ->description('Bu eklentiye ait bölge bilgilerini girin.')
                    ->schema([
                        Forms\Components\Grid::make([
                            'default' => 2,
                        ])
                            ->schema([
                                Forms\Components\TextInput::make('settings.city')
                                    ->label('Hangi Şehir?')
                                    ->required(fn ($record) => $record?->name === 'pharmacy')
                                    ->placeholder('Örn: Manisa')
                                    ->live(),
                                Forms\Components\TextInput::make('settings.district')
                                    ->label('Hangi İlçe? (Opsiyonel)')
                                    ->placeholder('Örn: Kula')
                                    ->helperText('Boş bırakılırsa tüm ilin eczaneleri gösterilir.')
                                    ->live(),
                                
                                Forms\Components\Section::make('Otomatik SEO Önizleme')
                                    ->compact()
                                    ->schema([
                                        Forms\Components\Placeholder::make('seo_preview')
                                            ->label('Sayfa Başlığı')
                                            ->content(function ($get) {
                                                $city = $get('settings.city') ?: '...';
                                                $dist = $get('settings.district');
                                                return ($dist ? "{$city} {$dist}" : $city) . ' Nöbetçi Eczaneler';
                                            }),
                                    ])
                                    ->columnSpanFull(),
                            ])
                            ->visible(fn ($record) => $record?->name === 'pharmacy'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Eklenti Adı'),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Durum'),
                Tables\Columns\TextColumn::make('full_slug')
                    ->label('Dinamik URL')
                    ->getStateUsing(function (Module $record) {
                        if ($record->name !== 'pharmacy') return '-';
                        $city = $record->settings['city'] ?? 'il';
                        $dist = $record->settings['district'] ?? '';
                        return '/' . Str::slug($city) . ($dist ? '-' . Str::slug($dist) : '') . '-nobetci-eczaneler';
                    })
                    ->badge()
                    ->color('primary'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Yönet')
                    ->modalHeading('Eklenti Yapılandırması'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageModules::route('/'),
        ];
    }
}
