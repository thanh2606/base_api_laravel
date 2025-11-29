<template>
	<AppLayout
		:breadcrumbs="breadcrumbItems"
		title="Vai trò"
		route-name="admin.roles.create"
	>
		<DataTable
			:data="data"
			:columns="columns"
			:pagination-data="paginationData"
		></DataTable>
	</AppLayout>
</template>

<script lang="ts" setup>
	import AppLayout from '@/layouts/AppLayout.vue'
	import type { BreadcrumbItem } from '@/types'
	import DataTable from '@/components/dataTableComponent/DataTable.vue'
	import { usePage } from '@inertiajs/vue3'
	import { columns } from './columns'
	import { usePagination } from '@/composables/usePagination'
	import { RoleResourceInterface } from '@/responses/role/RoleResource'

	const breadcrumbItems: BreadcrumbItem[] = [
		{
			title: 'Bảng điều khiển',
			href: route('admin.dashboard'),
		},
		{
			title: 'Vai trò',
			href: route('admin.roles.index'),
		},
	]

	const page = usePage()
	const data = page.props.records as Array<RoleResourceInterface>
	const { paginationData } = usePagination('admin.admins.index')
</script>

<style lang="scss" scoped></style>
