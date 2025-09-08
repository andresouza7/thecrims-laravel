<script setup>
import { Form } from '@inertiajs/vue3'
import { deposit } from '@/routes/bank';
import { withdraw } from '@/routes/bank';
import DefaultLayout from '@/layouts/DefaultLayout.vue';

</script>

<template>
    <DefaultLayout>
        <p>bank page</p>

        <div class="mt-6 flex gap-4">
            <Form :action="deposit()" method="post" class="flex-1">
                <input type="number" name="amount" class="border p-2 w-full" />
                <button type="submit" class="bg-blue-500 text-white py-1 px-4 rounded mt-2 w-full hover:bg-blue-600">
                    Deposit
                </button>
            </Form>

            <Form :action="withdraw()" method="post" class="flex-1">
                <input type="number" name="amount" class="border p-2 w-full" />
                <button type="submit"
                    class="bg-yellow-500 text-white py-1 px-4 rounded mt-2 w-full hover:bg-yellow-600">
                    Withdraw
                </button>
            </Form>
        </div>
    </DefaultLayout>
</template>