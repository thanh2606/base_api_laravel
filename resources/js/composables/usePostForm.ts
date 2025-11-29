import { useForm, usePage } from '@inertiajs/vue3'
import { PostResourceInterface } from '@/responses/post/PostResource'
import { StorePostRequestInterface } from '@/requests/PostRequest'
import { EnumStatus } from '@/enums/EnumStatus'
import { toSlug } from '@/utils/helpers'
import { getCategoriesForSelect } from '@/utils/categoryHelpers'
import { computed } from 'vue'
import { CategoryResourceInterface } from '@/responses/category/CategoryResource'
import { TagResourceInterface } from '@/responses/tag/TagResource'

export function usePostForm(post?: PostResourceInterface, defaultCategoryIds?: number[], defaultTagIds?: number[]) {
	const page = usePage()
	const statusOptions = EnumStatus.toArray()

	const categoriesForSelect = computed(() => {
		const categories = page.props.categories as CategoryResourceInterface[]
		return getCategoriesForSelect(categories)
	})

	const tagsForSelect = computed(() => {
		const tags = page.props.tags as TagResourceInterface[]
		return tags.map(tag => ({
			id: tag.id,
			label: tag.title
		}))
	})

	const form = useForm<StorePostRequestInterface>({
		title: post?.title || '',
		slug: post?.slug || null,
		desc: post?.desc || null,
		content: post?.content || null,
		status: post?.status !== undefined ? post.status : EnumStatus.ACTIVE,
		image_id: post?.image_id || null,
		image: post?.image || null,
		meta_title: post?.meta_title || null,
		meta_keywords: post?.meta_keywords || null,
		meta_desc: post?.meta_desc || null,
		category_ids: post?.category_ids || defaultCategoryIds || [],
		tag_ids: post?.tag_ids || defaultTagIds || []
	})

	const onChangeTitle = (event: Event) => {
		const value = (event.target as HTMLInputElement).value
		if (value && !post) {
			form.slug = toSlug(value)
		}
	}

	const onContentChange = (newContent: string | null) => {
		form.content = newContent
	}

	const onStatusChange = (value: string | number) => {
		form.status = Number(value)
	}

	const onUploadUpdate = (object: any) => {
		form.image_id = object.id
		form.image = object.imageUrl
	}

	const onCategoriesChange = (categoryIds: number[]) => {
		form.category_ids = categoryIds
	}

	const onTagsChange = (tagIds: number[]) => {
		form.tag_ids = tagIds
	}

	const submit = () => {
		if (post) {
			form.put(route('admin.posts.update', post.id))
		} else {
			form.post(route('admin.posts.store'))
		}
	}

	return {
		form,
		statusOptions,
		categoriesForSelect,
		tagsForSelect,
		onCategoriesChange,
		onTagsChange,
		onChangeTitle,
		onContentChange,
		onStatusChange,
		onUploadUpdate,
		submit,
	}
}
