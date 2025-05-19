<template>
  <div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>

    <div v-if="admin">
      <p class="mb-4">Welcome, <strong>{{ admin.name }}</strong> ({{ admin.email }})</p>
      <button @click="logout" class="bg-red-500 text-white px-4 py-2 rounded">Logout</button>
    </div>

    <p v-else class="text-gray-600">Loading admin info...</p>
  </div>
</template>

<script setup>
const admin = ref(null)
const config = useRuntimeConfig()
const router = useRouter()

onMounted(async () => {
  try {
    const response = await $fetch(`${config.public.apiBaseUrl}/api/me`, {
      credentials: 'include'
    })
    admin.value = response
  } catch (e) {
    // If not authenticated, redirect to login
    router.push('/admin/login')
  }
})

const logout = async () => {
  await $fetch(`${config.public.apiBaseUrl}/api/logout`, {
    method: 'POST',
    credentials: 'include'
  })
  router.push('/admin/login')
}
</script>
