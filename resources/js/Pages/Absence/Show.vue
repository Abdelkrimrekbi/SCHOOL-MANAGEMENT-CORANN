<script setup>
import { ref, defineProps, onMounted ,watch} from "vue";
import InsideLayout from "../../Layouts/InsideLayout.vue";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import { useI18n } from "vue-i18n";
import Dropdown from "primevue/dropdown";
import Vue3EasyDataTable from "vue3-easy-data-table";
import "vue3-easy-data-table/dist/style.css";
import { format } from "date-fns";
import Dialog from "primevue/dialog";
import { Head, router, useForm } from "@inertiajs/vue3";
import Button from "primevue/button";
import TabView from "primevue/tabview";
import TabPanel from "primevue/tabpanel";
import "primeicons/primeicons.css";
import moment from 'moment';
const toast = useToast();
const { t } = useI18n();
//Get Data From Controller:
const props = defineProps({
  absences: {
    type: Object,
    required: true,
  },

  groupMatiereTeacher: {
    type: Object,
    required: true,
  },
  seanceId: {
    type: Number,
    required: true,
  },
  seances: {
    type: Number,
    required: true,
  },
});

const getCurrentDate = () => {
  const today = new Date();
  return today.toISOString().split("T")[0];
};
// const SS = props.seances[0].absence[0].list_Absence;
const absenceForm = useForm({
  student_id: ref([]),
  date: getCurrentDate(),
});
//const page = usePage();
const headers = [
  { text: "name", value: "name", sortable: true },
 // { text: "date_birth", value: "date_birth", sortable: true },
  { text: "niveau_etude", value: "niveau_etude", sortable: true },
  { text: "isAbsence", value: "isAbsence", sortable: true },
  { text: "operations", value: "operations" },
];

const listStudent = ref([]);
const absentStudentIds = ref([]);
const selectedGroupId = ref([]);
const searchField = [""];
const searchValue = ref();
const isButtonClicked = ref(false);

const getAbsentStudentIds = (absences) => {
    const studentIds = absences.flatMap((absence) => {
        return absence.list_Absence.map((absentStudent) => absentStudent.id);
    });
    return studentIds;
};
const getAbsentStudentIdse = (absences) => {
    const data = absences;
    if (Array.isArray(data) && data.length > 0) {
        const firstItem = data[0];
        if (firstItem.hasOwnProperty("list_Absence")) {
            return firstItem.list_Absence.map(absentStudent => absentStudent.id);
        } else {
            return [];
        }
    } else {
        return [];
    }
};



selectedGroupId.value = props.groupMatiereTeacher.map((e) => e.group_id);



// const listAbsence = async () => {

const listAbsence = async () => {
  if (selectedGroupId) {
    const seances = props.seances;
    if (seances && seances.length > 0 && seances[0].absence && seances[0].absence.length > 0) {
      const StudentsAbsence = seances[0].absence[0].list_Absence;
      const students = seances[0].teacher_subject_groups.group.student;
      const StudentsAbsenceIds = StudentsAbsence.map((absence) => absence.id);
      
      listStudent.value = students.map((student) => {
        const isAbsent = StudentsAbsenceIds.includes(student.id);
        console.log("Is student absent: ", isAbsent);
        return {
          id: student.id,
          name: student.name,
          niveau_etude: student.niveau_etude,
          group_id: student.class_id,
          isAbsence: isAbsent,
        };
      });
    } else {
      const students = seances[0].teacher_subject_groups.group.student;
      const StudentsAbsenceIds = '';
      
      listStudent.value = students.map((student) => {
        const isAbsent = StudentsAbsenceIds.includes(student.id);
        console.log("Is student absent: ", isAbsent);
        return {
          id: student.id,
          name: student.name,
          niveau_etude: student.niveau_etude,
          group_id: student.class_id,
          isAbsence: isAbsent,
        };
      });
    }
  } else {
    console.error("Error: selectedGroupId is not defined");
  }
};




watch( ()=> {
  listAbsence();
    
});


// const showlNoteVisible = ref(false);
let itemToShow;
const showlNote = (item) => {
  itemToShow = item;
  showlNoteVisible.value = true;
 // console.log("item", item);
};
const getNotesStudentsAvant = ref("");
const getNotesStudents = ref("");
const addNoteVisible = ref(false);


const nAbsence = [];
const checkStudent = (item, seance) => {
    console.log("seaneid", props.seanceId);
    console.log("item", item);
    console.log("seance", seance);
    console.log("absenceForm.student_id", absenceForm.student_id);
    item.isAbsence = !item.isAbsence;
    const index = absenceForm.student_id.findIndex(student => student.id === item.id);

    console.log("index :", index);
    if (index !== -1) {
        absenceForm.student_id.forEach(element => {
            if (element.id === item.id) {
                const seanceIndex = element.seanceIds.findIndex(session => session.seance_id === seance);

                console.log("seanceIndex", seanceIndex);
                if (seanceIndex !== -1) {
                    element.seanceIds.splice(seanceIndex, 1);
                    if (element.seanceIds.length === 0) {
                        const studentIndex = absenceForm.student_id.indexOf(element);
                        console.log("seanceIndex2", studentIndex);
                        if (studentIndex !== -1) {
                            absenceForm.student_id.splice(studentIndex, 1);
                        }
                    }
                } else {
                    element.seanceIds.push({ seance_id: seance, id: item.id, name: item.name }); 
                    console.log("seanceIndex3", element.seanceIds);
                }
            }
        });
    } else {
        item.seanceIds = [{ seance_id: seance, id: item.id, name: item.name }]; // Include ID and Name in seanceIds
        absenceForm.student_id.push(item);
    }

   
};



// ADD___ABSENCE
const addAbsence = () => {
  console.log("value in addabsence ",absenceForm.student_id)
    const absenceData = absenceForm.student_id.map((student) => {
        return {
            id: student.id,
            nom: student.name,
          //  seanceIds: student.seanceIds // Include the list of session ids if needed
        };
    });
    const combinedData = {
        list_Absence: absenceData,
        seance_id: props.seanceId,
        date: absenceForm.date,
        group_id: selectedGroupId.value[0],
    };

    isButtonClicked.value = true;

    router.post("/absence",{ absences: combinedData },
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: (page) => {
                absenceForm.reset();
                toast.add({
                    severity: "success",
                    summary: t("toast.success"),
                    detail: t("toast.successMessages"),
                    group: "bc",
                    life: 3000,
                });
            },
            onError: (error) => {
                console.log("Error", error);
            },
        }
    );

    if (nAbsence.length > 0) {
        deleteAbsences(nAbsence, props.seanceId);
        isButtonClicked.value = false;
    }

    toast.add({
        severity: "success",
        summary: t("toast.success"),
        detail: t("toast.successMessages"),
        group: "bc",
        life: 3000,
    });
    isButtonClicked.value = false;
};

async function deleteAbsences(nAbsence) {
  for (const student_id of nAbsence) {
    try {
      await axios.delete(`/absence/${student_id}/${props.seanceId}`, {
        preserveScroll: true,
        preserveState: true,
      });
    } catch (error) {
      console.log(`Error deleting absence for student ${student_id}`, error);
    }
  }
}


const numbers = Array.from({ length: 60 }, (_, i) => i + 1);

const arrayTomon = [
  "الثمن الأول",
  "الثمن الثاني",
  "الثمن الثالت",
 "الثمن الرابع",
 "الثمن الخامس",
 "الثمن السادس",
 "الثمن السابع",
 "الثمن التامن"
];
const ingredient = ref("");
const IDnote = ref("");

/* fonction add note in models */

const AddNote = (item) => {
   // console.log("item :", item);
    if (noteForm.lhizb.is === "" || noteForm.tomon === "" || noteForm.evaluation === "") {
        toast.add({
            severity: "error",
            summary: t("toast.error"),
            detail: t("toast.errorMessage"),
            group: "bc",
            life: 3000,
        });

        return;
    }

    const formData = {
        lhizb: noteForm.lhizb,
        tomon: noteForm.tomon,
        evaluation: noteForm.evaluation,
        description: noteForm.description,
        seance_id: props.seanceId,
        student_id: noteForm.student_id,
    };

    axios.get("/getnote-bySeance/" + formData.student_id + "/" + formData.seance_id)
        .then((response) => {
            const data = response.data;
            if (data && data.notesSeance && data.notesSeance.length > 0) {
                const IDnote = data.notesSeance.map((e) => ({
                    id: e.id
                }));
     IDnote.forEach(note => {
                    router.put("/notes/" + note.id, formData, {
                        preserveScroll: true,
                        preserveState: true,
                        onProgress: (page) => {},
                        onSuccess: (page) => {
                            resetNoteForm();
                           isButtonClicked.value = false;
                            toast.add({
                                severity: "success",
                                summary: t("toast.success"),
                                detail: t("toast.successMessages"),
                                group: "bc",
                                life: 3000,
                            });
                            addNoteVisible.value = false;
                        },
                    });
                });
            }else {
          
    router.post("/notes", formData, {
      preserveScroll: true,
      preserveState: true,
      onSuccess: (page) => {
        resetNoteForm();
        isButtonClicked.value = false;
        toast.add({
          severity: "success",
          summary: t("toast.success"),
          detail: t("toast.successMessages"),
          group: "bc",
          life: 3000,
        });
        addNoteVisible.value = false;
      },
    });
       }
    });
   
  };


// partie notee
function formatDate(dateString) {
    const date = new Date(dateString);
    // Format the date as per your requirement
    const formattedDate = `${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getDate().toString().padStart(2, '0')} ${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}:${date.getSeconds().toString().padStart(2, '0')}`;
    return formattedDate;
}

const noteForm = useForm({
  lhizb: "",
  tomon: "",
  description: "",
  evaluation: "",
  student_id: null,
});
const resetNoteForm = () => {
  noteForm.lhizb = "";
  noteForm.tomon = "";
  noteForm.description = "";
  noteForm.evaluation = "";
  noteForm.student_id = null;
   getNotesStudentsAvant.value="";
};
/* fonction add note in models */
const openAddNoteModal = (item) => {
 // console.log("item test  :", item);
  resetNoteForm();
  axios
    .get("/get-notestudent-byseanceID/" + item.id + "/" + props.seanceId)
    .then((response) => {
      const data = response.data;
       if (data && data.notesAvant && data.notesAvant.length > 0){
          getNotesStudentsAvant.value = data.notesAvant.map((e) => ({
          lhizb: e.lhizb,
          evaluation: e.evaluation,
          tomon: e.tomon,
          description: e.description,
          createDate:moment(e.created_at).format('YYYY-MM-DD HH:mm'),
        }));
         if (data && data.notes && data.notes.length > 0) {
        getNotesStudents.value = data.notes.map((e) => ({
          lhizb: e.lhizb,
          student_id: e.student_id,
          seance_id: e.seance_id,
          evaluation: e.evaluation,
          tomon: e.tomon,
          description: e.description,
        }));
       // console.log("hadchi likyji ", getNotesStudents);
        addNoteVisible.value = true;
        noteForm.lhizb = getNotesStudents.value[0].lhizb;
        noteForm.id = getNotesStudents.value[0].id;
        noteForm.tomon = getNotesStudents.value[0].tomon;
        noteForm.student_id = getNotesStudents.value[0].student_id;
        noteForm.seance_id = getNotesStudents.value[0].seance_id;
        noteForm.evaluation = getNotesStudents.value[0].evaluation;
        noteForm.description = getNotesStudents.value[0].description;
        } else {
          addNoteVisible.value = true;
         noteForm.student_id = item.id;

        }
        }else {
             getNotesStudentsAvant.value="";
              addNoteVisible.value = true;
             noteForm.student_id = item.id;
        }
    
    });
};


</script>
<template>
  <Head />
  <InsideLayout>
      <Head :title="$t('asidebar.absences')" />
         <div class="m-4 outer-container" :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
      <div>
        <div class="m-4 p-6 bg-white rounded-md">
        <div class="center">

    <div class="group-container">
    <h2 class="group-title">Group: {{ groupMatiereTeacher[0].group.group }}</h2>
  </div>


     </div>  

        <div>
          <Toast position="bottom-center" group="bc" />

          <Vue3EasyDataTable
            :headers="headers"
            :items="listStudent"
            :search-field="searchField"
            :search-value="searchValue"
            border-cell
            theme-color="#6F32BE"
            table-class-name="customize-table"
          >
            <template #header-name="header">
              <div class="flex justify-center items-center">
                <p class="text-white text-[1.2rem] py-2">Name</p>
              </div>
            </template>

            <template #header-niveau_etude="header">
              <div class="flex justify-center items-center">
                <p class="text-white text-[1.2rem] py-2">niveau etude</p>
              </div>
            </template>

            <!-- <template #header-last_name="header">
              <div class="flex justify-center items-center">
                <p class="text-white text-[1.2rem] py-2">Last name</p>
              </div>
            </template> -->

            <template #header-isAbsence="header">
              <div class="flex justify-center items-center">
                <p class="text-white text-[1.2rem] py-2 m-0">Absence</p>
              </div>
            </template>
            <template #header-operations="header">
              <div
                style="
                  display: flex;
                  justify-content: center;
                  align-items: center;
                "
              >
                <p class="text-white text-[1.2rem] py-2">المحفوظ</p>
              </div>
            </template>

            <template #item-name="item">
              <div class="flex justify-center items-center">
                <p class="text-[1rem] flex font-semibold py-3">
                  {{ item.name }}
                </p>
              </div>
            </template>

            <template #item-niveau_etude="item">
              <div class="flex justify-center items-center">
                <p class="text-[1rem] flex font-semibold py-3">
                  {{ props.seanceId }}
                </p>
              </div>
            </template>
<!-- 
            <template #item-last_name="item">
              <div class="flex justify-center items-center">
                <p class="text-[1rem] flex font-semibold py-3">
                  {{ item.niveau_etude }}
                </p>
              </div>
            </template> -->

            <template #item-isAbsence="item">
              <div class="flex justify-center items-center">
                <input
                  v-model="item.isAbsence"
                  @change="checkStudent(item,props.seanceId)"
                  type="checkbox"
                  id="checkAll"
                />
              </div>
            </template>

            <template #item-operations="item">
              <div
                style="
                  display: flex;
                  justify-content: center;
                  align-items: center;
                "
              >
                <button @click="openAddNoteModal(item)">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="icon icon-tabler icon-tabler-presentation-analytics"
                    width="36"
                    height="36"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="#6366F1"
                    fill="none"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
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
          </Vue3EasyDataTable>
          <form class="grid" @submit.prevent="addAbsence">
            <div class="flex items-center justify-center">
              <button
                :disabled="isButtonClicked"
                class="w-40 h-10 mt-6 rounded-xl border border-gray-200 my-2 text-white bg-lime-400"
              >
                Confirm
              </button>
            </div>
          </form>
        </div>

        <!--SHOW  MODAL-->
      <div class="card flex justify-content-center m-2">
          <Dialog
            v-model:visible="addNoteVisible"
            header="الطلاب"
            modal
            :style="{ width: '60rem' }"
            :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
          >
           <div :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
            <!-- Content divided into two parts -->
            <div style="display: flex; align-items: stretch">

              <!-- Left section -->
        
         <div v-if="getNotesStudentsAvant != ''" style="flex: 1; padding: 10px; display: flex; flex-direction: column; border: 1px solid #ccc; border-radius: 5px;">
            
           <div class="upper-half" style="flex: 1; border-radius: 5px; padding: 10px;  border: 1px solid #ccc;">
             <!-- <div style="flex: 1; display: flex; flex-direction: column; text-align: right;"> -->
             <div class="header text-center">
         <h2 class="text-l font-bold text-gray centre">الحصة الماضية</h2>
           </div>
           <div class="grid grid-cols-3 m-2">
            <p class="text-l font-semibold text-[#6F32BE] text-right">الحزب :</p>
            <p class="text-l col-span-2">{{getNotesStudentsAvant[0].lhizb}}</p>
            
            
        </div>
        <div class="grid grid-cols-3 m-2">
            <p class="text-l font-semibold text-[#6F32BE]">الثمن  : </p>
            <p class="text-l col-span-2">{{getNotesStudentsAvant[0].tomon}}</p>
            
        </div>
        <div class="grid grid-cols-3 m-2">
            <p class="text-l font-semibold text-[#6F32BE]">التقييم :  </p>
            <p class="text-l col-span-2">{{getNotesStudentsAvant[0].evaluation}}</p>
            
        </div>
        <div class="grid grid-cols-3 m-2">
            <p class="text-l font-semibold text-[#6F32BE]">تاريخ : </p>
            <p class="text-l col-span-2">{{getNotesStudentsAvant[0].createDate}}</p>
            
        </div>
        <div class="grid grid-cols-3 m-2">
            <p class="text-l font-semibold text-[#6F32BE]">التعليق :  </p>
            <p class="text-l col-span-2">{{getNotesStudentsAvant[0].description}}</p>
            
        </div>
            </div>
     <!-- <div  style="flex: 1;  display: flex; flex-direction: column;  solid #ccc; border-radius: 5px; margin-top: 5px;">
           <div v-if="getNotesStudentsAvant && getNotesStudentsAvant.length > 1" class="upper-half" style="flex: 1; border-radius: 5px; padding: 10px; border: 1px solid #ccc;">
             <div class="header text-center">
        <h2 class="text-l font-bold text-gray centre">الحصة  قبل الماضية</h2>
           </div>
        <div class="grid grid-cols-3 m-2">
            <p class="text-l font-semibold text-[#6F32BE] ">الحزب : </p>
            <p class="text- col-span-2">{{getNotesStudentsAvant[1].lhizb}}</p>
            
        </div>
        <div class="grid grid-cols-3 m-2">
            <p class="text-l font-semibold text-[#6F32BE]">الثمن :</p>
            <p class="text-l col-span-2">{{getNotesStudentsAvant[1].tomon}}</p>
            
        </div>
        <div class="grid grid-cols-3 m-2">
            <p class="text-l font-semibold text-[#6F32BE]">التقييم : </p>
            <p class="text-l col-span-2">{{getNotesStudentsAvant[1].evaluation}}</p>
            
        </div>
        <div class="grid grid-cols-3 m-2">
            <p class="text-l font-semibold text-[#6F32BE]">تاريخ : </p>
            <p class="text-l col-span-2">{{getNotesStudentsAvant[1].createDate}}</p>
            
        </div>
        <div class="grid grid-cols-3 m-2">
            <p class="text-l font-semibold text-[#6F32BE]">التعليق :  </p>
            <p class="text-l col-span-2">{{getNotesStudentsAvant[1].description}}</p>
            
        </div>
           </div>
          </div> -->
           </div>
             
              <!-- <div style="width: 2px; background-color: #ccc"></div> -->

             
             
                <!-- Content for the right section goes here -->
               
                   <div style="flex: 1; padding: 10px">
                <form class="grid" @submit.prevent="AddNote">
                  <div>
                    <label for="source" class="mt-2 text-gray-500"
                      >الحزب *</label
                    >
                    <select
                      class="w-full h-14 rounded-xl border border-gray-200 my-2"
                      id="source"
                      v-model="noteForm.lhizb"
                    >
                      <option
                        v-for="number in numbers"
                        :value="number"
                        :key="number"
                      >
                        {{ number }}
                      </option>
                    </select>
                  </div>

                  <div>
                    <label for="tomon" class="mt-2 text-gray-500">الثمن*</label>
                    <select
                      class="w-full h-14 rounded-xl border border-gray-200 my-2"
                      id="totmon"
                      v-model="noteForm.tomon"
                    >
                      <option
                        v-for="item in arrayTomon"
                        :value="item"
                        :key="item"
                      >
                        {{ item }}
                      </option>
                    </select>
                  </div>

                  <label for="amount" class="mt-2 text-gray-500"
                    >التقييم *</label
                  >
                  <div class="card flex justify-content-center m-3">
                    <div class="flex flex-wrap gap-3">
                      <div class="flex align-items-center mr-2">
                        <input
                          type="radio"
                          id="ingredient1"
                          v-model="noteForm.evaluation"
                          value="ضعيف"
                          name="ضعيف"
                        />
                        <label for="ingredient1" class="ml-2 red mr-2">ضعيف</label>
                      </div>
                      <div class="flex align-items-center mr-2">
                        <input
                          type="radio"
                          id="ingredient2"
                          v-model="noteForm.evaluation"
                          value="متوسط"
                          name="متوسط"
                        />
                        <label for="ingredient2" class="ml-2 gray mr-2"
                          >متوسط</label
                        >
                      </div>
                      <div class="flex align-items-center mr-2">
                        <input
                          type="radio"
                          id="ingredient3"
                          v-model="noteForm.evaluation"
                          value="لابأس به"
                          name="لابأس به"
                        />
                        <label for="ingredient3" class="ml-2 light-blue mr-2"
                          >لابأس به</label
                        >
                      </div>
                      <div class="flex align-items-center mr-2">
                        <input
                          type="radio"
                          id="ingredient4"
                          v-model="noteForm.evaluation"
                          value=" جيد"
                          name="جيد"
                        />
                        <label for="ingredient4" class="ml-2 green mr-2"
                          >جيد</label
                        >
                      </div>
                    </div>
                  </div>

                  <label for="description" class="mt-2 text-gray-500"
                    >{{ $t("tableincome.description") }}*</label
                  >
                  <textarea
                    type="text"
                    class="w-full rounded-xl border border-gray-200 my-2"
                    :placeholder="$t('addevenmentForm.description')"
                    id="description"
                    rows="3"
                    v-model="noteForm.description"
                  ></textarea>
                  <div class="flex justify-end mt-5">
                    <button
                      type="submit"
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
                        </g>
                      </svg>
                      <span class="mx-2">اضافة ملاحظة</span>
                    </button>
                  </div>
                </form>
              </div>
              </div>
           </div>
          </Dialog>
        </div>
      </div>
      </div>
    </div>
  </InsideLayout>
</template>

<style>
.customize-table {
  --easy-table-header-background-color: #818cf8;
}
.checkbox {
  display: flex;
  align-items: center;
  margin-top: 10px;
}

.checkbox input[type="checkbox"] {
  display: none; /* Hide the default checkbox */
}

.checkbox input[type="checkbox"] + label::before {
  content: "";
  display: inline-block;
  width: 20px;
  height: 20px;
  background-color: #ccc; /* Default background color for the checkbox */
  border-radius: 3px;
  margin-right: 5px;
  border: 1px solid #ccc;
  cursor: pointer;
}

.checkbox input[type="checkbox"]:checked + label::before {
  background-color: #6cb2eb; /* Change the background color when checked */
}

/* Circle styling */
.checkbox-grid {
  display: grid;
  grid-template-columns: repeat(
    2,
    1fr
  ); /* You can adjust the number of columns */
  grid-gap: 10px; /* Adjust the gap between the checkboxes */
}

.checkbox {
  display: flex;
  align-items: center;
}

/* Add your circle styles here */
.red {
  color: red;
}

.gray {
  color: rgb(218, 193, 6);
}

.light-blue {
  color: rgb(23, 193, 249);
}

.green {
  color: rgb(39, 218, 39);
}
.group-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 17vh; /* Hauteur de la vue */
  background-color: #bee3f5; /* Couleur de fond légère */
}

.group-title {
  font-size: 1.2em; /* Taille de la police */
  font-weight: bold; /* Texte en gras */
  color: #333; /* Couleur du texte */
  padding: 20px; /* Espacement interne */
  border-radius: 8px; /* Bords arrondis */
  background-color: #fff; /* Couleur de fond du titre */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Ombre portée pour un effet de carte */
  text-align: center; /* Centrer le texte */
}

</style>