import { h } from 'vue'
import { ColumnDef } from '@tanstack/vue-table'
import { TagResourceInterface } from '@/responses/tag/TagResource'
import Actions from '@/components/dataTableComponent/Actions.vue'
import dayjs from 'dayjs'
import { Checkbox } from '@/components/ui/checkbox'
import { Badge } from '@/components/ui/badge'
import { EnumTagType } from '@/enums/EnumTagType'

export const columns: ColumnDef<TagResourceInterface>[] = [
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
		accessorKey: 'title',
		meta: {
			headerClass: 'text-left w-[15%]',
			cellClass: 'text-left w-[15%]',
		},
		header: () => h('div', { class: 'font-semibold text-black' }, 'Tiêu đề'),
		cell: ({ row }) => {
			return h(
				'div',
				{
					class: 'whitespace-normal',
				},
				row.original.title
			)
		},
	},
	{
		accessorKey: 'slug',
		meta: {
			headerClass: 'text-left w-[20%]',
			cellClass: 'text-left w-[20%]',
		},
		header: () => h('div', { class: 'font-semibold text-black' }, 'Đường dẫn'),
		cell: ({ row }) => {
			return h('div', { class: 'whitespace-normal' }, row.original.slug)
		},
	},
	{
		accessorKey: 'status',
		enableHiding: false,
		header: () => h('div', { class: 'font-semibold text-black' }, 'Trạng thái'),
		cell: ({ row }) => {
			const data = row.original

			return h(
				'div',
				{},
				h(Badge, {}, data.status === 1 ? 'Kích hoạt' : 'Vô hiệu hóa')
			)
		},
	},
	{
		accessorKey: 'actions',
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
					editUrl: route('admin.tags.show', { tag: data.id, type: EnumTagType.POST }),
					deleteUrl: route('admin.tags.destroy', { tag: data.id, type: EnumTagType.POST }),
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
