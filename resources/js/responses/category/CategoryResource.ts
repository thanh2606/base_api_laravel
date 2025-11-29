export interface CategoryResourceInterface {
	id: number
	title: string
	content: string | null
	slug: string
	desc: string | null
	meta_title: string | null
	meta_desc: string | null
	meta_keywords: string | null
	status: number
	image: string|null
	image_id: number|null
	parent_id: number | null
	children?: CategoryResourceInterface[]
	type: number
	created_at: string
	updated_at: string
}
