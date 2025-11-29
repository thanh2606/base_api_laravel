<template>
	<AppLayout
		:breadcrumbs="breadcrumbItems"
		title="Danh sách thuộc tính"
	>
		<div class="flex py-2 px-4 bg-accent items-center justify-between gap-2">
			<div class="relative">
				<Form :action="route('admin.attributes.index')" method="get">
					<Input class="bg-white" type="text" placeholder="Tìm kiếm" name="search" />
					<Search class="absolute bottom-1/2 right-1.5 translate-y-1/2 text-muted-foreground" />
					<button type="submit" hidden>Submit</button>
				</Form>
			</div>
			<div class="flex gap-2">
				<Link
					:href="route('admin.attributes.create')"
					class="inline-flex gap-1 ml-auto items-center rounded-md bg-primary px-2 py-1.5 text-sm font-medium text-white
						shadow-sm hover:bg-primary-hover outline-none"
				>
					<Plus class="w-4 h-4" />
					Thêm
				</Link>

<!--				<span-->
<!--					@click="dataTableRef?.destroyMany()"-->
<!--					class="inline-flex gap-1 ml-auto items-center rounded-md bg-destructive px-2 py-1.5 text-sm font-medium text-white-->
<!--						shadow-sm cursor-pointer hover:bg-destructive/90 dark:bg-destructive/60 outline-none"-->
<!--				>-->
<!--					<Trash class="w-4 h-4" />-->
<!--					Xoá-->
<!--				</span>-->
			</div>
		</div>
		<DataTable
			ref="dataTableRef"
			:data="data"
			:columns="columns"
			:pagination-data="paginationData"
		></DataTable>
	</AppLayout>
</template>

<script setup lang="ts">
	import AppLayout from '@/layouts/AppLayout.vue'
	import type { BreadcrumbItem } from '@/types'
	import DataTable from '@/components/dataTableComponent/DataTable.vue'
	import { usePage, Link } from '@inertiajs/vue3'
	import { usePagination } from '@/composables/usePagination'
	import { Plus, Search } from 'lucide-vue-next'
	import Input from '@/components/ui/input/Input.vue'
	import { ref } from 'vue'
	import {Form} from '@inertiajs/vue3'
	import { columns } from './columns'

	const page = usePage()
	const data = page.props.records as any[]
	const { paginationData } = usePagination('admin.attributes.index')
	const dataTableRef = ref()
	const breadcrumbItems: BreadcrumbItem[] = [
		{
			title: 'Bảng điều khiển',
			href: route('admin.dashboard'),
		},
		{
			title: 'Danh sách thuộc tính',
			href: route('admin.attributes.index'),
		},
	]

</script>
<style scoped>

</style>
