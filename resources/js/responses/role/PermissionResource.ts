export interface PermissionResource {
	id: number
	action: number
	model: string
	name: string
	created_at: string
	updated_at: string
}

export interface PermissionGroupByModelResource {
	model: string
	permissions: PermissionResource[]
}
