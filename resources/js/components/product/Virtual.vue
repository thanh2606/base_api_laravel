<template>
	<div class="grid w-full grid-cols-12 gap-y-4 gap-x-1">
		<!-- download_link -->
		<div class="grid col-span-12 items-center gap-1.5">
			<Label for="download_link">Link tải</Label>
			<Input
				v-model="product.download_link"
				id="download_link"
				class="mt-1 block w-full"
				name="download_link"
				placeholder="Link tải"
			/>
			<InputError v-if="props.errors?.download_link" :message="props.errors.download_link" />
		</div>
		<!-- download_link -->

		<!-- download_limit -->
		<div class="grid col-span-12 items-center gap-1.5">
			<Label for="download_limit">Giới hạn tải xuống</Label>
			<Input
				v-model="product.download_limit"
				id="download_limit"
				class="mt-1 block w-full"
				name="download_limit"
				placeholder="Giới hạn tải xuống"
			/>
			<InputError v-if="props.errors?.download_limit" :message="props.errors.download_limit" />
		</div>
		<!-- download_link -->

		<!--Thời hạn tải xuống -->
		<div class="grid col-span-12 md:col-span-6 items-center gap-1.5">
			<Label for="download_expiry">Thời hạn tải xuống</Label>
			<DateTimePicker
				v-model="product.download_expiry"
				placeholder="Thời hạn tải xuống"
				@update="(value: any) => (product.download_expiry = value)"
			/>
			<InputError v-if="props.errors?.download_expiry" :message="props.errors.download_expiry" />
		</div><!-- Thời hạn tải xuống -->

	</div>
</template>
<script lang="ts" setup>
	import { Label } from '@/components/ui/label'
	import { Input } from '@/components/ui/input'
	import { reactive, watch } from 'vue'
	import DateTimePicker from '../DateTimePicker.vue'
	import type { InertiaForm } from '@inertiajs/vue3'
	import InputError from '@/components/InputError.vue'

	const props = defineProps<{
		form: InertiaForm<any>
		errors?: Record<string, string>
	}>()

	const emits = defineEmits<{
		update: [value: any]
	}>()

	const product = reactive({
		download_link: props.form.download_link,
		download_limit: props.form.download_limit,
		download_expiry: props.form.download_expiry,
	})

	watch(
		() => product,
		(newValue) => {
			if (newValue) {
				emits('update', {
					download_link: newValue.download_link,
					download_limit: newValue.download_limit,
					download_expiry: newValue.download_expiry,
				})
			}
		},
		{ deep: true }
	)
</script>
