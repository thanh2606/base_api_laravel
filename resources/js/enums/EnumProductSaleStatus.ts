import { BaseEnum } from './BaseEnum'

export class EnumProductSaleStatus extends BaseEnum {
	static readonly ACTIVE = 1
	static readonly DISABLED = 0

	static {
		this.defineLabels({
			ACTIVE: 'Kích hoạt',
			DISABLED: 'Ngừng',
		})
	}
}
