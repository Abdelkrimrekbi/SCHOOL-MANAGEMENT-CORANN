<script setup>
import InsideLayout from '@/Layouts/InsideLayout.vue';

const props = defineProps({
    departement:Object
});

</script>

<template>
    <InsideLayout>
            <div class="grid : grid-cols-3">
            <p class="text-xl font-semibold text-[#6F32BE]">
              Deparetement :
            </p>
            <p class="text-lg col-span-2">{{ departement.departement }}</p>
          </div>
          <div class="border-t border-gray-200 my-2" />

        <div class="grid : grid-cols-3">
            <p class="text-xl font-semibold text-[#6F32BE]">
              Description :
            </p>
            <p class="text-lg col-span-2">{{ departement.description }}</p>
          </div>
          <div class="border-t border-gray-200 my-2" />



       
    </InsideLayout>
</template>
<style >


</style>