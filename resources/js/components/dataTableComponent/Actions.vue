<template>
	<div class="flex gap-1.5">
		<Badge
			@click="router.get(props.editUrl)"
			class="cursor-pointer"
			><Pencil class="h-3 w-3" />Sửa</Badge
		>
		<DeleteDialog :action="props.deleteUrl" />
	</div>
</template>

<script lang="ts" setup>
	import { Badge } from '@/components/ui/badge'
	import { router } from '@inertiajs/vue3'
	import { Pencil } from 'lucide-vue-next'
	import DeleteDialog from '@/components/dataTableComponent/DeleteDialog.vue'

	const props = defineProps<{
		editUrl: string
		deleteUrl: string
	}>()
</script>
<style scoped lang="scss"></style>
