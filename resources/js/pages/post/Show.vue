<template>
	<AppLayout
		:breadcrumbs="breadcrumbItems"
		title="Sửa bài viết"
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
							/>
							<InputError :message="errors.title" />
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
							<InputError :message="errors.slug" />
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
							<InputError :message="errors?.desc" />
						</div>

						<div class="grid w-full items-center gap-1.5">
							<Label for="content">Nội dung</Label>
							<EditorContent name="content" @change="onContentChange" :content="form.content" :height="800" />
							<InputError :message="errors?.content" />
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
								:imageId="form.image_id"
								:imageUrl="form.image"
								@update="onUploadUpdate"
							/>
						</div>

						<div class="grid w-full items-center gap-1.5">
							<Label for="category_ids" class="mb-1">Danh mục</Label>
							  <MultiSelect
									:categories="categoriesForSelect"
									v-model="form.category_ids"
									placeholder="Chọn danh mục..."
									@change="onCategoriesChange"
								/>
						</div>

						<div class="grid w-full items-center gap-1.5">
							<Label for="tag_ids" class="mb-1">Thẻ</Label>
							  <MultiSelect
									:categories="tagsForSelect"
									v-model="form.tag_ids"
									placeholder="Chọn thẻ..."
									@change="onTagsChange"
								/>
						</div>

						<div class="grid w-full items-center gap-1.5">
							<Label for="status" class="mb-1">Trạng thái</Label>
							<SelectBase
								:items="statusOptions"
								:default-value="form.status"
								name="status"
								placeholder="Chọn trạng thái"
								@change="onStatusChange"
							/>
							<InputError :message="errors?.status" />
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
							<InputError :message="errors?.meta_title" />
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
							<InputError :message="errors.meta_keywords" />
						</div>
						<div class="grid w-full items-center gap-1.5">
							<Label for="meta_desc">Meta description</Label>
							<Textarea
								v-model="form.meta_desc"
								class="mt-1 block w-full"
								name="meta_desc"
								placeholder="Mô tả ngắn"
							/>
							<InputError :message="errors.meta_desc" />
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
								:href="route('admin.posts.index')"
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
<script setup lang="ts">
	import { Button } from '@/components/ui/button'
	import UploadImage from '@/components/upload/Upload.vue'
	import AppLayout from '@/layouts/AppLayout.vue'
	import SeoPreview from '@/components/SeoPreview.vue'
	import Textarea from '../../components/ui/textarea/Textarea.vue'
	import { Ban, Loader, Save } from 'lucide-vue-next'
	import ButtonLink from '@/components/ButtonLink.vue'
	import InputError from '@/components/InputError.vue'
	import EditorContent from '@/components/EditorContent.vue'
	import { Input } from '@/components/ui/input'
	import SelectBase from '@/components/common/SelectBase.vue'
	import { Label } from '@/components/ui/label'
	import { usePostForm } from '@/composables/usePostForm'
	import type { BreadcrumbItem } from '@/types'
	import MultiSelect from '@/components/MultiSelect.vue'
	import { usePage } from '@inertiajs/vue3'
	import { PostResourceInterface } from '@/responses/post/PostResource'

	const page = usePage()
	const errors = page.props.errors as Record<string, string>
	const defaultCategories = usePage().props.selectCategories as number[]
	const defaultTags = usePage().props.selectTags as number[]
	const post = usePage().props.post as PostResourceInterface
	const {
		form,
		statusOptions,
		categoriesForSelect,
		tagsForSelect,
		onContentChange,
		onStatusChange,
		onUploadUpdate,
		submit,
		onCategoriesChange,
		onTagsChange,
	} = usePostForm(post, defaultCategories, defaultTags)

	const breadcrumbItems: BreadcrumbItem[] = [
		{
			title: 'Bảng điều khiển',
			href: route('admin.dashboard'),
		},
		{
			title: 'Bài viết',
			href: route('admin.posts.index'),
		},
		{
			title: 'Sửa bài viết',
			href: route('admin.posts.show', post.id),
		},
	]
</script>
<style scoped></style>
