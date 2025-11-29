import { PermissionResource } from './PermissionResource'

export interface RoleResourceInterface {
	id: number
	name: string
	status: number
	permissions: PermissionResource[]
	created_at: string
	updated_at: string
}
