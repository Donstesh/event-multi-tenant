<template>
  <div class="d-flex vh-100 justify-content-center align-items-center bg-light">
    <form @submit.prevent="login" class="p-4 bg-white rounded shadow" style="width: 320px;">
      <div class="mb-3">
        <input
          v-model="email"
          type="email"
          class="form-control"
          placeholder="Email"
          required
          autofocus
        />
      </div>
      <div class="mb-3">
        <input
          v-model="password"
          type="password"
          class="form-control"
          placeholder="Password"
          required
        />
      </div>
      <div class="mb-3 form-check">
        <input
          v-model="rememberMe"
          type="checkbox"
          class="form-check-input"
          id="rememberMe"
        />
        <label class="form-check-label" for="rememberMe">Remember Me</label>
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
      <p v-if="error" class="text-danger mt-3 text-center">{{ error }}</p>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const email = ref('')
const password = ref('')
const rememberMe = ref(false)
const error = ref('')

const config = useRuntimeConfig()
const router = useRouter()

const login = async () => {
  error.value = ''

  try {
    await $fetch(`${config.public.apiBaseUrl}/sanctum/csrf-cookie`, { credentials: 'include' })

    await $fetch(`${config.public.apiBaseUrl}/api/login`, {
      method: 'POST',
      body: {
        email: email.value,
        password: password.value,
        remember: rememberMe.value,
      },
      credentials: 'include',
    })

    router.push('/admin/dashboard')
  } catch (e) {
    error.value = 'Login failed. Please check your credentials and try again.'
  }
}
</script>
