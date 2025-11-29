<script setup lang="ts">
	import {
		Dialog,
		DialogClose,
		DialogContent,
		DialogDescription,
		DialogFooter,
		DialogHeader,
		DialogTitle,
		DialogTrigger,
	} from '@/components/ui/dialog'
	import { Button } from '@/components/ui/button'
	import { Loader, Trash } from 'lucide-vue-next'
	import { Badge } from '@/components/ui/badge'
	import { Form } from '@inertiajs/vue3'

	const props = defineProps<{
		action: string
	}>()
</script>

<template>
	<Dialog>
		<DialogTrigger>
			<Badge
				variant="destructive"
				class="cursor-pointer"
			>
				<Trash class="h-3 w-3" />Xóa
			</Badge>
		</DialogTrigger>
		<DialogContent>
			<Form
				method="delete"
				:action="props.action"
				reset-on-success
				:options="{ preserveScroll: true, preserveState: false }"
				class="space-y-6"
				v-slot="{ processing, reset, clearErrors }"
			>
				<DialogHeader>
					<DialogTitle>Confirm delete</DialogTitle>
					<DialogDescription>Are you sure you want delete record? </DialogDescription>
				</DialogHeader>

				<DialogFooter>
					<DialogClose as-child>
						<Button
							variant="outline"
							@click="
								() => {
									clearErrors()
									reset()
								}
							"
							>Cancel</Button
						>
					</DialogClose>

					<Button
						variant="destructive"
						:disabled="processing"
						type="submit"
					>
						<Loader
							v-if="processing"
							class="animate-spin"
						/>
						Delete</Button
					>
				</DialogFooter>
			</Form>
		</DialogContent>
	</Dialog>
</template>

<style scoped></style>
