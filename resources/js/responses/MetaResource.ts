export interface MetaResourceInterface {
	message: string
	code: number
}

export interface MetaPaginateResourceInterface {
	message: string
	code: number
	paginate: {
		current_page: number
		last_page: number
		per_page: number
		total: number
	}
}
