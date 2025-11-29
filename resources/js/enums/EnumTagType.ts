import { BaseEnum } from './BaseEnum'

export class EnumTagType extends BaseEnum {
	static readonly POST = 1
	static readonly PRODUCT = 2

	static {
		this.defineLabels({
			POST: 'Bài viết',
			PRODUCT: 'Sản phẩm',
		})
	}
}
