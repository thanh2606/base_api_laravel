<?php

namespace Database\Seeders;

use App\Enums\EnumAttributeType;
use App\Enums\EnumStatus;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            [
                'name' => 'Màu sắc',
                'type' => EnumAttributeType::COLOR->value,
                'values' => [
                    [
                        'label' => 'Đỏ',
                        'value' => '#FF0000',
                    ],
                    [
                        'label' => 'Xanh',
                        'value' => '#0000FF',
                    ],
                    [
                        'label' => 'Vàng',
                        'value' => '#FFFF00',
                    ],
                    [
                        'label' => 'Trắng',
                        'value' => '#FFFFFF',
                    ],
                    [
                        'label' => 'Đen',
                        'value' => '#000000',
                    ],
                    [
                        'label' => 'Xám',
                        'value' => '#808080',
                    ],
                    [
                        'label' => 'Hồng',
                        'value' => '#FFC0CB',
                    ],
                ],
            ],
            [
                'name' => 'Kích thước',
                'type' => EnumAttributeType::SELECT->value,
                'values' => [
                    [
                        'label' => 'S',
                        'value' => 's',
                    ],
                    [
                        'label' => 'M',
                        'value' => 'm',
                    ],
                    [
                        'label' => 'L',
                        'value' => 'l',
                    ],
                    [
                        'label' => 'XL',
                        'value' => 'xl',
                    ],
                    [
                        'label' => 'XL',
                        'value' => 'xl',
                    ],
                    [
                        'label' => 'XXL',
                        'value' => 'xxl',
                    ],
                ],
            ],
        ];

        foreach ($attributes as $key => $attribute) {
            $record = Attribute::query()->create([
                'name' => $attribute['name'],
                'slug' => Str::slug($attribute['name']),
                'type' => $attribute['type'],
                'sort_order' => $key + 1,
                'status' => EnumStatus::ACTIVE->value,
            ]);

            foreach ($attribute['values'] as $sort => $value) {
                AttributeValue::query()->create([
                    'attribute_id' => $record->id,
                    'label' => $value['label'],
                    'value' => $value['value'],
                    'sort_order' => $sort + 1,
                ]);
            }
        }
    }
}
