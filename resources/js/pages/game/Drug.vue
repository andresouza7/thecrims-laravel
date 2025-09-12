<script setup>
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { sell } from '@/routes/drug';
import { Form } from '@inertiajs/vue3';

const props = defineProps(['drugs']);
</script>

<template>
  <DefaultLayout>
    <section class="max-w-4xl mx-auto mt-8">
      <h1 class="text-2xl font-bold mb-6">Drug Shop</h1>

      <div class="space-y-4">
        <div v-for="drug in drugs" :key="drug.id"
          class="flex items-center justify-between border rounded p-4 shadow-sm">
          <!-- Left: Avatar + Name -->
          <div class="flex items-center space-x-4">
            <img :src="drug.avatar || `https://picsum.photos/seed/${drug.id}/48`" alt=""
              class="w-12 h-12 rounded-full object-cover" />
            <div>
              <h2 class="font-medium">{{ drug.name }}</h2>
              <p class="text-gray-500">Price: ${{ drug.price }}</p>
            </div>
          </div>

          <!-- Right: Sell Form -->
          <Form :action="sell(drug.id)" method="post" class="flex items-center space-x-2">
            <div class="text-sm text-light">Estoque: {{ drug.pivot.amount }}</div>
            <input type="number" name="amount" min="1" placeholder="Qty" class="border rounded p-1 w-20 text-center" />
            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
              Sell
            </button>
          </Form>
        </div>
      </div>
    </section>
  </DefaultLayout>
</template>

<style scoped>
/* opcional: sombreamento leve para hover */
div.flex.items-center.justify-between:hover {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
</style>
