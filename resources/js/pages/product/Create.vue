<template>
	<AppLayout
		:breadcrumbs="breadcrumbItems"
		title="Tạo sản phẩm"
	>
		<form
			@submit.prevent="submit"
			class="space-y-6"
			disableWhileProcessing
		>
			<div class="grid grid-cols-12 h-full p-4 gap-4 w-full max-w-[calc(100vw-6.25rem)]">
				<div class="col-span-12 md:col-span-8 2xl:col-span-9">
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
							<Label for="desc">Mô tả</Label>
							<EditorContent
								:height="400"
								class="mt-1 block w-full"
								name="desc"
								@change="(content) => onChangeData(content, 'desc')"
							/>
							<InputError :message="errors.desc" />
						</div>

						<div class="grid w-full items-center gap-1.5">
							<Label for="short_desc">Mô tả ngắn</Label>
							<Textarea
								id="short_desc"
								class="mt-1 block w-full"
								name="short_desc"
								placeholder="Mô tả ngắn"
								v-model="form.short_desc"
							/>
							<InputError :message="errors.short_desc" />
						</div>

						<div class="grid w-full items-center gap-1.5">
							<Label for="content">Nội dung</Label>
							<EditorContent
								:height="800"
								class="mt-1 block w-full"
								name="content"
								@change="(content) => onChangeData(content, 'content')"
							/>
							<InputError :message="errors.content" />
						</div>

					<template v-if="form.type === EnumProductType.SIMPLE">
						<ProductSimple
							:form="form"
							:priceFormatOptions="priceFormatOptions"
							:statusOptions="saleStatusOptions"
							:manageStockOptions="manageStockOptions"
							:stockStatusOptions="stockStatusOptions"
							@update="(value) => onUpdateProductSimple(value)"
						/>
					</template>

					<template v-if="form.type === EnumProductType.VIRTUAL">
						<ProductSimple
							:form="form"
							:priceFormatOptions="priceFormatOptions"
							:statusOptions="saleStatusOptions"
							:manageStockOptions="manageStockOptions"
							:stockStatusOptions="stockStatusOptions"
							:errors="errors"
							@update="(value) => onUpdateProductSimple(value)"
						/>
						<ProductVirtual
							:form="form"
							:priceFormatOptions="priceFormatOptions"
							:statusOptions="saleStatusOptions"
							:manageStockOptions="manageStockOptions"
							:stockStatusOptions="stockStatusOptions"
							:errors="errors"
							@update="(value) => onUpdateProductVirtual(value)"
						/>
					</template>
					<template v-if="form.type === EnumProductType.VARIABLE">
						<ProductVariation
							:attributes="attributesForSelect"
						/>
					</template>

					</div>
				</div>
				<div class="col-span-12 md:col-span-4 2xl:col-span-3">
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
							<Label for="gallery_ids">Thư viện ảnh</Label>
							<UploadMultiFile
								inputId="gallery_ids"
								inputName="gallery_ids"
								@uploadSuccess="(media) => uploadGallerySuccess(media)"
							/>
						</div>

						<div class="grid w-full items-center gap-1.5">
							<Label
								for="category_ids"
								class="mb-1"
								>Danh mục</Label
							>
							<MultiSelect
								:categories="categoriesForSelect"
								v-model="form.category_ids"
								placeholder="Chọn danh mục..."
								@change="onCategoriesChange"
							/>
							<InputError :message="errors.category_ids" />
						</div>

						<div class="grid w-full items-center gap-1.5">
							<Label
								for="tag_ids"
								class="mb-1"
								>Thẻ tag</Label
							>
							<MultiSelect
								:categories="tagsForSelect"
								v-model="form.tag_ids"
								placeholder="Chọn thẻ tag..."
								@change="onTagsChange"
							/>
							<InputError :message="errors.tag_ids" />
						</div>

						<div class="grid w-full items-center gap-1.5">
							<Label
								for="type"
								class="mb-1"
								>Loại sản phẩm</Label
							>
							<SelectBase
								:items="productTypeOptions"
								:default-value="EnumProductType.SIMPLE"
								name="type"
								placeholder="Chọn loại sản phẩm"
								@change="(value) => onChangeData(value as number, 'type')"
							/>
							<InputError :message="errors.type" />
						</div>

						<div class="grid w-full items-center gap-1.5">
							<Label
								for="status"
								class="mb-1"
								>Trạng thái</Label
							>
							<SelectBase
								:items="statusOptions"
								:default-value="1"
								name="status"
								placeholder="Chọn trạng thái"
								@change="(value) => onChangeData(value as number, 'status')"
							/>
							<InputError :message="errors.status" />
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
							<InputError :message="errors.meta_title" />
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
							<SeoPreview
								:title="form.meta_title"
								:description="form.meta_desc"
							/>
						</div>

						<div class="flex items-center gap-4">
							<Button
								:disabled="form.processing"
								type="submit"
								><Loader
									v-if="form.processing"
									class="animate-spin"
								/><Save v-if="!form.processing" /> Lưu</Button
							>

							<ButtonLink
								label="Huỷ"
								:href="route('admin.products.index')"
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
	import type { BreadcrumbItem } from '@/types'
	import { Button } from '@/components/ui/button'
	import { Ban, Loader, Save } from 'lucide-vue-next'
	import { Label } from '@/components/ui/label'
	import { Input } from '@/components/ui/input'
	import AppLayout from '@/layouts/AppLayout.vue'
	import EditorContent from '@/components/EditorContent.vue'
	import SeoPreview from '@/components/SeoPreview.vue'
	import InputError from '@/components/InputError.vue'
	import Textarea from '../../components/ui/textarea/Textarea.vue'
	import ButtonLink from '@/components/ButtonLink.vue'
	import UploadImage from '@/components/upload/Upload.vue'
	import MultiSelect from '@/components/MultiSelect.vue'
	import SelectBase from '@/components/common/SelectBase.vue'
	import { useProductForm } from '@/composables/useProductForm'
	import { EnumProductType } from '@/enums/EnumProductType'
	import ProductSimple from '@/components/product/Simple.vue'
	import ProductVirtual from '@/components/product/Virtual.vue'
	import UploadMultiFile from '@/components/upload/UploadMultiFile.vue'
	import { usePage } from '@inertiajs/vue3'
	import ProductVariation from '@/components/product/Variation.vue'

	const page = usePage()
	const breadcrumbItems: BreadcrumbItem[] = [
		{
			title: 'Bảng điều khiển',
			href: route('admin.dashboard'),
		},
		{
			title: 'Sản phẩm',
			href: route('admin.products.index'),
		},
		{
			title: 'Tạo sản phẩm',
			href: route('admin.products.create'),
		},
	]

	const {
		statusOptions,
		saleStatusOptions,
		manageStockOptions,
		productTypeOptions,
		priceFormatOptions,
		stockStatusOptions,
		form,
		onUploadUpdate,
		onChangeTitle,
		onChangeData,
		onCategoriesChange,
		onTagsChange,
		onUpdateProductSimple,
		onUpdateProductVirtual,
		categoriesForSelect,
		attributesForSelect,
		tagsForSelect,
		uploadGallerySuccess,
		submit,
	} = useProductForm()

	const errors = page.props.errors
</script>
<style scoped></style>
