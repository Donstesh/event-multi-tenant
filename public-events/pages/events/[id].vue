<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold">{{ event?.title }}</h1>
    <p>{{ event?.description }}</p>
    <p><strong>Date:</strong> {{ event?.date }}</p>
    <p><strong>Venue:</strong> {{ event?.venue }}</p>
    <p><strong>Price:</strong> {{ event?.price }}</p>

    <NuxtLink :to="`/${org}/events/${id}/register`" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded">
      Register Now
    </NuxtLink>
  </div>
</template>

<script setup>
const route = useRoute();
const config = useRuntimeConfig();

const org = route.params.org;
const id = route.params.id;

const { data: event, error } = await useFetch(
  `${config.public.apiBaseUrl}/organizations/${org}/events/${id}`
);
</script>
