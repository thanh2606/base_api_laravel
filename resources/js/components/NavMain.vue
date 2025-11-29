<script setup lang="ts">
	import {
		SidebarGroup,
		SidebarMenu,
		SidebarMenuButton,
		SidebarMenuItem,
		SidebarMenuSub,
		SidebarMenuSubItem,
		SidebarMenuSubButton,
	} from '@/components/ui/sidebar'
	import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible'
	import { type NavItem } from '@/types'
	import { Link, usePage } from '@inertiajs/vue3'
	import { ChevronDown } from 'lucide-vue-next'

	defineProps<{
		items: NavItem[]
	}>()

	const page = usePage()
</script>

<template>
	<SidebarGroup class="px-2 py-0">
		<SidebarMenu>
			<Collapsible
				v-for="item in items"
				:key="item.title"
				class="group/collapsible"
			>
				<SidebarMenuItem>
					<template v-if="!item.children">
						<SidebarMenuButton
							as-child
							:is-active="item.href === page.url"
							:tooltip="item.title"
						>
							<Link
								v-if="!item.children"
								:href="item.href"
							>
								<component :is="item.icon" />
								<span>{{ item.title }}</span>
							</Link>
						</SidebarMenuButton>
					</template>
					<template v-else>
						<CollapsibleTrigger as-child>
							<SidebarMenuButton :tooltip="item.title">
								<component
									:is="item.icon"
									v-if="item.icon"
								/>
								<span>{{ item.title }}</span>
								<ChevronDown
									class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-180"
								/>
							</SidebarMenuButton>
						</CollapsibleTrigger>
						<CollapsibleContent>
							<SidebarMenuSub>
								<SidebarMenuSubItem
									v-for="subItem in item.children"
									:key="subItem.title"
								>
									<SidebarMenuSubButton as-child>
										<a :href="subItem.href">
											<span>{{ subItem.title }}</span>
										</a>
									</SidebarMenuSubButton>
								</SidebarMenuSubItem>
							</SidebarMenuSub>
						</CollapsibleContent>
					</template>
				</SidebarMenuItem>
			</Collapsible>
		</SidebarMenu>
	</SidebarGroup>
</template>
