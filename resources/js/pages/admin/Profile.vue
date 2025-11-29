<script setup lang="ts">
	import { Form, usePage } from '@inertiajs/vue3'
	import DeleteUser from '@/components/user/DeleteUser.vue'
	import InputError from '@/components/InputError.vue'
	import { Button } from '@/components/ui/button'
	import { Input } from '@/components/ui/input'
	import { Label } from '@/components/ui/label'
	import AppLayout from '@/layouts/AppLayout.vue'
	import { type BreadcrumbItem } from '@/types'
	import { type AdminResourceInterface } from '@/responses/admin/AdminResource'
	import HeadingSmall from '@/components/heading/HeadingSmall.vue'
	import { Loader } from 'lucide-vue-next'
	import {
		Select,
		SelectContent,
		SelectItem,
		SelectTrigger,
		SelectValue,
		SelectGroup,
	} from '@/components/ui/select'
	import { ref } from 'vue'
	import Upload from '@/components/upload/Upload.vue'

	const page = usePage()
	const admin = page.props.auth.admin as AdminResourceInterface
	const avatarId = ref<number | null>(admin.image_id)
	const avatarUrl = ref<string | null>(admin.image)
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
		>
			<div class="grid mx-auto max-w-xl h-full p-4 gap-4">
				<HeadingSmall
					title="Profile information"
					description="Update your name and email address"
				/>
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
					<Button :disabled="processing"
						><Loader
							v-if="processing"
							class="animate-spin"
						/>Save</Button
					>
				</div>
			</div>
		</Form>
		<Form
			method="put"
			:action="route('admin.change-password', admin.id)"
			class="space-y-6"
			v-slot="{ errors, processing, recentlySuccessful }"
		>
			<div class="grid mx-auto max-w-xl h-full p-4 gap-4">
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
						type="password"
						id="password_confirmation"
						class="mt-1 block w-full"
						name="password_confirmation"
						required
						autocomplete="password_confirmation"
						placeholder="Confirm Password"
					/>
				</div>
				<div class="flex items-center gap-4">
					<Button :disabled="processing">Change Password</Button>
					<Transition
						enter-active-class="transition ease-in-out"
						enter-from-class="opacity-0"
						leave-active-class="transition ease-in-out"
						leave-to-class="opacity-0"
					>
						<p
							v-show="recentlySuccessful"
							class="text-sm text-neutral-600"
						>
							Saved.
						</p>
					</Transition>
				</div>
			</div>
		</Form>
		<DeleteUser />
	</AppLayout>
</template>
