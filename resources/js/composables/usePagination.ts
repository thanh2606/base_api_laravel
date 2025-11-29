import { usePage } from '@inertiajs/vue3'
import type { MetaPaginateResourceInterface } from '@/responses/MetaResource'

export interface PaginationData {
	lastPage: number
	perPage: number
	total: number
	currentPage: number
	routeName: string
	routeParams: Record<string, any>
}

export function usePagination(routeName: string, routeParams: Record<string, any> = {}) {
	const page = usePage()
	const meta = page.props.meta as MetaPaginateResourceInterface
	const paginationData: PaginationData = {
		lastPage: meta?.paginate?.last_page || 1,
		perPage: meta?.paginate?.per_page || 10,
		total: meta?.paginate?.total || 0,
		currentPage: meta?.paginate?.current_page || 1,
		routeName: routeName,
		routeParams: routeParams
	}

	return {
		paginationData,
	}
}
