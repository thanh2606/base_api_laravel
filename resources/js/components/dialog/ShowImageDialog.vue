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
	import { Pencil } from 'lucide-vue-next'
	import { Badge } from '@/components/ui/badge'
	import { MediaResourceInterface } from '@/responses/media/MediaResource'

	const props = defineProps<{
		data: MediaResourceInterface
	}>()
</script>

<template>
	<Dialog>
		<DialogTrigger>
			<Badge class="cursor-pointer"><Pencil class="h-3 w-3" />Chi tiết</Badge>
		</DialogTrigger>
		<DialogContent :class="'md:w-4xl md:max-w-full'">
			<DialogHeader>
				<DialogTitle>Thông tin</DialogTitle>
				<DialogDescription></DialogDescription>
			</DialogHeader>
			<div class="overflow-hidden">
				<div class="overflow-scroll mb-5">
					<img
							class="block max-w-full mx-auto"
							:src="props.data.url"
							:alt="props.data.name"
						/>
				</div>
				<div>
					<ul class="list-none list-inside">
						<li class="text-base"><b>Tiêu đề:</b> {{ props.data.name }}</li>
						<li class="text-base"><b>URL:</b> {{ props.data.url }}</li>
						<li class="text-base">
							<b>Kích thước:</b> {{ props.data.width }} x {{ props.data.height }}
						</li>
						<li class="text-base"><b>Ngày tạo:</b> {{ props.data.created_at }}</li>
					</ul>
				</div>
			</div>
			<DialogFooter>
				<DialogClose as-child>
					<Button variant="outline">Close</Button>
				</DialogClose>
			</DialogFooter>
		</DialogContent>
	</Dialog>
</template>

<style scoped></style>
