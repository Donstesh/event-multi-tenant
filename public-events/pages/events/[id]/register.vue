<template>
  <div class="p-6 max-w-xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Register for: {{ event?.title }}</h1>

    <form @submit.prevent="handleRegister" class="space-y-4">
      <div>
        <label class="block text-sm font-medium">Name</label>
        <input
          v-model="form.name"
          type="text"
          required
          class="w-full border border-gray-300 px-3 py-2 rounded"
        />
      </div>

      <div>
        <label class="block text-sm font-medium">Email</label>
        <input
          v-model="form.email"
          type="email"
          required
          class="w-full border border-gray-300 px-3 py-2 rounded"
        />
      </div>

      <div>
        <label class="block text-sm font-medium">Phone</label>
        <input
          v-model="form.phone"
          type="text"
          required
          class="w-full border border-gray-300 px-3 py-2 rounded"
        />
      </div>

      <button
        type="submit"
        class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700"
      >
        Register
      </button>

      <p v-if="error" class="text-red-600 mt-2 text-sm">
        {{ error }}
      </p>
    </form>
  </div>
</template>

<script setup>
const route = useRoute();
const router = useRouter();
const config = useRuntimeConfig();

const org = route.params.org;
const id = route.params.id;

const form = reactive({
  name: '',
  email: '',
  phone: ''
});

const error = ref(null);

// Fetch event info
const { data: event } = await useFetch(
  `${config.public.apiBaseUrl}/organizations/${org}/events/${id}`
);

// Handle form submission
const handleRegister = async () => {
  error.value = null;

  try {
    await $fetch(
      `${config.public.apiBaseUrl}/organizations/${org}/events/${id}/register`,
      {
        method: 'POST',
        body: form
      }
    );

    router.push(`/${org}/success`);
  } catch (err) {
    // You can check the status code if needed: err.response?.status
    error.value = err?.data?.message || 'Something went wrong.';
    router.push(`/${org}/failed`);
  }
};
</script>
