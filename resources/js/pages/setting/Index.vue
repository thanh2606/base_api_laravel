<template>
	<AppLayout
		:breadcrumbs="breadcrumbItems"
		title="Cấu hình chung"
	>
		<form
			@submit.prevent="submit"
			class="space-y-6"
			disableWhileProcessing
		>
			<div
				class="h-full p-4 gap-4 w-full max-w-xl mx-auto"
			>
				<div class="grid gap-4">
					<div class="grid w-full items-center gap-1.5">
						<Label for="title">Tên website</Label>
						<Input
							id="title"
							class="mt-1 block w-full"
							v-model="form.title"
							placeholder="Tên website"
						/>
						<InputError :message="form.errors.title" />
					</div>
					<div class="grid w-full items-center gap-1.5">
						<Label for="title">Mô tả ngắn</Label>
						<Input
							id="desc"
							class="mt-1 block w-full"
							placeholder="Mô tả ngắn"
							v-model="form.desc"
						/>
						<InputError :message="form.errors.desc" />
					</div>
					<div class="grid w-full items-center gap-1.5">
						<Label for="title">Từ khoá chính</Label>
						<Input
							id="keywords"
							class="mt-1 block w-full"
							required
							placeholder="Từ khoá chính"
							v-model="form.keywords"
						/>
						<InputError :message="form.errors.keywords" />
					</div>
					<div class="grid w-full items-center gap-1.5">
						<Label for="image_id">Hình ảnh đại diện</Label>
						<UploadImage
							inputId="image_id"
							inputName="image_id"
							:image-url="form.image"
							:image-id="form.image_id"
						/>
					</div>
					<div class="grid w-full items-center gap-1.5">
						<Label for="title">Email liên hệ</Label>
						<Input
							id="email"
							class="mt-1 block w-full"
							required
							placeholder="Email liên hệ"
							v-model="form.email"
						/>
						<InputError :message="form.errors.email" />
					</div>
					<div class="grid w-full items-center gap-1.5">
						<Label for="title">Số điện thoại</Label>
						<Input
							id="phone"
							class="mt-1 block w-full"
							required
							placeholder="Số điện thoại"
							v-model="form.phone"
						/>
						<InputError :message="form.errors.phone" />
					</div>
					<div class="grid w-full items-center gap-1.5">
						<Label for="title">Địa chỉ</Label>
						<Input
							id="address"
							class="mt-1 block w-full"
							required
							placeholder="Địa chỉ"
							v-model="form.address"
						/>
						<InputError :message="form.errors.address" />
					</div>
					<div class="grid w-full items-center gap-1.5">
						<Label for="title">Facebook</Label>
						<Input
							id="facebook_url"
							class="mt-1 block w-full"
							required
							placeholder="Facebook URL"
							v-model="form.facebook_url"
						/>
						<InputError :message="form.errors.facebook_url" />
					</div>
					<div class="grid w-full items-center gap-1.5">
						<Label for="title">Zalo</Label>
						<Input
							id="zalo_url"
							class="mt-1 block w-full"
							required
							placeholder="Zalo URL"
							v-model="form.zalo_url"
						/>
						<InputError :message="form.errors.facebook_url" />
					</div>
					<div class="grid w-full items-center gap-1.5">
						<Label for="title">Youtube</Label>
						<Input
							id="youtube_url"
							class="mt-1 block w-full"
							required
							placeholder="Youtube URL"
							v-model="form.youtube_url"
						/>
						<InputError :message="form.errors.youtube_url" />
					</div>
					<div class="grid w-full items-center gap-1.5">
						<div class="flex items-center gap-4">
							<Button :disabled="form.processing" type="submit"
							><Loader
								v-if="form.processing"
								class="animate-spin"
							/><Save v-if="!form.processing" /> Save</Button
							>
						</div>
					</div>
				</div>
			</div>
		</form>
	</AppLayout>
</template>

<script lang="ts" setup>
	import AppLayout from '@/layouts/AppLayout.vue'
	import type { BreadcrumbItem } from '@/types'
	import UploadImage from '@/components/upload/Upload.vue'
	import { useForm, usePage } from '@inertiajs/vue3'
	import { Label } from '@/components/ui/label'
	import { Button } from '@/components/ui/button'
	import { Input } from '@/components/ui/input'
	import InputError from '@/components/InputError.vue'

	const page = usePage<any>()
	const form = useForm({
		title: '',
		desc: '',
		keywords: '',
		image_id: null,
		image: null,
		email: '',
		phone: '',
		address: '',
		facebook_url: '',
		zalo_url: '',
		youtube_url: '',
	})

	const submit = () => {
		console.log('submit')
	}
	const breadcrumbItems: BreadcrumbItem[] = [
		{
			title: 'Bảng điều khiển',
			href: route('admin.dashboard'),
		},
		{
			title: 'Cấu hình chung',
			href: route('admin.settings.index'),
		},
	]


</script>
