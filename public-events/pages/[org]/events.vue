<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Events for {{ orgSlug }}</h1>

    <div v-if="pending">Loading...</div>
    <div v-else-if="error">Error loading events: {{ error.message }}</div>

    <ul v-else>
      <li v-for="event in events" :key="event.id" class="mb-4 p-4 border rounded">
        <h2 class="text-xl font-semibold">{{ event.title }}</h2>
        <p>{{ event.description }}</p>
        <NuxtLink :to="`/${orgSlug}/events/${event.id}`" class="text-blue-600 underline">View</NuxtLink>
      </li>
    </ul>
  </div>
</template>

<script setup>
const route = useRoute();
const config = useRuntimeConfig();
const orgSlug = route.params.org;

const { data: events, pending, error } = await useFetch(
  `${config.public.apiBaseUrl}/organizations/${orgSlug}/events`
);
</script>
