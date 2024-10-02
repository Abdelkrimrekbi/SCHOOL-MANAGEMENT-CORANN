<script setup>
import InsideLayout from "../../Layouts/InsideLayout.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import Dropdown from "primevue/dropdown";
import Dialog from "primevue/dialog";
import { onMounted, ref } from "vue";
import { useI18n } from "vue-i18n";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import Calendar from "primevue/calendar";
import { format } from "date-fns";
import Vue3EasyDataTable from "vue3-easy-data-table";
import "vue3-easy-data-table/dist/style.css";
import axios from "axios";
import { watch } from "vue";
import Pagination from "@/Components/Pagination.vue";

const { t } = useI18n();
//Get Data From Controller:
const props = defineProps({
  students: {
    type: Array,
    required: true,
  },
  levels: {
    type: Array,
    required: true,
  },
});
let isDisabled;
//DataTable items ::
const headers = [
  { text: "name", value: "name", sortable: true },
  { text: "date_birth", value: "date_birth", sortable: true },
  { text: "level_id", value: "level_id", sortable: true },
  { text: "address", value: "address", sortable: true },
  { text: "phone", value: "phone", sortable: true },
  { text: "niveau_etude", value: "niveau_etude", sortable: true },
  { text: "operations", value: "operations" },
];
const searchField = [
  "name",
  "date_birth",
  "address",
  "phone",
  "niveau_etude",
  "level.level",
];
const searchValue = ref();

const inputDisabled = true;
const toast = useToast();
const studentForm = useForm({
  id: null,
  name: "",
  date_birth: "",
  profession: "",
  la_situation: "",
  level_id: "",
  class_id: "",
  lieu_birth: "",
  address: "",
  phone: "",
  niveau_etude: "",
  name_school: "",
  service_mensuel: "",
  droit_annuel: "",
  isChecked: false,
});
const resetStudentForm = () => {
  studentForm.name = "";
  studentForm.date_birth = "";
  studentForm.profession = "";
  studentForm.la_situation = "";
  studentForm.lieu_birth = "";
  studentForm.level_id = "";
  studentForm.class_id = "";
  studentForm.address = "";
  studentForm.phone = "";
  studentForm.niveau_etude = "";
  studentForm.name_school = "";
  studentForm.service_mensuel = "";
  studentForm.droit_annuel = "";
  studentForm.isChecked = false;
};
const editStudentVisible = ref(false);
const editStudent = (item) => {
  studentForm.id = item.id;
  console.log(" value test", item);
  studentForm.name = item.name;
  studentForm.date_birth = item.date_birth;
  studentForm.address = item.address;
  studentForm.lieu_birth = item.lieu_birth;
  studentForm.phone = item.phone;
  studentForm.la_situation = item.la_situation;
  studentForm.profession = item.profession;
  studentForm.level_id = item.level.id;
  studentForm.class_id = item.class_id;
  studentForm.niveau_etude = item.niveau_etude;
  studentForm.name_school = item.name_school;
  studentForm.service_mensuel = item.service_mensuel;
  studentForm.droit_annuel = item.droit_annuel;
  studentForm.isChecked = item.isChecked;
  editStudentVisible.value = true;
};

const updateStudent = () => {
  if (
    studentForm.class_id === "" ||
    studentForm.service_mensuel === "" ||
    studentForm.droit_annuel === ""
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
    const formData = new FormData();
    const combinedData = {
      name: studentForm.name,
      la_situation: studentForm.la_situation,
      niveau_etude: studentForm.niveau_etude,
      date_birth: studentForm.date_birth,
      lieu_birth: studentForm.lieu_birth,
      level_id: studentForm.level_id,
      address: studentForm.address,
      name_school: studentForm.name_school,
      profession: studentForm.profession,
      service_mensuel: studentForm.service_mensuel,
      droit_annuel: studentForm.droit_annuel,
      class_id: studentForm.class_id,
      isChecked: studentForm.isChecked == true,
      phone: studentForm.phone,
    };

    console.log("combinedData", combinedData);
    console.log("studentForm.id :", studentForm.id);
    combinedData._method = "PUT";
    router.post(`/students/${studentForm.id}`, combinedData, {
      method: "PUT",
      headers: {
        "Content-Type": "multipart/form-data",
      },
      preserveScroll: true,
      preserveState: true,
      onSuccess: (page) => {
        editStudentVisible.value = false;
        resetStudentForm();
        toast.add({
          severity: "success",
          summary: t("toast.success"),
          detail: t("toast.successMessages"),
          group: "bc",
          life: 3000,
        });
      },
    });
  }
};

const filteredClasses = ref([]);
const classWithLevel = (level) => {
    if (level) {
        router
            .get("/get-group-by-level/" + level, '', {
                preserveScroll: true,
                preserveState: true,
                onSuccess: (response) => {
                    filteredClasses.value = response.props.flash.groups;
                },
                onError: (error) => {
                    console.log("Error: ", error)
                }
            })

    }
};

const getStudents = ref(props.students);



const areYouSureToAdd = ref(false);
const areYouSureItem = (item) => {
 
  areYouSureToAdd.value = true;
};
const incrementStudentLevel = () => {
  axios.put('/increment-level')
    .then(response => {
       areYouSureToAdd.value = false;
      toast.add({
        severity: "success",
        summary: t("toast.success"),
        detail: response.data.message,
        group: "bc",
        life: 3000,
      });
    })
    
   
};



</script>
<template>
  <Head :title="$t('title.students')" />
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
      <div >
        <!-- <button @click="incrementStudentLevel" class="flex items-center text-lg bg-green-500 shadow-lg shadow-green-500/50 hover:bg-green-400 text-white p-2 rounded-xl">
  <span class="mx-2">Increment All Student Levels</span>
</button> -->
 <button @click="areYouSureItem(item)" class="flex items-center text-lg bg-green-500 shadow-lg shadow-green-500/50 hover:bg-green-400 text-white p-2 rounded-xl">
             <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
            stroke="#ffffff">
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
            <g id="SVGRepo_iconCarrier">
              <circle cx="12" cy="12" r="10" stroke="#ffffff" stroke-width="1.5"></circle>
              <path d="M15 12L12 12M12 12L9 12M12 12L12 9M12 12L12 15" stroke="#ffffff" stroke-width="1.5"
                stroke-linecap="round"></path>
            </g>
          </svg><span class="mx-2">Increment All Student Levels</span>
            </button>

      </div>
    </div>
    <div
      class="m-4 outer-container"
      :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'"
    >
      <Vue3EasyDataTable
        :headers="headers"
        :items="getStudents.data"
        :search-field="searchField"
        :search-value="searchValue"
        table-class-name="customize-table"
        border-cell
        theme-color="#f7071b"
        table-responsive
        :hide-footer="true"
      >
     
      
        <template #header-name="header">
          <p class="text-white text-[1.2rem] py-2"> {{ $t('studentTable.Name') }}</p>
        </template>
        <template #header-date_birth="header">
          <p class="text-white text-[1.2rem] py-2">{{ $t('studentTable.date_birth') }}</p>
        </template>
        <template #header-address="header">
          <p class="text-white text-[1.2rem] py-2">{{ $t('studentTable.Address') }}</p>
        </template>
        <template #header-phone="header">
          <p class="text-white text-[1.2rem] py-2"> {{ $t('studentTable.Phone') }}</p>
        </template>
        <template #header-level_id="header">
          <p class="text-white text-[1.2rem] py-2"> {{ $t('studentTable.Level') }}</p>
        </template>
        <template #header-niveau_etude="header">
          <p class="text-white text-[1.2rem] py-2"> {{$t('studentTable.niveau_etude') }}</p>
        </template>
        <template #header-operations="header">
          <p class="text-white text-[1.2rem] py-2">
            {{ $t("tablestudent.operation") }}
          </p>
        </template>

        <template #item-name="item">
          <div
            style="display: flex; justify-content: center; align-items: center"
          >
            <p
              class="text-[1rem] flex font-medium py-3"
              :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'"
            >
              {{ item.name }}
            </p>
          </div>
        </template>
        <template #item-date_birth="item">
          <div
            style="display: flex; justify-content: center; align-items: center"
          >
            <p
              class="text-[1rem] flex font-medium py-3"
              :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'"
            >
              {{ item.date_birth }}
            </p>
          </div>
        </template>
        <template #item-level_id="item">
          <div
            style="display: flex; justify-content: center; align-items: center"
          >
            <p
              class="text-[1rem] flex font-medium py-3"
              :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'"
            >
              {{ item.level.level }}
            </p>
          </div>
        </template>
        <template #item-address="item">
          <div
            style="display: flex; justify-content: center; align-items: center"
          >
            <p
              class="text-[1rem] flex font-medium py-3"
              :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'"
            >
              {{ item.address }}
            </p>
          </div>
        </template>
        <template #item-phone="item">
          <div
            style="display: flex; justify-content: center; align-items: center"
          >
            <p
              class="text-[1rem] flex font-medium py-3"
              :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'"
            >
              {{ item.phone }}
            </p>
          </div>
        </template>
        <template #item-niveau_etude="item">
          <div
            style="display: flex; justify-content: center; align-items: center"
          >
            <p
              class="text-[1rem] flex font-medium py-3"
              :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'"
            >
              {{ item.niveau_etude }}
            </p>
          </div>
        </template>
        <template #item-operations="item">
          <div
            style="display: flex; justify-content: center; align-items: center"
            :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'"
          >
            <button @click="editStudent(item)" class="mx-2">
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
          </div>
        </template>
      </Vue3EasyDataTable>
        <Pagination :pagination="getStudents"/> 
    </div>

  <!--EDI STUDENT MODAL-->
    <Toast position="bottom-center" group="bc" />
    <div class="card flex justify-content-center m-2">
      <Dialog
        v-model:visible="editStudentVisible"
        modal
        header="تعديل معلومات الطالب"
        :style="{ width: '50rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
      >
        <div :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
          <div class="m-10">
            <form
              class="grid mx-2"
              @submit.prevent="updateStudent"
              enctype="multipart/form-data"
            >
              <div class="grid xl:grid-cols-2">
                <div class="xl:ml-4">
                  <label for="level_id" class="mt-2 text-gray-500"
                    >المستوى*</label
                  >
                  <Dropdown
                    v-model="studentForm.level_id"
                    :options="levels"
                    optionLabel="level"
                    placeholder="المستوى"
                    optionValue="id"
                    class="w-full overflow-hidden"
                    @change="classWithLevel(studentForm.level_id)"
                  />
                </div>

                <div class="xl:ml-4 sm:ml-0">
                  <label for="niveau_etude" class="mt-2 text-gray-500"
                    >{{$t('studentTable.niveau_etude') }}*</label
                  >
                  <input
                    type="text"
                    class="w-full h-12 rounded-xl"
                    :placeholder="$t('studentTable.niveau_etude')"
                    id="niveau_etude"
                    v-model="studentForm.niveau_etude"
                  />
                </div>

                  <div class="xl:ml-4">
                  <label for="classe_id" class="mt-2 text-gray-500"
                    >فصل*</label
                  >
                  <Dropdown
                    v-model="studentForm.class_id"
                    :options="filteredClasses"
                    optionLabel="group"
                    optionValue="id"
                    placeholder="فصل"
                    class="w-full overflow-hidden"
                  />
                </div>

                <div class="xl:ml-4 sm:ml-0 mt-6 xl:mt-0">
                  <label for="service_mensuel" class="mt-2 text-gray-500"
                    >الواجب الشهري *</label
                  >
                  <input
                    type="text"
                    class="w-full h-12 rounded-xl"
                    placeholder="الواجب الشهري "
                    id="service_mensuel"
                    v-model="studentForm.service_mensuel"
                    :disabled="isDisabled"
                  />
                </div>

                <div class="mt-6">
                  <label for="droit_annuel" class="mt-2 text-gray-500"
                    >الواجب السنوي *</label
                  >
                  <input
                    type="text"
                    class="w-full h-12 rounded-xl"
                    placeholder="الواجب السنوي "
                    id="droit_annuel"
                    v-model="studentForm.droit_annuel"
                    :disabled="isDisabled"
                  />
                </div>
              </div>
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
                  ><span class="mx-2">تعديل معلومات الطالب </span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </Dialog>
    </div>

     <!--ARE YOU suew want to add leve-->
    <div class="card flex justify-content-center m-2">
      <Dialog v-model:visible="areYouSureToAdd" modal header="ajouter un annee a niveau" :style="{ width: '50rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div class="flex justify-center">
         <svg width="106px" height="106px" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--emojione" preserveAspectRatio="xMidYMid meet" fill="#75aeea"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g fill="#81c7fd"> <path d="M6 42.4h10L20 2H2z"> </path> <ellipse cx="11" cy="54.4" rx="7.7" ry="7.6"> </ellipse> <path d="M40.2 2.1C28.6 2.8 22.5 9.4 22 21.3h11.7c.1-4.1 2.5-7.2 6.7-7.7c4.2-.4 8.2.6 9.4 3.4c1.3 3.1-1.6 6.7-3 8.2c-2.6 2.8-6.8 4.9-8.9 7.9c-2.1 3-2.5 6.9-2.7 11.7h10.3c.1-3.1.3-6 1.7-7.9c2.3-3.1 5.7-4.5 8.5-7c2.7-2.3 5.6-5.1 6-9.5c1.6-12.9-9-19.1-21.5-18.3"> </path> <ellipse cx="40.5" cy="55.6" rx="6.5" ry="6.4"> </ellipse> </g> </g></svg>
        </div>
        <p></p>
        <p class="text-2xl my-6 text-center">{{ $t("delete.sure") }}</p>

        <div class="flex justify-end mt-5">
          <button @click="incrementStudentLevel()" 
            class="flex items-center text-lg text-white p-2 rounded-xl bg-blue-500 shadow-lg shadow-blue-500/50 hover:bg-blue-400">
            
            
            <span class="mx-2">ajouter un annee a niveau</span>
          </button>
        </div>
      </Dialog>
    </div>
  </InsideLayout>
</template>
<style>
.customize-table {
  --easy-table-header-background-color: #818cf8;
}
.pagination-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.1rem 0.5rem;
  margin-right: 5px;
  border-radius: 0.375rem;
  /* 6px */
  background-color: #6c726c;
  /* Lime green */
  color: white;
  text-align: center;
  font-weight: bold;
  border: none;
  cursor: pointer;
  transition: background-color 0.2s;
}
.table-responsive {
  overflow-x: auto;
}

/* Responsive table styles */
@media (max-width: 768px) {
  .table-responsive table {
    width: 100%;
  }
}
.pagination-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.1rem 0.5rem;
    margin-right: 5px;
    border-radius: 0.375rem;
    /* 6px */
    background-color: #6c726c;
    /* Lime green */
    color: white;
    text-align: center;
    font-weight: bold;
    border: none;
    cursor: pointer;
    transition: background-color 0.2s;
}

.pagination-button:hover {
    background-color: #28a745;
    /* Darker lime green */
}

.pagination-button:disabled,
.pagination-button-disabled {
    background-color: #cccccc;
    color: #666666;
    cursor: not-allowed;
}
</style>