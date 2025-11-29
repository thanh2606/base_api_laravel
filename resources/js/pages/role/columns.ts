import { h } from 'vue'
import { ColumnDef } from '@tanstack/vue-table'
import Actions from '@/components/dataTableComponent/Actions.vue'
import dayjs from 'dayjs'
import { RoleResourceInterface } from '@/responses/role/RoleResource'

export const columns: ColumnDef<RoleResourceInterface>[] = [
	{
		accessorKey: 'id',
		meta: {
			headerClass: 'text-center w-20',
			cellClass: 'text-center w-20',
		},
		header: () => h('div', { class: 'font-semibold text-black' }, 'ID'),
		cell: ({ row }) => {
			return h('div', {}, row.original.id)
		},
	},
	{
		accessorKey: 'name',
		meta: {
			headerClass: 'text-left w-[40%]',
			cellClass: 'text-left w-[40%]',
		},
		header: () => h('div', { class: 'font-semibold text-black' }, 'Họ và tên'),
		cell: ({ row }) => {
			return h(
				'div',
				{
					class: 'whitespace-normal',
				},
				row.original.name
			)
		},
	},
	{
		accessorKey: 'Actions',
		enableHiding: false,
		meta: {
			headerClass: 'text-left w-[30%]',
			cellClass: 'text-left w-[30%]',
		},
		header: () => h('div', { class: 'font-semibold text-black' }, 'Hành động'),
		cell: ({ row }) => {
			const data = row.original

			return h(
				'div',
				{},
				h(Actions, {
					editUrl: route('admin.roles.show', { role: data.id }),
					deleteUrl: route('admin.roles.destroy', { role: data.id }),
				})
			)
		},
	},

	{
		accessorKey: 'created_at',
		meta: {
			headerClass: 'text-left',
			cellClass: 'text-left',
		},
		header: () => h('div', { class: 'font-semibold text-black' }, 'Ngày tạo'),
		cell: ({ row }) => {
			const time = dayjs(row.original.created_at).format('DD/MM/YYYY HH:mm:ss')
			return h('div', { class: 'whitespace-normal' }, time)
		},
	},
]
