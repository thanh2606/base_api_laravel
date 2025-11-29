import { h } from 'vue'
import { ColumnDef } from '@tanstack/vue-table'
import Actions from '@/components/dataTableComponent/Actions.vue'
import ImageBase from '@/components/image/ImageBase.vue'
import dayjs from 'dayjs'
import { UserResourceInterface } from '@/responses/user/UserResourceInteface'

export const columns: ColumnDef<UserResourceInterface>[] = [
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
					editUrl: route('admin.users.show', { user: data.id }),
					deleteUrl: route('admin.users.destroy', { user: data.id }),
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
