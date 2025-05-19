<template>
  <form @submit.prevent="register">
    <input v-model="name" placeholder="Name" />
    <input v-model="email" placeholder="Email" />
    <input v-model="password" type="password" placeholder="Password" />
    <input v-model="password_confirmation" type="password" placeholder="Confirm Password" />
    <button type="submit">Register</button>
    <p v-if="error" class="text-red-600">{{ error }}</p>
  </form>
</template>

<script setup>
const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const error = ref('')

const config = useRuntimeConfig()
const router = useRouter()

const register = async () => {
  try {
    await $fetch(`${config.public.apiBaseUrl}/sanctum/csrf-cookie`, { credentials: 'include' })
    await $fetch(`${config.public.apiBaseUrl}/api/register`, {
      method: 'POST',
      body: {
        name: name.value,
        email: email.value,
        password: password.value,
        password_confirmation: password_confirmation.value
      },
      credentials: 'include'
    })
    router.push('/admin/login')
  } catch (e) {
    error.value = 'Registration failed'
  }
}
</script>
