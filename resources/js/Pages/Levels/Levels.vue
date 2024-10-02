<script setup>
import InsideLayout from '../../Layouts/InsideLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import Dialog from 'primevue/dialog';
import { ref } from "vue";
import Toast from 'primevue/toast';
import { useToast } from "primevue/usetoast";
import { useI18n } from 'vue-i18n';
import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
const { t } = useI18n()
//Get Data From Controller: 
const props = defineProps({
  levels: {
    type: Array,
    required: true
  }
})
//DataTable items :: 
const headers = [
  { text: t('levelTable.levelName'), value: "level", sortable: true },
  { text: t('levelTable.description'), value: "description", sortable: true, },
  { text: t('levelTable.price'), value: "price", sortable: true, },
  { text: t('levelTable.operations'), value: "operations" },
];
const searchField = ["level", "id"];
const searchValue = ref();

//Modal Variables & control
const addLevelVisible = ref(false);
const showlLevelVisible = ref(false);
const editLevelVisible = ref(false);
const areYouSure = ref(false)
let itemToShow;
const showlLevel = (item) => {
  itemToShow = item;
  showlLevelVisible.value = true;
}
let itemToEdit;
const editLevel = (item) => {
  itemToEdit = item.id
  levelForm.level = item.level;
  levelForm.price = item.price;
  levelForm.description = item.description;
  editLevelVisible.value = true;
}

let itemToDelete;
const areYouSureItem = (item) => {
  itemToDelete = item;
  areYouSure.value = true;
}
const toast = useToast();

const levelForm = useForm({
  level: "",
  price: "",
  description: ""
})
const resertLevelForm = () => {
  levelForm.level = "";
  levelForm.price = "";
  levelForm.description = "";
}
//Open ADD FILIERE MODAL :: 
const openAddLevelModal = () => {
  levelForm.reset
  resertLevelForm();
  addLevelVisible.value = true;
}

const isButtonClicked = ref(false);
const addLevel = () => {
  if (levelForm.level === "" || levelForm.price === "") {
    toast.add({ severity: 'error', summary: t('toast.error'), detail: t('toast.errorMessage'), group: 'bc', life: 3000 });
    return
  } else {
    isButtonClicked.value = true;
    router.post('/levels', levelForm, {
      preserveScroll: true,
      preserveState: true,
      onProgress: page => {
      },
      onSuccess: page => {
        addLevelVisible.value = false
        levelForm.reset;
        toast.add({ severity: 'success', summary: t('toast.success'), detail: t('toast.successMessages'), group: 'bc', life: 3000 });
        isButtonClicked.value = false;
      },
      onError: error => {
        console.log("Error: ", error);
      }
    });
  }
}

//Edit Level Function
const editLevelFun = () => {
  if (levelForm.level === "" || levelForm.price === "") {
    toast.add({ severity: 'error', summary: t('toast.error'), detail: t('toast.errorMessage'), group: 'bc', life: 3000 });
    return
  } else {
    levelForm._method = 'PUT';
    router.post('/levels/' + itemToEdit, levelForm, {
      preserveScroll: true,
      preserveState: true,
      onProgress: page => {
      },
      onSuccess: page => {
        editLevelVisible.value = false;
        props.levels = props.levels
        levelForm.reset;
        toast.add({ severity: 'success', summary: t('toast.success'), detail: t('toast.successMessages'), group: 'bc', life: 3000 });
      },
      onError: error => {
        console.log("Error: ", error);
      }
    });
  }
}
//Delet Level Function  
const deleteLevel = (item) => {
  router.delete(`/levels/${item.id}`, {
    preserveScroll: true,
    preserveState: true,
    onProgress: page => {
    },
    onSuccess: page => {
      areYouSure.value = false
      toast.add({ severity: 'success', summary: t('toast.success'), detail: t('toast.successMessages'), group: 'bc', life: 3000 });
    },
    onError: error => {
      console.log("Error: ", error);
    }
  })
}
</script>

<template>
  <Head :title="$t('asidebar.levels')" />

  <InsideLayout>
    <div class="bg-white p-6 m-4 rounded-xl grid xl:grid-cols-4">
      <div class="xl:col-span-2 mx-2">
        <input type="text" v-model="searchValue" :placeholder="$t('search.search')"
          class="h-14 py-6 w-full rounded-xl border border-gray-200" />
      </div>
      <div class="flex justify-start lg:m-2 xl:m-0">
        <button label="Show" @click="openAddLevelModal"
          class="flex items-center text-lg bg-indigo-500 shadow-lg shadow-indigo-500/50 hover:bg-indigo-400 text-white  p-2 rounded-xl"><svg
            width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
            stroke="#ffffff">
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
            <g id="SVGRepo_iconCarrier">
              <circle cx="12" cy="12" r="10" stroke="#ffffff" stroke-width="1.5"></circle>
              <path d="M15 12L12 12M12 12L9 12M12 12L12 9M12 12L12 15" stroke="#ffffff" stroke-width="1.5"
                stroke-linecap="round"></path>
            </g>
          </svg><span class="mx-2">{{ $t('buttons.addLevel') }}</span></button>
      </div>

    </div>
    <!--Data Table Here-->
    <div class="m-4" :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
      <Vue3EasyDataTable :headers="headers" :items="levels" :search-field="searchField" :search-value="searchValue"
        border-cell theme-color="#6F32BE" table-class-name="customize-table">
        <!--Control Header-->
        <template #header-price="header">
          <p class="text-white text-[1.2rem] py-2">{{ $t('levelTable.price') }}</p>
        </template>
        <template #header-level="header">
          <p class="text-white text-[1.2rem] py-2">{{ $t('levelTable.levelName') }}</p>
        </template>
        <template #header-description="header">
          <p class="text-white text-[1.2rem] py-2">{{ $t('levelTable.description') }}</p>
        </template>
        <template #header-operations="header">
          <p class="text-white text-[1.2rem] py-2">{{ $t('levelTable.operations') }}</p>
        </template>

        <template #item-id="item">
          <div style=" display: flex; justify-content: center; align-items: center;">
            <p class="text-[1rem] flex font-semibold  py-3"
              :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">{{ item.id }}</p>
          </div>
        </template>
        <template #item-level="item">
          <div style=" display: flex; justify-content: center; align-items: center;">
            <p class="text-[1rem] flex font-semibold  py-3"
              :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">{{ item.level }}</p>
          </div>
        </template>
        <template #item-price="item">
          <div style=" display: flex; justify-content: center; align-items: center;">
            <p class="text-[1rem] flex font-semibold  py-3"
              :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">{{ item.price }}</p>
          </div>
        </template>
        <template #item-description="item">
          <div style=" display: flex; justify-content: center; align-items: center;">
            <p class="text-[1rem] flex font-semibold  py-3"
              :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'"> {{ item.description ?
                item.description.substring(0, 80) + "..." : 'No description available' }}
            </p>
          </div>
        </template>
        <template #item-created_at="item">
          <p class="text-[1rem] flex font-semibold  py-3"
            :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">{{ item.created_at }}</p>
        </template>
        <template #item-operations="item" :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
          <div class="flex justify-center">
            <button @click="showlLevel(item)">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-presentation-analytics"
                width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#6366F1" fill="none"
                stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M9 12v-4" />
                <path d="M15 12v-2" />
                <path d="M12 12v-1" />
                <path d="M3 4h18" />
                <path d="M4 4v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-10" />
                <path d="M12 16v4" />
                <path d="M9 20h6" />
              </svg>
            </button>
            <button @click="editLevel(item)" class="mx-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="36" height="36"
                viewBox="0 0 24 24" stroke-width="1.5" stroke="#F59E0B" fill="none" stroke-linecap="round"
                stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                <path d="M16 5l3 3" />
              </svg>
            </button>
            <button @click="areYouSureItem(item)">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="36" height="36"
                viewBox="0 0 24 24" stroke-width="1.5" stroke="#EF4444" fill="none" stroke-linecap="round"
                stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M4 7l16 0" />
                <path d="M10 11l0 6" />
                <path d="M14 11l0 6" />
                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
              </svg>
            </button>
          </div>
        </template>
      </Vue3EasyDataTable>
    </div>
    <!--Modals-->
    <!--ADD LEVEL MODAL-->
    <Toast position="bottom-center" group="bc" />
    <div class="card flex justify-content-center m-2">
      <Dialog v-model:visible="addLevelVisible" modal :header="$t('buttons.addLevel')" :style="{ width: '50rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
          <div class="m-10">
            <form class="grid " @submit.prevent="addLevel">
              <label for="levelName" class="mt-2 text-gray-500">{{ $t('addLevelForm.level') }}*</label>
              <input type="text" class="w-full h-14 rounded-xl border border-gray-200 my-2"
                :placeholder="$t('addLevelForm.level')" id="levelName" v-model="levelForm.level" />

              <label for="price" class=" mt-2 text-gray-500">{{ $t('addLevelForm.price') }}*</label>
              <input type="text" class="w-full h-14 rounded-xl border border-gray-200 my-2"
                :placeholder="$t('addLevelForm.price')" id="price" v-model="levelForm.price" />

              <label for="description" class="mt-2 text-gray-500">{{ $t('addLevelForm.description')
              }}*</label>
              <textarea class="w-full rounded-xl border border-gray-200 my-2"
                :placeholder="$t('addLevelForm.description')" id="description" rows="10"
                v-model="levelForm.description"></textarea>

              <div class="flex justify-end mt-5">
                <button :disabled="isButtonClicked"
                  class="flex items-center text-lg bg-indigo-500 shadow-lg shadow-indigo-500/50 hover:bg-indigo-400 text-white  p-2 rounded-xl"><svg
                    width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    stroke="#ffffff">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                      <circle cx="12" cy="12" r="10" stroke="#ffffff" stroke-width="1.5"></circle>
                      <path d="M15 12L12 12M12 12L9 12M12 12L12 9M12 12L12 15" stroke="#ffffff" stroke-width="1.5"
                        stroke-linecap="round"></path>
                    </g>
                  </svg><span class="mx-2">{{ $t('buttons.addLevel') }}</span></button>
              </div>
            </form>
          </div>
        </div>
      </Dialog>
    </div>

    <!--SHOW LEVEL MODAL-->
    <div class="card flex justify-content-center m-2">
      <Dialog v-model:visible="showlLevelVisible" modal :header="$t('levelTable.level')" :style="{ width: '40rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
          <label for="amount" class="text-x font-semibold text-[#6F32BE]">{{ $t('levelTable.levelName')
          }}*</label>
          <input type="text" class="w-full h-10 rounded-xl border border-gray-200 my-2" :placeholder="itemToShow.level"
            :disabled="inputDisabled" />

          <label for="amount" class="text-x font-semibold text-[#6F32BE]">{{ $t('levelTable.levelName')
          }}*</label>
          <input type="text" class="w-full h-10 rounded-xl border border-gray-200 my-2"
            :placeholder="itemToShow.description" :disabled="inputDisabled" />

          <label for="amount" class="text-x font-semibold text-[#6F32BE]">{{ $t('levelTable.levelName')
          }}*</label>
          <input type="text" class="w-full h-10 rounded-xl border border-gray-200 my-2" :placeholder="itemToShow.price"
            :disabled="inputDisabled" />
        </div>
      </Dialog>
    </div>


    <!--EDit LEVEL MODAL-->
    <div class="card flex justify-content-center m-2">
      <Dialog v-model:visible="editLevelVisible" modal :header="$t('buttons.editLevel')" :style="{ width: '50rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
          <div class="m-10">
            <form class="grid" @submit.prevent="editLevelFun">
              <label for="levelName" class="mt-2 text-gray-500">{{ $t('addLevelForm.level') }}*</label>
              <input type="text" class="w-full h-14 rounded-xl border border-gray-200 my-2"
                :placeholder="$t('addLevelForm.level')" id="levelName" v-model="levelForm.level" />

              <label for="price" class=" mt-2 text-gray-500">{{ $t('addLevelForm.price') }}*</label>
              <input type="text" class="w-full h-14 rounded-xl border border-gray-200 my-2"
                :placeholder="$t('addLevelForm.price')" id="price" v-model="levelForm.price" />

              <label for="description" class="mt-2 text-gray-500">{{ $t('addLevelForm.description')
              }}*</label>
              <textarea type="text" class="w-full rounded-xl border border-gray-200 my-2"
                :placeholder="$t('addLevelForm.description')" id="description" rows="10"
                v-model="levelForm.description"></textarea>

              <div class="flex justify-end mt-5">
                <button
                  class="flex items-center text-lg bg-yellow-500 shadow-lg shadow-yellow-500/50 hover:bg-yellow-400   text-white  p-2 rounded-xl"><svg
                    width="30px" height="30px" viewBox="0 -0.5 25 25" fill="none" xmlns="http://www.w3.org/2000/svg"
                    stroke="">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                      <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M17.7 5.12758L19.266 6.37458C19.4172 6.51691 19.5025 6.71571 19.5013 6.92339C19.5002 7.13106 19.4128 7.32892 19.26 7.46958L18.07 8.89358L14.021 13.7226C13.9501 13.8037 13.8558 13.8607 13.751 13.8856L11.651 14.3616C11.3755 14.3754 11.1356 14.1751 11.1 13.9016V11.7436C11.1071 11.6395 11.149 11.5409 11.219 11.4636L15.193 6.97058L16.557 5.34158C16.8268 4.98786 17.3204 4.89545 17.7 5.12758Z"
                        stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path
                        d="M12.033 7.61865C12.4472 7.61865 12.783 7.28287 12.783 6.86865C12.783 6.45444 12.4472 6.11865 12.033 6.11865V7.61865ZM9.23301 6.86865V6.11865L9.23121 6.11865L9.23301 6.86865ZM5.50001 10.6187H6.25001L6.25001 10.617L5.50001 10.6187ZM5.50001 16.2437L6.25001 16.2453V16.2437H5.50001ZM9.23301 19.9937L9.23121 20.7437H9.23301V19.9937ZM14.833 19.9937V20.7437L14.8348 20.7437L14.833 19.9937ZM18.566 16.2437H17.816L17.816 16.2453L18.566 16.2437ZM19.316 12.4937C19.316 12.0794 18.9802 11.7437 18.566 11.7437C18.1518 11.7437 17.816 12.0794 17.816 12.4937H19.316ZM15.8863 6.68446C15.7282 6.30159 15.2897 6.11934 14.9068 6.2774C14.5239 6.43546 14.3417 6.87397 14.4998 7.25684L15.8863 6.68446ZM18.2319 9.62197C18.6363 9.53257 18.8917 9.13222 18.8023 8.72777C18.7129 8.32332 18.3126 8.06792 17.9081 8.15733L18.2319 9.62197ZM8.30001 16.4317C7.8858 16.4317 7.55001 16.7674 7.55001 17.1817C7.55001 17.5959 7.8858 17.9317 8.30001 17.9317V16.4317ZM15.767 17.9317C16.1812 17.9317 16.517 17.5959 16.517 17.1817C16.517 16.7674 16.1812 16.4317 15.767 16.4317V17.9317ZM12.033 6.11865H9.23301V7.61865H12.033V6.11865ZM9.23121 6.11865C6.75081 6.12461 4.7447 8.13986 4.75001 10.6203L6.25001 10.617C6.24647 8.96492 7.58269 7.62262 9.23481 7.61865L9.23121 6.11865ZM4.75001 10.6187V16.2437H6.25001V10.6187H4.75001ZM4.75001 16.242C4.7447 18.7224 6.75081 20.7377 9.23121 20.7437L9.23481 19.2437C7.58269 19.2397 6.24647 17.8974 6.25001 16.2453L4.75001 16.242ZM9.23301 20.7437H14.833V19.2437H9.23301V20.7437ZM14.8348 20.7437C17.3152 20.7377 19.3213 18.7224 19.316 16.242L17.816 16.2453C17.8195 17.8974 16.4833 19.2397 14.8312 19.2437L14.8348 20.7437ZM19.316 16.2437V12.4937H17.816V16.2437H19.316ZM14.4998 7.25684C14.6947 7.72897 15.0923 8.39815 15.6866 8.91521C16.2944 9.44412 17.1679 9.85718 18.2319 9.62197L17.9081 8.15733C17.4431 8.26012 17.0391 8.10369 16.6712 7.7836C16.2897 7.45165 16.0134 6.99233 15.8863 6.68446L14.4998 7.25684ZM8.30001 17.9317H15.767V16.4317H8.30001V17.9317Z"
                        fill="#ffffff"></path>
                    </g>
                  </svg><span class="mx-2">{{ $t('buttons.editLevel') }}</span></button>
              </div>
            </form>
          </div>
        </div>
      </Dialog>
    </div>


    <!--ARE YOU SURE ??-->
    <div class="card flex justify-content-center m-2">
      <Dialog v-model:visible="areYouSure" modal :header="$t('buttons.removeLevel')" :style="{ width: '50rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div class="flex justify-center">
          <svg width="100x" height="100px" viewBox="0 0 1024 1024" class="icon" version="1.1"
            xmlns="http://www.w3.org/2000/svg" fill="">
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
            <g id="SVGRepo_iconCarrier">
              <path
                d="M905.92 237.76a32 32 0 0 0-52.48 36.48A416 416 0 1 1 96 512a418.56 418.56 0 0 1 297.28-398.72 32 32 0 1 0-18.24-61.44A480 480 0 1 0 992 512a477.12 477.12 0 0 0-86.08-274.24z"
                fill="#E10000"></path>
              <path
                d="M630.72 113.28A413.76 413.76 0 0 1 768 185.28a32 32 0 0 0 39.68-50.24 476.8 476.8 0 0 0-160-83.2 32 32 0 0 0-18.24 61.44zM489.28 86.72a36.8 36.8 0 0 0 10.56 6.72 30.08 30.08 0 0 0 24.32 0 37.12 37.12 0 0 0 10.56-6.72A32 32 0 0 0 544 64a33.6 33.6 0 0 0-9.28-22.72A32 32 0 0 0 505.6 32a20.8 20.8 0 0 0-5.76 1.92 23.68 23.68 0 0 0-5.76 2.88l-4.8 3.84a32 32 0 0 0-6.72 10.56A32 32 0 0 0 480 64a32 32 0 0 0 2.56 12.16 37.12 37.12 0 0 0 6.72 10.56zM726.72 297.28a32 32 0 0 0-45.12 0l-169.6 169.6-169.28-169.6A32 32 0 0 0 297.6 342.4l169.28 169.6-169.6 169.28a32 32 0 1 0 45.12 45.12l169.6-169.28 169.28 169.28a32 32 0 0 0 45.12-45.12L557.12 512l169.28-169.28a32 32 0 0 0 0.32-45.44z"
                fill="#E10000"></path>
            </g>
          </svg>
        </div>
        <p></p>
        <p class="text-2xl my-6 text-center">{{ $t('delete.sure') }}</p>

        <div class="flex justify-end mt-5">
          <button @click="deleteLevel(itemToDelete)"
            class="flex items-center text-lg text-white p-2 rounded-xl bg-red-500 shadow-lg shadow-red-500/50 hover:bg-red-400"><svg
              width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="">
              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
              <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
              <g id="SVGRepo_iconCarrier">
                <path d="M10 12V17" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                </path>
                <path d="M14 12V17" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                </path>
                <path d="M4 7H20" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M6 10V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18V10" stroke="#ffffff"
                  stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z" stroke="#ffffff"
                  stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
              </g>
            </svg><span class="mx-2">{{ $t('buttons.removeLevel') }}</span></button>
        </div>
      </Dialog>
    </div>

  </InsideLayout>
</template>

<style>
.customize-table {
  --easy-table-header-background-color: #818CF8;
}
</style>