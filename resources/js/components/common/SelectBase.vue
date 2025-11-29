<template>
	<Select
		:default-value="defaultValue !== null && defaultValue !== undefined ? String(defaultValue) : undefined"
		:model-value="modelValue ? String(modelValue) : undefined"
		:name="name"
		@update:model-value="handleValueChange"
	>
		<SelectTrigger :class="triggerClass">
			<SelectValue :placeholder="placeholder" />
		</SelectTrigger>
		<SelectContent>
			<SelectGroup>
				<SelectItem
					v-for="(item, key) in items"
					:key="key"
					:value="String(item.id)"
				>
					<slot name="item" :item="item">
						{{ truncateString(item.label, 60) }}
					</slot>
				</SelectItem>
			</SelectGroup>
		</SelectContent>
	</Select>
</template>

<script setup lang="ts">
	import {
		Select,
		SelectContent,
		SelectGroup,
		SelectItem,
		SelectTrigger,
		SelectValue,
	} from '@/components/ui/select'
	import { truncateString } from '@/utils/helpers'

	export interface SelectOption {
		id: string | number | null
		label: string
		[key: string]: any
	}

	interface Props {
		items: SelectOption[]
		placeholder?: string
		defaultValue?: string | number | null
		name?: string
		triggerClass?: string
		modelValue?: string | number | null
	}

	interface Emits {
		(e: 'update:modelValue', value: string | number): void
		(e: 'change', value: string | number): void
		(e: 'change-item', value: SelectOption | undefined): void
	}

	const props = withDefaults(defineProps<Props>(), {
		placeholder: 'Select an option',
		triggerClass: 'w-full',
	})

	const emit = defineEmits<Emits>()

	const handleValueChange = (value: any) => {
		if (value === null || value === undefined) return

		const stringValue = String(value)
		const originalItem = props.items.find((item) => String(item.id) === stringValue)
		const actualValue = originalItem ? originalItem.id : value

		emit('update:modelValue', actualValue as string | number)
		emit('change', actualValue as string | number)
		emit('change-item', originalItem)
	}
</script>

<style scoped>
	/* Add any custom styles if needed */
</style>
