<template>
	<AppLayout
		:breadcrumbs="breadcrumbItems"
		title="Quản lý Media"
	>
		<!-- Upload Form -->
		<div
			v-if="isShowUploadForm"
			class="p-4 mx-auto w-full md:w-2xl bg-accent/50 rounded-lg mb-4"
		>
			<UploadMultiFile
				input-id="upload-file"
				input-name="files"
				:images="medias"
				@upload-success="onUploadSuccess"
				@delete="onDelete"
				@complete="onComplete"
			/>
		</div>

		<div class="flex py-2 px-4 bg-accent items-center justify-between gap-2">
			<div class="relative">
				<Form :action="route('admin.medias.index')" method="get">
					<Input class="bg-white" type="text" placeholder="Tìm kiếm media..." name="search" />
					<Search class="absolute bottom-1/2 right-1.5 translate-y-1/2 text-muted-foreground" />
					<button type="submit" hidden>Submit</button>
				</Form>
			</div>
			<div class="flex gap-2">
				<Button @click="showUploadForm($event)" class="inline-flex gap-1 items-center">
					<Plus class="w-4 h-4" />
					Upload
				</Button>

				<span
					@click="dataTableRef?.destroyMany()"
					class="inline-flex gap-1 ml-auto items-center rounded-md bg-destructive px-2 py-1.5 text-sm font-medium text-white
						shadow-sm cursor-pointer hover:bg-destructive/90 dark:bg-destructive/60 outline-none"
				>
					<Trash class="w-4 h-4" />
					Xoá
				</span>
			</div>
		</div>
		<DataTable
			ref="dataTableRef"
			:data="data"
			:columns="columns"
			:pagination-data="paginationData"
			:deleteUrl="route('admin.medias.destroy.many')"
		></DataTable>
	</AppLayout>
</template>
<script lang="ts" setup>
	import AppLayout from '@/layouts/AppLayout.vue'
	import type { BreadcrumbItem } from '@/types'
	import { Form } from '@inertiajs/vue3'
	import { Plus, Search, Trash } from 'lucide-vue-next'
	import { usePage } from '@inertiajs/vue3'
	import type { MediaResourceInterface } from '@/responses/media/MediaResource'
	import UploadMultiFile from '@/components/upload/UploadMultiFile.vue'
	import Button from '@/components/ui/button/Button.vue'
	import Input from '@/components/ui/input/Input.vue'
	import DataTable from '@/components/dataTableComponent/DataTable.vue'
	import { ref } from 'vue'
	import { MediaInterface } from '@/responses/media/MediaResource'
	import { columns } from './columns'
	import { usePagination } from '@/composables/usePagination'

	const page = usePage()
	const data = page.props.records as MediaResourceInterface[]
	const { paginationData } = usePagination('admin.medias.index')
	const dataTableRef = ref()

	const isShowUploadForm = ref(false)
	const medias = ref<MediaInterface[]>([])

	const breadcrumbItems: BreadcrumbItem[] = [
		{
			title: 'Bảng điều khiển',
			href: route('admin.dashboard'),
		},
		{
			title: 'Media',
			href: route('admin.medias.index'),
		},
	]

	const onUploadSuccess = (media: { id: number; name: string; url: string }) => {
		medias.value.push(media)
	}

	const onDelete = (id: number) => {
		medias.value = medias.value.filter((media) => media.id !== id)
	}

	const showUploadForm = (e: Event) => {
		e.preventDefault()
		isShowUploadForm.value = !isShowUploadForm.value
	}

	const onComplete = () => {
		window.location.reload()
	}
</script>
