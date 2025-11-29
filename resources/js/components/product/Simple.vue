<template>
	<div class="grid w-full grid-cols-12 gap-y-4 gap-x-1">

		<!-- SKU -->
		<div class="grid col-span-12 md:col-span-4 items-center gap-1.5">
			<Label for="sku">SKU</Label>
			<Input
				v-model="productSimple.sku"
				id="sku"
				class="mt-1 block w-full"
				name="sku"
				placeholder="SKU"
			/>
			<InputError v-if="props.errors?.sku" :message="props.errors.sku" />
		</div>
		<!-- SKU -->

		<!-- Giá gốc -->
		<div class="grid col-span-12 md:col-span-4 items-center gap-1.5">
			<Label for="price">Giá gốc</Label>
			<NumberField
				:format-options="priceFormatOptions"
				locale="vi-VN"
				v-model="productSimple.price"
			>
				<NumberFieldInput
					id="price"
					class="w-full block mt-1 py-1 px-3 h-9 text-left focus-visible:ring-0 focus-visible:ring-offset-0
						focus-visible:border-primary"
					name="price"
					placeholder="Giá gốc"
				/>
			</NumberField>
			<InputError v-if="props.errors?.price" :message="props.errors.price" />
		</div>
		<!-- Giá gốc -->

		<!-- Trạng thái sale -->
		<div class="grid col-span-12 md:col-span-4 items-center gap-1.5">
			<Label
				for="sale_status"
				class="mb-1"
				>Trạng thái sale</Label
			>
			<SelectBase
				:items="statusOptions"
				:default-value="productSimple.sale_status"
				name="sale_status"
				placeholder="Chọn trạng thái sale"
				@change="(value) => (productSimple.sale_status = value as number)"
			/>
			<InputError v-if="props.errors?.sale_status" :message="props.errors.sale_status" />
		</div>
		<!-- Trạng thái sale -->

		<!-- Giá khuyến mãi -->
		<div class="grid col-span-12 md:col-span-4 items-center gap-1.5">
			<Label for="sale_price">Giá khuyến mãi</Label>
			<NumberField
				:format-options="priceFormatOptions"
				locale="vi-VN"
				v-model="productSimple.sale_price"
				:disabled="!isSaleActive"
			>
				<NumberFieldInput
					id="sale_price"
					class="w-full block mt-1 py-1 px-3 h-9 text-left focus-visible:ring-0 focus-visible:ring-offset-0
						focus-visible:border-primary"
					name="sale_price"
					placeholder="Giá khuyến mãi"
				/>
			</NumberField>
			<InputError v-if="props.errors?.sale_price" :message="props.errors.sale_price" />
		</div>
		<!-- Giá khuyến mãi -->

		<!-- Ngày bắt đầu khuyến mãi -->
		<div class="grid col-span-12 md:col-span-4 items-center gap-1.5">
			<Label for="sale_price_start">Ngày bắt đầu khuyến mãi</Label>
			<DateTimePicker
				v-model="productSimple.sale_price_start"
				placeholder="Ngày bắt đầu khuyến mãi"
				:disabled="!isSaleActive"
				@update="(value: any) => (productSimple.sale_price_start = value)"
			/>
			<InputError v-if="props.errors?.sale_price_start" :message="props.errors.sale_price_start" />
		</div><!-- Ngày bắt đầu khuyến mãi -->

		<!-- Ngày kết thúc khuyến mãi -->
		<div class="grid col-span-12 md:col-span-4 items-center gap-1.5">
			<Label for="sale_price_end">Ngày kết thúc khuyến mãi</Label>
			<DateTimePicker
				v-model="productSimple.sale_price_end"
				placeholder="Ngày kết thúc khuyến mãi"
				:disabled="!isSaleActive"
				@update="(value: any) => (productSimple.sale_price_end = value)"
			/>
			<InputError v-if="props.errors?.sale_price_end" :message="props.errors.sale_price_end" />
		</div>
		<!-- Ngày kết thúc khuyến mãi -->

		<!-- Quản lý kho -->
		<div class="grid col-span-12 md:col-span-4 items-center gap-1.5">
			<Label
				for="manage_stock"
				class="mb-1"
				>Quản lý kho</Label
			>
			<SelectBase
				:items="manageStockOptions"
				:default-value="productSimple.manage_stock"
				name="manage_stock"
				placeholder="Trạng thái quản lý kho"
				@change="(value) => (productSimple.manage_stock = value as number)"
			/>
			<InputError v-if="props.errors?.manage_stock" :message="props.errors.manage_stock" />
		</div>
		<!-- Quản lý kho -->

		<!-- Trạng thái kho -->
		<div class="grid col-span-12 md:col-span-4 items-center gap-1.5">
			<Label
				for="stock_status"
				class="mb-1"
				>Trạng thái kho</Label
			>
			<SelectBase
				:items="stockStatusOptions"
				:default-value="productSimple.stock_status"
				name="stock_status"
				placeholder="Trạng thái kho"
				@change="(value) => (productSimple.stock_status = value as number)"
			/>
			<InputError v-if="props.errors?.stock_status" :message="props.errors.stock_status" />
		</div>
		<!-- Trạng thái kho -->

		<!--Số lượng-->
		<div
			class="grid col-span-12 md:col-span-4 items-center gap-1.5"
		>
			<Label
				for="stock_qty"
				class="mb-1"
				>Số lượng</Label
			>
			<NumberField
				v-model="productSimple.stock_qty"
				:disabled="productSimple.manage_stock === EnumManageStock.NO || productSimple.stock_status === EnumStockStatus.OUT_OF_STOCK"
			>
				<NumberFieldInput
					id="stock_qty"
					class="w-full block mt-1 py-1 px-3 h-9 text-left focus-visible:ring-0 focus-visible:ring-offset-0
						focus-visible:border-primary"
					name="stock_qty"
					placeholder="Số lượng"
				/>
			</NumberField>
			<InputError v-if="props.errors?.stock_qty" :message="props.errors.stock_qty" />
		</div>
		<!-- Số lượng sản phẩm trong kho -->
	</div>
</template>

<script lang="ts" setup>
	import DateTimePicker from '../DateTimePicker.vue'
	import { EnumProductSaleStatus } from '@/enums/EnumProductSaleStatus'
	import { NumberField, NumberFieldInput } from '@/components/ui/number-field'
	import SelectBase from '@/components/common/SelectBase.vue'
	import InputError from '@/components/InputError.vue'
	import { Label } from '@/components/ui/label'
	import { Input } from '@/components/ui/input'
	import { EnumManageStock } from '@/enums/EnumManageStock'
	import type { InertiaForm } from '@inertiajs/vue3'
	import { computed, reactive, watch } from 'vue'
	import { EnumStatus } from '@/enums/EnumStatus'
	import { EnumStockStatus } from '@/enums/EnumStockStatus'

	const props = defineProps<{
		form: InertiaForm<any>
		errors?: Record<string, string>
		priceFormatOptions: Intl.NumberFormatOptions
		statusOptions: Array<{ label: string; id: number | string }>
		manageStockOptions: Array<{ label: string; id: number | string }>
		stockStatusOptions: Array<{ label: string; id: number | string }>
	}>()

	const isSaleActive = computed(() => {
		return props.form.sale_status === EnumProductSaleStatus.ACTIVE
	})

	const emits = defineEmits<{
		update: [value: any]
	}>()

	const productSimple = reactive({
		sku: props.form.sku,
		price: props.form.price,
		sale_price: props.form.sale_price,
		sale_status: props.form.sale_status,
		manage_stock: props.form.manage_stock,
		stock_qty: props.form.stock_qty,
		stock_status: props.form.stock_status,
		sale_price_start: props.form.sale_price_start,
		sale_price_end: props.form.sale_price_end,
	})

	watch(
		() => productSimple,
		(newValue) => {
			if (newValue) {
				let stockQty = newValue.stock_qty
				let salePrice = newValue.sale_price
				let salePriceStart = newValue.sale_price_start
				let salePriceEnd = newValue.sale_price_end

				if (newValue.manage_stock === EnumManageStock.NO) {
					stockQty = null
				}

				if (newValue.sale_status === EnumStatus.DISABLED) {
					salePrice = null
					salePriceStart = null
					salePriceEnd = null
				}

				emits('update', {
					sku: newValue.sku,
					price: newValue.price,
					sale_price: salePrice,
					sale_price_start: salePriceStart,
					sale_price_end: salePriceEnd,
					sale_status: newValue.sale_status,
					manage_stock: newValue.manage_stock,
					stock_qty: stockQty,
					stock_status: newValue.stock_status,
				})
			}
		},
		{ deep: true }
	)
</script>
