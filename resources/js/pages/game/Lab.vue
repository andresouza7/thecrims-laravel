<script setup>
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { Form } from '@inertiajs/vue3';
import { create, cancel, claim } from '@/routes/factory';

const props = defineProps(['lab', 'components']);

function formatDateTime(datetime) {
    if (!datetime) return '';
    return new Date(datetime).toLocaleString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}
</script>

<template>
    <DefaultLayout>
        <h2 class="text-xl font-bold mb-2 text-gray-100">Laboratório</h2>
        <p class="text-sm text-gray-400">Nível {{ lab.level }}</p>

        <!-- Produção atual -->
        <h3 class="mt-6 font-semibold text-lg text-gray-200">Produções ativas</h3>
        <div v-if="lab.productions && lab.productions.length" class="space-y-3 mt-3">
            <div v-for="production in lab.productions" :key="production.id"
                class="border border-gray-700 rounded-lg p-4 shadow-sm bg-gray-800 space-y-2">
                <div class="flex justify-between items-start">
                    <div class="text-gray-200 text-xs">
                        <p><strong>Droga:</strong> {{ production.drug.name }}</p>
                        <p><strong>Quantidade:</strong> {{ production.amount }}</p>
                        <p><strong>Termina em:</strong> {{ formatDateTime(production.ends_at) }}</p>
                    </div>

                    <Form v-if="production.progress < 100" :action="cancel(production.id)" method="delete">
                        <button type="submit" class="text-red-400 hover:text-red-600 text-sm font-semibold">
                            Cancelar ✖
                        </button>
                    </Form>
                </div>

                <div v-if="production.progress < 100">
                    <!-- progress bar -->
                    <div class="w-full bg-gray-700 rounded h-3 overflow-hidden mt-2">
                        <div class="h-3 bg-green-500 transition-all duration-300"
                            :style="{ width: production.progress + '%' }"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">
                        Restante: {{ production.remaining_time }}s
                    </p>
                </div>

                <Form v-else :action="claim(production.id)" method="post" class="mt-2">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white py-1 px-3 rounded text-sm">
                        Coletar
                    </button>
                </Form>
            </div>
        </div>
        <div v-else class="text-sm text-gray-400 mt-3">Nenhuma produção ativa</div>

        <!-- Criar nova produção -->
        <h3 class="mt-8 font-semibold text-lg text-gray-200">Nova produção</h3>
        <Form :action="create(lab.id)" method="post" class="mt-3 space-y-2">
            <select name="component_id" class="border border-gray-700 rounded p-2 w-full bg-gray-900 text-gray-100">
                <option disabled selected>Selecione um componente</option>
                <option v-for="component in components" :key="component.id" :value="component.id">
                    {{ component.name }} ({{ component.pivot.amount }})
                </option>
            </select>

            <input type="number" name="amount" min="1" placeholder="Quantidade"
                class="border border-gray-700 rounded p-2 w-full bg-gray-900 text-gray-100" />

            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded font-semibold w-full">
                Produzir drogas
            </button>
        </Form>
    </DefaultLayout>
</template>
