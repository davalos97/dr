import { useEventSource } from '@vueuse/core'

const { status, data, error, close } = useEventSource('/api/sse-endpoint')

