<script setup>
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { round } from '@/routes/admin';
import { Form } from '@inertiajs/vue3';

</script>

<template>
    <DefaultLayout>
        <h4>Admin page</h4>

        <p>some content</p>

        <Form :action="round()" method="post" class="w-full" :options="{ preserveScroll: true }">
            <button type="submit" class="bg-red-500 text-white py-1 w-full rounded hover:bg-red-600">Iniciar
                Round</button>
        </Form>
    </DefaultLayout>
</template>