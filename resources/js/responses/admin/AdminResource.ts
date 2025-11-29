export interface AdminResourceInterface {
	id: number
	name: string
	email: string
	phone?: string
	status: number
	address?: string
	image: string
	image_id: number
	role_id: number
	created_at: string
	updated_at: string
}
