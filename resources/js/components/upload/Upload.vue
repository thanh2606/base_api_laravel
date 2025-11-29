<template>
	<div class="flex flex-col items-center gap-1.5">
		<div
			class="flex min-h-58 w-full items-center justify-center gap-2 rounded-md border border-dashed border-gray-300
				bg-transparent p-1 whitespace-nowrap shadow-xs transition-[color,box-shadow] outline-none
				focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50
				disabled:cursor-not-allowed disabled:opacity-50 aria-invalid:border-destructive
				aria-invalid:ring-destructive/20 data-[size=default]:h-9
				data-[size=sm]:h-8 *:data-[slot=select-value]:line-clamp-1 *:data-[slot=select-value]:flex
				*:data-[slot=select-value]:items-center *:data-[slot=select-value]:gap-2 dark:bg-input/30
				dark:hover:bg-input/50 dark:aria-invalid:ring-destructive/40 [&_svg]:pointer-events-none
				[&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-6
				[&_svg:not([class*='text-'])]:text-muted-foreground"
		>
			<label
				v-if="!fileUrl"
				:for="props.inputId"
				class="cursor-pointer flex gap-1.5 items-center text-base text-gray-700 hover:text-gray-900
					dark:text-gray-300 dark:hover:text-gray-100"
				><LucideUpload /> <span>Upload image</span></label
			>

			<img
				v-if="fileUrl"
				:src="fileUrl"
				class="w-full h-full object-cover object-center rounded-md"
			 :alt="fileUrl"/>

			<input
				:id="props.inputId"
				:name="props.inputName"
				type="file"
				hidden
				@change="onFileChange"
				ref="fileInput"
			/>
		</div>
		<Progress
			v-if="showProgress"
			:model-value="progressPercent"
		/>
		<Badge
			v-if="fileUrl && !showProgress"
			variant="destructive"
			@click="deleteFile"
			class="cursor-pointer"
		>
			<Trash class="h-3 w-3 " />Delete
		</Badge>
	</div>
</template>
<script lang="ts" setup>
	import { Progress } from '@/components/ui/progress'
	import { Badge } from '@/components/ui/badge'
	import { LucideUpload, Trash } from 'lucide-vue-next'
	import { ref } from 'vue'
	import Resumable from 'resumablejs'
	import { usePage } from '@inertiajs/vue3'
	import axios from 'axios'

	const progressPercent = ref<number>(0)
	const showProgress = ref<boolean>(false)
	const canDelete = ref<boolean>(false)
	const props = defineProps<{
		inputId: string
		inputName: string
		imageUrl?: string | null
		imageId?: number | null
	}>()
	const fileData = ref<(File & { uniqueIdentifier?: string }) | null>(null)
	const fileUrl = ref<string | null>(props.imageUrl ?? null)
	const fileInput = ref<HTMLInputElement | null>()
	const emits = defineEmits<{
		(e: 'update', data: { id: number | null; imageUrl: string | null }): void
	}>()

	interface FileWithIdentifier extends File {
		uniqueIdentifier?: string
	}

	const onFileChange = (event: Event) => {
		const input = event.target as HTMLInputElement
		const files = input.files
		if (files) {
			fileData.value = files[0] as File & { uniqueIdentifier?: string }
			fileUrl.value = URL.createObjectURL(fileData.value)
			uploadFile(event)
		}
	}

	const deleteFile = async () => {
		if (fileData.value && fileUrl.value) {
			if (fileInput.value) {
				fileInput.value.value = ''
			}
			URL.revokeObjectURL(fileUrl.value)
			fileData.value = null
			fileUrl.value = null
		}

		await axios
			.delete(route('admin.medias.destroy', { media: props.imageId }))
			.then((res) => {
				console.log('res', res)
				emits('update', {
					id: null,
					imageUrl: null,
				})
			})
			.finally(() => {})
	}

	const uploadFile = (event: Event) => {
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
		})

		resumable.addFiles([fileData.value] as File[])

		resumable.on('fileAdded', () => {
			showProgress.value = true
			progressPercent.value = 0
			resumable.upload()
		})

		resumable.on('fileProgress', (file: Resumable.ResumableFile) => {
			progressPercent.value = Math.floor(file.progress(true) * 100)
		})

		resumable.on('fileSuccess', (file: FileWithIdentifier, response: any) => {
			showProgress.value = false
			progressPercent.value = 0
			canDelete.value = true
			const res = JSON.parse(response)
			showProgress.value = false
			emits('update', {
				id: res.id,
				imageUrl: res.image,
			})
		})
	}
</script>
