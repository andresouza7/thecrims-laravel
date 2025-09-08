<script setup>
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { Form } from '@inertiajs/vue3';
import { buy, sell, upgrade } from '@/routes/factory';

const props = defineProps(['factories', 'owned']);
console.log(props.owned)
</script>

<template>
    <DefaultLayout>
        <section class="max-w-4xl mx-auto mt-8 space-y-12">

            <!-- Owned Factories -->
            <div>
                <h2 class="text-3xl font-bold mb-4 text-white">Your Factories</h2>
                <ul class="space-y-4">
                    <li v-for="owned in owned" :key="owned.id"
                        class="flex items-center justify-between p-4 border rounded shadow bg-gray-900 text-white">

                        <!-- Left: image + info -->
                        <div class="flex items-center space-x-4">
                            <img :src="owned.avatar || `https://picsum.photos/seed/${owned.id}/48`" alt=""
                                class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <h3 class="font-medium">{{ owned.name }}</h3>
                                <p class="text-gray-300">Level: {{ owned.pivot.level }}</p>
                            </div>
                        </div>

                        <!-- Right: sell button -->
                        <div class="flex gap-2">
                            <Form :action="upgrade(owned.pivot.id)" method="post">
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded font-semibold">
                                    Upgrade
                                </button>
                            </Form>
                            <Form :action="sell(owned.pivot.id)" method="post">
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded font-semibold">
                                    Sell
                                </button>
                            </Form>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Available Factories -->
            <div>
                <h2 class="text-3xl font-bold mb-4 text-white">Factory Shop</h2>
                <ul class="space-y-4">
                    <li v-for="factory in factories" :key="factory.id"
                        class="flex items-center justify-between p-4 border rounded shadow bg-gray-900 text-white">

                        <!-- Left: image + info -->
                        <div class="flex items-center space-x-4">
                            <img :src="factory.avatar || `https://picsum.photos/seed/${factory.id}/48`" alt=""
                                class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <h3 class="font-medium">{{ factory.name }}</h3>
                                <p class="text-gray-300">Price: ${{ factory.price }}</p>
                            </div>
                        </div>

                        <!-- Right: buy button -->
                        <Form :action="buy(factory.id)" method="post">
                            <button type="submit"
                                class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded font-semibold">
                                Buy
                            </button>
                        </Form>

                    </li>
                </ul>
            </div>

        </section>
    </DefaultLayout>
</template>
