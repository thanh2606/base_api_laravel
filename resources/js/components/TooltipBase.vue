<template>
	<TooltipProvider>
    <Tooltip>
      <TooltipTrigger><slot/></TooltipTrigger>
      <TooltipContent class="bg-black text-white">
        <p>{{props.text}}</p>
      </TooltipContent>
    </Tooltip>
  </TooltipProvider>
</template>

<script lang="ts" setup>
	import {
		Tooltip,
		TooltipContent,
		TooltipProvider,
		TooltipTrigger
	} from '@/components/ui/tooltip'

	const props = defineProps<{
		text: string
	}>()
</script>
