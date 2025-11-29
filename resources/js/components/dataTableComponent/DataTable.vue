<template>
	<div class="overflow-x-auto w-full">
		<Table>
			<TableHeader>
				<TableRow
					v-for="headerGroup in table.getHeaderGroups()"
					:key="headerGroup.id"
				>
					<TableHead
						v-for="header in headerGroup.headers"
						:key="header.id"
						:class="(header.column.columnDef.meta as any)?.headerClass || ''"
					>
						<FlexRender
							v-if="!header.isPlaceholder"
							:render="header.column.columnDef.header"
							:props="header.getContext()"
						/>
					</TableHead>
				</TableRow>
			</TableHeader>
			<TableBody>
				<template v-if="table.getRowModel().rows?.length">
					<TableRow
						v-for="row in table.getRowModel().rows"
						:key="row.id"
						:data-state="row.getIsSelected() ? 'selected' : undefined"
					>
						<TableCell
							v-for="cell in row.getVisibleCells()"
							:key="cell.id"
							:class="(cell.column.columnDef.meta as any)?.cellClass || ''"
						>
							<FlexRender
								:render="cell.column.columnDef.cell"
								:props="cell.getContext()"
							/>
						</TableCell>
					</TableRow>
				</template>
				<template v-else>
					<TableRow>
						<TableCell
							:colspan="columns.length"
							class="h-24 text-center"
						>
							No results.
						</TableCell>
					</TableRow>
				</template>
			</TableBody>
		</Table>

		<div class="flex justify-center items-center px-4 py-3 mt-4 border-t bg-white relative min-h-18">
			<!-- Paginate Component -->
			<Paginate
				v-if="paginationData"
				:last-page="paginationData.lastPage"
				:per-page="paginationData.perPage"
				:total="paginationData.total"
				:current-page="paginationData.currentPage"
				:route-name="paginationData.routeName || 'admin.categories.index'"
				:route-params="paginationData.routeParams || {}"
			/>

			<div class="absolute right-4" v-if="paginationData?.routeName && table.getRowModel().rows?.length">
				<Form :action="route(paginationData?.routeName, { perPage: perPage, ...paginationData.routeParams })" method="get">
					<div class="flex gap-x-2 items-center">
						<span class="text-xs">Số bản ghi trên 1 trang: </span>
						<Input class="w-22" type="number" v-model="perPage" placeholder="Số bản ghi" />
					</div>
					<button type="submit" hidden>Submit</button>
				</Form>
			</div>
		</div>
	</div>
</template>

<script lang="ts" setup generic="TData, TValue">
	import type { ColumnDef } from '@tanstack/vue-table'
	import { FlexRender, getCoreRowModel, useVueTable } from '@tanstack/vue-table'
	import { router } from '@inertiajs/vue3'
	import Input from '../ui/input/Input.vue'
	import {Form} from '@inertiajs/vue3'
	import {
		Table,
		TableBody,
		TableCell,
		TableHead,
		TableHeader,
		TableRow,
	} from '@/components/ui/table'
	import Paginate from '@/components/Paginate.vue'
	import { ref } from 'vue'

	interface PaginationData {
		lastPage: number
		perPage: number
		total: number
		currentPage: number
		routeName?: string
		routeParams?: object
	}

	const props = defineProps<{
		columns: ColumnDef<TData, TValue>[]
		data: TData[]
		paginationData?: PaginationData
		deleteUrl?: string
	}>()

	const perPage = ref(props.paginationData?.perPage)

	const table = useVueTable({
		get data() {
			return props.data
		},
		get columns() {
			return props.columns
		},
		getCoreRowModel: getCoreRowModel(),
	})

	const destroyMany = () => {
		if (!props.deleteUrl) return
		const selectedRows = table.getSelectedRowModel().rows
		const selectedIds = selectedRows.map((row: any) => row.original['id'])

		if (selectedIds.length === 0) {
			alert('Vui lòng chọn ít nhất một mục để xoá.')
			return
		}

		const confirmMessage = 'Bạn có chắc chắn muốn xoá các mục đã chọn không?'
		if (confirm(confirmMessage)) {
			router.visit(props.deleteUrl, {
				method: 'post',
				data: { ids: selectedIds },
			})
		}
	}

	defineExpose({
		table,
		destroyMany
	})
</script>
