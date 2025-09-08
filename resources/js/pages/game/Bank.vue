<script setup>
import { Form, usePage } from '@inertiajs/vue3';
import { deposit, withdraw } from '@/routes/bank';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { computed } from 'vue';

const page = usePage()

const user = computed(() => page.props.user)
let amount = 0;
</script>

<template>
    <DefaultLayout>
        <section class="max-w-3xl mx-auto mt-12 p-6 bg-gray-900 rounded shadow-lg text-white">
            <h1 class="text-2xl font-bold mb-4 text-center">🏦 Bank</h1>

            <p class="mb-6 text-center text-gray-300">
                Deposit or withdraw money from your account. <br>
                Enter an amount and choose an operation. <br>
                <strong>Tip:</strong> Enter <span class="font-bold">0</span> to deposit or withdraw <em>all</em>
                available funds.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 items-center">
                <!-- Amount input -->
                <input type="number" v-model.number="amount" min="0" placeholder="Enter amount"
                    class="flex-1 p-2 rounded border border-gray-500 bg-gray-800 text-white text-center focus:outline-none focus:ring-2 focus:ring-blue-400" />

                <!-- Deposit -->
                <Form :action="deposit()" method="post" class="flex-1"  :transform="data => ({ amount: amount === 0 ? user.cash : amount})">
                    <input type="hidden" name="amount" :value="amount" />
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 py-2 rounded font-semibold transition">
                        Deposit
                    </button>
                </Form>

                <!-- Withdraw -->
                <Form :action="withdraw()" method="post" class="flex-1" :transform="data => ({ amount: amount === 0 ? user.bank : amount})">
                    <input type="hidden" name="amount" :value="amount" />
                    <button type="submit"
                        class="w-full bg-yellow-600 hover:bg-yellow-700 py-2 rounded font-semibold transition">
                        Withdraw
                    </button>
                </Form>
            </div>
        </section>
    </DefaultLayout>
</template>

<style scoped>
button {
    transition: all 0.2s ease-in-out;
}
</style>
