<script setup>
import InsideLayout from "../../Layouts/InsideLayout.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import Dialog from "primevue/dialog";
import { ref } from "vue";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import { useI18n } from "vue-i18n";
import Vue3EasyDataTable from "vue3-easy-data-table";
import "vue3-easy-data-table/dist/style.css";
const { t } = useI18n();

const props = defineProps({
  departements: {
    type: Array,
    required: true,
  },
});

//DataTable items ::
const headers = [
  { text: "ID", value: "id", sortable: true },
  { text: "Departement", value: "departement", sortable: true },
  { text: "Created Date", value: "created_at", sortable: true },
  { text: "Operations", value: "operations" },
];
const searchField = ["departement", "id"];
const searchValue = ref();

const addDepartementVisible = ref(false);
const editDepartementVisible = ref(false);
const areYouSure = ref(false);

const departementForm = useForm({
  departement: "",
  description: "",
});

const resetDepartementForm = () => {
  departementForm.departement = "";
  departementForm.description = "";
};
const toast = useToast();
//Add Departement
const openAddDepartement = () => {
  addDepartementVisible.value = true;
  resetDepartementForm();
};

const addDepartement = () => {
  if (
    departementForm.departement === "" ||
    departementForm.description === ""
  ) {
    toast.add({
      severity: "error",
      summary: t("toast.error"),
      detail: t("toast.errorMessage"),
      group: "bc",
      life: 3000,
    });
    return;
  } else {
    router.post("/departements", departementForm, {
      preserveScroll: true,
      preserveState: true,
      onProgress: (page) => {},
      onSuccess: (page) => {
        addDepartementVisible.value = false;
        props.departements = props.departements;
        resetDepartementForm();
        departementForm.reset;
      },
    });
    toast.add({
      severity: "success",
      summary: t("toast.success"),
      detail: t("toast.successMessages"),
      group: "bc",
      life: 3000,
    });
  }
};

//Edit DEpartement
let itemToEdit;
const editDepartement = (item) => {
  itemToEdit = item.id;
  departementForm.departement = item.departement;
  departementForm.description = item.description;
  editDepartementVisible.value = true;
};
const EditDepartementFun = () => {
  if (
    departementForm.departement === "" ||
    departementForm.description === ""
  ) {
    toast.add({
      severity: "error",
      summary: t("toast.error"),
      detail: t("toast.errorMessage"),
      group: "bc",
      life: 3000,
    });
    return;
  } else {
    departementForm._method = "PUT";
    router.post("/departements/" + itemToEdit, departementForm, {
      preserveScroll: true,
      preserveState: true,
      onProgress: (page) => {},
      onSuccess: (page) => {
        addDepartementVisible.value = false;
        props.departements = props.departements;
        resetDepartementForm();
        departementForm.reset;
      },
    });
    editDepartementVisible.value = false;
    toast.add({
      severity: "success",
      summary: t("toast.success"),
      detail: t("toast.successMessages"),
      group: "bc",
      life: 3000,
    });
  }
};

//Delete Departement ::
let itemToDelete;
const areYouSureItem = (item) => {
  itemToDelete = item;
  areYouSure.value = true;
};

const deleteDepartement = (item) => {
  router.delete(`/departements/${item.id}`, {
    preserveScroll: true,
    preserveState: true,
    onProgress: (page) => {},
    onSuccess: (page) => {
      props.departements = props.departements;
      areYouSure.value = false;
      toast.add({
        severity: "success",
        summary: t("toast.success"),
        detail: t("toast.successMessages"),
        group: "bc",
        life: 3000,
      });
    },
  });
};

const visible = ref(false);
const showlDepartementVisible = ref(false);

let itemToShow;
const showlDepartement = (item) => {
  itemToShow = item;
  showlDepartementVisible.value = true;
};
</script>

<template>
  <Head :title="$t('asidebar.evenment')" />
  <InsideLayout>
    <div class="bg-white p-6 m-4 rounded-xl grid xl:grid-cols-4">
      <div class="xl:col-span-2 mx-2">
        <input
          type="text"
          v-model="searchValue"
          :placeholder="$t('search.search')"
          class="h-14 py-6 w-full rounded-xl border border-gray-200"
        />
      </div>
      <div class="flex justify-start lg:m-2 xl:m-0">
        <button
          label="Show"
          @click="openAddDepartement"
          class="flex items-center text-lg bg-indigo-500 shadow-lg shadow-indigo-500/50 hover:bg-indigo-400 text-white p-2 rounded-xl"
        >
          <svg
            width="30px"
            height="30px"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            stroke="#ffffff"
          >
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g
              id="SVGRepo_tracerCarrier"
              stroke-linecap="round"
              stroke-linejoin="round"
            ></g>
            <g id="SVGRepo_iconCarrier">
              <circle
                cx="12"
                cy="12"
                r="10"
                stroke="#ffffff"
                stroke-width="1.5"
              ></circle>
              <path
                d="M15 12L12 12M12 12L9 12M12 12L12 9M12 12L12 15"
                stroke="#ffffff"
                stroke-width="1.5"
                stroke-linecap="round"
              ></path>
            </g></svg
          ><span class="mx-2">{{ $t('buttons.addevenment') }}</span>
        </button>
      </div>
    </div>
    <!--Data Table Here-->
    <div class="m-4" :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
      <!--FETCH DEPARTEMENTS-->
      <div
        class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10 m-10"
      >
        <div v-for="depa in departements">
          <div
            class="p-4 rounded-xl bg-white shadow-xl overflow-hidden card h-full"
          >
            <div class="flex justify-end">
              <button @click="editDepartement(depa)">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="icon icon-tabler icon-tabler-edit"
                  width="36"
                  height="36"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="#F59E0B"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path
                    d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"
                  />
                  <path
                    d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"
                  />
                  <path d="M16 5l3 3" />
                </svg>
              </button>
              <button @click="areYouSureItem(depa)">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="icon icon-tabler icon-tabler-trash"
                  width="36"
                  height="36"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="#EF4444"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M4 7l16 0" />
                  <path d="M10 11l0 6" />
                  <path d="M14 11l0 6" />
                  <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                  <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                </svg>
              </button>
            </div>
            <div class="flex justify-center">
              <svg
                fill="#C109FC"
                width="128px"
                height="128px"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
                stroke="#ababab"
              >
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g
                  id="SVGRepo_tracerCarrier"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                ></g>
                <g id="SVGRepo_iconCarrier">
                  <path
                    d="M5.04146 3C5.22009 2.6906 5.55022 2.5 5.90748 2.5L9.75278 2.5C10.11 2.5 10.4402 2.6906 10.6188 3L12.5415 6.33013C12.7201 6.63953 12.7201 7.02073 12.5415 7.33013L10.6188 10.6603C10.4402 10.9697 10.11 11.1603 9.75278 11.1603H5.90748C5.55022 11.1603 5.22009 10.9697 5.04146 10.6603L3.11881 7.33013C2.94017 7.02073 2.94017 6.63953 3.11881 6.33013L5.04146 3Z"
                  ></path>
                  <path
                    d="M5.1216 13.2272C5.30023 12.9178 5.63036 12.7272 5.98762 12.7272H9.83292C10.1902 12.7272 10.5203 12.9178 10.6989 13.2272L12.6216 16.5574C12.8002 16.8668 12.8002 17.248 12.6216 17.5574L10.6989 20.8875C10.5203 21.1969 10.1902 21.3875 9.83292 21.3875H5.98762C5.63036 21.3875 5.30023 21.1969 5.1216 20.8875L3.19895 17.5574C3.02031 17.248 3.02031 16.8668 3.19895 16.5574L5.1216 13.2272Z"
                  ></path>
                  <path
                    d="M14.1216 8.22723C14.3002 7.91783 14.6304 7.72723 14.9876 7.72723L18.8329 7.72723C19.1902 7.72723 19.5203 7.91783 19.6989 8.22723L21.6216 11.5574C21.8002 11.8668 21.8002 12.248 21.6216 12.5574L19.6989 15.8875C19.5203 16.1969 19.1902 16.3875 18.8329 16.3875H14.9876C14.6304 16.3875 14.3002 16.1969 14.1216 15.8875L12.1989 12.5574C12.0203 12.248 12.0203 11.8668 12.1989 11.5574L14.1216 8.22723Z"
                  ></path>
                </g>
              </svg>
            </div>
            <h1 class="text-xl font-semibold text-indigo-500">
              {{ depa.departement }}
            </h1>
            <p class="text-gray-500 text-center my-4">
              {{ depa.description.substring(0, 40) + "..." }}
            </p>
            <div class="flex justify-center items-end my-4">
              <button @click="showlDepartement(depa)" class="mx-2">
                <div
                  class="hover:scale-110 transition-all duration-500 cursor-pointer flex items-center bg-indigo-500 shadow-lg shadow-indigo-500/50 hover:bg-indigo-400 text-white px-4 py-2 rounded-xl text-xl font-semibold"
                >
                  <svg
                    class="w-4 h-4 dark:text-white"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 8 14"
                  >
                    <path
                      stroke="currentColor"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="m1 13 5.7-5.326a.909.909 0 0 0 0-1.348L1 1"
                    />
                  </svg>
                  <span>{{ $t("addevenmentForm.showdeataile") }} </span>
                </div>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--Modals-->
    <!--ADD DEPARTEMENT MODAL-->
    <Toast position="bottom-center" group="bc" />
    <div class="card flex justify-content-center">
      <Dialog
        v-model:visible="addDepartementVisible"
        modal
        :header="$t('buttons.addevenment')"
        :style="{ width: '35vw' }"
      >
        <div :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
          <div class="m-10">
            <form class="grid" @submit.prevent="addDepartement">
              <label for="levelName" class="mt-2 text-gray-500"
                >{{ $t("addevenmentForm.departement") }}*</label
              >
              <input
                type="text"
                class="w-full h-14 rounded-xl border border-gray-200 my-2"
                :placeholder="$t('addevenmentForm.departement')"
                id="levelName"
                v-model="departementForm.departement"
              />

              <label for="description" class="mt-2 text-gray-500"
                >{{ $t("addevenmentForm.description") }}*</label
              >
              <textarea
                type="text"
                class="w-full rounded-xl border border-gray-200 my-2"
                :placeholder="$t('addevenmentForm.description')"
                id="description"
                rows="10"
                v-model="departementForm.description"
              ></textarea>

              <div class="flex justify-end mt-5">
                <button
                  class="flex items-center text-lg bg-indigo-500 shadow-lg shadow-indigo-500/50 hover:bg-indigo-400 text-white p-2 rounded-xl"
                >
                  <svg
                    width="30px"
                    height="30px"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    stroke="#ffffff"
                  >
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g
                      id="SVGRepo_tracerCarrier"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    ></g>
                    <g id="SVGRepo_iconCarrier">
                      <circle
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="#ffffff"
                        stroke-width="1.5"
                      ></circle>
                      <path
                        d="M15 12L12 12M12 12L9 12M12 12L12 9M12 12L12 15"
                        stroke="#ffffff"
                        stroke-width="1.5"
                        stroke-linecap="round"
                      ></path>
                    </g></svg
                  ><span class="mx-2">{{ $t('buttons.addevenment')}}</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </Dialog>
    </div>

    <!--Edit DEPARTEMENT MODAL-->
    <div class="card flex justify-content-center m-2">
      <Dialog
        v-model:visible="editDepartementVisible"
        modal
        :header="$t('buttons.editevenment')"
        :style="{ width: '50rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
      >
        <div :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
          <div class="m-10">
            <form class="grid" @submit.prevent="EditDepartementFun">
              <label for="departement" class="mt-2 text-gray-500"
                >{{ $t("addevenmentForm.evenment") }}*</label
              >
              <input
                type="text"
                class="w-full h-14 rounded-xl border border-gray-200 my-2"
                :placeholder="$t('addDepartementForm.departement')"
                id="departement"
                v-model="departementForm.departement"
              />
              <label for="description" class="mt-2 text-gray-500"
                >{{ $t("addevenmentForm.description") }}*</label
              >
              <textarea
                type="text"
                class="w-full rounded-xl border border-gray-200 my-2"
                :placeholder="$t('addevenmentForm.description')"
                id="description"
                rows="10"
                v-model="departementForm.description"
              ></textarea>

              <div class="flex justify-end mt-5">
                <button
                  class="flex items-center text-lg bg-yellow-500 shadow-lg shadow-yellow-500/50 hover:bg-yellow-400 text-white p-2 rounded-xl"
                >
                  <svg
                    width="30px"
                    height="30px"
                    viewBox="0 -0.5 25 25"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    stroke=""
                  >
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g
                      id="SVGRepo_tracerCarrier"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    ></g>
                    <g id="SVGRepo_iconCarrier">
                      <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M17.7 5.12758L19.266 6.37458C19.4172 6.51691 19.5025 6.71571 19.5013 6.92339C19.5002 7.13106 19.4128 7.32892 19.26 7.46958L18.07 8.89358L14.021 13.7226C13.9501 13.8037 13.8558 13.8607 13.751 13.8856L11.651 14.3616C11.3755 14.3754 11.1356 14.1751 11.1 13.9016V11.7436C11.1071 11.6395 11.149 11.5409 11.219 11.4636L15.193 6.97058L16.557 5.34158C16.8268 4.98786 17.3204 4.89545 17.7 5.12758Z"
                        stroke="#ffffff"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      ></path>
                      <path
                        d="M12.033 7.61865C12.4472 7.61865 12.783 7.28287 12.783 6.86865C12.783 6.45444 12.4472 6.11865 12.033 6.11865V7.61865ZM9.23301 6.86865V6.11865L9.23121 6.11865L9.23301 6.86865ZM5.50001 10.6187H6.25001L6.25001 10.617L5.50001 10.6187ZM5.50001 16.2437L6.25001 16.2453V16.2437H5.50001ZM9.23301 19.9937L9.23121 20.7437H9.23301V19.9937ZM14.833 19.9937V20.7437L14.8348 20.7437L14.833 19.9937ZM18.566 16.2437H17.816L17.816 16.2453L18.566 16.2437ZM19.316 12.4937C19.316 12.0794 18.9802 11.7437 18.566 11.7437C18.1518 11.7437 17.816 12.0794 17.816 12.4937H19.316ZM15.8863 6.68446C15.7282 6.30159 15.2897 6.11934 14.9068 6.2774C14.5239 6.43546 14.3417 6.87397 14.4998 7.25684L15.8863 6.68446ZM18.2319 9.62197C18.6363 9.53257 18.8917 9.13222 18.8023 8.72777C18.7129 8.32332 18.3126 8.06792 17.9081 8.15733L18.2319 9.62197ZM8.30001 16.4317C7.8858 16.4317 7.55001 16.7674 7.55001 17.1817C7.55001 17.5959 7.8858 17.9317 8.30001 17.9317V16.4317ZM15.767 17.9317C16.1812 17.9317 16.517 17.5959 16.517 17.1817C16.517 16.7674 16.1812 16.4317 15.767 16.4317V17.9317ZM12.033 6.11865H9.23301V7.61865H12.033V6.11865ZM9.23121 6.11865C6.75081 6.12461 4.7447 8.13986 4.75001 10.6203L6.25001 10.617C6.24647 8.96492 7.58269 7.62262 9.23481 7.61865L9.23121 6.11865ZM4.75001 10.6187V16.2437H6.25001V10.6187H4.75001ZM4.75001 16.242C4.7447 18.7224 6.75081 20.7377 9.23121 20.7437L9.23481 19.2437C7.58269 19.2397 6.24647 17.8974 6.25001 16.2453L4.75001 16.242ZM9.23301 20.7437H14.833V19.2437H9.23301V20.7437ZM14.8348 20.7437C17.3152 20.7377 19.3213 18.7224 19.316 16.242L17.816 16.2453C17.8195 17.8974 16.4833 19.2397 14.8312 19.2437L14.8348 20.7437ZM19.316 16.2437V12.4937H17.816V16.2437H19.316ZM14.4998 7.25684C14.6947 7.72897 15.0923 8.39815 15.6866 8.91521C16.2944 9.44412 17.1679 9.85718 18.2319 9.62197L17.9081 8.15733C17.4431 8.26012 17.0391 8.10369 16.6712 7.7836C16.2897 7.45165 16.0134 6.99233 15.8863 6.68446L14.4998 7.25684ZM8.30001 17.9317H15.767V16.4317H8.30001V17.9317Z"
                        fill="#ffffff"
                      ></path>
                    </g></svg
                  ><span class="mx-2">{{ $t('buttons.editevenment') }}</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </Dialog>
    </div>

    <!--ARE YOU SURE ??-->
    <div class="card flex justify-content-center m-2">
      <Dialog
        v-model:visible="areYouSure"
        modal
        :header="$t('buttons.removeevenment')"
        :style="{ width: '50rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
      >
        <div class="flex justify-center">
          <svg
            width="100x"
            height="100px"
            viewBox="0 0 1024 1024"
            class="icon"
            version="1.1"
            xmlns="http://www.w3.org/2000/svg"
            fill=""
          >
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g
              id="SVGRepo_tracerCarrier"
              stroke-linecap="round"
              stroke-linejoin="round"
            ></g>
            <g id="SVGRepo_iconCarrier">
              <path
                d="M905.92 237.76a32 32 0 0 0-52.48 36.48A416 416 0 1 1 96 512a418.56 418.56 0 0 1 297.28-398.72 32 32 0 1 0-18.24-61.44A480 480 0 1 0 992 512a477.12 477.12 0 0 0-86.08-274.24z"
                fill="#E10000"
              ></path>
              <path
                d="M630.72 113.28A413.76 413.76 0 0 1 768 185.28a32 32 0 0 0 39.68-50.24 476.8 476.8 0 0 0-160-83.2 32 32 0 0 0-18.24 61.44zM489.28 86.72a36.8 36.8 0 0 0 10.56 6.72 30.08 30.08 0 0 0 24.32 0 37.12 37.12 0 0 0 10.56-6.72A32 32 0 0 0 544 64a33.6 33.6 0 0 0-9.28-22.72A32 32 0 0 0 505.6 32a20.8 20.8 0 0 0-5.76 1.92 23.68 23.68 0 0 0-5.76 2.88l-4.8 3.84a32 32 0 0 0-6.72 10.56A32 32 0 0 0 480 64a32 32 0 0 0 2.56 12.16 37.12 37.12 0 0 0 6.72 10.56zM726.72 297.28a32 32 0 0 0-45.12 0l-169.6 169.6-169.28-169.6A32 32 0 0 0 297.6 342.4l169.28 169.6-169.6 169.28a32 32 0 1 0 45.12 45.12l169.6-169.28 169.28 169.28a32 32 0 0 0 45.12-45.12L557.12 512l169.28-169.28a32 32 0 0 0 0.32-45.44z"
                fill="#E10000"
              ></path>
            </g>
          </svg>
        </div>
        <p></p>
        <p class="text-2xl my-6 text-center">{{ $t("delete.sure") }}</p>

        <div class="flex justify-end mt-5">
          <button
            @click="deleteDepartement(itemToDelete)"
            class="flex items-center text-lg text-white p-2 rounded-xl bg-red-500 shadow-lg shadow-red-500/50 hover:bg-red-400"
          >
            <svg
              width="30px"
              height="30px"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
              stroke=""
            >
              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
              <g
                id="SVGRepo_tracerCarrier"
                stroke-linecap="round"
                stroke-linejoin="round"
              ></g>
              <g id="SVGRepo_iconCarrier">
                <path
                  d="M10 12V17"
                  stroke="#ffffff"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                ></path>
                <path
                  d="M14 12V17"
                  stroke="#ffffff"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                ></path>
                <path
                  d="M4 7H20"
                  stroke="#ffffff"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                ></path>
                <path
                  d="M6 10V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18V10"
                  stroke="#ffffff"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                ></path>
                <path
                  d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z"
                  stroke="#ffffff"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                ></path>
              </g></svg
            ><span class="mx-2">{{ $t('buttons.removeevenment') }}</span>
          </button>
        </div>
      </Dialog>
    </div>

    <!--Show evenment ??-->

    <div class="card flex justify-content-center m-2">
      <Dialog
        v-model:visible="showlDepartementVisible"
        modal
        :header="$t('addevenmentForm.evenment')"
        :style="{ width: '50rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
      >
        <div :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
          <div class="grid : grid-cols-3">
            <p class="text-xl font-semibold text-[#6F32BE]">{{$t('addevenmentForm.departement')}} :</p>
             <div
            class="p-4 rounded-xl bg-white shadow-xl overflow-hidden card h-full"
          >
            <p c class="text-lg col-span-2">{{ itemToShow.departement }}</p>
             </div>
          </div>
          <div class="border-t border-gray-200 my-2" />

          <div class="grid : grid-cols-3">
            <p class="text-xl font-semibold text-[#6F32BE]">{{$t('addevenmentForm.description')}} :</p>
              <div
            class="p-4 rounded-xl bg-white shadow-xl overflow-hidden card h-full" width="400px"
          >
            <p class="text-lg col-span-2">{{ itemToShow.description }}</p>
              </div>
          </div>
          <div class="border-t border-gray-200 my-2" />
        </div>
      </Dialog>
    </div>
  </InsideLayout>
</template>

