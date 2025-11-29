<template>
	<AppLayout
		:breadcrumbs="breadcrumbItems"
		title="Tạo vai trò"
	>
		<Form
			method="post"
			:action="route('admin.roles.store')"
			class="space-y-6"
			v-slot="{ errors, processing }"
		>
			<div
				class="grid grid-cols-1 md:grid-cols-[2fr_3fr] lg:grid-cols-[3fr_7fr] mx-auto h-full p-4 gap-4 items-start"
			>
				<div class="grid gap-4">
					<div class="grid w-full items-center gap-1.5">
						<Label for="name">Vai trò</Label>
						<Input
							id="name"
							class="mt-1 block w-full"
							name="name"
							required
							autocomplete="name"
							placeholder="Vai trò"
						/>
						<InputError :message="errors.name" />
					</div>

					<div class="grid w-full items-center gap-1.5">
						<Label for="status">Trạng thái</Label>
						<Select
							default-value="1"
							id="status"
							name="status"
						>
							<SelectTrigger class="w-full">
								<SelectValue placeholder="Select a status" />
							</SelectTrigger>
							<SelectContent>
								<SelectGroup>
									<SelectItem value="0">Ngừng</SelectItem>
									<SelectItem value="1">Kích hoạt</SelectItem>
								</SelectGroup>
							</SelectContent>
						</Select>
						<InputError :message="errors.status" />
					</div>

					<div class="flex items-center gap-4">
						<Button :disabled="processing"
							><Loader
								v-if="processing"
								class="animate-spin"
							/>
							<Save v-if="!processing" /> Save</Button
						>

						<ButtonLink
							label="Cancel"
							:href="route('admin.roles.index')"
							variants="default"
						>
							<Ban />
						</ButtonLink>
					</div>
				</div>
				<div>
					<HeadingSmall
						title="Phân quyền"
						description="Chọn quyền ở bảng dưới đây"
						class="mb-4"
					/>

					<Table>
						<TableHeader class="bg-muted">
							<TableRow>
								<TableHead class="rounded-tl-md text-sm font-semibold text-black uppercase px-4"
									>Module</TableHead
								>
								<TableHead class="rounded-tl-md text-sm font-semibold text-black uppercase px-4"
									>View</TableHead
								>
								<TableHead class="rounded-tl-md text-sm font-semibold text-black uppercase px-4"
									>Create</TableHead
								>
								<TableHead class="rounded-tl-md text-sm font-semibold text-black uppercase px-4"
									>Edit</TableHead
								>
								<TableHead class="rounded-tl-md text-sm font-semibold text-black uppercase px-4"
									>Delete</TableHead
								>
							</TableRow>
						</TableHeader>
						<TableBody>
							<TableRow
								v-for="(item, index) in permissionGroup"
								:key="index"
							>
								<TableCell class="text-sm font-semibold text-black px-4">{{
									item.model
								}}</TableCell>
								<TableCell
									class="px-4"
									v-for="(permission, key) in item.permissions"
									:key="key"
								>
									<Checkbox
										name="permissions[]"
										:value="permission.id"
									/>
								</TableCell>
							</TableRow>
						</TableBody>
					</Table>
				</div>
			</div>
		</Form>
	</AppLayout>
</template>

<script lang="ts" setup>
	import { Form, usePage } from '@inertiajs/vue3'
	import HeadingSmall from '@/components/heading/HeadingSmall.vue'
	import { Loader, Ban, Save } from 'lucide-vue-next'
	import ButtonLink from '@/components/ButtonLink.vue'
	import AppLayout from '@/layouts/AppLayout.vue'
	import InputError from '@/components/InputError.vue'
	import { Label } from '@/components/ui/label'
	import { Input } from '@/components/ui/input'
	import { Button } from '@/components/ui/button'
	import type { BreadcrumbItem } from '@/types'
	import { Checkbox } from '@/components/ui/checkbox'
	import {
		Select,
		SelectContent,
		SelectGroup,
		SelectItem,
		SelectTrigger,
		SelectValue,
	} from '@/components/ui/select'
	import {
		Table,
		TableBody,
		TableCell,
		TableHead,
		TableHeader,
		TableRow,
	} from '@/components/ui/table'
	import { computed } from 'vue'
	import { groupPermissionByModel } from '@/composables/useRole'
	import { PermissionResource } from '@/responses/role/PermissionResource'
	const permissions = usePage().props.permissions as PermissionResource[]
	const permissionGroup = computed(() => {
		return groupPermissionByModel(permissions)
	})
	const breadcrumbItems: BreadcrumbItem[] = [
		{
			title: 'Bảng điều khiển',
			href: route('admin.dashboard'),
		},
		{
			title: 'Tạo vai trò',
			href: route('admin.roles.create'),
		},
	]
</script>

<style lang="scss" scoped></style>
