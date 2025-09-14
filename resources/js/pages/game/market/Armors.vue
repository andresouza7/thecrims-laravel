<script setup>
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router } from '@inertiajs/vue3';
import { buy } from '@/routes/market';

const props = defineProps({
    armors: Array
});

const buyArmor = (armorId) => {
    router.post(buy(armorId));
};
</script>

<template>
    <DefaultLayout>
        <div class="max-w-7xl mx-auto py-6 px-4">
            <h2 class="text-3xl font-bold text-white mb-6">Armor Shop</h2>

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

                        <button @click="buyArmor(armor.id)"
                            class="mt-4 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition">
                            Buy
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>
