<script setup>
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router, usePage } from '@inertiajs/vue3';
import { sell, activate, deactivate } from '@/routes/inventory';
import { computed } from 'vue';

const page = usePage()

const user = computed(() => page.props.user)

const props = defineProps({
    armors: Array,
    weapons: Array,
});

console.log(props.armors)

const sellItem = (itemId) => {
    router.post(sell(itemId));
};
const activateItem = (itemId) => {
    router.post(activate(itemId));
};
const deactivateItem = (itemId) => {
    router.post(deactivate(itemId));
};

const isActiveWeapon = (id) => computed(() => user.value.weapon_id === id)
</script>

<template>
    <DefaultLayout>
        <div class="max-w-7xl mx-auto py-6 px-4">
            <h2 class="text-3xl font-bold text-white mb-6">Inventory</h2>

            <h4>my weapons</h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <div v-for="armor in props.weapons" :key="armor.id"
                    class="bg-gray-800 text-gray-200 rounded-xl overflow-hidden shadow-lg flex flex-col">
                    <img :src="armor.avatar" :alt="armor.name" class="w-full h-40 object-cover" />

                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-semibold mb-2">{{ armor.name }}</h3>
                            <p class="text-gray-400 text-sm mb-1">Base Damage: {{ armor.base_damage }}</p>
                            <p class="text-gray-400 text-sm mb-1">Multiplier: {{ armor.multiplier }}</p>
                            <p class="text-gray-400 text-sm mb-2">Required Level: {{ armor.required_level }}</p>
                            <p class="text-yellow-400 font-bold text-lg">Price: ${{ armor.price }}</p>
                        </div>

                        <div class="flex gap-2">
                            <button v-if="user.weapon_id === armor.id" @click="deactivateItem(armor.pivot.id)"
                                class="mt-4 bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg transition">
                                Dectivate
                            </button>
                            <button v-else @click="activateItem(armor.pivot.id)"
                                class="mt-4 bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg transition">
                                Activate
                            </button>
                            <button @click="sellItem(armor.pivot.id)"
                                class="mt-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition">
                                Sell
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <h4>my armors</h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <div v-for="armor in props.armors" :key="armor.id"
                    class="bg-gray-800 text-gray-200 rounded-xl overflow-hidden shadow-lg flex flex-col">
                    <img :src="armor.avatar" :alt="armor.name" class="w-full h-40 object-cover" />

                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-semibold mb-2">{{ armor.name }}</h3>
                            <p class="text-gray-400 text-sm mb-1">Base Damage: {{ armor.base_damage }}</p>
                            <p class="text-gray-400 text-sm mb-1">Multiplier: {{ armor.multiplier }}</p>
                            <p class="text-gray-400 text-sm mb-2">Required Level: {{ armor.required_level }}</p>
                            <p class="text-yellow-400 font-bold text-lg">Price: ${{ armor.price }}</p>
                        </div>

                        <div class="flex gap-2">
                            <button v-if="user.armor_id === armor.id" @click="deactivateItem(armor.pivot.id)"
                                class="mt-4 bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg transition">
                                Deactivate
                            </button>
                            <button v-else @click="activateItem(armor.pivot.id)"
                                class="mt-4 bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg transition">
                                Activate
                            </button>
                            <button @click="sellItem(armor.pivot.id)"
                                class="mt-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition">
                                Sell
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>
