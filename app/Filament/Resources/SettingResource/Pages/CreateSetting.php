<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSetting extends CreateRecord
{
    protected static string $resource = SettingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['key']) && $data['key'] === 'slider_type' && isset($data['value_slider_type'])) {
            $data['value'] = $data['value_slider_type'];
        }
        
        unset($data['value_slider_type']);

        return $data;
    }
}
