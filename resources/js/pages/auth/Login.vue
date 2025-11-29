<script setup lang="ts">
	import InputError from '@/components/InputError.vue'
	import { Button } from '@/components/ui/button'
	import { Checkbox } from '@/components/ui/checkbox'
	import { Input } from '@/components/ui/input'
	import { Label } from '@/components/ui/label'
	import AuthLayout from '@/layouts/AuthLayout.vue'
	import { Form, Head } from '@inertiajs/vue3'
	import ButtonLink from '@/components/ButtonLink.vue'
	import { LoaderCircle } from 'lucide-vue-next'
</script>

<template>
	<AuthLayout
		title="Log in to your account"
		description="Enter your email and password below to log in"
	>
		<Head title="Log in" />

		<Form
			method="post"
			:action="route('admin.auth.login')"
			#default="{ errors, processing }"
			class="flex flex-col gap-6"
		>
			<div class="grid gap-4">
				<div class="grid gap-2">
					<Label for="email">Email address</Label>
					<Input
						id="email"
						type="email"
						name="email"
						required
						autofocus
						:tabindex="1"
						autocomplete="email"
						placeholder="email@example.com"
					/>
					<InputError :message="errors.email" />
				</div>

				<div class="grid gap-2">
					<div class="flex items-center justify-between">
						<Label for="password">Password</Label>
					</div>
					<Input
						id="password"
						type="password"
						name="password"
						required
						:tabindex="2"
						autocomplete="current-password"
						placeholder="Password"
					/>
					<InputError :message="errors.password" />
				</div>

				<div class="flex items-center justify-between">
					<Label
						for="remember"
						class="flex items-center space-x-3"
					>
						<Checkbox
							id="remember"
							name="remember"
							:tabindex="3"
						/>
						<span>Remember me</span>
					</Label>
				</div>

				<Button
					type="submit"
					class="w-full mt-4"
					:tabindex="4"
					:disabled="processing"
				>
					<LoaderCircle
						v-if="processing"
						class="w-4 h-4 animate-spin"
					/>
					Log in
				</Button>
				<ButtonLink
					label="Forgot password"
					:href="route('admin.forgot-password')"
				/>
			</div>
		</Form>
	</AuthLayout>
</template>
