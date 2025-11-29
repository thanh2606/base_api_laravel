<template>
	<div class="flex flex-col gap-1.5">
		<div
			class="flex h-80 w-full items-center justify-center gap-2 rounded-md border border-dashed border-gray-300
				px-3 py-2 whitespace-nowrap shadow-xs transition-[color,box-shadow] outline-none
				focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50
				disabled:cursor-not-allowed disabled:opacity-50 aria-invalid:border-destructive
				aria-invalid:ring-destructive/20 data-[size=default]:h-9
				data-[size=sm]:h-8 *:data-[slot=select-value]:line-clamp-1 *:data-[slot=select-value]:flex
				*:data-[slot=select-value]:items-center *:data-[slot=select-value]:gap-2 dark:bg-input/30
				dark:hover:bg-input/50 dark:aria-invalid:ring-destructive/40 [&_svg]:pointer-events-none
				[&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-6
				[&_svg:not([class*='text-'])]:text-muted-foreground relative"
			@drop="dropHandler"
			@dragover="dragOverHandler"
		>
			<label
				:for="props.inputId"
				class="cursor-pointer flex gap-1.5 items-center text-base text-gray-700 hover:text-gray-900
					dark:text-gray-300 dark:hover:text-gray-100 w-full h-full justify-center absolute z-50"
				><div
					v-show="uploadFileData.length === 0"
					class="flex gap-5"
				>
					<LucideUpload /> <span>Drag media or click here</span>
				</div>
			</label>
			<Input
				:id="props.inputId"
				:name="props.inputName"
				type="file"
				multiple
				hidden
				@change="onFileChange"
			></Input>
			<template v-if="uploadFileData.length > 0">
				<div class="w-full flex flex-wrap h-80 items-center justify-center gap-1 overflow-y-scroll relative py-2 z-[51]">
					<div
						class="rounded block bg-white/70 border border-gray-300 shadow-xs p-1"
						v-for="(item, index) in uploadFileData"
						:key="index"
					>
						<img
							class="w-24 h-24 block object-cover object-center rounded transition-opacity ease-in-out duration-300"
							v-if="item.url"
							:src="item.url"
							alt="image"
						/>
						<div v-if="item.progress === 100 || item.id" class="flex justify-center mt-2">
							<Badge
								variant="destructive"
								@click="onDelete(item.id)"
								class="cursor-pointer"
							>
								Xoá
							</Badge>
						</div>
						<Progress
							class="mt-2"
							v-if="item.progress !== undefined && item.progress < 100"
							:model-value="item.progress"
						/>
					</div>
				</div>
			</template>
		</div>
	</div>
</template>
<script setup lang="ts">
	import { LucideUpload } from 'lucide-vue-next'
	import { Input } from '@/components/ui/input'
	import { ref, watch } from 'vue'
	import Resumable from 'resumablejs'
	import { usePage } from '@inertiajs/vue3'
	import { MediaInterface } from '@/responses/media/MediaResource'
	import { Progress } from '@/components/ui/progress'
	import { Badge } from '@/components/ui/badge'

	const props = defineProps<{
		inputId: string
		inputName: string
		images?: MediaInterface[]
	}>()

	const uploadFileData = ref<
		Array<{
			file?: File
			progress?: number
			status?: number
			url?: string
			id?: number
			name?: string
		}>
	>([])

	watch(
		() => props.images,
		(newImages) => {
			if (newImages) {
				uploadFileData.value = [...newImages]
			}
		},
		{ immediate: true, deep: true }
	)

	const emits = defineEmits<{
		(e: 'uploadSuccess', media: { id: number; name: string; url: string }): void
		(e: 'delete', id: number): void
		(e: 'complete'): void
	}>()

	interface FileWithIdentifier extends File {
		uniqueIdentifier?: string
		fileName: string
	}

	const onFileChange = (event: Event) => {
		const input = event.target as HTMLInputElement
		const files = input.files

		if (files) {
			for (const index in files) {
				if (files[index] instanceof File) {
					uploadFileData.value.push({
						file: files[index],
						progress: 0,
						status: 0,
						url: URL.createObjectURL(files[index]),
					})
				}
			}
		}

		input.value = ''
		uploadMultiFiles(event)
	}

	const dragOverHandler = (event: Event) => {
		event.preventDefault()
	}

	const dropHandler = (event: DragEvent) => {
		event.preventDefault()

		if (event.dataTransfer && event.dataTransfer.items) {
			;[...event.dataTransfer.items].forEach((item) => {
				if (item.kind === 'file') {
					const file = item.getAsFile()
					if (file) {
						uploadFileData.value.push({
							file: file,
							progress: 0,
							status: 0,
							url: URL.createObjectURL(file),
							name: file.name,
						})
					}
				}
			})
		}
	}

	const uploadMultiFiles = (event: Event) => {
		event.preventDefault()

		const resumable = new Resumable({
			target: route('admin.medias.store'),
			headers: {
				'X-CSRF-TOKEN': usePage().props.csrf_token,
				'X-Inertia': true,
				Accept: 'application/json',
			},
			forceChunkSize: true,
			withCredentials: true,
			fileParameterName: 'file',
			simultaneousUploads: 1,
			testChunks: false,
			maxChunkRetries: 1,
			chunkSize: 10000,
		})

		resumable.addFiles(uploadFileData.value.filter((f) => f.file).map((f) => f.file) as File[])
		resumable.on('fileAdded', () => {
			resumable.upload()
		})

		resumable.on('fileProgress', (file: Resumable.ResumableFile) => {
			const progress = Math.floor(file.progress(true) * 100) as number
			const index = uploadFileData.value.findIndex((f) => f.file && f.file.name === file.fileName)
			if (index != -1) {
				uploadFileData.value[index].progress = progress
			}
		})

		resumable.on('fileSuccess', (file: FileWithIdentifier, response: any) => {
			const res = JSON.parse(response)
			emits('uploadSuccess', {
				id: res.id,
				name: res.name,
				url: res.image,
			})
		})

		resumable.on('complete', () => {
			emits('complete')
		})
	}

	const onDelete = (id?: number) => {
		if (id) {
			emits('delete', id)
		}
	}
</script>
<style scoped></style>
