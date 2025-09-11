<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { home } from '@/routes'
import { index as bank } from '@/routes/bank'
import { index as hooker} from '@/routes/hooker'
import { index as drug } from '@/routes/drug'
import { index as factory } from '@/routes/factory'
const page = usePage()

const user = computed(() => page.props.user)
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

        <div class="p-2 flex gap-2">
            <Link :href="home()">Home</Link>
            <Link :href="bank()">Bank</Link>
            <Link :href="hooker()">Hookers</Link>
            <Link :href="drug()">Drugs</Link>
            <Link :href="factory()">Factories</Link>
        </div>

        <div class="p-2">
            <slot />
        </div>
    </div>
</template>