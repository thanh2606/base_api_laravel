export interface AttributeRequestInterface {
	name: string
	slug: string
	type: number
	sort_order: number
	status: number
	values: Array<AttributeValueRequestInterface>
}

export interface AttributeValueRequestInterface {
	label: string|null
	value: string|null
	color_code?: string | null
	sort_order: number
}
