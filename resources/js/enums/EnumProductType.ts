import { BaseEnum } from './BaseEnum'

export class EnumProductType extends BaseEnum {
	static readonly SIMPLE = 1
	static readonly VARIABLE = 2
	static readonly VIRTUAL = 3

	static {
		this.defineLabels({
			SIMPLE: 'Sản phẩm đơn giản',
			VARIABLE: 'Sản phẩm có biến thể',
			VIRTUAL: 'Sản phẩm ảo',
		})
	}
}
