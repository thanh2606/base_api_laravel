<script setup lang="ts">
	import UserInfo from '@/components/user/UserInfo.vue'
	import {
		DropdownMenuGroup,
		DropdownMenuItem,
		DropdownMenuLabel,
		DropdownMenuSeparator,
	} from '@/components/ui/dropdown-menu'
	import { Link } from '@inertiajs/vue3'
	import { LogOut, UserCog } from 'lucide-vue-next'
	import { route } from 'ziggy-js'
	import { type AdminResourceInterface } from '@/responses/admin/AdminResource'

	defineProps<{
		admin: AdminResourceInterface
	}>()
</script>

<template>
	<DropdownMenuLabel class="p-0 font-normal">
		<div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
			<UserInfo
				:admin="admin"
				:show-email="true"
			/>
		</div>
	</DropdownMenuLabel>
	<DropdownMenuSeparator />
	<DropdownMenuGroup>
		<DropdownMenuItem :as-child="true">
			<Link
				class="block w-full"
				:href="route('admin.profile')"
				prefetch
				as="button"
			>
				<UserCog class="mr-2 h-4 w-4" />
				Tài khoản
			</Link>
		</DropdownMenuItem>
	</DropdownMenuGroup>
	<DropdownMenuSeparator />
	<DropdownMenuItem :as-child="true">
		<Link
			class="block w-full"
			method="delete"
			:href="route('admin.logout')"
			as="button"
		>
			<LogOut class="mr-2 h-4 w-4" />
			Đăng xuất
		</Link>
	</DropdownMenuItem>
</template>
