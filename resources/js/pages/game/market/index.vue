<script setup>
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { Link } from '@inertiajs/vue3';
import { armors, weapons, items, components } from '@/routes/market';

</script>

<template>
    <DefaultLayout>
        <div class="flex flex-col gap-4 p-2">
            <h4>Market index</h4>

            <p>some content</p>

            <div class="p-2 flex gap-2">
                <Link :href="components()">Drug Components</Link>
                <Link :href="armors()">Armors</Link>
                <Link :href="weapons()">Weapons</Link>
                <Link :href="items()">Items</Link>
            </div>
        </div>
    </DefaultLayout>
</template>