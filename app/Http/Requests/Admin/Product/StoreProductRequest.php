<?php

namespace App\Http\Requests\Admin\Product;

use App\Enums\EnumProductManagerStock;
use App\Enums\EnumProductStockStatus;
use App\Enums\EnumProductType;
use App\Enums\EnumStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $type = $this->input('type');

        if ($type === EnumProductType::VARIABLE->value) {
            return $this->rulesForVariableProduct();
        }
        if ($type === EnumProductType::VIRTUAL->value) {
            return $this->ruleForVirtualProduct();
        }

        return $this->ruleForSimpleProduct();
    }

    protected function ruleDefaultProduct(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug'],
            'desc' => ['nullable', 'string', 'max:10000'],
            'short_desc' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_desc' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'type' => ['required', Rule::in(EnumProductType::cases())],
            'status' => ['required', Rule::in(EnumStatus::cases())],
            'image_id' => ['nullable', 'integer', 'exists:medias,id'],
            'gallery_ids' => ['nullable', 'array'],
            'gallery_ids.*' => ['integer', 'exists:medias,id'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['integer', 'exists:tags,id'],
        ];
    }

    protected function ruleForSimpleProduct(): array
    {
        return array_merge($this->ruleDefaultProduct(), [
            'sku' => ['nullable', 'string', 'max:100'],
            'sale_status' => ['required', Rule::in(EnumStatus::cases())],
            'price' => ['nullable', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'sale_price_start' => ['nullable', 'required_if:sale_status,' . EnumStatus::ACTIVE->value, 'date_format:Y-m-d H:i:s'],
            'sale_price_end' => ['nullable', 'required_if:sale_status,' . EnumStatus::ACTIVE->value, 'date_format:Y-m-d H:i:s', 'after:sale_price_start'],
            'manage_stock' => ['required', Rule::in(EnumProductManagerStock::cases())],
            'stock_qty' => ['required_if:manage_stock,' . EnumProductManagerStock::YES->value, 'nullable', 'min:0'],
            'stock_status' => ['required', Rule::in(EnumProductStockStatus::cases())],
        ]);
    }

    protected function ruleForVirtualProduct(): array
    {
        return array_merge($this->ruleDefaultProduct(), [
            'download_limit' => ['nullable', 'integer', 'min:0'],
            'download_link' => ['nullable', 'url', 'max:255'],
            'download_expiry' => ['nullable', 'date_format:Y-m-d H:i:s'],
        ]);
    }

    protected function rulesForVariableProduct(): array
    {
        return array_merge($this->ruleDefaultProduct(), [
            'attributes' => ['required', 'array'],
            'attributes.*.id' => ['required', 'integer', 'exists:attributes,id'],
            'attributes.*.values' => ['required', 'array'],
            'attributes.*.values.*' => ['required', 'string', 'max:255'],
        ]);
    }
}
