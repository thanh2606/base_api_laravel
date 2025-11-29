import { h } from 'vue'
import { ColumnDef } from '@tanstack/vue-table'
import ImageBase from '@/components/image/ImageBase.vue'
import ShowImageDialog from '@/components/dialog/ShowImageDialog.vue'
import DeleteDialog from '@/components/dataTableComponent/DeleteDialog.vue'
import dayjs from 'dayjs'
import { Checkbox } from '@/components/ui/checkbox'
import { MediaResourceInterface } from '@/responses/media/MediaResource'

export const columns: ColumnDef<MediaResourceInterface>[] = [
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
		accessorKey: 'id',
		meta: {
			headerClass: 'text-center w-[10%]',
			cellClass: 'text-center w-[10%]',
		},
		header: () => h('div', { class: 'font-semibold text-black' }, 'ID'),
		cell: ({ row }) => {
			return h('div', { class: 'text-xs font-semibold text-black' }, row.original.id)
		},
	},
	{
		accessorKey: 'name',
		meta: {
			headerClass: 'text-left w-[25%]',
			cellClass: 'text-left w-[25%]',
		},
		header: () => h('div', { class: 'font-semibold text-black' }, 'Tiêu đề'),
		cell: ({ row }) => {
			return h('div', { class: 'whitespace-normal' }, row.original.name)
		},
	},
	{
		accessorKey: 'url',
		meta: {
			headerClass: 'text-center w-[20%]',
			cellClass: 'text-center w-[20%]',
		},
		header: () => h('div', { class: 'font-semibold text-black' }, 'Ảnh'),
		cell: ({ row }) => {
			return h('div', { class: 'flex justify-center' }, [
				h(ImageBase, {
					src: row.original.url || '',
					alt: row.original.name || 'Media image',
					size: 'md',
				}),
			])
		},
	},
	{
		accessorKey: 'Actions',
		enableHiding: false,
		meta: {
			headerClass: 'text-left w-[25%]',
			cellClass: 'text-left w-[25%]',
		},
		header: () => h('div', { class: 'font-semibold text-black' }, 'Hành động'),
		cell: ({ row }) => {
			const data = row.original

			return h('div', { class: 'flex gap-1.5' }, [
				h(ShowImageDialog, { data }),
				h(DeleteDialog, {
					action: route('admin.medias.destroy', { media: data.id })
				})
			])
		},
	},
	{
		accessorKey: 'created_at',
		meta: {
			headerClass: 'text-left w-[20%]',
			cellClass: 'text-left w-[20%]',
		},
		header: () => h('div', { class: 'font-semibold text-black' }, 'Thời gian tạo'),
		cell: ({ row }) => {
			const time = dayjs(row.original.created_at).format('DD/MM/YYYY HH:mm:ss')
			return h('span', { class: 'text-primary' }, time)
		},
	},
]
