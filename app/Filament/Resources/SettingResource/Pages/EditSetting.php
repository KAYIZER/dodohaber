<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['key']) && $data['key'] === 'slider_type' && isset($data['value_slider_type'])) {
            $data['value'] = $data['value_slider_type'];
        }
        
        unset($data['value_slider_type']);

        return $data;
    }
}
