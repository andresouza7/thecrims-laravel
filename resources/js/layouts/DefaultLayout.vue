<script setup>
import { Link } from '@inertiajs/vue3'
import { computed, ref, onMounted, onBeforeUnmount } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { home } from '@/routes'
import { index as bank } from '@/routes/bank'
import { indexs as hooker} from '@/routes/hooker'
import { index as drug } from '@/routes/drug'
import { index as factory } from '@/routes/factory'

const page = usePage()
const user = computed(() => page.props.user)

// reactive state for game info
const gameDay = ref(0)
const gameTime = ref('00:00')
let intervalId = null

// function to fetch /info
const fetchGameInfo = async () => {
    try {
        const res = await fetch('/info')
        const data = await res.json()
        gameDay.value = data.day
        gameTime.value = data.time
    } catch (error) {
        console.error('Failed to fetch game info', error)
    }
}

// start the interval when component mounts
onMounted(() => {
    fetchGameInfo() // initial fetch
    intervalId = setInterval(fetchGameInfo, 2000) // every 2 seconds
})

// clear the interval on unmount
onBeforeUnmount(() => {
    clearInterval(intervalId)
})
</script>

<template>
    <div class="flex flex-col gap-4 p-2">
        <div class="alert">
            {{ $page.props.flash.message }}
            {{ $page.props.flash.success }}
            {{ $page.props.flash.error }}
        </div>

        <div class="p-2 rounded-sm border-1">
            <h4 class="font-medium">User data</h4>
            <div v-if="user" class="text-sm">
                <div>{{ user.name }}</div>
                <div>{{ user.cash }}</div>
                <div>{{ user.bank }}</div>
            </div>
        </div>

        <!-- game info -->
        <div class="p-2 rounded-sm border-1">
            <h4 class="font-medium">Game Info</h4>
            <div class="text-sm">
                <div>Day: {{ gameDay }}</div>
                <div>Time: {{ gameTime }}</div>
            </div>
        </div>

        <div class="p-2 flex gap-2">
            <Link :href="home()">Home</Link>
            <Link :href="bank()">Bank</Link>
            <Link :href="hooker()">Hookers</Link>
            <Link :href="drug()">Drugs</Link>
            <Link :href="factory()">Factories</Link>
            <Link href="/admin">Admin</Link>
        </div>

        <div class="p-2">
            <slot />
        </div>
    </div>
</template>
