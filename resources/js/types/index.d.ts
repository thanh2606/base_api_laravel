import type { LucideIcon } from 'lucide-vue-next'
import type { Config } from 'ziggy-js'

export interface Auth {
	admin: Admin
}

export interface BreadcrumbItem {
	title: string
	href: string
}

export interface NavItem {
	title: string
	href?: string
	icon?: LucideIcon
	isActive?: boolean
	children?: NavItem[]
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
	name: string
	auth: Auth
	ziggy: Config & { location: string }
	sidebarOpen: boolean
	success: string
}

export type BreadcrumbItemType = BreadcrumbItem
