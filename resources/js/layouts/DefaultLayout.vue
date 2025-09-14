<script setup>
import { Link } from '@inertiajs/vue3'
import { computed, ref, onMounted, onBeforeUnmount } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { home } from '@/routes'
import { index as bank } from '@/routes/bank'
import { indexs as hooker } from '@/routes/hooker'
import { index as drug } from '@/routes/drug'
import { index as factory } from '@/routes/factory'
import { index as nightclub } from '@/routes/nightclub'
import { index as boat } from '@/routes/boat'
import { index as jail } from '@/routes/jail'
import { index as hospital} from '@/routes/hospital'
import { useGameInfo } from '@/composables/useGameInfo'

const page = usePage()
const user = computed(() => page.props.user)

console.log(user.value)

const { gameDay, gameTime } = useGameInfo()
</script>

<template>
    <div class="flex flex-col gap-4 p-2">
        <div class="alert">
            {{ $page.props.flash.message }}
            {{ $page.props.flash.success }}
            {{ $page.props.flash.error }}
        </div>

        <div class="p-2 rounded-sm border-1">
            <h4 class="font-medium mb-2">User data</h4>

            <div v-if="user" class="grid grid-cols-4 gap-4 text-sm">
                <!-- Coluna 1: Avatar + barras -->
                <div class="flex flex-col items-center gap-2">
                    <!-- <img :src="`https://picsum.photos/seed/${user.id}/100`" alt="User Avatar"
                        class="w-20 h-20 rounded-full object-cover border" /> -->

                    <!-- Health -->
                    <div class="w-full">
                        <div class="flex justify-between text-xs">
                            <span>Health</span>
                            <span>{{ user.health }} / {{ user.max_health }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded h-3">
                            <div class="bg-red-500 h-3 rounded"
                                :style="{ width: ((user.health / user.max_health) * 100) + '%' }"></div>
                        </div>
                    </div>

                    <!-- Stamina -->
                    <div class="w-full">
                        <div class="flex justify-between text-xs">
                            <span>Stamina</span>
                            <span>{{ user.stamina }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded h-3">
                            <div class="bg-green-500 h-3 rounded" :style="{ width: user.stamina + '%' }"></div>
                        </div>
                    </div>

                    <!-- Addiction -->
                    <div class="w-full">
                        <div class="flex justify-between text-xs">
                            <span>Addiction</span>
                            <span>{{ user.addiction }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded h-3">
                            <div class="bg-purple-500 h-3 rounded" :style="{ width: user.addiction + '%' }"></div>
                        </div>
                    </div>
                </div>

                <div class="w-full">
                    <h4 class="font-medium">User data</h4>
                    <div v-if="user" class="text-sm">
                        <div>{{ user.name }}</div>
                        <div>{{ user.cash }}</div>
                        <div>{{ user.bank }}</div>
                        <div>{{ user.jail_end_time }} {{ user.in_jail }}</div>
                    </div>
                </div>

                <!-- Coluna 2: Atributos -->
                <div>
                    <h5 class="font-medium text-xs mb-1">Attributes</h5>
                    <div>Strength: {{ user.strength }}</div>
                    <div>Intelligence: {{ user.intelligence }}</div>
                    <div>Charisma: {{ user.charisma }}</div>
                    <div>Tolerance: {{ user.tolerance }}</div>
                </div>

                <!-- Coluna 3: Powers -->
                <div>
                    <h5 class="font-medium text-xs mb-1">Powers</h5>
                    <div>Single Robbery: {{ user.single_robbery_power }}</div>
                    <div>Gang Robbery: {{ user.gang_robbery_power }}</div>
                    <div>Assault: {{ user.assault_power }}</div>
                </div>
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
            <Link :href="nightclub()">Nightclub</Link>
            <Link :href="hooker()">Hookers</Link>
            <Link :href="drug()">Drugs</Link>
            <Link :href="factory()">Factories</Link>
            <Link :href="boat()">Docks</Link>
            <Link :href="jail()">Jail</Link>
            <Link :href="hospital()">Hospital</Link>
            <Link href="/admin">Admin</Link>
        </div>

        <div class="p-2">
            <slot />
        </div>
    </div>
</template>
