<script setup>
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { Form } from '@inertiajs/vue3';
import { sell } from '@/routes/boat';

const props = defineProps(['data']);
console.log(props.data)
</script>

<template>
    <DefaultLayout>
        <h2 class="text-xl font-bold text-gray-100 mb-4">Docks</h2>

        <!-- Condição 1: Barco atual -->
        <div v-if="data.current_boat" class="bg-gray-800 text-gray-100 p-4 rounded shadow mb-6">
            <h3 class="font-semibold mb-2">Barco atual</h3>
            <p><strong>Droga:</strong> {{ data.current_boat.drug.name }}</p>
            <p><strong>Quantidade disponível:</strong> {{ data.owned_amount }}</p>

            <Form :action="sell(data.current_boat.id)" method="post" class="mt-2 flex gap-2 items-center">
                <input type="number" name="amount" min="1" :max="data.owned_amount" placeholder="Quantidade para vender"
                    class="border p-2 rounded w-32" />
                <button type="submit"
                    class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded font-semibold">
                    Vender
                </button>
            </Form>
        </div>

        <!-- Condição 2: Próximo barco -->
        <div v-else-if="data.next_boat" class="bg-gray-800 text-gray-100 p-4 rounded shadow mb-6">
            <h3 class="font-semibold mb-2">Próximo barco chegando</h3>
            <p><strong>Chega no dia:</strong> {{ data.next_boat.day }}</p>
            <p><strong>Droga que compra:</strong> {{ data.next_boat.drug.name }}</p>
        </div>

        <!-- Condição 3: Nenhum barco -->
        <div v-else class="bg-gray-800 text-gray-100 p-4 rounded shadow mb-6">
            <p>Nenhum barco disponível no momento.</p>
        </div>

        <!-- Lista de barcos passados -->
        <div class="bg-gray-800 text-gray-100 p-4 rounded shadow">
            <h3 class="font-semibold mb-2">Barcos passados</h3>
            <table class="w-full text-left text-gray-100 border-collapse">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="py-1 px-2">Dia</th>
                        <th class="py-1 px-2">Droga transportada</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(boat, index) in data.past_boats" :key="index" class="border-b border-gray-700">
                        <td class="py-1 px-2">{{ boat.day }}</td>
                        <td class="py-1 px-2">{{ boat.drug_name }}</td>
                    </tr>
                    <tr v-if="!data.past_boats || !data.past_boats.length">
                        <td colspan="2" class="py-2 px-2 text-gray-400 text-center">Nenhum barco passado</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </DefaultLayout>
</template>
