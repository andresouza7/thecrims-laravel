<script setup>
import { Form, usePage } from '@inertiajs/vue3'
import { buy, sell, collect } from '@/routes/hooker';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { computed } from 'vue';
defineProps(['hookers', 'owned']);

const page = usePage()

const user = computed(() => page.props.user)

</script>

<template>
    <DefaultLayout>
        <p class="text-xl font-semibold mb-4">Available income</p>
        <div>{{ user.hooker_income }}</div>
        <Form :action="collect()" method="post" class="w-full" :options="{ preserveScroll: true }">
            <button type="submit" class="bg-red-500 text-white py-1 w-full rounded hover:bg-red-600">Coletar</button>
        </Form>

        <p class="text-xl font-semibold mb-4">Owned Hookers</p>

        <!-- Owned items grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-8">
            <div v-for="owned in owned" :key="owned.id" class="border rounded shadow p-4 flex flex-col items-center">

                <!-- <a :href="owned.avatar" target="_blank">
                    <img :src="owned.avatar" alt="" class="w-24 h-24 object-cover rounded mb-2" />
                </a> -->
                <h3 class="font-medium mb-1">{{ owned.name }}</h3>
                <p class="text-sm text-gray-600 mb-2">Owned: {{ owned.pivot?.amount }}</p>
                <p class="text-sm text-gray-600 mb-2">Earning: {{ owned.pivot?.amount * owned.income }}</p>

                <Form :action="sell(owned.id)" method="post" class="w-full" :options="{ preserveScroll: true }">
                    <input type="number" name="amount" min="1" placeholder="Quantity" class="border p-2 w-full mb-2" />
                    <button type="submit"
                        class="bg-red-500 text-white py-1 w-full rounded hover:bg-red-600">Sell</button>
                </Form>
            </div>
        </div>

        <p class="text-xl font-semibold mb-4">Available Hookers</p>

        <!-- Available hookers grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <div v-for="hooker in hookers" :key="hooker.id"
                class="border rounded shadow p-4 flex flex-col items-center">

                <!-- <a :href="hooker.avatar" target="_blank">
                    <img :src="hooker.avatar" alt="" class="w-24 h-24 object-cover rounded mb-2" />
                </a> -->
                <h3 class="font-medium mb-1">{{ hooker.name }}</h3>
                <p class="text-sm text-gray-600 mb-2">Price: {{ hooker.price }}</p>
                <p class="text-sm text-gray-600 mb-2">Income: {{ hooker.income }}</p>

                <Form :action="buy(hooker.id)" method="post" class="w-full">
                    <input type="number" name="amount" min="1" placeholder="Quantity" class="border p-2 w-full mb-2" />
                    <button type="submit"
                        class="bg-green-500 text-white py-1 w-full rounded hover:bg-green-600">Buy</button>
                </Form>
            </div>
        </div>
    </DefaultLayout>
</template>
