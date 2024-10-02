<script setup>
import { Link } from "@inertiajs/vue3";
const props = defineProps({
  pagination: Object,
});
</script>
<template>
  <nav class="relative flex justify-end bg-white border-1">
    <template v-for="link in pagination.links" :key="link.label" class="">
      <Link
        :href="link.url ?? ''"
        v-html="link.label"
        class="mt-1 mb-1 flex items-center justify-center px-3 py-2 text-sm rounded-lg text-gray-600"
        :class="{ 'bg-gray-200 ': link.active, '!text-gray-300': !link.url }"
      />
    </template>
  </nav>
</template>
