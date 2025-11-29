<script setup lang="ts">
	import {
		Select,
		SelectContent,
		SelectGroup,
		SelectItem,
		SelectTrigger,
		SelectValue,
	} from '@/components/ui/select'
	import { Button } from '@/components/ui/button'
	import { Form } from '@inertiajs/vue3'
	import { Loader, Ban } from 'lucide-vue-next'
	import AppLayout from '@/layouts/AppLayout.vue'
	import InputError from '@/components/InputError.vue'
	import { Label } from '@/components/ui/label'
	import { Input } from '@/components/ui/input'
	import type { BreadcrumbItem } from '@/types'
	import Upload from '@/components/upload/Upload.vue'
	import { ref } from 'vue'
	import ButtonLink from '@/components/ButtonLink.vue'

	const avatarId = ref<number | null>(null)
	const avatarUrl = ref<string | null>(null)
	const breadcrumbItems: BreadcrumbItem[] = [
		{
			title: 'Bảng điều khiển',
			href: route('admin.dashboard'),
		},
		{
			title: 'Tạo tài khoản',
			href: route('admin.users.create'),
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
		title="Tạo tài khoản"
	>
		<Form
			method="post"
			:action="route('admin.users.store')"
			class="space-y-6"
			v-slot="{ errors, processing }"
			:transform="(data) => ({ ...data, image_id: avatarId, image: avatarUrl })"
		>
			<div class="grid mx-auto max-w-xl h-full p-4 gap-4">
				<div class="grid w-full items-center gap-1.5">
					<Label for="name">Name</Label>
					<Input
						id="name"
						class="mt-1 block w-full"
						name="name"
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
						required
						placeholder="Email address"
					/>
					<InputError :message="errors.email" />
				</div>
				<div class="grid w-full items-center gap-1.5">
					<Label for="password">Mật khẩu</Label>
					<Input
						type="password"
						id="password"
						class="mt-1 block w-full"
						name="password"
						required
						autocomplete="password"
						placeholder="Mật khẩu"
					/>
					<InputError :message="errors.password" />
				</div>
				<div class="grid w-full items-center gap-1.5">
					<Label for="password_confirmation">Xác nhận mật khẩu</Label>
					<Input
						type="password"
						id="password_confirmation"
						class="mt-1 block w-full"
						name="password_confirmation"
						required
						autocomplete="password_confirmation"
						placeholder="Xác nhận mật khẩu"
					/>
					<InputError :message="errors.password_confirmation" />
				</div>
				<div class="grid w-full items-center gap-1.5">
					<Label for="phone">Phone</Label>
					<Input
						id="phone"
						type="tel"
						class="mt-1 block w-full"
						name="phone"
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
						placeholder="Address"
					/>
					<InputError :message="errors.address" />
				</div>
				<div class="grid w-full items-center gap-1.5">
					<Label for="status">Status</Label>
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
					<ButtonLink
						label="Cancel"
						:href="route('admin.users.index')"
						variants="default"
					>
						<Ban />
					</ButtonLink>
				</div>
			</div>
		</Form>
	</AppLayout>
</template>

<style scoped></style>
