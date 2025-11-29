import { BaseEnum } from './BaseEnum'

export class EnumAttributeType extends BaseEnum {
	static readonly SELECT = 1
	static readonly COLOR = 2
	static readonly BUTTON = 3
	static readonly RADIO = 4

	static {
		this.defineLabels({
			SELECT: 'Lựa chọn',
			COLOR: 'Màu sắc',
			BUTTON: 'Nút bấm',
			RADIO: 'Hộp kiểm',
		})
	}
}
