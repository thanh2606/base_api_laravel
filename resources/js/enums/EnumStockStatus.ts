import { BaseEnum } from './BaseEnum'

export class EnumStockStatus extends BaseEnum {
	static readonly IN_STOCK = 1
	static readonly OUT_OF_STOCK = 2
	static readonly ON_BACKORDER = 3

	static {
		this.defineLabels({
			IN_STOCK: 'Còn hàng',
			OUT_OF_STOCK: 'Hết hàng',
			ON_BACKORDER: 'Đặt hàng trước',
		})
	}
}
