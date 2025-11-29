import { h } from 'vue'
import { ColumnDef } from '@tanstack/vue-table'
import Actions from '@/components/dataTableComponent/Actions.vue'
import ImageBase from '@/components/image/ImageBase.vue'
import dayjs from 'dayjs'
import { AdminResourceInterface } from '@/responses/admin/AdminResource'
import { Checkbox } from '@/components/ui/checkbox'

export const columns: ColumnDef<AdminResourceInterface>[] = [
	{
		id: 'select',
		meta: {
			headerClass: 'text-center w-20',
			cellClass: 'text-center w-20',
		},
		header: ({ table }) => h(Checkbox, {
			'modelValue': table.getIsAllPageRowsSelected(),
			'onUpdate:modelValue': (value: boolean | "indeterminate") => table.toggleAllPageRowsSelected(value === true),
			'ariaLabel': 'Select all',
		}),
		cell: ({ row }) => h(Checkbox, {
			'modelValue': row.getIsSelected(),
			'onUpdate:modelValue': (value: boolean | "indeterminate") => row.toggleSelected(value === true),
			'ariaLabel': 'Select row',
		}),
		enableSorting: false,
		enableHiding: false,
	},
	{
		accessorKey: 'name',
		meta: {
			headerClass: 'text-left w-[20%]',
			cellClass: 'text-left w-[20%]',
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
		accessorKey: 'email',
		meta: {
			headerClass: 'text-left w-[20%]',
			cellClass: 'text-left w-[20%]',
		},
		header: () => h('div', { class: 'font-semibold text-black' }, 'Email'),
		cell: ({ row }) => {
			return h(
				'div',
				{
					class: 'whitespace-normal',
				},
				row.original.email
			)
		},
	},
	{
		accessorKey: 'image',
		meta: {
			headerClass: 'text-center w-[20%]',
			cellClass: 'text-center w-[20%]',
		},
		header: () => h('div', { class: 'font-semibold text-black' }, 'Hình ảnh'),
		cell: ({ row }) => {
			return h('div', { class: 'flex justify-center' }, [
				h(ImageBase, {
					src: row.original.image || '',
					alt: row.original.name || 'Admin image',
					size: 'sm',
					customClass: 'border border-gray-200',
				}),
			])
		},
	},
	{
		accessorKey: 'Actions',
		enableHiding: false,
		meta: {
			headerClass: 'text-left w-[15%]',
			cellClass: 'text-left w-[15%]',
		},
		header: () => h('div', { class: 'font-semibold text-black' }, 'Hành động'),
		cell: ({ row }) => {
			const data = row.original

			return h(
				'div',
				{},
				h(Actions, {
					editUrl: route('admin.admins.show', { admin: data.id }),
					deleteUrl: route('admin.admins.destroy', { admin: data.id }),
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
