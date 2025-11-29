<template>
	<AppLayout
		:breadcrumbs="breadcrumbItems"
		title="Thành viên"
		route-name="admin.users.create"
	>
		<DataTable
			:data="data"
			:columns="columns"
			:pagination-data="paginationData"
		></DataTable>
	</AppLayout>
</template>

<script setup lang="ts">
	import AppLayout from '@/layouts/AppLayout.vue'
	import DataTable from '@/components/dataTableComponent/DataTable.vue'
	import type { BreadcrumbItem } from '@/types'
	import { usePage } from '@inertiajs/vue3'
	import { columns } from './columns'
	import { usePagination } from '@/composables/usePagination'

	import { UserResourceInterface } from '@/responses/user/UserResourceInteface'

	const breadcrumbItems: BreadcrumbItem[] = [
		{
			title: 'Bảng điều khiển',
			href: route('admin.dashboard'),
		},
		{
			title: 'Danh sách người dùng',
			href: route('admin.users.index'),
		},
	]

	const page = usePage()
	const data = page.props.records as Array<UserResourceInterface>
	const { paginationData } = usePagination('admin.users.index')
</script>

<style scoped></style>
