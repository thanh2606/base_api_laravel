<script setup lang="ts">
	import { Form, usePage } from '@inertiajs/vue3'
	import DeleteUser from '@/components/user/DeleteUser.vue'
	import HeadingSmall from '@/components/heading/HeadingSmall.vue'
	import InputError from '@/components/InputError.vue'
	import { Button } from '@/components/ui/button'
	import { Input } from '@/components/ui/input'
	import { Label } from '@/components/ui/label'
	import {
		Select,
		SelectContent,
		SelectGroup,
		SelectItem,
		SelectTrigger,
		SelectValue,
	} from '@/components/ui/select'
	import AppLayout from '@/layouts/AppLayout.vue'
	import { type BreadcrumbItem } from '@/types'
	import { type AdminResourceInterface } from '@/responses/admin/AdminResource'
	import { Loader, Save, Ban, RotateCcw } from 'lucide-vue-next'
	import ButtonLink from '@/components/ButtonLink.vue'
	import Upload from '@/components/upload/Upload.vue'
	import { ref } from 'vue'
	const page = usePage()
	const admin = page.props.admin as AdminResourceInterface
	const breadcrumbItems: BreadcrumbItem[] = [
		{
			title: 'Bảng điều khiển',
			href: route('admin.dashboard'),
		},
		{
			title: 'Sửa tài khoản',
			href: route('admin.admins.show', admin.id),
		},
	]

	const avatarId = ref<number | null>(admin.image_id)
	const avatarUrl = ref<string | null>(admin.image)

	const updateImage = (data: { id: number | null; imageUrl: string | null }) => {
		avatarId.value = data.id
		avatarUrl.value = data.imageUrl
	}
</script>

<template>
	<AppLayout
		:breadcrumbs="breadcrumbItems"
		title="Sửa tài khoản"
	>
		<Form
			method="put"
			:action="route('admin.admins.update', admin.id)"
			class="space-y-6"
			v-slot="{ errors, processing }"
			:transform="(data) => ({ ...data, image_id: avatarId, image: avatarUrl })"
		>
			<div class="mx-auto grid h-full max-w-xl gap-4 p-4">
				<div class="grid w-full items-center gap-1.5">
					<Label for="name">Name</Label>
					<Input
						id="name"
						class="mt-1 block w-full"
						name="name"
						v-model="admin.name"
						required
						autocomplete="name"
						placeholder="Full name"
					/>
					<InputError :message="errors.name" />
				</div>
				<div class="grid w-full items-center gap-1.5">
					<Label for="email">Email address</Label>
					<Input
						id="email"
						type="email"
						class="mt-1 block w-full"
						name="email"
						v-model="admin.email"
						required
						placeholder="Email address"
					/>
					<InputError :message="errors.email" />
				</div>
				<div class="grid w-full items-center gap-1.5">
					<Label for="phone">Phone</Label>
					<Input
						id="phone"
						type="tel"
						class="mt-1 block w-full"
						name="phone"
						v-model="admin.phone"
						placeholder="Phone number"
					/>
					<InputError :message="errors.phone" />
				</div>
				<div class="grid w-full items-center gap-1.5">
					<Label for="phone">Address</Label>
					<Input
						id="address"
						type="text"
						class="mt-1 block w-full"
						name="address"
						v-model="admin.address"
						placeholder="Address"
					/>
					<InputError :message="errors.address" />
				</div>
				<div class="grid w-full items-center gap-1.5">
					<Label for="status">Status</Label>
					<Select
						:default-value="admin.status.toString()"
						id="status"
						name="status"
					>
						<SelectTrigger class="w-full">
							<SelectValue placeholder="Select a status" />
						</SelectTrigger>
						<SelectContent>
							<SelectGroup>
								<SelectItem value="0"> Inactive </SelectItem>
								<SelectItem value="1"> Active </SelectItem>
							</SelectGroup>
						</SelectContent>
					</Select>
					<InputError :message="errors.status" />
				</div>

				<div class="grid w-full items-center gap-1.5">
					<div class="flex flex-col gap-1.5 w-44">
						<Label for="image">Avatar</Label>
						<Upload
							inputId="image"
							inputName="image_id"
							:imageId="avatarId"
							:imageUrl="avatarUrl"
							@update="updateImage"
						/>
						<InputError :message="errors.image" />
					</div>
				</div>

				<div class="flex items-center gap-4">
					<Button
						:disabled="processing"
						class="cursor-pointer"
						><Loader
							v-if="processing"
							class="animate-spin"
						/>
						<Save v-if="!processing" /> Save</Button
					>

					<ButtonLink
						label="Cancel"
						:href="route('admin.admins.index')"
						variants="default"
					>
						<Ban />
					</ButtonLink>
				</div>
			</div>
		</Form>
		<Form
			method="put"
			:action="route('admin.change-password', admin.id)"
			class="space-y-6"
			v-slot="{ errors, processing }"
		>
			<div class="mx-auto grid h-full max-w-xl gap-4 p-4">
				<HeadingSmall title="Change password" />
				<div class="grid w-full items-center gap-1.5">
					<Label for="password">Password</Label>
					<Input
						type="password"
						id="password"
						class="mt-1 block w-full"
						name="password"
						required
						autocomplete="password"
						placeholder="Password"
					/>
					<InputError :message="errors.password" />
				</div>

				<div class="grid w-full items-center gap-1.5">
					<Label for="password_confirmation">Password confirm</Label>
					<Input
						id="password_confirmation"
						type="password"
						class="mt-1 block w-full"
						name="password_confirmation"
						required
						autocomplete="password_confirmation"
						placeholder="Confirm Password"
					/>
				</div>
				<div class="flex items-center gap-4">
					<Button
						:disabled="processing"
						class="cursor-pointer"
						><Loader
							v-if="processing"
							class="animate-spin"
						/><RotateCcw v-if="!processing" />Change Password</Button
					>
				</div>
			</div>
		</Form>
		<DeleteUser />
	</AppLayout>
</template>
