<template>
	<Popover>
		<PopoverTrigger as-child>
			<Button
				variant="outline"
				:class="cn(
          'w-full justify-start text-left font-normal',
          !modelValue && 'text-muted-foreground',
        )"
			>
				<CalendarIcon class="mr-2 h-4 w-4" />
				{{ modelValue ? df.format(modelValue.toDate(getLocalTimeZone())) : props.placeholder }}
			</Button>
		</PopoverTrigger>
		<PopoverContent class="w-auto p-0">
			<Calendar v-model="internalValue" initial-focus />
		</PopoverContent>
	</Popover>
</template>

<script setup lang="ts">
	import { type DateValue } from "@internationalized/date"
	import {
		DateFormatter,
		getLocalTimeZone,
	} from "@internationalized/date"
	import { CalendarIcon } from "lucide-vue-next"

	import { ref, watch } from "vue"
	import { cn } from "@/utils"
	import { Button } from "@/components/ui/button"
	import { Calendar } from "@/components/ui/calendar"
	import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover"

	const props = withDefaults(
		defineProps<{
			modelValue: DateValue | null
			placeholder?: string | null
			minDate?: DateValue
			maxDate?: DateValue
		}>(),
		{
			placeholder: 'Pick a date',
			modelValue: null
		}
	)

	const emits = defineEmits<{
		(event: 'update', date: DateValue | null): void
	}>()

	const df = new DateFormatter("vi-VN", {
		dateStyle: "long",
	})

	const internalValue = ref<any>(props.modelValue)

	watch(internalValue, (newValue) => {
		emits('update', newValue as DateValue | null)
	}, { deep: true })

</script>
