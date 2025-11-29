<template>
	<AppLayout
		:breadcrumbs="breadcrumbItems"
		title="Sửa thuộc tính"
	>
		<form
			@submit.prevent="submit"
			class="space-y-6"
			disableWhileProcessing
		>
			<div class="grid grid-cols-12 h-full p-4 gap-4 w-full max-w-[calc(100vw-6.25rem)]">
				<div class="col-span-12 md:col-span-4 flex flex-col gap-y-4">
					<div class="grid w-full items-center gap-1.5">
						<Label for="title">Tên thuộc tính</Label>
						<Input
							id="name"
							class="mt-1 block w-full"
							name="name"
							required
							placeholder="Tên thuộc tính"
							v-model="form.name"
							@change="onChangeTitle"
						/>
						<InputError :message="form.errors.name" />
					</div>
					<div class="grid w-full items-center gap-1.5">
						<Label for="title">Slug</Label>
						<Input
							id="slug"
							class="mt-1 block w-full"
							name="slug"
							required
							placeholder="Đường dẫn"
							v-model="form.slug"
						/>
						<InputError :message="form.errors.slug" />
					</div>
					<div class="grid w-full items-center gap-1.5">
						<Label for="title">Loại thuộc tính</Label>
						<SelectBase
							:items="attributeTypeOptions"
							:default-value="EnumAttributeType.SELECT"
							name="type"
							placeholder="Chọn kiểu thuộc tính"
							@change="onTypeChange"
						/>
						<InputError :message="form.errors.type" />
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
							@change="onStatusChange"
						/>
						<InputError :message="form.errors.status" />
					</div>
					<div class="flex justify-end w-full items-center gap-4 mt-4">
						<Button
							@click="addRow"
							type="submit"
						>
							<Plus />
							Thêm thuộc tính
						</Button>
						<Button
							:disabled="form.processing"
							type="submit"
							><Loader
								v-if="form.processing"
								class="animate-spin"
							/><Save v-if="!form.processing" /> Save</Button
						>

						<ButtonLink
							label="Cancel"
							:href="route('admin.attributes.index')"
							variants="default"
						>
							<Ban />
						</ButtonLink>
					</div>
				</div>

				<div class="col-span-12 md:col-span-8 flex flex-col gap-y-1">
					<draggableComponent
						:list="form.values"
						:item-key="Date.now().toString()"
						:disabled="false"
						@start="dragging = true"
						@end="dragging = false"
						class="flex flex-col gap-4"
					>
						<template #item="{ element, index }">
							<div
								class="flex flex-wrap gap-1.5"
								:key="index"
							>
								<div
									class="flex-1 flex flex-col gap-y-1.5"
									:class="{ 'not-draggable': !enabled }"
								>
									<Label>Nhãn</Label>
									<Input
										class="mt-1"
										required
										placeholder="Nhãn"
										v-model="element.label"
									/>
								</div>

								<div
									v-if="form.type === EnumAttributeType.COLOR"
									class="flex-1 flex flex-col gap-y-1.5"
									:class="{ 'not-draggable': !enabled }"
								>
									<Label>Giá trị</Label>
									<input
										type="color"
										class="file:text-foreground placeholder:text-muted-foreground selection:bg-primary
											selection:text-primary-foreground dark:bg-input/30 border-input h-9 min-w-0 rounded-md border
											bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none
											file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium
											disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm
											focus-visible:border-ring focus-visible:ring-ring/50 aria-invalid:ring-destructive/20
											dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive mt-1 block w-full"
										required
										v-model="element.value"
									/>
								</div>

								<div class="flex flex-col gap-y-1.5">
									<Label>Xoá</Label>
									<span
										class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium
											transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none
											[&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none
											focus-visible:border-ring focus-visible:ring-[3px] aria-invalid:ring-destructive/20
											dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive bg-destructive text-white
											shadow-xs hover:bg-destructive/90 focus-visible:ring-destructive/20
											dark:focus-visible:ring-destructive/40 dark:bg-destructive/60 h-9 px-4 py-2 has-[>svg]:px-3 mt-1"
										@click="deleteRow(index)"
										><Trash
									/></span>
								</div>
							</div>
						</template>
					</draggableComponent>
				</div>
			</div>
		</form>
	</AppLayout>
</template>

<script setup lang="ts">
	import AppLayout from '@/layouts/AppLayout.vue'
	import type { BreadcrumbItem } from '@/types'
	import { Ban, Loader, Save, Plus, Trash } from 'lucide-vue-next'
	import { Label } from '@/components/ui/label'
	import SelectBase from '@/components/common/SelectBase.vue'
	import { Input } from '@/components/ui/input'
	import InputError from '@/components/InputError.vue'
	import ButtonLink from '@/components/ButtonLink.vue'
	import { Button } from '@/components/ui/button'
	import { useAttributeForm } from '@/composables/useAttributeForm'
	import { EnumAttributeType } from '@/enums/EnumAttributeType'
	import draggableComponent from 'vuedraggable'
	import { ref } from 'vue'
	import { usePage } from '@inertiajs/vue3'
	import { AttributeResourceInterface } from '@/responses/attribute/AttributeResource'
	const attribute = usePage().props.attribute as AttributeResourceInterface

	const dragging = ref(false)
	const enabled = ref(true)
	const breadcrumbItems: BreadcrumbItem[] = [
		{
			title: 'Bảng điều khiển',
			href: route('admin.dashboard'),
		},
		{
			title: 'Thuộc tính',
			href: route('admin.attributes.index'),
		},
		{
			title: 'Sửa thuộc tính',
			href: route('admin.attributes.create', { attribute: attribute.id }),
		},
	]

	const {
		form,
		statusOptions,
		attributeTypeOptions,
		addRow,
		deleteRow,
		submit,
		onStatusChange,
		onTypeChange,
		onChangeTitle,
	} = useAttributeForm(attribute)
</script>
<style scoped></style>
