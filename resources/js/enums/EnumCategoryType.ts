import { BaseEnum } from './BaseEnum'

export class EnumCategoryType extends BaseEnum {
	static readonly POST = 1
	static readonly PRODUCT = 2

	static {
		this.defineLabels({
			POST: 'Bài viết',
			PRODUCT: 'Sản phẩm',
		})
	}
}
