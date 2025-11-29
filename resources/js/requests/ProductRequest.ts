export interface StoreProductRequestInterface {
	title: string
	slug: string
	short_desc: string | null
	desc: string | null
	content: string | null
	meta_title: string | null
	meta_keywords: string | null
	meta_desc: string | null
	sku: string | null
	type: number
	status: number
	sale_status: number
	price: number | null
	sale_price: number | null
	sale_price_start: string | null
	sale_price_end: string | null
	manage_stock: number
	stock_qty: number | null
	stock_status: number
	image_id: number | null
	download_link: string | null
	download_limit: number | null
	download_expiry: string | null
	view_count: number | null
	order_count: number | null
	attributes: { [key: string]: any }[] | null
	variations: { [key: string]: any }[] | null
	category_ids: number[]
	tag_ids: number[]
	[key: string]: any
}
