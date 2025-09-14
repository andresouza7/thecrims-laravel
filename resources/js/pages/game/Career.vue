<script setup>
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router } from '@inertiajs/vue3';
import { select } from '@/routes/career';

const props = defineProps({
    careers: Array,
});

const submit = (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    router.post(select(), formData);
};
</script>

<template>
    <DefaultLayout>
        <div class="max-w-md mx-auto bg-gray-800 text-gray-100 p-6 rounded-xl shadow-lg">
            <h4 class="text-2xl font-bold mb-4">Escolher Carreira</h4>
            <p class="mb-6 text-gray-400">Please choose a career path to start the game.</p>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label for="career" class="block mb-2 text-sm font-medium text-gray-300">
                        Career
                    </label>
                    <select id="career" name="career_id"
                        class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-white focus:ring focus:ring-blue-500"
                        required>
                        <option value="" disabled selected>-- Select a career --</option>
                        <option v-for="career in props.careers" :key="career.id" :value="career.id">
                            {{ career.name }}
                        </option>
                    </select>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition">
                    Confirm
                </button>
            </form>
        </div>
    </DefaultLayout>
</template>
