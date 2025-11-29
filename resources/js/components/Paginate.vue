<template>
	<div
		v-if="props.lastPage > 1 || props.total > 0"
		class="flex flex-col md:flex-row items-center md:items-center justify-center
			space-y-3 md:space-y-0"
	>
		<div class="relative">
			<div
				v-if="isLoading"
				class="absolute inset-0 bg-white/50 flex items-center justify-center z-10 rounded"
			>
				<div
					class="animate-spin h-5 w-5 border-2 border-black border-t-transparent rounded-full"
				></div>
			</div>

			<Pagination
				class="mt-2 md:mt-0"
				v-slot="{ page }"
				:items-per-page="props.perPage"
				:total="props.total"
				:default-page="currentPage"
				:sibling-count="1"
				show-edges
			>
				<PaginationContent v-slot="{ items }">
					<PaginationFirst
						@click="goToFirstPage"
						:disabled="isLoading || currentPage === 1"
						class="disabled:opacity-50 disabled:cursor-not-allowed"
					/>
					<PaginationPrevious
						@click="goToPreviousPage"
						:disabled="isLoading || currentPage === 1"
						class="disabled:opacity-50 disabled:cursor-not-allowed"
					/>
					<template
						v-for="(item, index) in items"
						:key="index"
					>
						<PaginationItem
							v-if="item.type === 'page'"
							:value="item.value"
							:is-active="item.value === page"
							:disabled="isLoading"
							class="cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
							@click="!isLoading && changePaginateLink(item.value, null)"
						>
							{{ item.value }}
						</PaginationItem>
						<PaginationEllipsis
							v-else
							:index="1"
						/>
					</template>
					<PaginationNext
						@click="goToNextPage"
						:disabled="isLoading || currentPage === props.lastPage"
						class="disabled:opacity-50 disabled:cursor-not-allowed"
					/>
					<PaginationLast
						@click="goToLastPage"
						:disabled="isLoading || currentPage === props.lastPage"
						class="disabled:opacity-50 disabled:cursor-not-allowed"
					/>
				</PaginationContent>
			</Pagination>
		</div>
	</div>
</template>

<script lang="ts" setup>
	import { router } from '@inertiajs/vue3'
	import {
		Pagination,
		PaginationContent,
		PaginationEllipsis,
		PaginationItem,
		PaginationNext,
		PaginationPrevious,
		PaginationFirst,
		PaginationLast,
	} from '@/components/ui/pagination'
	import { computed, ref } from 'vue'

	const props = defineProps<{
		lastPage: number
		perPage: number
		total: number
		currentPage: number
		routeName: string
		routeParams: Record<string, any>
	}>()

	// Loading state
	const isLoading = ref(false)

	const changePaginateLink = (page: number, limit: number | null) => {
		if (isLoading.value) return

		isLoading.value = true

		router.get(
			route(props.routeName, {
				page: page,
				perPage: limit || props.perPage,
				...props.routeParams
			}),
			{},
			{
				onFinish: () => {
					isLoading.value = false
				},
			}
		)
	}

	const currentPage = computed(() => {
		return props.currentPage
	})

	const goToPreviousPage = () => {
		if (currentPage.value === 1 || isLoading.value) {
			return
		}
		changePaginateLink(currentPage.value - 1, null)
	}

	const goToNextPage = () => {
		if (currentPage.value === props.lastPage || isLoading.value) {
			return
		}
		changePaginateLink(currentPage.value + 1, null)
	}

	const goToLastPage = () => {
		if (currentPage.value === props.lastPage || isLoading.value) {
			return
		}
		changePaginateLink(props.lastPage, null)
	}

	const goToFirstPage = () => {
		if (currentPage.value === 1 || isLoading.value) {
			return
		}
		changePaginateLink(1, null)
	}
</script>
