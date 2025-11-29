import {
	PermissionGroupByModelResource,
	PermissionResource,
} from '@/responses/role/PermissionResource'

export const groupPermissionByModel = (
	permissions: PermissionResource[]
): PermissionGroupByModelResource[] => {
	const grouped: PermissionGroupByModelResource[] = []

	for (const permission of permissions) {
		const modelName = permission.model.split('\\').pop() || ''
		let modelGroup = grouped.find((group) => group.model === modelName)

		if (!modelGroup) {
			modelGroup = {
				model: modelName,
				permissions: [],
			}
			grouped.push(modelGroup)
		}

		modelGroup.permissions.push(permission)
	}

	return grouped
}
