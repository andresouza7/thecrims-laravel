<script setup>
import DefaultLayout from '@/layouts/DefaultLayout.vue';

const props = defineProps({
    career: Object
});

console.log(props.career)
</script>

<template>
    <DefaultLayout>
        <h4>About Career: {{ career.name }}</h4>

        <div v-for="level in career.levels" :key="level.id" class="mb-8 border rounded p-4 bg-gray-50 dark:bg-gray-800">
            <h5 class="font-bold mb-2">Level {{ level.level }}: {{ level.name }}</h5>

            <!-- Requirements -->
            <div class="mb-2">
                <h6 class="font-semibold text-gray-700 dark:text-gray-200">Requirements</h6>
                <ul class="list-disc list-inside">
                    <li v-for="req in level.requirements" :key="req.id">
                        {{ req.name }}: {{ req.value }}
                    </li>
                    <li v-if="level.requirements.length === 0" class="italic text-gray-500">No requirements</li>
                </ul>
            </div>

            <!-- Rewards -->
            <div>
                <h6 class="font-semibold text-gray-700 dark:text-gray-200">Rewards</h6>
                <ul class="list-disc list-inside">
                    <li v-for="reward in level.rewards" :key="reward.id">
                        {{ reward.name }}: {{ reward.value }}
                        <span v-if="reward.related">
                            ({{ reward.related.name ?? 'no name' }})
                        </span>
                    </li>
                    <li v-if="level.rewards.length === 0" class="italic text-gray-500">No rewards</li>
                </ul>
            </div>
        </div>
    </DefaultLayout>
</template>
