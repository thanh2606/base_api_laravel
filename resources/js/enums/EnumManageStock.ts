import { BaseEnum } from './BaseEnum'

export class EnumManageStock extends BaseEnum {
	static readonly YES = 1
	static readonly NO = 0

	static {
		this.defineLabels({
			YES: 'Có',
			NO: 'Không',
		})
	}
}
