<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    public static function getFriendlyKey(string $key): string
    {
        return match ($key) {
            'slider_type' => 'Manşet (Slider) Tasarımı',
            'header_type' => 'Üst Menü (Header) Tasarımı',
            'footer_type' => 'Alt Bilgi (Footer) Tasarımı',
            'color_primary' => 'Ana Renk (Primary)',
            'color_secondary' => 'İkincil Renk (Secondary)',
            'font' => 'Yazı Tipi (Font)',
            'site_title' => 'Site Başlığı',
            'site_description' => 'Site Açıklaması',
            'contact_email' => 'İletişim E-posta',
            'contact_phone' => 'İletişim Telefon',
            'facebook_url' => 'Facebook Adresi',
            'twitter_url' => 'Twitter Adresi',
            'instagram_url' => 'Instagram Adresi',
            'logo' => 'Site Logosu',
            'favicon' => 'Site İkonu (Favicon)',
            'pharmacy_is_active' => 'Nöbetçi Eczane Modülü',
            'pharmacy_city' => 'Nöb. Eczane: İl',
            'pharmacy_district' => 'Nöb. Eczane: İlçe',
            'pharmacy_custom_slug' => 'Nöb. Eczane: URL Bağlantısı',
            'pharmacy_seo_title' => 'Nöb. Eczane: SEO Başlığı',
            'pharmacy_seo_description' => 'Nöb. Eczane: SEO Açıklama',
            default => str($key)->replace('_', ' ')->title()->toString(),
        };
    }

    public static function getFriendlyValue(string $key, ?string $value): ?string
    {
        if (!$value) return '-';
        
        return match ($key) {
            'slider_type' => match ($value) {
                'dual-slider' => 'Dual Slider (Dar + Dev Manşet)',
                'full-width' => 'Tam Genişlik (Tek Dev Manşet)',
                'headline-grid' => 'Izgara Manşet',
                'numbered-slider' => 'Numaralı Manşet',
                default => $value,
            },
            'header_type' => match ($value) {
                'type-1' => 'Klasik Menü (Tip 1)',
                'type-2' => 'Modern Menü (Tip 2)',
                'type-3' => 'Minimal Menü (Tip 3)',
                default => $value,
            },
            'footer_type' => match ($value) {
                'type-1' => 'Klasik Footer (Tip 1)',
                'type-2' => 'Modern Footer (Tip 2)',
                'type-3' => 'Minimal Footer (Tip 3)',
                default => $value,
            },
            'color_primary', 'color_secondary' => "Renk Kodu: {$value}",
            default => str($value)->limit(50)->toString(),
        };
    }

    protected static ?string $model = Setting::class;

    protected static ?string $modelLabel = 'Ayar';
    protected static ?string $pluralModelLabel = 'Sistem Ayarları';
    protected static ?string $navigationGroup = 'Sistem Yönetimi';

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Select::make('group')
                        ->label('Ayar Grubu')
                        ->options([
                            'general' => 'Genel Ayarlar',
                            'theme' => 'Tema Ayarları',
                            'seo' => 'SEO Ayarları',
                            'modules' => 'Modül Ayarları',
                        ])
                        ->required()
                        ->default('theme'),

                    Forms\Components\TextInput::make('key')
                        ->label('Ayar Anahtarı (Key)')
                        ->required()
                        ->maxLength(255)
                        ->live(),

                    Forms\Components\Select::make('value_slider_type')
                        ->label('Slider Seçimi (Değer)')
                        ->options([
                            'dual-slider' => 'Dual Slider (Dar + Dev Manşet)',
                            'full-width' => 'Tam Genişlik (Tek Dev Manşet)',
                            'headline-grid' => 'Izgara Manşet',
                            'numbered-slider' => 'Numaralı Manşet',
                        ])
                        ->afterStateHydrated(function (Forms\Components\Select $component, ?Setting $record) {
                            if ($record?->key === 'slider_type') {
                                $component->state($record->value);
                            }
                        })
                        ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                             if ($get('key') === 'slider_type') {
                                 $set('value', $state);
                             }
                        })
                        ->live()
                        ->visible(fn (Forms\Get $get) => $get('key') === 'slider_type')
                        ->required(fn (Forms\Get $get) => $get('key') === 'slider_type'),

                    Forms\Components\Textarea::make('value')
                        ->label('Değer (Value)')
                        ->required(fn (Forms\Get $get) => $get('key') !== 'slider_type')
                        ->visible(fn (Forms\Get $get) => $get('key') !== 'slider_type')
                        ->columnSpanFull(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')
                    ->label('Grup')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'general' => 'Genel Ayarlar',
                        'theme' => 'Tema Ayarları',
                        'seo' => 'SEO Ayarları',
                        'modules' => 'Modül Ayarları',
                        default => $state,
                    })
                    ->colors([
                        'primary' => 'theme',
                        'success' => 'general',
                        'warning' => 'seo',
                        'info' => 'modules',
                    ]),
                Tables\Columns\TextColumn::make('key')
                    ->label('Ayar Adı')
                    ->formatStateUsing(fn (string $state): string => self::getFriendlyKey($state))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->label('Kayıtlı Değer')
                    ->formatStateUsing(fn (string $state, Setting $record): string => self::getFriendlyValue($record->key, $state))
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Son Güncelleme')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->label('Grup Seç')
                    ->options([
                        'general' => 'Genel Ayarlar',
                        'theme' => 'Tema Ayarları',
                        'seo' => 'SEO Ayarları',
                        'modules' => 'Modül Ayarları',
                    ]),
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
