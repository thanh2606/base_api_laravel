export interface TagResourceInterface {
	id: number
	title: string
	slug: string
	meta_title: string | null
	meta_desc: string | null
	meta_keywords: string | null
	type: number
	status: number
	author_id: number
	created_at: string
	updated_at: string
}
