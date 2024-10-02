<script setup>
import InsideLayout from "../../Layouts/InsideLayout.vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import Dialog from "primevue/dialog";
import { ref, watch, watchEffect } from "vue";
import { useForm } from '@inertiajs/vue3';
import Toast from 'primevue/toast';
import { useToast } from "primevue/usetoast";
import { useI18n } from "vue-i18n";
import Vue3EasyDataTable from "vue3-easy-data-table";
import "vue3-easy-data-table/dist/style.css";
import Dropdown from "primevue/dropdown";
const { t } = useI18n();
const toast = useToast();


const props = defineProps({
  exams: {
    type: Array,
    required: true,
  },
  TeachersHasMatiere: {
    type: Array,
    required: true,
  },
  locales: {
    type: Array,
    required: true,
  },
  teachers: {
    type: Array,
    required: true,
  },
  matieres: {
    type: Array,
    required: true,
  },
  groupes: {
    type: Array,
    required: true,
  },
});


const page = usePage();
//get name the account connected
const userName = page.props.auth.user.name;
//get email the account connected
const getEmailUser = page.props.auth.user.email;
//get ID the account connected
const getIdUserConeceted = (id) => {
  const testfon = props.teachers.filter((e) => e.email == id);
  return testfon.map((e) => {
    return e.id;
  });
};

const classWithLevel = (id) => {
  const filteredClasses = props.TeachersHasMatiere.filter(
    (e) => (e.teacher_id = id)
  );
  return filteredClasses.map((e) => {
    return e.teacher_id;
  });
};

const teacherWhereConnecte = (id) => {
  const filtreTeacher = props.TeachersHasMatiere.filter(
    (e) => e.teacher_id == id
  );
  return filtreTeacher.map((e) => {
    return e.group_id;
  });
};
//return l'object de group
const GetIdTheGroup = (id) => {
  const testfon = props.TeachersHasMatiere.filter((e) => e.teacher_id == id);
  return testfon
  //.map((e) => { return e.group_id;});
};
//return l'object de matiere
const GetIdTheMatiere = (id) => {
  const testfon = props.TeachersHasMatiere.filter((e) => e.teacher_id == id);
  return testfon.map((e) => { return e.matier_id;});
};

const mat = (ids) => {
  const filteredMatieres = props.matieres.filter((e) => ids.includes(e.id));

  return filteredMatieres.map((e) => e.name);
};

const getnamegroup = (ids) => {
  const filteredMatieres = props.groupes.filter((e) => ids.includes(e.id));

  return filteredMatieres;
};
const getnamematiere = (ids) => {
  const filteredMatieres = props.matieres.filter((e) => ids.includes(e.id));

  return filteredMatieres;
};
//id dyal teacher is conected
const var1 = GetIdTheGroup(getIdUserConeceted(getEmailUser));
const var2 = GetIdTheMatiere(getIdUserConeceted(getEmailUser));
const veriable2 = mat(var1);
// return name de group
const var4 = getnamegroup(var1);

const var5 = getnamematiere(var2);

const getLocaleName = (id) => {
  const localeName = props.locales.find((locale) => locale.id === id);
  return localeName.locale;
};

const searchValue = ref("");
const selectedGroup = ref(null);
const examDate = ref('');
const selectedLocale = ref(null);
const selectedMatiere = ref(null);



const updateMatiereOptions = () => {
  const selectedGroupId = selectedGroup.value;
  const selectedMatiereId = selectedMatiere.value;
  console.log("group id > ", selectedGroup.value);
  console.log("teacher id > ", getIdUserConeceted(page.props.auth.user.email));

  const matchingMatieres = props.TeachersHasMatiere.filter((item) => {
    console.log("item > ", item);
    return (
      item.group_id == selectedGroupId &&
      item.teacher_id == getIdUserConeceted(page.props.auth.user.email)
    );
  }).map((item) => item.matier_id);
console.log("matchingMatieres > ", matchingMatieres);
  selectedMatiere.value = matchingMatieres[0] || "";

  // Store both selected matiere and group IDs
//   examsForm.teacherHasMatiers_id = selectedGroupId; // Set to group ID
//   examsForm.selectedMatiereId = selectedMatiereId; // Store selected matiere ID (if needed)
};

const examsForm = useForm({
  id: null,
  date: "",
  locale_id: "",
  teacherHasMatiers_id: "",

});
const resertExamsForm = () => {
  examsForm.date = "";
  examsForm.locale_id = "";
  examsForm.teacherHasMatiers_id = "";
  
};
const openAddExamsModal = () => {
  examsForm.reset();
  resertExamsForm();
  addExamsVisible.value = true;
};

import axios from 'axios';

const AddExam = async () => {
  try {
    if (examDate.value === "" || selectedMatiere.value === "" || selectedLocale.value === "") {
      // Validation error, show error toast
      toast.add({
        severity: "error",
        summary: t("toast.error"),
        detail: t("toast.errorMessage"),
        group: "bc",
        life: 3000,
      });
      return;
    }

    console.log('Request Payload:', {
      date: examDate.value,
      teacherHasMatiers_id: selectedMatiere.value,
      locale_id: selectedLocale.value,
    });

    const response = await axios.post('/exams', {
      date: examDate.value,
      teacherHasMatiers_id: selectedMatiere.value,
      locale_id: selectedLocale.value,
    });

    // Assuming the server responds with a 2xx status code for a successful request
    if (response.status >= 200 && response.status < 300) {
      // Exam added successfully, show success toast
      toast.add({
        severity: "success",
        summary: t("toast.success"),
        detail: t("toast.successMessages"),
        group: "bc",
        life: 3000,
      });

      // Handle success...
    } else {
      console.error('Unexpected response status:', response.status);
      // Handle other response statuses if needed...
    }
  } catch (error) {
    console.error('Axios Error:', error);

    // Add generic error toast
    toast.add({
      severity: "error",
      summary: t("toast.error"),
      detail: t("toast.errorMessage"),
      group: "bc",
      life: 3000,
    });
  }
};




</script>


<template>
  <Head :title="$t('asidebar.exams')" />
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
    </div>
<!-- ADD EXAM -->
<Toast position="bottom-center" group="bc" />
    <div class="grid xl:grid-cols-4">
    <div>
      <label for="dateInput" class="block text-sm font-medium text-gray-700">Date</label>
     <input
    v-model="examDate"
    id="dateInput"
    type="date"
    class="w-full h-14 rounded-xl border border-gray-200 my-2 overflow-hidden"
  />
    </div>
    <div>
      <label for="dropdown2" class="block text-sm font-medium text-gray-700">Group</label>
      <Dropdown
        v-model="selectedGroup"
        @change="updateMatiereOptions"
        :options="var1"
        placeholder="Group"
        optionLabel="group_id"
        optionValue="id"
        class="w-full xl:ml-1 h-14 mt-2 rounded-xl border border-gray-200 my-2 overflow-hidden"
      />
    </div>
    <div>
      <label for="matiereDropdown" class="block text-sm font-medium text-gray-700">Matiere</label>
      <Dropdown
        v-model="selectedMatiere"
        filter
        :options="var1"
        placeholder="Matiere"
        optionLabel="matier_id"
        optionValue="id"
        class="w-full xl:ml-2 h-14 mt-2 rounded-xl border border-gray-200 my-2 overflow-hidden"
      />
    </div>
    <div>
      <label for="localeDropdown" class="block text-sm font-medium text-gray-700">Locale</label>
      <Dropdown
        id="localeDropdown"
        placeholder="Locale"
        v-model="selectedLocale"
        :options="locales"
         optionLabel="locale"
        optionValue="id"
        class="w-full xl:ml-3 h-14 mt-2 rounded-xl border border-gray-200 my-2 overflow-hidden"
      />
    </div>
  </div>
  <div class="flex justify-center items-center">
    <div class="flex justify-start lg:m-2 xl:m-0">
                <button label="Show" @click="AddExam"
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
                    </svg><span class="mx-2">Add Exam</span></button>
            </div>
  </div>
 

{{var1}}
  
  </InsideLayout>
</template>