<script setup lang="ts">
	import NavMain from '@/components/NavMain.vue'
	import NavUser from '@/components/user/NavUser.vue'
	import {
		Sidebar,
		SidebarContent,
		SidebarFooter,
		SidebarHeader,
		SidebarMenu,
		SidebarMenuButton,
		SidebarMenuItem,
	} from '@/components/ui/sidebar'
	import { type NavItem } from '@/types'
	import { Link } from '@inertiajs/vue3'
	import {
		LayoutGrid,
		Users,
		SquarePen,
		ShoppingBasket,
		Archive,
		Receipt,
		Settings,
		Images,
	} from 'lucide-vue-next'
	import AppLogo from './AppLogo.vue'
	import { EnumCategoryType } from '@/enums/EnumCategoryType'
	import { EnumTagType } from '@/enums/EnumTagType'

	const mainNavItems: NavItem[] = [
		{
			title: 'Bảng điều khiển',
			href: route('admin.dashboard'),
			icon: LayoutGrid,
		},
		{
			title: 'Tài khoản',
			icon: Users,
			children: [
				{
					title: 'Quản trị viên',
					href: route('admin.admins.index'),
				},
				{
					title: 'Vai trò',
					href: route('admin.roles.index'),
				},
				{
					title: 'Người dùng',
					href: route('admin.users.index'),
				},
			],
		},
		{
			title: 'Media',
			href: route('admin.medias.index'),
			icon: Images,
		},
		{
			title: 'Tin tức',
			icon: SquarePen,
			children: [
				{
					title: 'Bài viết',
					href: route('admin.posts.index'),
				},
				{
					title: 'Danh mục',
					href: route('admin.categories.index', {type: EnumCategoryType.POST}),
				},
				{
					title: 'Thẻ tag',
					href: route('admin.tags.index', {type: EnumTagType.POST}),
				},
			],
		},
		{
			title: 'E-commerce',
			icon: ShoppingBasket,
			children: [
				{
					title: 'Sản phẩm',
					href: route('admin.products.index'),
				},
				{
					title: 'Danh mục sản phẩm',
					href: route('admin.categories.index', {type: EnumCategoryType.PRODUCT}),
				},
				{
					title: 'Thẻ tag sản phẩm',
					href: route('admin.tags.index', {type: EnumTagType.PRODUCT}),
				},
				{
					title: 'Thuộc tính',
					href: route('admin.attributes.index'),
				},
			],
		},
		{
			title: 'Đơn hàng',
			icon: Archive,
			href: route('admin.admins.index'),
		},
		{
			title: 'Mã giảm giá',
			icon: Receipt,
			children: [
				{
					title: 'Danh sách',
					href: route('admin.admins.index'),
				},
				{
					title: 'Chiến dịch',
					href: route('admin.admins.index'),
				},
			],
		},
		{
			title: 'Cài đặt',
			icon: Settings,
			children: [
				{
					title: 'Menu',
					href: route('admin.admins.index'),
				},
				{
					title: 'Thanh toán',
					href: route('admin.admins.index'),
				},
				{
					title: 'Cấu hình chung',
					href: route('admin.settings.index'),
				},
			],
		},
	]
</script>

<template>
	<Sidebar
		collapsible="icon"
		variant="inset"
	>
		<SidebarHeader>
			<SidebarMenu>
				<SidebarMenuItem>
					<SidebarMenuButton
						size="lg"
						as-child
					>
						<Link :href="route('admin.dashboard')">
							<AppLogo />
						</Link>
					</SidebarMenuButton>
				</SidebarMenuItem>
			</SidebarMenu>
		</SidebarHeader>

		<SidebarContent>
			<NavMain :items="mainNavItems" />
		</SidebarContent>

		<SidebarFooter>
			<NavUser />
		</SidebarFooter>
	</Sidebar>
	<slot />
</template>
