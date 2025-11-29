<template>
	<AppLayout
		:breadcrumbs="breadcrumbItems"
		title="Chỉnh sửa thẻ tag"
	>
		<form
			@submit.prevent="submit"
			class="space-y-6"
			disableWhileProcessing
		>
			<div
				class="grid grid-cols-1 md:grid-cols-[3fr_1fr] lg:grid-cols-[7fr_3fr] mx-auto h-full p-4 gap-4 items-start"
			>
				<div class="grid gap-4">
					<div class="grid w-full items-center gap-1.5">
						<Label for="title">Tiêu đề</Label>
						<Input
							id="title"
							class="mt-1 block w-full"
							name="title"
							v-model="form.title"
							required
							autocomplete="title"
							placeholder="Tiêu đề"
						/>
						<InputError :message="form.errors.title" />
					</div>

					<div class="grid w-full items-center gap-1.5">
						<Label for="slug">Đường dẫn</Label>
						<Input
							id="slug"
							class="mt-1 block w-full"
							name="slug"
							v-model="form.slug"
							required
							autocomplete="off"
							placeholder="Đường dẫn"
						/>
						<InputError :message="form.errors.slug" />
					</div>
				</div>

				<div class="grid gap-4">
					<div class="grid w-full items-center gap-1.5">
						<Label for="status" class="mb-1">Trạng thái</Label>
						<SelectBase
							:items="statusOptions"
							:model-value="form.status"
							name="status"
							placeholder="Chọn trạng thái"
							@change="onStatusChange"
						/>
						<InputError :message="form.errors.status" />
					</div>

					<div class="grid w-full items-center gap-1.5">
						<Label for="meta_title">Meta title</Label>
						<Input
							v-model="form.meta_title"
							id="meta_title"
							class="mt-1 block w-full"
							name="meta_title"
							autocomplete="off"
							placeholder="Meta title"
						/>
						<InputError :message="form.errors.meta_title" />
					</div>

					<div class="grid w-full items-center gap-1.5">
						<Label for="meta_keywords">Meta keyword</Label>
						<Input
							v-model="form.meta_keywords"
							id="meta_keywords"
							class="mt-1 block w-full"
							name="meta_keywords"
							autocomplete="off"
							placeholder="Meta keyword"
						/>
						<InputError :message="form.errors.meta_keywords" />
					</div>

					<div class="grid w-full items-center gap-1.5">
						<Label for="meta_desc">Meta description</Label>
						<Textarea
							v-model="form.meta_desc"
							id="meta_desc"
							class="mt-1 block w-full"
							name="meta_desc"
							autocomplete="off"
							placeholder="Mô tả ngắn"
						/>
						<InputError :message="form.errors.meta_desc" />
					</div>

					<div class="grid w-full items-center gap-1.5">
						<SeoPreview :title="form.meta_title" :description="form.meta_desc" />
					</div>

					<div class="flex items-center gap-4">
						<Button :disabled="form.processing" type="submit"
							><Loader
								v-if="form.processing"
								class="animate-spin"
							/><Save v-if="!form.processing" /> Update</Button
						>

						<ButtonLink
							label="Cancel"
							:href="route('admin.tags.index', {type: EnumTagType.POST})"
							variants="default"
						>
							<Ban />
						</ButtonLink>
					</div>
				</div>
			</div>
		</form>
	</AppLayout>
</template>

<script lang="ts" setup>
	import { Ban, Loader, Save } from 'lucide-vue-next'
	import SelectBase from '@/components/common/SelectBase.vue'
	import AppLayout from '@/layouts/AppLayout.vue'
	import Textarea from '../../components/ui/textarea/Textarea.vue'
	import { Label } from '@/components/ui/label'
	import { Input } from '@/components/ui/input'
	import SeoPreview from '@/components/SeoPreview.vue'
	import InputError from '@/components/InputError.vue'
	import ButtonLink from '@/components/ButtonLink.vue'
	import { Button } from '@/components/ui/button'
	import type { BreadcrumbItem } from '@/types'
	import { useTagForm } from '@/composables/useTagForm'
	import { usePage } from '@inertiajs/vue3'
	import { TagResourceInterface } from '@/responses/tag/TagResource'
	import { EnumTagType } from '@/enums/EnumTagType'

	const tag = usePage().props.record as TagResourceInterface
	const breadcrumbItems: BreadcrumbItem[] = [
		{
			title: 'Bảng điều khiển',
			href: route('admin.dashboard'),
		},
		{
			title: 'Danh sách thẻ tags',
			href: route('admin.tags.index', {type: EnumTagType.POST}),
		},
		{
			title: 'Chỉnh sửa',
			href: route('admin.tags.show', { tag: tag.id, type: EnumTagType.POST }),
		},
	]

	const {
		form,
		statusOptions,
		onStatusChange,
		submit,
	} = useTagForm(tag)

</script>

<style scoped>

</style>
