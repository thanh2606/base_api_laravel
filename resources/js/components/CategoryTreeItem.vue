<template>
  <div>
    <div
      class="flex items-center space-x-2 p-2 hover:bg-gray-100 cursor-pointer rounded"
      :style="{ paddingLeft: `${(category.level || 0) * 20 + 8}px` }"
      @click="handleClick"
    >
      <span class="text-sm font-medium">{{ category.name }}</span>
      <span class="text-xs text-gray-500">({{ category.children?.length || 0 }} con)</span>
    </div>

    <!-- Render children recursively -->
    <CategoryTreeItem
      v-for="child in category.children"
      :key="child.id"
      :category="child"
      @select="$emit('select', $event)"
    />
  </div>
</template>

<script setup lang="ts">
import type { Category } from '@/utils/categoryHelpers'

interface Props {
  category: Category
}

interface Emits {
  (e: 'select', category: Category): void
}

const props = defineProps<Props>()
const emit = defineEmits<Emits>()

const handleClick = () => {
  emit('select', props.category)
}
</script>
