import { useForm, usePage } from '@inertiajs/vue3'
import { EnumStatus } from '@/enums/EnumStatus'
import { toSlug } from '@/utils/helpers'
import { TagResourceInterface } from '@/responses/tag/TagResource'
import { StoreTagRequestInterface } from '@/requests/TagRequest'

export const useTagForm = (tag?: TagResourceInterface) => {
	const statusOptions = EnumStatus.toArray()
	const page = usePage()

	const form = useForm<StoreTagRequestInterface>({
		title: tag?.title || '',
		slug: tag?.slug || null,
		status: tag?.status !== undefined ? tag.status : EnumStatus.ACTIVE,
		meta_title: tag?.meta_title || null,
		meta_keywords: tag?.meta_keywords || null,
		meta_desc: tag?.meta_desc || null,
		type: tag?.type ?? page.props.type as number,
	})

	const onChangeTitle = (event: Event) => {
		const value = (event.target as HTMLInputElement).value
		if (value && !tag) {
			form.slug = toSlug(value)
		}
	}

	const onStatusChange = (value: string | number) => {
		form.status = Number(value)
	}
	const submit = () => {
		if (tag) {
			form.put(route('admin.tags.update', tag.id))
		} else {
			form.post(route('admin.tags.store'))
		}
	}

	return {
		form,
		statusOptions,
		onChangeTitle,
		onStatusChange,
		submit,
	}
}
