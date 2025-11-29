<script setup lang="ts">
	import AppContent from '@/components/appLayout/AppContent.vue'
	import AppShell from '@/components/appLayout/AppShell.vue'
	import AppSidebar from '@/components/appLayout/AppSidebar.vue'
	import AppSidebarHeader from '@/components/appLayout/AppSidebarHeader.vue'
	import type { BreadcrumbItemType } from '@/types'
	import { Check, TriangleAlert } from 'lucide-vue-next'
	import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert'
	import { usePage, Head } from '@inertiajs/vue3'
	import { computed } from 'vue'

	interface Props {
		breadcrumbs?: BreadcrumbItemType[]
		title?: string
	}

	withDefaults(defineProps<Props>(), {
		breadcrumbs: () => [],
		title: 'Dashboard',
	})

	const page = usePage()
	const flash = computed(() => page.props.success || Object.keys(page.props.errors).length > 0)
	const success = computed(() => page.props.success)
	const errors = computed(() => page.props.errors)
</script>

<template>
	<AppShell variant="sidebar">
		<AppSidebar />
		<AppContent
			variant="sidebar"
			class="overflow-x-hidden"
		>
			<AppSidebarHeader
				:breadcrumbs="breadcrumbs"
			/>
			<div
				v-if="flash"
				class="p-4"
			>
				<Alert
					v-if="success"
					variant="success"
				>
					<Check class="w-4 h-4" />
					<AlertTitle>Success</AlertTitle>
					<AlertDescription>
						<div class="text-xs">{{ success }}</div>
					</AlertDescription>
				</Alert>

				<Alert
					v-if="Object.keys(errors).length > 0"
					variant="danger"
				>
					<TriangleAlert class="w-4 h-4" />
					<AlertTitle>Errors</AlertTitle>
					<AlertDescription>
						<div
							v-for="(item, index) in errors"
							:key="index"
							class="text-xs"
						>
							{{ item }}
						</div>
					</AlertDescription>
				</Alert>
			</div>
			<Head :title="title" />
			<div class="mx-auto h-full w-full gap-4">
				<slot />
			</div>
		</AppContent>
	</AppShell>
</template>
