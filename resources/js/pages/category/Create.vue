<template>
	<AppLayout
		:breadcrumbs="breadcrumbItems"
		title="Tạo danh mục"
	>
		<form
			@submit.prevent="submit"
			class="space-y-6"
			disableWhileProcessing
		>
			<div
				class="grid grid-cols-12 h-full p-4 gap-4 w-full max-w-[calc(100vw-6.25rem)]"
			>
				<div class="col-span-8">
					<div class="grid gap-4">
						<div class="grid w-full items-center gap-1.5">
							<Label for="title">Tiêu đề</Label>
							<Input
								id="title"
								class="mt-1 block w-full"
								name="title"
								required
								placeholder="Tiêu đề"
								v-model="form.title"
								@change="(value: Event) => onChangeTitle(value)"
							/>
							<InputError :message="form.errors.title" />
						</div>

						<div class="grid w-full items-center gap-1.5">
							<Label for="slug">Đường dẫn</Label>
							<Input
								id="slug"
								class="mt-1 block w-full"
								name="slug"
								required
								placeholder="Đuờng dẫn"
								v-model="form.slug"
							/>
							<InputError :message="form.errors.slug" />
						</div>

						<div class="grid w-full items-center gap-1.5">
							<Label for="desc">Mô tả ngắn</Label>
							<Textarea
								id="desc"
								class="mt-1 block w-full"
								name="desc"
								placeholder="Mô tả ngắn"
								v-model="form.desc"
							/>
							<InputError :message="form.errors.desc" />
						</div>

						<div class="grid w-full items-center gap-1.5">
							<Label for="content">Nội dung</Label>
							<EditorContent name="content" @change="onContentChange" />
							<InputError :message="form.errors.content" />
						</div>
					</div>
				</div>
				<div class="col-span-4">
					<div class="grid gap-4">
						<div class="grid w-full items-center gap-1.5">
							<Label for="image_id">Hình ảnh đại diện</Label>
							<UploadImage
								inputId="image_id"
								inputName="image_id"
								@update="onUploadUpdate"
							/>
						</div>

						<div class="grid w-full items-center gap-1.5">
							<Label for="parent_id" class="mb-1">Danh mục cha</Label>
							<SelectBase
								:items="categoriesForSelect"
								name="parent_id"
								placeholder="Chọn danh mục cha"
								@change="onParentChange"
							/>
							<InputError :message="form.errors.parent_id" />
						</div>

						<div class="grid w-full items-center gap-1.5">
							<Label for="status" class="mb-1">Trạng thái</Label>
							<SelectBase
								:items="statusOptions"
								:default-value="1"
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
								placeholder="Meta keyword"
							/>
							<InputError :message="form.errors.meta_keywords" />
						</div>
						<div class="grid w-full items-center gap-1.5">
							<Label for="meta_desc">Meta description</Label>
							<Textarea
								v-model="form.meta_desc"
								class="mt-1 block w-full"
								name="meta_desc"
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
							/><Save v-if="!form.processing" /> Save</Button
							>

							<ButtonLink
								label="Cancel"
								:href="route('admin.categories.index', {type: EnumCategoryType.POST})"
								variants="default"
							>
								<Ban />
							</ButtonLink>
						</div>
					</div>
				</div>
			</div>
		</form>
	</AppLayout>
</template>

id="meta_desc"
<script lang="ts" setup>
	import AppLayout from '@/layouts/AppLayout.vue'
	import EditorContent from '@/components/EditorContent.vue'
	import InputError from '@/components/InputError.vue'
	import { Label } from '@/components/ui/label'
	import { Input } from '@/components/ui/input'
	import Textarea from '@/components/ui/textarea/Textarea.vue'
	import type { BreadcrumbItem } from '@/types'
	import UploadImage from '@/components/upload/Upload.vue'
	import ButtonLink from '@/components/ButtonLink.vue'
	import { Button } from '@/components/ui/button'
	import { Loader, Ban, Save } from 'lucide-vue-next'
	import SelectBase from '@/components/common/SelectBase.vue'
	import SeoPreview from '@/components/SeoPreview.vue'
	import { useCategoryForm } from '@/composables/useCategoryForm'
	import { EnumCategoryType } from '@/enums/EnumCategoryType'

	const breadcrumbItems: BreadcrumbItem[] = [
		{
			title: 'Bảng điều khiển',
			href: route('admin.dashboard'),
		},
		{
			title: 'Danh mục',
			href: route('admin.categories.index', {type: EnumCategoryType.POST}),
		},
		{
			title: 'Tạo danh mục',
			href: route('admin.categories.create', {type: EnumCategoryType.POST}),
		},
	]

	const {
		form,
		statusOptions,
		categoriesForSelect,
		onChangeTitle,
		onContentChange,
		onStatusChange,
		onParentChange,
		onUploadUpdate,
		submit,
	} = useCategoryForm()
</script>
