import { useForm, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { CategoryResourceInterface } from '@/responses/category/CategoryResource'
import { StoreCategoryRequestInterface } from '@/requests/CategoryRequest'
import { EnumStatus } from '@/enums/EnumStatus'
import { getCategoriesForSelect } from '@/utils/categoryHelpers'
import { toSlug } from '@/utils/helpers'

export function useCategoryForm(category?: CategoryResourceInterface) {
	const page = usePage()
	const statusOptions = EnumStatus.toArray()

	const categoriesForSelect = computed(() => {
		const categories = page.props.categories as CategoryResourceInterface[]
		return getCategoriesForSelect(categories, category?.id)
	})

	const form = useForm<StoreCategoryRequestInterface>({
		title: category?.title || '',
		slug: category?.slug || null,
		desc: category?.desc || null,
		content: category?.content || null,
		status: category?.status !== undefined ? category.status : EnumStatus.ACTIVE,
		parent_id: category?.parent_id || null,
		image_id: category?.image_id || null,
		image: category?.image || null,
		meta_title: category?.meta_title || null,
		meta_keywords: category?.meta_keywords || null,
		meta_desc: category?.meta_desc || null,
		type: category?.type ?? page.props.type as number,
	})

	// Event handlers
	const onChangeTitle = (event: Event) => {
		const value = (event.target as HTMLInputElement).value
		if (value && !category) { // Only auto-generate slug for new categories
			form.slug = toSlug(value)
		}
	}

	const onContentChange = (newContent: string | null) => {
		form.content = newContent
	}

	const onStatusChange = (value: string | number) => {
		form.status = Number(value)
	}

	const onParentChange = (value: string | number) => {
		form.parent_id = value ? Number(value) : null
	}

	const onUploadUpdate = (object: any) => {
		form.image_id = object.id
		form.image = object.imageUrl
	}
	const submit = () => {
		if (category) {
			form.put(route('admin.categories.update', category.id))
		} else {
			form.post(route('admin.categories.store'))
		}
	}

	return {
		form,
		statusOptions,
		categoriesForSelect,
		onChangeTitle,
		onContentChange,
		onStatusChange,
		onParentChange,
		onUploadUpdate,
		submit,
	}
}
