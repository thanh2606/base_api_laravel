export abstract class BaseEnum {
	private static _labelMaps: Map<any, Map<any, string>> = new Map()

	protected static defineLabels(labels: Record<string | number, string>) {
		if (!this._labelMaps.has(this)) {
			this._labelMaps.set(this, new Map())
		}

		const labelMap = this._labelMaps.get(this)
		Object.entries(labels).forEach(([key, label]) => {
			const value = (this as any)[key]
			if (value !== undefined) {
				labelMap!.set(value, label)
			}
		})
	}

	static getLabel(value: string | number): string {
		const labelMap = this._labelMaps.get(this)
		return labelMap?.get(value) || String(value)
	}

	static getValue(value: string | number): string | number {
		return value
	}

	static is(value1: string | number, value2: string | number): boolean {
		return value1 === value2
	}

	static isNot(value1: string | number, value2: string | number): boolean {
		return !this.is(value1, value2)
	}

	static isIn(value: string | number, ...others: (string | number)[]): boolean {
		return others.includes(value)
	}

	static isNotIn(value: string | number, ...others: (string | number)[]): boolean {
		return !this.isIn(value, ...others)
	}

	static values(): (string | number)[] {
		const result: (string | number)[] = []
		Object.getOwnPropertyNames(this).forEach((key) => {
			const descriptor = Object.getOwnPropertyDescriptor(this, key)
			if (descriptor && key !== 'prototype' && key !== 'name' && key !== 'length') {
				const value = (this as any)[key]
				if (typeof value === 'string' || typeof value === 'number') {
					result.push(value)
				}
			}
		})

		return result
	}

	static keys(): string[] {
		const result: string[] = []
		Object.getOwnPropertyNames(this).forEach((key) => {
			const descriptor = Object.getOwnPropertyDescriptor(this, key)
			if (descriptor && key !== 'prototype' && key !== 'name' && key !== 'length') {
				const value = (this as any)[key]
				if (typeof value === 'string' || typeof value === 'number') {
					result.push(key)
				}
			}
		})

		return result
	}

	static toArray(): { id: string | number, label: string }[] {
		const labelMap = this._labelMaps.get(this)
		if (!labelMap) return []
		return Array.from(labelMap.entries()).map(([id, label]) => ({ id, label }))
	}
}
