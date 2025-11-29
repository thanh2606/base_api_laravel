import { AttributeValueRequestInterface } from "@/requests/ProductAttributeRequest"

export interface AttributeResourceInterface {
	id: number
	name: string
	slug: string
	type: string
	sort_order: number
	status: number
	values: Array<AttributeValueResourceInterface|AttributeValueRequestInterface>
	created_at: string
	updated_at: string
}

export interface AttributeValueResourceInterface {
	id: number
	attribute_id: number
	label: string
	value: string
	color_code: string | null
	sort_order: number
	created_at: string
	updated_at: string
}
