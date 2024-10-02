<script setup>
import InsideLayout from "../../Layouts/InsideLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import Dropdown from "primevue/dropdown";
import Dialog from "primevue/dialog";
import { ref } from "vue";
import { useI18n } from "vue-i18n";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import Vue3EasyDataTable from "vue3-easy-data-table";
import "vue3-easy-data-table/dist/style.css";
const { t } = useI18n();

const props = defineProps({
 
});
</script>
<template>
  <div class="card flex justify-content-center m-2">
    <Dialog v-model:visible="props.isVisible" modal header="Exams Results" :style="{ width: '50rem' }"
      :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
      <div>
        <label for="date" class="text-x font-semibold text-[#FFFFF]">
          Students</label>
      </div>
      <!--Data Table Here-->
      <div class="m-4" :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
        <Vue3EasyDataTable :items="props.students" border-cell theme-color="#6F32BE" table-class-name="customize-table">
          <!--Control Header-->
          <template #header-id="header">
            <div style="
                display: flex;
                justify-content: center;
                align-items: center;
              ">
              <p class="text-white text-[1.2rem] py-2">ID</p>
            </div>
          </template>

          <template #header-name="header">
            <div style="
                display: flex;
                justify-content: center;
                align-items: center;
              ">
              <p class="text-white text-[1.2rem] py-2">Name</p>
            </div>
          </template>

          <template #header-note="header">
            <div style="
                display: flex;
                justify-content: center;
                align-items: center;
              ">
              <p class="text-white text-[1.2rem] py-2">Note</p>
            </div>
          </template>

          <template #item-id="item">
            <div>
              {{ item }}
            </div>
          </template>
        </Vue3EasyDataTable>
      </div>
    </Dialog>
  </div>
</template>