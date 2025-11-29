import slugify from 'slugify'

// Helper: Convert decimal string to number
export const toNumber = (value: any): number | null => {
	if (value === null || value === undefined || value === '') return null
	const num = typeof value === 'string' ? parseFloat(value) : value
	return isNaN(num) ? null : num
}

/**
 * Format number với separator và decimal places
 */
export const formatNumber = (
	value: number | string,
	options: {
		locale?: string
		minimumFractionDigits?: number
		maximumFractionDigits?: number
		useGrouping?: boolean
	} = {}
): string => {
	const {
		locale = 'vi-VN',
		minimumFractionDigits = 0,
		maximumFractionDigits = 2,
		useGrouping = true
	} = options

	const numValue = typeof value === 'string' ? parseFloat(value) : value

	if (isNaN(numValue)) return '0'

	return new Intl.NumberFormat(locale, {
		minimumFractionDigits,
		maximumFractionDigits,
		useGrouping
	}).format(numValue)
}

/**
 * Format currency VND
 */
export const formatCurrency = (value: number | string): string => {
	const numValue = typeof value === 'string' ? parseFloat(value) : value

	if (isNaN(numValue)) return '0 ₫'

	return new Intl.NumberFormat('vi-VN', {
		style: 'currency',
		currency: 'VND'
	}).format(numValue)
}

/**
 * Chuyển string thành slug (URL-friendly)
 */
export const toSlug = (text: string): string => {
	if (!text) return ''

	return slugify(text, {
		replacement: '-',
		lower: true,
		strict: true,
		locale: 'vi',
		trim: true,
	})
}

/**
 * Trim string và loại bỏ khoảng trắng thừa
 */
export const trimString = (text: string | null | undefined): string => {
	if (!text) return ''

	return text
		.trim() // Remove leading/trailing spaces
		.replace(/\s+/g, ' ') // Replace multiple spaces with single space
}

/**
 * Truncate string với ellipsis
 */
export const truncateString = (
	text: string,
	maxLength: number,
	suffix: string = '...'
): string => {
	if (!text || text.length <= maxLength) return text

	return text.substring(0, maxLength - suffix.length) + suffix
}

/**
 * Capitalize first letter
 */
export const capitalizeFirst = (text: string): string => {
	if (!text) return ''

	return text.charAt(0).toUpperCase() + text.slice(1).toLowerCase()
}

/**
 * Capitalize each word
 */
export const capitalizeWords = (text: string): string => {
	if (!text) return ''

	return text
		.split(' ')
		.map(word => capitalizeFirst(word))
		.join(' ')
}

/**
 * Remove HTML tags
 */
export const stripHtml = (html: string): string => {
	if (!html) return ''

	return html.replace(/<[^>]*>/g, '')
}

/**
 * Generate random string
 */
export const generateRandomString = (
	length: number = 8,
	includeNumbers: boolean = true,
	includeSymbols: boolean = false
): string => {
	let chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'

	if (includeNumbers) {
		chars += '0123456789'
	}

	if (includeSymbols) {
		chars += '!@#$%^&*()_+-=[]{}|;:,.<>?'
	}

	let result = ''
	for (let i = 0; i < length; i++) {
		result += chars.charAt(Math.floor(Math.random() * chars.length))
	}

	return result
}

/**
 * Check if email is valid
 */
export const isValidEmail = (email: string): boolean => {
	const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
	return emailRegex.test(email)
}

/**
 * Check if phone number is valid (Vietnamese format)
 */
export const isValidPhone = (phone: string): boolean => {
	const phoneRegex = /^(\+84|84|0[3|5|7|8|9])[0-9]{8,9}$/
	return phoneRegex.test(phone.replace(/\s/g, ''))
}

/**
 * Format file size
 */
export const formatFileSize = (bytes: number): string => {
	if (bytes === 0) return '0 Bytes'

	const k = 1024
	const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB']
	const i = Math.floor(Math.log(bytes) / Math.log(k))

	return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

/**
 * Get file extension from filename
 */
export const getFileExtension = (filename: string): string => {
	return filename.slice((filename.lastIndexOf('.') - 1 >>> 0) + 2)
}

/**
 * Deep clone object
 */
export const deepClone = <T>(obj: T): T => {
	if (obj === null || typeof obj !== 'object') return obj
	if (obj instanceof Date) return new Date(obj.getTime()) as unknown as T
	if (obj instanceof Array) return obj.map(item => deepClone(item)) as unknown as T
	if (typeof obj === 'object') {
		const clonedObj = {} as { [key: string]: any }
		for (const key in obj) {
			if (obj.hasOwnProperty(key)) {
				clonedObj[key] = deepClone(obj[key])
			}
		}
		return clonedObj as T
	}
	return obj
}

/**
 * Check if object is empty
 */
export const isEmpty = (obj: any): boolean => {
	if (obj == null) return true
	if (Array.isArray(obj) || typeof obj === 'string') return obj.length === 0
	if (typeof obj === 'object') return Object.keys(obj).length === 0
	return false
}

/**
 * Generate UUID v4
 */
export const generateUUID = (): string => {
	return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
		const r = Math.random() * 16 | 0
		const v = c === 'x' ? r : (r & 0x3 | 0x8)
		return v.toString(16)
	})
}
