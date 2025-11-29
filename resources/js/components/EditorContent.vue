
<template>
	<div class="editor-content">
		<div class="tinymce-editor">
			<Editor
				v-model="content"
				:init="editorConfig"
				api-key="no-api-key"
				license-key="gpl"
				@change="emits('change', content)"
				v-bind="$attrs"
			></Editor>
		</div>
	</div>
</template>

<script setup lang="ts">
	import axios from 'axios'
	import 'tinymce/tinymce'
	import 'tinymce/icons/default/icons.min.js'
	/* Required TinyMCE components */
	import 'tinymce/themes/silver/theme.min.js'
	import 'tinymce/models/dom/model.min.js'

	/* Import a skin (can be a custom skin instead of the default) */
	import 'tinymce/skins/ui/oxide/skin.js'
	import 'tinymce/skins/ui/oxide/content.js'

	/* Import plugins */
	import 'tinymce/plugins/advlist'
	import 'tinymce/plugins/code'
	import 'tinymce/plugins/emoticons'
	import 'tinymce/plugins/emoticons/js/emojis'
	import 'tinymce/plugins/link'
	import 'tinymce/plugins/lists'
	import 'tinymce/plugins/table'
	import 'tinymce/plugins/image'
	import 'tinymce/plugins/media'
	import 'tinymce/plugins/wordcount'
	import 'tinymce/skins/content/tinymce-5/content.js'
	import Editor from '@tinymce/tinymce-vue'
	import { ref } from 'vue'
	import { usePage } from '@inertiajs/vue3'

	const props = withDefaults(
		defineProps<{
			name?: string|null
			content?: string | null
			height?: number|string
		}>(),
		{
			content: null,
			height: 200
		}
	)

	const content = ref(props.content)

	const handleImageUpload = async (blobInfo: any, progress: any) => {
		const formData = new FormData()
		formData.append('file', blobInfo.blob(), blobInfo.filename())
		formData.append('_token', usePage().props.csrf_token as string)
		const headerConfig = {
			headers: { 'Content-Type': 'multipart/form-data' },
			onUploadProgress: (progressEvent: any) => {
				const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total)
				if (progress && typeof progress === 'function') {
					progress(percentCompleted)
				}
			},

		}

		try {
			const response = await axios.post(route('admin.tinymce-upload'), formData, headerConfig)
			return response.data.location
		} catch (err: any) {
			alert(err.response.data.meta.errors.file)

			if (err.response && err.response.status === 403) {
				throw new Error('HTTP Error: ' + err.response.status);
			}

			throw new Error(
				err.response
					? 'HTTP Error: ' + err.response.status
					: 'Image upload failed: ' + err.message
			)
		}
	}

	const editorConfig = {
		plugins: [
			'advlist',
			'link',
			'image',
			'lists',
			'code',
			'table',
			'emoticons',
			'image',
			'media',
			'wordcount',
		],
		menu: {
			file: {
				title: 'File',
				items:
					'newdocument restoredraft | preview | importword exportpdf exportword | print | deleteallconversations',
			},
			edit: {
				title: 'Edit',
				items: 'undo redo | cut copy paste pastetext | selectall | searchreplace',
			},
			view: {
				title: 'View',
				items:
					'code revisionhistory | visualaid visualchars visualblocks | spellchecker | preview fullscreen | showcomments',
			},
			insert: {
				title: 'Insert',
				items:
					'image link media addcomment pageembed codesample | math | charmap emoticons hr | pagebreak nonbreaking anchor tableofcontents | insertdatetime',
			},
			format: {
				title: 'Format',
				items:
					'bold italic underline strikethrough superscript subscript codeformat | styles blocks fontfamily fontsize align lineheight | forecolor backcolor | language | removeformat',
			},
			tools: {
				title: 'Tools',
				items: 'spellchecker spellcheckerlanguage | a11ycheck code wordcount',
			},
			table: {
				title: 'Table',
				items: 'inserttable | cell row column | advtablesort | tableprops deletetable',
			},
			help: { title: 'Help', items: 'help' },
		},
		toolbar:
			'styles | bold italic fontsize | forecolor backcolor | bullist numlist image link| alignleft aligncenter alignright alignjustify',
		skin: 'oxide',
		content_css: 'tinymce-5',
		file_picker_types: 'image',
		relative_urls: true,
		document_base_url: '/',
		remove_script_host: true,
		convert_urls: true,
		images_upload_credentials: true,
		images_upload_handler: handleImageUpload,
		height: props.height as number
	}

	const emits = defineEmits<{
		(event: 'change', content: string|null): void
	}>()
</script>

<style scoped lang="scss">
	.tinymce-editor {
		border: 1px solid #d4d4d8;
		border-radius: 0.375rem;
		transition: all ease 0.5s;

		&:hover {
			border-color: #fff;
			outline: 1px solid #000;
		}

		:deep(.tox-promotion) {
			display: none;
		}

		:deep(.tox.tox-tinymce) {
			border: none;
		}

		:deep(.tox .tox-edit-area::before) {
			border: none;
		}

		:deep(.tox:not(.tox-tinymce-inline) .tox-editor-header) {
			box-shadow: none;
			border-bottom: 1px solid #d4d4d8;
		}
	}
</style>
