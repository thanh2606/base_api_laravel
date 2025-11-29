import { CategoryResourceInterface } from '@/responses/category/CategoryResource'
import { SelectOption } from '@/components/common/SelectBase.vue'

/**
 * Transform tree structure categories to flat select options
 * @param categories - Tree structure categories from backend
 * @param excludeId - ID of category to exclude (used when editing to prevent self-parent)
 * @param level - Current nesting level (for indentation)
 * @returns Flat array of options for select component
 */
export function getCategoriesForSelect(categories: CategoryResourceInterface[], excludeId?: number, level: number = 0): SelectOption[] {
    const options: SelectOption[] = []

    // if (level === 0) {
    //     options.push({ id: null, label: 'Không có danh mục cha' })
    // }

    for (const category of categories) {
        // Skip if this is the category we want to exclude
        if (excludeId && category.id === excludeId) {
            continue
        }

        const prefix = '—'.repeat(level)
        const label = level > 0 ? `${prefix} ${category.title}` : category.title

        options.push({
            id: category.id,
            label: label
        })

        if (category.children && category.children.length > 0) {
            const childOptions = getCategoriesForSelect(category.children, excludeId, level + 1)
            options.push(...childOptions.filter(option => option.id !== null))
        }
    }

    return options
}

/**
 * Transform tree structure categories to flat options for MultiSelect (without null option)
 * @param categories - Tree structure categories from backend
 * @param level - Current nesting level (for indentation)
 * @returns Flat array of options for MultiSelect component
 */
export function getCategoriesForMultiSelect(categories: CategoryResourceInterface[], level: number = 0): SelectOption[] {
    const options: SelectOption[] = []

    for (const category of categories) {
        const prefix = '—'.repeat(level)
        const label = level > 0 ? `${prefix} ${category.title}` : category.title

        options.push({
            id: category.id,
            label: label
        })

        if (category.children && category.children.length > 0) {
            const childOptions = getCategoriesForMultiSelect(category.children, level + 1)
            options.push(...childOptions)
        }
    }

    return options
}

/**
 * Get category names by IDs from flat categories list
 * @param categoryIds - Array of category IDs
 * @param categories - Tree structure categories from backend
 * @returns Array of category names
 */
export function getCategoryNamesByIds(categoryIds: number[], categories: CategoryResourceInterface[]): string[] {
    const names: string[] = []

    const findNames = (cats: CategoryResourceInterface[]) => {
        for (const cat of cats) {
            if (categoryIds.includes(cat.id)) {
                names.push(cat.title)
            }
            if (cat.children && cat.children.length > 0) {
                findNames(cat.children)
            }
        }
    }

    findNames(categories)
    return names
}
