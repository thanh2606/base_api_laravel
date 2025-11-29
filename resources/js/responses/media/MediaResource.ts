export interface MediaResourceInterface {
	id: number
	name: string
	url: string
	path: string
	width: number | null
	height: number | null
	mime_type: string | null
	image: string | null
	created_at: string
	updated_at: string
}

export interface MediaInterface {
	name: string
	id: number
	url: string
}
