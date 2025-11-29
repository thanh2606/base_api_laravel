import { useForm, usePage } from '@inertiajs/vue3'
import { EnumStatus } from '@/enums/EnumStatus'
import { toSlug } from '@/utils/helpers'
import { StoreProductRequestInterface } from '@/requests/ProductRequest'
import { EnumProductType } from '@/enums/EnumProductType'
import { EnumManageStock } from '@/enums/EnumManageStock'
import { EnumStockStatus } from '@/enums/EnumStockStatus'
import { computed, reactive } from 'vue'
import { CategoryResourceInterface } from '@/responses/category/CategoryResource'
import { getCategoriesForSelect } from '@/utils/categoryHelpers'
import { TagResourceInterface } from '@/responses/tag/TagResource'
import { EnumProductSaleStatus } from '@/enums/EnumProductSaleStatus'
import { toNumber } from '@/utils/helpers'

export function useProductForm(product?: any, defaultCategoryIds?: number[], defaultTagIds?: number[], defaultGalleryIds?: number[]) {
	const page = usePage()
	const statusOptions = EnumStatus.toArray()
	const saleStatusOptions = EnumProductSaleStatus.toArray()
	const productTypeOptions = EnumProductType.toArray()
	const manageStockOptions = EnumManageStock.toArray()
	const stockStatusOptions = EnumStockStatus.toArray()

	const priceFormatOptions = reactive<Intl.NumberFormatOptions>({
		currency:	'VND',
		style: 'currency',
		minimumFractionDigits: 0,
		maximumFractionDigits: 0,
	})

	const form = useForm<StoreProductRequestInterface>({
		title: product?.title || '',
		slug: product?.slug || null,
		desc: product?.desc || null,
		content: product?.content || null,
		status: product?.status !== undefined ? product.status : EnumStatus.ACTIVE,
		sale_status: product?.status || EnumProductSaleStatus.DISABLED,
		image_id: product?.image_id || null,
		image: product?.image || null,
		attributes: product?.attributes !== undefined ? product.attributes : [],
		download_expiry: product?.download_expiry || null,
		download_limit: product?.download_limit || null,
		download_link: product?.download_link || null,
		manage_stock: product?.manage_stock || EnumManageStock.NO,
		order_count: product?.order_count || null,
		price: toNumber(product?.price) ?? null,
		sale_price: toNumber(product?.sale_price) ?? null,
		sale_price_start: product?.sale_price_start || null,
		sale_price_end: product?.sale_price_end || null,
		short_desc: product?.short_desc || null,
		sku: product?.sku || null,
		stock_qty: product?.stock_qty || null,
		stock_status: product?.stock_status || EnumStockStatus.IN_STOCK,
		type: product?.stock_status || EnumProductType.VARIABLE,
		variations: product?.variations || null,
		view_count: product?.view_count || 0,
		meta_title: product?.meta_title || null,
		meta_keywords: product?.meta_keywords || null,
		meta_desc: product?.meta_desc || null,
		category_ids: defaultCategoryIds || [],
		tag_ids: defaultTagIds || [],
		gallery_ids: defaultGalleryIds || [],
	})

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

	const attributesForSelect = computed(() => {
		return page.props.productAttributes ?? []
	})

	const onChangeTitle = (event: Event) => {
		const value = (event.target as HTMLInputElement).value
		if (value && !product) {
			form.slug = toSlug(value)
		}
	}

	const onChangeData = (newValue: string | number | null, key: string) => {
		form[key] = newValue
	}

	const onUploadUpdate = (object: any) => {
		form.image_id = object.id
		form.image = object.imageUrl
	}

	const uploadGallerySuccess = (media: any) => {
		form.gallery_ids.push(media.id)
	}

	const onCategoriesChange = (categoryIds: number[]) => {
		form.category_ids = categoryIds
	}

	const onTagsChange = (tagIds: number[]) => {
		form.tag_ids = tagIds
	}

	const onUpdateProductSimple = (value: any) => {
		Object.assign(form, value)
	}

	const onUpdateProductVirtual = (value: any) => {
		Object.assign(form, value)
	}


	const submit = () => {
		if (product) {
			form.put(route('admin.products.update', product.id))
		} else {
			form.post(route('admin.products.store'))
		}
	}

	return {
		statusOptions,
		form,
		onUploadUpdate,
		onChangeTitle,
		onChangeData,
		onCategoriesChange,
		onTagsChange,
		categoriesForSelect,
		tagsForSelect,
		attributesForSelect,
		productTypeOptions,
		manageStockOptions,
		stockStatusOptions,
		saleStatusOptions,
		priceFormatOptions,
		onUpdateProductSimple,
		onUpdateProductVirtual,
		uploadGallerySuccess,
		submit
	}
}
