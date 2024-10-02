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
import {  watch } from 'vue';
import Pagination from "@/Components/Pagination.vue";

const { t } = useI18n();
const props = defineProps({
  student: {
    type: Object,
    required: true,
  },
  levels: {
    type: Array,
    required: true,
  },
});
// const hello = () => {
//   console.log("Hello World", props.student);
// }

// onMounted(()=> {
//   console.log("on monted");
//   hello();
// })




//DataTable items ::
const headers = [
  { text: "name", value: "name", sortable: true },
  { text: "date_birth", value: "date_birth", sortable: true },
  { text: "level_id", value: "level_id", sortable: true },
  { text: "address", value: "address", sortable: true },
  { text: "phone", value: "phone", sortable: true },
  { text: "niveau_etude", value: "niveau_etude", sortable: true },
  { text: "operations", value: "operations" },
  { text: "print", value: "print" },
];

const searchField = ["CNE", "first_name", "last_name"];
const searchValue = ref();
const toast = useToast();
let isDisabled;
const addStudentVisible = ref(false);
const editStudentVisible = ref(false);
const areYouSure = ref(false);
let selectedLevel = ref(null);
let selectedClass = ref(null);
 //<div v-if="studentForm.type === 'niveau1'" class="mt-4">
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
  studentForm. service_mensuel = "";
  studentForm.droit_annuel = "";
  studentForm.isChecked = false;
};

let itemToDelete;
const areYouSureItem = (item) => {
  itemToDelete = item;
  areYouSure.value = true;
};

// ROUTERN CLASSES WITH SAME LEVEL

// const filteredClasses = ref([]);
// const classByLevel = (level) => {
//     if (level) {
//         axios.get(`/get-group-by-level/${level}`)
//             .then((response) => {
//                 filteredClasses.value = response.data.groups;
//                 console.log("New group by level:", filteredClasses.value);
//             })
//             .catch((error) => {
//                 console.log("Error:", error);
//             });
//     }
// };
const filteredClasses = ref([]);
const classByLevel = (level) => {
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


const editStudent = (item) => {
   studentForm.id = item.id;
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
  studentForm.isChecked = item.isChecked == 1;
  editStudentVisible.value = true;
};




const getStudents = ref(props.student);
const fetchFilteredStudents = (item) => {
    if (item) {
        router
            .get("/filter-student/" + item, '', {
                preserveScroll: true,
                preserveState: true,
                onSuccess: (response) => {
                    getStudents.value = response.props.flash.students;
                    console.log("value students :",getStudents.data)
                },
                onError: (error) => {
                    console.log("Error: ", error);
                }
            })
    }
};



// ADD NEW STUDENTS
const isButtonClicked = ref(false);
const addStudent = async () => {
  if (
    studentForm.name === "" ||
    studentForm.date_birth === "" ||
    studentForm.lieu_birth === "" ||
    studentForm.address === "" ||
    studentForm.level_id === "" ||
    studentForm.niveau_etude === "" 

  ) {
    toast.add({
      severity: "error",
      summary: t("toast.error"),
      detail: t("toast.errorMessage"),
      group: "bc",
      life: 3000,
    });
    return;
  }
  const formData = new FormData();
  const combinedData = {
   
      name : studentForm.name,
      la_situation: studentForm.la_situation,
      niveau_etude: studentForm.niveau_etude,
      date_birth: format(studentForm.date_birth, "yyyy-MM-dd"),
      lieu_birth: studentForm.lieu_birth,
      level_id: studentForm.level_id,
      address: studentForm.address,
      name_school: studentForm.name_school,
      profession: studentForm.profession,
      service_mensuel: studentForm.service_mensuel,
      droit_annuel: studentForm.droit_annuel,
      class_id: studentForm.class_id.id,
      isChecked: studentForm.isChecked,
      phone: studentForm.phone,
 
  
  };

   {
    isButtonClicked.value = true;
    console.log("combinedData", combinedData);
    router.post("/students", combinedData, {
      preserveScroll: true,
      preserveState: true,
      onSuccess: async (page) => {
        addStudentVisible.value = false;
        resetStudentForm();
        toast.add({ severity: "success", summary: t("toast.success"), detail: t("toast.successMessages"), group: "bc", life: 3000, });
        isButtonClicked.value = false;
      },
      onError: (error) => {
        console.log("Error Ajoute student", error);
        isButtonClicked.value = false;
      },
    });
  }
};

//Edit Student Function
const updateStudent = () => {
  if (
    studentForm.name === "" ||
    studentForm.date_birth === "" ||
    studentForm.lieu_birth === "" ||
    studentForm.address === "" ||
    studentForm.level_id === "" ||
    studentForm.niveau_etude === "" 
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
      name : studentForm.name,
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
      isChecked: studentForm.isChecked,
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
//Delete Student Function
const deleteStudent = (item) => {
  router.delete(`/students/${item.id}`, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: (page) => {
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



const showStudent = (student) => {
  router.get(route("students.show", student.id));
};
watch(() => {
     getStudents.value = props.student;
})



//fonction print pdf inscreption
let itemToShow;
const generatePDF = async (item) => {
  try {
    itemToShow = item;
    console.log("value:", itemToShow);
    const id = itemToShow.id;
    const response = await axios.get(`/generate-pdf-inscription/${id}`, {
      responseType: "blob",
    });
    console.log("Response:", response);
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement("a");
    link.href = url;
    link.setAttribute("download", "Inscription.pdf");
    document.body.appendChild(link);
    link.click();

   
  } catch (error) {
    console.error("Error generating PDF:", error);
  }
};
</script>

<template>
  <Head :title="$t('title.students')" />
  <InsideLayout>
    <div class="bg-white p-6 m-4 rounded-xl grid xl:grid-cols-4">
      <div class="xl:col-span-3 mx-2">
        <input type="text" v-model="searchValue" :placeholder="$t('search.search')"
          class="h-14 py-6 w-full rounded-xl border border-gray-200" />
      </div>
      <div class="flex justify-start lg:m-2 xl:m-0">
        <button label="Show" @click="addStudentVisible = true"
          class="flex items-center text-lg bg-indigo-500 shadow-lg shadow-indigo-500/50 hover:bg-indigo-400 text-white p-2 rounded-xl">
          <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
            stroke="#ffffff">
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
            <g id="SVGRepo_iconCarrier">
              <circle cx="12" cy="12" r="10" stroke="#ffffff" stroke-width="1.5"></circle>
              <path d="M15 12L12 12M12 12L9 12M12 12L12 9M12 12L12 15" stroke="#ffffff" stroke-width="1.5"
                stroke-linecap="round"></path>
            </g>
          </svg><span class="mx-2">{{ $t("buttons.addStudent") }}</span>
        </button>
      </div>
      <div class="flex mt-2">
                <div class="ml-2 mr-6">
                    <Dropdown v-model="selectedLevel" :options="props.levels" filter optionLabel="level"
                              placeholder="Sélectionner un niveau" optionValue="id" class="overflow-hidden"
                              style="height: 50px; width: 251px; border: 1px solid rgb(54, 53, 53);"
                              @change="classByLevel(selectedLevel)"/>
                </div>
                <div class="flex-grow">
                    <Dropdown v-model="selectedClass" :options="filteredClasses" filter optionLabel="group"
                              placeholder="Sélectionner un classe" optionValue="id" class="overflow-hidden"
                              @change="fetchFilteredStudents(selectedClass)"
                              style="height: 50px; width: 251px; border: 1px solid rgb(54, 53, 53);"/>
                </div>
            </div>
    </div>

    <!--DATATABLE HERE-->
    <div class="m-4 outer-container" :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
        <Vue3EasyDataTable :headers="headers" :items="getStudents.data" :hide-footer="true" 
                       :search-field="searchField" :search-value="searchValue"
                       border-cell theme-color="#6F32BE" table-class-name="customize-table" table-responsive>
        <template #header-name="header">
          <p class="text-white text-[1.2rem] py-2">
           {{ $t('studentTable.Name') }}
          </p>
        </template>d
        <template #header-date_birth="header">
          <p class="text-white text-[1.2rem] py-2">
          {{ $t('studentTable.date_birth') }}
          </p>
        </template>
        <template #header-address="header">
          <p class="text-white text-[1.2rem] py-2">
           {{ $t('studentTable.Address') }}
          </p>
        </template>
         <template #header-phone="header">
          <p class="text-white text-[1.2rem] py-2">
          {{ $t('studentTable.Phone') }}
          </p>
        </template>
         <template #header-level_id="header">
          <p class="text-white text-[1.2rem] py-2">
           {{ $t('studentTable.Level') }}
          </p>
        </template>
         <template #header-niveau_etude="header">
          <p class="text-white text-[1.2rem] py-2">
          {{$t('studentTable.niveau_etude') }}
          </p>
        </template>
        <template #header-operations="header">
          <p class="text-white text-[1.2rem] py-2">
            {{ $t("tablestudent.operation") }}
          </p>
        </template>
          <template #header-print="header">
          <p class="text-white text-[1.2rem] py-2">
            print
          </p>
        </template>

        <template #item-name="item">
          <div style="display: flex; justify-content: center; align-items: center">
            <p class="text-[1rem] flex font-medium py-3"
              :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
              {{ item.name }}
            </p>
          </div>
        </template>
        <template #item-date_birth="item">
          <div style="display: flex; justify-content: center; align-items: center">
            <p class="text-[1rem] flex font-medium py-3"
              :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
              {{ item.date_birth }}
            </p>
          </div>
        </template>
        <template #item-level_id="item">
          <div style="display: flex; justify-content: center; align-items: center">
            <p class="text-[1rem] flex font-medium py-3"
              :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
              {{ item.level.level}}
            </p>
          </div>
        </template>
          <template #item-address="item">
          <div style="display: flex; justify-content: center; align-items: center">
            <p class="text-[1rem] flex font-medium py-3"
              :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
              {{ item.address}}
            </p>
          </div>
        </template>
         <template #item-phone="item">
          <div style="display: flex; justify-content: center; align-items: center">
            <p class="text-[1rem] flex font-medium py-3"
              :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
              {{ item.phone }}
            </p>
          </div>
        </template>
        <template #item-niveau_etude="item">
          <div style="display: flex; justify-content: center; align-items: center">
            <p class="text-[1rem] flex font-medium py-3"
              :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
              {{ item.niveau_etude }}
            </p>
          </div>
        </template>
        <template #item-operations="item" :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
          <div style="display: flex; justify-content: center; align-items: center">
            <button @click="editStudent(item)" class="mx-2">
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
            <button @click="showStudent(item)">
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
          </div>
        </template>
         <template #item-print="item" :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
          <div style="display: flex; justify-content: center; align-items: center">
            
             <button
                      class="flex items-center text-lg bg-yellow-500 shadow-lg shadow-yellow-500/50 hover:bg-yellow-400 text-white p-2 rounded-xl"
                      @click="generatePDF(item)"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="icon icon-tabler icon-tabler-printer"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        fill="none"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      >
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path
                          d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"
                        />
                        <path
                          d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"
                        />
                        <path
                          d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z"
                        /></svg
                      >
                    </button>
           
          </div>
        </template>
      </Vue3EasyDataTable>
       <Pagination :pagination="getStudents"/>
    </div>

    <!-- Toast -->
    <Toast position="bottom-center" group="bc" />
    <!--Add Student Modals-->
    <div class="card flex justify-content-center">
      <Dialog v-model:visible="addStudentVisible" modal :header="$t('buttons.addStudent')" :style="{ width: '50vw' }">
        <div :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
        
          <form class="grid" @submit.prevent="addStudent">
            <span class="font-bold">{{
              $t("buttons.studentInformation")
            }}</span>
            <hr />
             <div >
            <div class="grid xl:grid-cols-2 mt-6">
              
               <div class="xl:ml-4">
                <label for="name" class="mt-2 text-gray-500"> {{ $t('studentTable.Name') }}*</label>
                <input type="text" class="w-full h-12 rounded-xl" 
                  :placeholder="$t('studentTable.Name')" id="name" v-model="studentForm.name" />
              </div>

              <div class="xl:ml-4 mb-4 xl:mt-0 sm:ml-0">
               <label for="date_birth" class="mt-2 text-gray-500">{{ $t('studentTable.date_birth') }}*</label>
                <Calendar v-model="studentForm.date_birth" dateFormat="dd/mm/yy" class="w-full h-12 "
                  :placeholder="$t('studentTable.date_birth')" />
              </div> 
              

              <div class="xl:ml-4 mb-4">
                <label for="lieu_birth" class="mt-2 text-gray-500">مكان الازدياد *</label>
                <input type="text" class="w-full h-12 rounded-xl" placeholder=" مكان الازدياد "
                   id="lieu_birth" v-model="studentForm.lieu_birth" />
              </div>

              <div class="xl:ml-4 mt-4 xl:mt-0 sm:ml-0">
                <label for="address" class="mt-2 text-gray-500">{{ $t('studentTable.Address') }}*</label>
                <input type="text" class="w-full h-12 rounded-xl" :placeholder="$t('studentTable.Address')" id="address"
                  v-model="studentForm.address" />
              </div>

               <div class="xl:ml-4 mb-4">
              <label for="level_id" class="mt-2 text-gray-500">المستوى*</label>
                <Dropdown v-model="studentForm.level_id" :options="levels" optionLabel="level" placeholder="المستوى"
                  optionValue="id" class="w-full overflow-hidden" @change="classByLevel(studentForm.level_id)" />
              </div>

             <div class="xl:ml-4 mt-6 xl:mt-0 sm:ml-0">
                    <label for="niveau_etude" class="mt-2 text-gray-500"> {{$t('studentTable.niveau_etude') }}*</label>
                <input type="text" class="w-full h-12 rounded-xl" :placeholder="$t('studentTable.niveau_etude')" id="niveau_etude"
                  v-model="studentForm.niveau_etude" />
              </div>
              <div class="xl:ml-4 mb-4">
                 <label for="phone" class="mt-2 text-gray-500"> {{ $t('studentTable.Phone') }}*</label>
                <input type="text" class="w-full h-12 rounded-xl" :placeholder="$t('studentTable.Phone')" id="phone"
                  v-model="studentForm.phone" />
              </div>

              <div class="xl:ml-4 mt-6 xl:mt-0 sm:ml-0">
                <label for="profession" class="mt-2 text-gray-500">مهنة او مهنة الأب*</label>
                <input type="text" class="w-full h-12 rounded-xl" placeholder="مهنة او مهنة الأب " id="profession"
                  v-model="studentForm.profession" :disabled="isDisabled" />
              </div>
               <div class="xl:ml-4 mb-4">
                <label for="name_school" class="mt-2 text-gray-500">  اسم المؤسسة *</label>
                <input type="text" class="w-full h-12 rounded-xl" placeholder="اسم المؤسسة " id="name_school"
                  v-model="studentForm.name_school" :disabled="isDisabled" />
              </div>
               <div class="xl:ml-4 mt-6 xl:mt-0 sm:ml-0">
                <label for="la_situation" class="mt-2 text-gray-500">الحالة العائلية* </label>
                <input type="text" class="w-full h-12 rounded-xl" placeholder="الحالة العائلية" id="la_situation"
                  v-model="studentForm.la_situation" :disabled="isDisabled" />
              </div>
           
                 <div class="xl:ml-4 mb-4">
                <label for="name_school" class="mt-2 text-gray-500">تسجيل  </label>
              
                    <input type="checkbox" class="ml-6" v-model="studentForm.isChecked" id="isChecked" />
              </div>
            </div>
             </div>
            
          
              <div v-if="studentForm.isChecked" class="grid xl:grid-cols-2 mt-4" >
                   
                <div class="xl:ml-4 mb-4">
                  <label for="classe_id" class="mt-2 text-gray-500">فصل*</label>
                <Dropdown v-model="studentForm.class_id" :options="filteredClasses" optionLabel="group"
                  placeholder="فصل" class="w-full overflow-hidden" />
                </div>
              
              <div class="xl:ml-4 mt-6 xl:mt-0 sm:ml-0">
                 <label for="service_mensuel" class="mt-2 text-gray-500">الواجب الشهري *</label>
                 <input type="text" class="w-full h-12 rounded-xl" placeholder="الواجب الشهري" id="service_mensuel"
                  v-model="studentForm.service_mensuel" :disabled="isDisabled" />
                </div>

               <div class="xl:ml-4 mb-4">
                 <label for="droit_annuel" class="mt-2 text-gray-500">الواجب السنوي *</label>
                 <input type="text" class="w-full h-12 rounded-xl" placeholder="الواجب السنوي" id="droit_annuel"
                  v-model="studentForm.droit_annuel" :disabled="isDisabled" />
                </div>
              </div>
           


            <div class="flex justify-end mt-5">
              <button :disabled="isButtonClicked"
                class="flex items-center text-lg text-white p-2 rounded-xl hover:bg-[#9464D1] bg-[#6F32BE] hover:shadow-lg">
                <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                  stroke="#ffffff">
                  <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                  <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                  <g id="SVGRepo_iconCarrier">
                    <circle cx="12" cy="12" r="10" stroke="#ffffff" stroke-width="1.5"></circle>
                    <path d="M15 12L12 12M12 12L9 12M12 12L12 9M12 12L12 15" stroke="#ffffff" stroke-width="1.5"
                      stroke-linecap="round"></path>
                  </g>
                </svg><span class="mx-2">{{ $t("buttons.addStudent") }}</span>
              </button>
            </div>
          </form>
        </div>
      </Dialog>
    </div>

    <!--EDI STUDENT MODAL-->
    <div class="card flex justify-content-center m-2">
      <Dialog v-model:visible="editStudentVisible" modal header="تعديل معلومات الطالب " :style="{ width: '50rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
          
            <form class="grid mx-2" @submit.prevent="updateStudent" enctype="multipart/form-data">
            
            <div class="grid xl:grid-cols-2 mt-6">
              <div class="xl:ml-4">
                <label for="name" class="mt-2 text-gray-500"> {{ $t('studentTable.Name') }}*</label>
                <input type="text" class="w-full h-12 rounded-xl" 
                  :placeholder="$t('studentTable.Name')" id="name" v-model="studentForm.name" />
              </div>

              <div class="xl:ml-4 mb-4 xl:mt-0 sm:ml-0">
               <label for="date_birth" class="mt-2 text-gray-500">{{ $t('studentTable.date_birth') }}*</label>
                <Calendar v-model="studentForm.date_birth" dateFormat="dd/mm/yy" class="w-full h-12 "
                  :placeholder="$t('studentTable.date_birth')" />
              </div> 

              <div class="xl:ml-4 mb-4">
                <label for="lieu_birth" class="mt-2 text-gray-500">مكان الازدياد *</label>
                <input type="text" class="w-full h-12 rounded-xl" placeholder=" مكان الازدياد "
                   id="lieu_birth" v-model="studentForm.lieu_birth" />
              </div>

              <div class="xl:ml-4 mt-4 xl:mt-0 sm:ml-0">
                <label for="address" class="mt-2 text-gray-500">{{ $t('studentTable.Address') }}*</label>
                <input type="text" class="w-full h-12 rounded-xl" :placeholder="$t('studentTable.Address')" id="address"
                  v-model="studentForm.address" />
              </div>

                 <div class="xl:ml-4 mb-4">
              <label for="level_id" class="mt-2 text-gray-500">المستوى*</label>
                <Dropdown v-model="studentForm.level_id" :options="levels" optionLabel="level" placeholder="المستوى"
                  optionValue="id" class="w-full overflow-hidden" @change="classByLevel(studentForm.level_id)" />
              </div>

            <div class="xl:ml-4 mt-6 xl:mt-0 sm:ml-0">
                    <label for="niveau_etude" class="mt-2 text-gray-500"> {{$t('studentTable.niveau_etude') }}*</label>
                <input type="text" class="w-full h-12 rounded-xl" :placeholder="$t('studentTable.niveau_etude')" id="niveau_etude"
                  v-model="studentForm.niveau_etude" />
              </div>


                  <div class="xl:ml-4 mb-4">
                 <label for="phone" class="mt-2 text-gray-500"> {{ $t('studentTable.Phone') }}*</label>
                <input type="text" class="w-full h-12 rounded-xl" :placeholder="$t('studentTable.Phone')" id="phone"
                  v-model="studentForm.phone" />
              </div>

              <div class="xl:ml-4 mt-6 xl:mt-0 sm:ml-0">
                <label for="profession" class="mt-2 text-gray-500">مهنة او مهنة الأب*</label>
                <input type="text" class="w-full h-12 rounded-xl" placeholder="مهنة او مهنة الأب " id="profession"
                  v-model="studentForm.profession" :disabled="isDisabled" />
              </div>

                 <div class="xl:ml-4 mb-4">
                <label for="name_school" class="mt-2 text-gray-500">  اسم المؤسسة *</label>
                <input type="text" class="w-full h-12 rounded-xl" placeholder="اسم المؤسسة " id="name_school"
                  v-model="studentForm.name_school" :disabled="isDisabled" />
              </div>
               <div class="xl:ml-4 mt-6 xl:mt-0 sm:ml-0">
                <label for="la_situation" class="mt-2 text-gray-500">الحالة العائلية* </label>
                <input type="text" class="w-full h-12 rounded-xl" placeholder="الحالة العائلية" id="la_situation"
                  v-model="studentForm.la_situation" :disabled="isDisabled" />
              </div>

                   <div class="xl:ml-4 mb-4">
                <label for="name_school" class="mt-2 text-gray-500">تسجيل  </label>
              
                    <input type="checkbox" class="ml-6" v-model="studentForm.isChecked" id="isChecked" />
              </div>
               </div>
               <div v-if="studentForm.isChecked" class="grid xl:grid-cols-2 mt-4" >
                   
               <div class="xl:ml-4 mb-4">
                  <label for="classe_id" class="mt-2 text-gray-500">فصل*</label>
                <Dropdown v-model="studentForm.class_id" :options="filteredClasses" optionLabel="group"
                  placeholder="فصل" class="w-full overflow-hidden" />
                </div>
              
              <div class="xl:ml-4 mt-6 xl:mt-0 sm:ml-0">
                 <label for="service_mensuel" class="mt-2 text-gray-500">الواجب الشهري *</label>
                 <input type="text" class="w-full h-12 rounded-xl" placeholder="الواجب الشهري" id="service_mensuel"
                  v-model="studentForm.service_mensuel" :disabled="isDisabled" />
                </div>

               <div class="xl:ml-4 mb-4">
                 <label for="droit_annuel" class="mt-2 text-gray-500">الواجب السنوي *</label>
                 <input type="text" class="w-full h-12 rounded-xl" placeholder="الواجب السنوي" id="droit_annuel"
                  v-model="studentForm.droit_annuel" :disabled="isDisabled" />
                </div>
              </div>
           

              <div class="flex justify-end mt-5">
                <button
                  class="flex items-center text-lg bg-yellow-500 shadow-lg shadow-yellow-500/50 hover:bg-yellow-400 text-white p-2 rounded-xl">
                  <svg width="30px" height="30px" viewBox="0 -0.5 25 25" fill="none" xmlns="http://www.w3.org/2000/svg"
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
                  </svg><span class="mx-2">تعديل معلومات الطالب </span>
                </button>
              </div>
            </form>
         
        </div>
      </Dialog>
    </div>

    <!--ARE YOU SURE ??-->
    <div class="card flex justify-content-center m-2">
      <Dialog v-model:visible="areYouSure" modal :header="$t('buttons.removeStudent')" :style="{ width: '50rem' }"
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
        <p class="text-2xl my-6 text-center">{{ $t("delete.sure") }}</p>

        <div class="flex justify-end mt-5">
          <button @click="deleteStudent(itemToDelete)"
            class="flex items-center text-lg text-white p-2 rounded-xl bg-red-500 shadow-lg shadow-red-500/50 hover:bg-red-400">
            <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="">
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
            </svg><span class="mx-2">{{ $t("buttons.removeStudent") }}</span>
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

.flex {
  display: flex;
}

.flex-grow {
  flex: 1;
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