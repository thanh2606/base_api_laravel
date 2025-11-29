import { parseDate, type DateValue } from "@internationalized/date"
import dayjs from "dayjs"

/**
 * Convert timestamp (seconds) to DateValue
 * @param timestamp - Unix timestamp in seconds (hoặc string số)
 * @returns DateValue object hoặc null
 */
export const timestampToDateValue = (timestamp: string | number | null | undefined): DateValue | null => {
	if (!timestamp) return null
	try {
		const ts = typeof timestamp === 'string' ? parseInt(timestamp) : timestamp
		return parseDate(dayjs.unix(ts).format('YYYY-MM-DD'))
	} catch (error) {
		console.error('Error converting timestamp to DateValue:', error)
		return null
	}
}

/**
 * Convert DateValue to string format Y-m-d H:i:s
 * @param date - DateValue object
 * @returns String "YYYY-MM-DD 00:00:00" hoặc null
 */
export const dateValueToString = (date: any): string | null => {
	if (!date) return null
	return dayjs(`${date.year}-${date.month}-${date.day}`).format('YYYY-MM-DD 00:00:00')
}

/**
 * Format DateValue to DD/MM/YYYY (Vietnamese format)
 * @param date - DateValue object
 * @returns String "DD/MM/YYYY" hoặc null
 */
export const formatDateVietnamese = (date: DateValue | null | undefined): string | null => {
	if (!date) return null
	return dayjs(`${date.year}-${date.month}-${date.day}`).format('DD/MM/YYYY')
}

/**
 * Format DateValue using dayjs
 * @param date - DateValue object
 * @param format - Format string (default: 'DD/MM/YYYY')
 * @returns Formatted date string
 */
export const formatDate = (date: DateValue | null | undefined, format: string = 'DD/MM/YYYY'): string | null => {
	if (!date) return null
	return dayjs(`${date.year}-${date.month}-${date.day}`).format(format)
}
