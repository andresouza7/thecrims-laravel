<script setup>
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { fight } from '@/routes/nightclub';
import { Form } from '@inertiajs/vue3';

const props = defineProps(['foe'])

</script>

<template>
    <DefaultLayout>
        <h4>Nightclub</h4>

        <div v-if="foe">
            <p>{{ foe.name }}</p>
            <p>power: {{ foe.assault_power }}</p>

            <Form :action="fight(foe.id)" method="post" class="w-full" :options="{ preserveScroll: true }">
                <button type="submit" class="bg-red-500 text-white py-1 w-full rounded hover:bg-red-600">Fight</button>
            </Form>
        </div>
    </DefaultLayout>
</template>