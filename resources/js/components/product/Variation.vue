<template>
	<div class="grid w-full grid-cols-12 gap-y-4 gap-x-1">
		<div class="grid col-span-12 md:col-span-6 items-center gap-1.5">
			<Label>Chọn thuộc tính</Label>
			<MultiSelect
				:categories="attributeForSelect"
				v-model="attributeIdsSelected"
				placeholder="Chọn thuộc tính..."
			/>
		</div>

		<div
			v-if="attributeIdsSelected.length > 0"
			class="grid col-span-12 md:col-span-6 items-end"
		>
			<div class="flex flex-wrap justify-end gap-x-2">
				<span
					class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium
						transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none
						[&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none
						focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]
						aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40
						aria-invalid:border-destructive bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-9
						px-4 py-2 has-[>svg]:px-3 cursor-pointer"
					@click="onCreateVariable"
					>Tạo biến thể</span
				>
				<span
					class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium
						transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none
						[&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none
						focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]
						aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40
						aria-invalid:border-destructive bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-9
						px-4 py-2 has-[>svg]:px-3 cursor-pointer"
					@click="onGenerateVariable"
					>Tạo nhiều biến thể</span
				>
			</div>
		</div>

		<div
			v-for="(attribute, index) in attributeSelect"
			class="grid col-span-12 md:col-span-2 items-center gap-1.5"
			:key="index"
		>
			<Label>{{ attribute.name }}</Label>
			<SelectBase
				:items="attribute.values"
				:placeholder="attribute.label"
				@changeItem="(item: AttributeValue) => onValueChange(item, index)"
			>
				<template #item="{ item }">
					<div class="flex gap-x-2 items-center">
						<span
							v-if="attribute.type === EnumAttributeType.COLOR"
							class="w-4 h-4 block border border-gray-400 p-1"
							:style="`background-color: ${item.value}`"
						></span>
						<span>{{ item.label }}</span>
					</div>
				</template>
			</SelectBase>
		</div>

		<template v-if="form.length > 0">
			<div v-for="(item, index) in form"
					 class="grid col-span-12 items-center gap-1.5"
					 :key="index">
				<Accordion type="single" collapsible class="w-full" default-value="item-1">
					<AccordionItem value="item-1">
						<AccordionTrigger as-child>
							<div v-for="(attribute, key) in item.attributes" :key="key">
								<span class="font-semibold">{{attribute.label}}</span>
							</div>
						</AccordionTrigger>
						<AccordionContent>
							<p>
								Our flagship product combines cutting-edge technology with sleek
								design. Built with premium materials, it offers unparalleled
								performance and reliability.
							</p>
							<p class="mt-2">
								Key features include advanced processing capabilities, and an
								intuitive user interface designed for both beginners and experts.
							</p>
						</AccordionContent>
					</AccordionItem>
				</Accordion>
			</div>
		</template>
	</div>
</template>

<script lang="ts" setup>
	import { Label } from '@/components/ui/label'
	import MultiSelect from '@/components/MultiSelect.vue'
	import { computed, reactive, ref, watch } from 'vue'
	import { EnumAttributeType } from '@/enums/EnumAttributeType'
	import SelectBase from '@/components/common/SelectBase.vue'
	import { Button } from '@/components/ui/button'
	import { EnumStatus } from '@/enums/EnumStatus'
	import { EnumManageStock } from '@/enums/EnumManageStock'
	import {
		Accordion,
		AccordionContent,
		AccordionItem,
		AccordionTrigger,
	} from '@/components/ui/accordion'

	interface Attribute {
		attribute_id: number
		values: any[]
		name: string
		type: string
	}

	interface AttributeValue {
		id: number
		attribute_id: number
		label: string
		value: string | null
		sort_order: number
	}

	interface Variation {
		sku: string | null
		title: string | null
		status: EnumStatus
		price: number | null
		sale_price: number | null
		manage_stock: EnumManageStock
		stock_qty: number | null
		stock_status: EnumStatus
		image_id: number | null
		image: string | null
		is_default: boolean
		attributes: {
			attribute_id: number
			value_id: number
			label: string
		}[] | null
	}

	const props = defineProps<{
		attributes: any
	}>()
	const form = ref<Variation[]>([])

	const attributeForSelect = computed(() => {
		return props.attributes.map((attr: any) => {
			return {
				id: attr.id,
				label: attr.name,
			}
		})
	})
	const attributeIdsSelected = ref<number[]>([])
	const attributeSelect = ref()
	const attributeValueSelect = ref<AttributeValue[]>([])

	const onValueChange = (value: AttributeValue, index: number) => {
		attributeValueSelect.value[index] = value
	}

	const onCreateVariable = () => {
		if (form.value.length === 0) {
			form.value = [
				{
					sku: null,
					title: null,
					status: EnumStatus.ACTIVE,
					price: null,
					sale_price: null,
					manage_stock: EnumManageStock.NO,
					stock_qty: null,
					stock_status: EnumStatus.ACTIVE,
					image_id: null,
					image: null,
					is_default: true,
					attributes: attributeValueSelect.value.map((item) => {
						return {
							attribute_id: item.attribute_id,
							value_id: item.id,
							label: item.label,
						}
					}),
				},
			]
		}
		console.log(form.value)
	}

	const onGenerateVariable = () => {}

	watch(attributeIdsSelected, (newVal) => {
		if (newVal.length > 0) {
			attributeSelect.value = props.attributes
				.filter((attr: any) => newVal.includes(attr.id))
				.map((attr: any) => ({
					attribute_id: attr.id,
					values: attr.values,
					name: attr.name,
					type: attr.type,
				}))
		} else {
			attributeSelect.value = []
		}
	})
</script>
