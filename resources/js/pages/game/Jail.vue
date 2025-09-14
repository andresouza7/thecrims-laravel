<script setup>
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import { release } from '@/routes/jail'
import { usePage, router, Form } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted, computed } from 'vue'

const page = usePage()

const user = computed(() => page.props.user)

const remaining = ref('')
const intervalId = ref(null)

const updateRemaining = () => {
  if (!user?.in_jail || !user?.jail_end_time) {
    remaining.value = ''
    return
  }
  const end = new Date(user.jail_end_time).getTime()
  const now = Date.now()
  const diff = end - now

  if (diff <= 0) {
    remaining.value = 'Agora!'
    clearInterval(intervalId.value)
    return
  }

  const h = Math.floor(diff / 1000 / 60 / 60)
  const m = Math.floor((diff / 1000 / 60) % 60)
  const s = Math.floor((diff / 1000) % 60)
  remaining.value = `${h}h ${m}m ${s}s`
}

onMounted(() => {
  if (user?.in_jail) {
    updateRemaining()
    intervalId.value = setInterval(updateRemaining, 1000)
  }
})

onUnmounted(() => {
  if (intervalId.value) clearInterval(intervalId.value)
})

const pay = () => {
  router.post(route('jail.bribe'))
}
</script>

<template>
  <DefaultLayout>
    <div class="max-w-lg mx-auto bg-gray-900 text-gray-200 rounded-2xl shadow-xl overflow-hidden">
      <img src="https://picsum.photos/800/200?blur" alt="Jail" class="w-full h-48 object-cover opacity-70" />
      <div class="p-6 text-center">
        <h2 class="text-3xl font-bold text-red-400 mb-6">🚔 Cadeia</h2>

        <div v-if="user?.in_jail">
          <p class="text-gray-300 mb-4">
            Você está preso.<br />
            Tempo restante: <span class="font-semibold text-yellow-400">{{ remaining }}</span>
          </p>

          <Form :action="release()" class="w-full">
            <button
              type="submit"
              class="bg-red-600 text-white py-2 px-4 w-full rounded-lg hover:bg-red-700 transition"
            >
              Pagar suborno (${{ user.jail_release_cost }})
            </button>
          </Form>
        </div>

        <div v-else>
          <p class="text-green-400 font-semibold text-lg mb-4">🎉 Você está livre! Aproveite sua liberdade.</p>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>
