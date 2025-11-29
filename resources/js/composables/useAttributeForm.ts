import { useForm } from '@inertiajs/vue3'
import { AttributeResourceInterface } from '@/responses/attribute/AttributeResource'
import { EnumStatus } from '@/enums/EnumStatus'
import { EnumAttributeType } from '@/enums/EnumAttributeType'
import { toSlug } from '@/utils/helpers'

export const useAttributeForm = (attribute?: AttributeResourceInterface) => {
	const statusOptions = EnumStatus.toArray()
	const attributeTypeOptions = EnumAttributeType.toArray()
	let sort_order = 0;

	const form = useForm({
		name: attribute?.name || '',
		slug: attribute?.slug || '',
		type: attribute?.type || EnumAttributeType.SELECT,
		sort_order: attribute?.sort_order || sort_order++,
		status: attribute?.status !== undefined ? attribute.status : 1,
		values: attribute?.values || []
	})

	const onChangeTitle = (event: Event) => {
		const value = (event.target as HTMLInputElement).value
		if (value && !attribute) { // Only auto-generate slug for new categories
			form.slug = toSlug(value)
		}
	}

	const onStatusChange = (value: string | number) => {
		form.status = Number(value)
	}

	const onTypeChange = (value: string | number) => {
		if (value !== EnumAttributeType.COLOR) {
			const values = form.values.map((item) => {
				return {
					...item,
					value: '',
				}
			})

			form.values = values
		}

		if (form.values.length === 1) {
			form.values[0].value = value === EnumAttributeType.COLOR ? '#000000' : ''
		}

		form.type = Number(value)
	}

	const addRow = (e: Event) => {
		e.preventDefault()
		form.values.push({
			label: '',
			value: form.type === EnumAttributeType.COLOR ? '#000000' : '',
			color_code: null,
			sort_order: sort_order++,
		})
	}

	const deleteRow = (index: number) => {
		if (!attribute) {
			form.values.splice(index, 1)
		} else {
			const values = form.values.filter((_, i) => i !== index)
			form.values = values
		}
	}

	const submit = () => {
		if (attribute) {
			form.put(route('admin.attributes.update', attribute.id))
		} else {
			form.post(route('admin.attributes.store'))
		}
	}

	return {
		form,
		statusOptions,
		attributeTypeOptions,
		submit,
		addRow,
		onStatusChange,
		onTypeChange,
		onChangeTitle,
		deleteRow,
	}
}
