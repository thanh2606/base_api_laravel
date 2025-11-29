import type { VariantProps } from "class-variance-authority"
import { cva } from "class-variance-authority"

export { default as Alert } from "./Alert.vue"
export { default as AlertDescription } from "./AlertDescription.vue"
export { default as AlertTitle } from "./AlertTitle.vue"

export const alertVariants = cva(
  "relative w-full rounded-lg border px-4 py-3 text-sm grid has-[>svg]:grid-cols-[calc(var(--spacing)*4)_1fr] grid-cols-[0_1fr] has-[>svg]:gap-x-3 gap-y-0.5 items-start [&>svg]:size-4 [&>svg]:translate-y-0.5 [&>svg]:text-current",
  {
    variants: {
      variant: {
        default: "bg-card text-card-foreground",
        danger:
          "text-destructive bg-red-50 border-red-50 [&>svg]:text-current *:data-[slot=alert-description]:text-destructive/90",
	  	success: "text-green-700 bg-green-50 border-green-50 [&>svg]:text-green-500 *:data-[slot=alert-description]:text-green-700/90",
		warning: "text-amber-700 bg-amber-50 border-amber-50 [&>svg]:text-amber-500 *:data-[slot=alert-description]:text-amber-700/90",
		info: "text-blue-700 bg-blue-50 border-blud-50 [&>svg]:text-blue-500 *:data-[slot=alert-description]:text-blue-700/90",
      },
    },
    defaultVariants: {
      variant: "default",
    },
  },
)

export type AlertVariants = VariantProps<typeof alertVariants>
