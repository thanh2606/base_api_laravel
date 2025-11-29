<script setup lang="ts">
	import { ref, computed, watch } from 'vue'
	import { Popover, PopoverTrigger, PopoverContent } from '@/components/ui/popover'
	import {
		Command,
		CommandInput,
		CommandList,
		CommandItem,
		CommandGroup,
	} from '@/components/ui/command'
	import { Badge } from '@/components/ui/badge'
	import { SelectOption } from '@/components/common/SelectBase.vue'
	import { Check } from 'lucide-vue-next'

	interface Props {
		categories: SelectOption[]
		modelValue?: number[]
		placeholder?: string
		searchPlaceholder?: string
	}

	interface Emits {
		(e: 'update:modelValue', value: number[]): void
		(e: 'change', value: number[]): void
	}

	const props = withDefaults(defineProps<Props>(), {
		modelValue: () => [],
		placeholder: 'Chọn danh mục...',
		searchPlaceholder: 'Tìm kiếm...'
	})

	const emit = defineEmits<Emits>()

	const selected = ref<number[]>([...props.modelValue])
	const open = ref(false)

	watch(
		() => props.modelValue,
		(newValue) => {
			selected.value = [...newValue]
		},
		{ deep: true }
	)

	const flatCategories = computed(() => {
		return props.categories
	})

	const selectedCategoryNames = computed(() => {
		return selected.value
			.map((id) =>
				props.categories
					.find((cat) => cat.id === id)
					?.label.replace('—', '')
					.trim()
			)
			.filter(Boolean) as string[]
	})

	const toggle = (id: any) => {
		if (selected.value.includes(id)) {
			selected.value = selected.value.filter((x) => x !== id)
		} else {
			selected.value.push(id)
		}

		emit('update:modelValue', selected.value)
		emit('change', selected.value)
	}

	const isChecked = (id: any) => {
		return id && typeof id === 'number' && selected.value.includes(id)
	}
</script>

<template>
	<Popover v-model:open="open">
		<PopoverTrigger as-child>
			<div
				class="whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none
					disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4
					[&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50
					focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40
					aria-invalid:border-destructive border bg-background shadow-xs hover:text-accent-foreground
					dark:bg-input/30 dark:border-input dark:hover:bg-input/50 m-h-9 px-4 py-1.5 has-[>svg]:px-3
					w-full hover:bg-white/50"
			>
				<div class="flex flex-wrap gap-1 w-full">
					<Badge
						v-for="name in selectedCategoryNames.slice(0, 3)"
						:key="name"
						variant="secondary"
						class="text-xs truncate"
					>
						{{ name }}
					</Badge>
					<Badge
						v-if="selectedCategoryNames.length > 3"
						variant="secondary"
						class="text-xs"
					>
						+{{ selectedCategoryNames.length - 3 }} khác
					</Badge>
					<span
						v-if="!selected.length"
						class="text-muted-foreground font-normal"
						>{{ props.placeholder }}</span
					>
				</div>
			</div>
		</PopoverTrigger>
		<PopoverContent class="p-0">
			<Command>
				<CommandInput :placeholder="props.searchPlaceholder" />
				<CommandList>
					<CommandGroup>
						<CommandItem
							v-for="cat in flatCategories"
							:key="cat.id || 0"
							:value="cat.id ? cat.id.toString() : '0'"
							class="cursor-pointer"
							@select="toggle(cat.id)"
						>
							<div
								class="flex items-center gap-2 w-full hover:bg-accent/50 rounded-sm p-1 -m-1 cursor-pointer
									transition-colors"
								tabindex="0"
								role="checkbox"
								:aria-checked="isChecked(cat.id)"
								:aria-label="`Toggle ${cat.label}`"
							>
								<div
									class="flex items-center justify-center border-input shrink-0 rounded-[4px] size-4 border shadow-xs
										transition-shadow outline-none"
									:class="{ 'bg-primary': isChecked(cat.id) }"
								>
									<Check
										v-show="isChecked(cat.id)"
										:class="{ 'text-white': isChecked(cat.id) }"
										class="!size-3"
									/>
								</div>
								<span class="flex-1 select-none">
									{{ cat.label }}
								</span>
							</div>
						</CommandItem>
					</CommandGroup>
				</CommandList>
			</Command>
		</PopoverContent>
	</Popover>
</template>

<style scoped>
	/* Custom checkbox styling to match shadcn/ui design */
	@keyframes checkmark-appear {
		0% {
			opacity: 0;
			transform: rotate(45deg) scale(0.5);
		}
		100% {
			opacity: 1;
			transform: rotate(45deg) scale(1);
		}
	}

	/* Focus styles for accessibility */
	div[role='checkbox']:focus {
		outline: none;
		box-shadow: 0 0 0 2px hsl(var(--ring));
		border-radius: 0.25rem;
	}

	/* Hover effects */
	div[role='checkbox']:hover .custom-checkbox {
		border-color: hsl(var(--primary));
	}

	div[role='checkbox']:hover .custom-checkbox:not(.checked) {
		background-color: hsl(var(--accent));
	}
</style>
