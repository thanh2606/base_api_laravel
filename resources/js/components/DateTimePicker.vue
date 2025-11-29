<template>
	<div class="space-y-2">
		<Popover>
			<PopoverTrigger as-child>
				<Button
					variant="outline"
					:disabled="props.disabled"
					:class="cn(
						'w-full justify-start text-left font-normal :disabled:cursor-not-allowed',
						!dateValue && 'text-muted-foreground',
					)"
				>
					<CalendarIcon class="mr-2 h-4 w-4" />
					{{ formattedDateTime || props.placeholder }}
				</Button>
			</PopoverTrigger>
			<PopoverContent class="w-auto p-0">
				<Calendar
					v-model="(dateValue as any)"
					initial-focus
					:disabled="props.disabled"
				/>
				<div class="p-3 border-t">
					<div class="flex items-center gap-2">
						<Input
							v-model="hours"
							type="number"
							min="0"
							max="23"
							placeholder="HH"
							class="w-16 text-center"
						/>
						<span>:</span>
						<Input
							v-model="minutes"
							type="number"
							min="0"
							max="59"
							placeholder="MM"
							class="w-16 text-center"
						/>
						<span>:</span>
						<Input
							v-model="seconds"
							type="number"
							min="0"
							max="59"
							placeholder="SS"
							class="w-16 text-center"
						/>
					</div>
				</div>
			</PopoverContent>
		</Popover>
	</div>
</template>

<script setup lang="ts">
	import { type DateValue } from "@internationalized/date"
	import { getLocalTimeZone, parseDate } from "@internationalized/date"
	import { CalendarIcon } from "lucide-vue-next"
	import { ref, watch, computed } from "vue"
	import { cn } from "@/utils"
	import { Button } from "@/components/ui/button"
	import { Calendar } from "@/components/ui/calendar"
	import { Input } from "@/components/ui/input"
	import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover"
	import dayjs from "dayjs"

	const props = withDefaults(
		defineProps<{
			modelValue?: string | null
			placeholder?: string
			disabled?: boolean
		}>(),
		{
			placeholder: 'Pick date and time',
			modelValue: null,
			disabled: false
		}
	)

	const emits = defineEmits<{
		'update:modelValue': [value: string | null]
	}>()

	// Parse initial value
	const parseInitialValue = () => {
		if (!props.modelValue) {
			return {
				date: null,
				h: '00',
				m: '00',
				s: '00'
			}
		}

		const parsed = dayjs(props.modelValue)
		const dateStr = parsed.format('YYYY-MM-DD')

		return {
			date: parseDate(dateStr),
			h: parsed.format('HH'),
			m: parsed.format('mm'),
			s: parsed.format('ss')
		}
	}

	const initial = parseInitialValue()
	const dateValue = ref<DateValue | null>(initial.date)
	const hours = ref(initial.h)
	const minutes = ref(initial.m)
	const seconds = ref(initial.s)

	// Format display
	const formattedDateTime = computed(() => {
		if (!dateValue.value) return null
		const date = dateValue.value.toDate(getLocalTimeZone())
		const dateStr = dayjs(date).format('DD/MM/YYYY')
		return `${dateStr} ${hours.value}:${minutes.value}:${seconds.value}`
	})

	// Watch changes and emit
	watch([dateValue, hours, minutes, seconds], () => {
		if (!dateValue.value) {
			emits('update:modelValue', null)
			return
		}

		const date = dateValue.value.toDate(getLocalTimeZone())
		const h = String(hours.value || '0').padStart(2, '0')
		const m = String(minutes.value || '0').padStart(2, '0')
		const s = String(seconds.value || '0').padStart(2, '0')

		const datetime = dayjs(date)
			.hour(parseInt(h))
			.minute(parseInt(m))
			.second(parseInt(s))
			.format('YYYY-MM-DD HH:mm:ss')

		emits('update:modelValue', datetime)
	}, { deep: true })
</script>
