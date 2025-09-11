<script setup>
import DefaultLayout from '@/layouts/DefaultLayout.vue';

const props = defineProps(['lab'])

console.log(props.lab)
</script>

<template>
    <DefaultLayout>

        <p>Lab</p>
        <p>level {{ lab.level }}</p>

    </DefaultLayout>
</template>