<script setup>
import InsideLayout from "../../Layouts/InsideLayout.vue";
import { computed, onMounted, ref, watch } from "vue";
import axios from 'axios';
import Button from 'primevue/button';
import { router, useForm } from '@inertiajs/vue3';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';
import { useI18n } from 'vue-i18n';
import Vue3EasyDataTable from "vue3-easy-data-table";
import "vue3-easy-data-table/dist/style.css";

const { t } = useI18n()
const props = defineProps({
    teacher: {
        type: Array,
        required: true,
    },
    group: {
        type: Object,
        required: true
    },
//     matiers: {
//          type: Array,
//         required: true
//    },
    // listseance: {
    //     type: Array,
    //     required: true
    // }
});
const selectedGroup = ref(null);
const selectedMatier = ref(null);
let selectedGroupId = null;
let selectedMatierId = null;
const listeMatiere = ref([]);
const isGroupSelected = ref(false);
const listeSeance = ref([]);
//
const listStudent = ref([]);
const addSessionVisible = ref(false);
const listAbsenceVisible = ref(false);
const toast = useToast();
const areYouSure = ref(false);

const searchField = [""];
const searchValue = ref();

const headers = [
    // { text: "Commencer Séance", value: "startSeance", sortable: true },
  //   { text: "date de création", value: " created_at", sortable: true, },
    { text: "date de création", value: "created_at", sortable: true, },
  //  { text: "Local", value: "local_id", sortable: true, },
    { text: "operations", value: "operations" },
];

const sessionsForm = useForm({
    // startSeance: "",
    // finSeance: "",
    // type: "",
    // local_id: "",
    teacherHasMatier_id: ""
})
const absenceForm = useForm({
    student_id: ref([]),
    seance_id: "",
})
const resetSessionForm = () => {
    // sessionsForm.startSeance = "";
    // sessionsForm.finSeance = "";
    // sessionsForm.type = "";
    // sessionsForm.local_id = "";
}
const resetAbsenceForm = () => {
    absenceForm.reset()
}

// const showMatiere = async (item) => {
//     let matierResponse = ref([]);
//     listeSeance.value = [];
//     selectedGroupId = item.value.group_id;
//     if (selectedGroup.value) {
//         await axios.get('/get-matier-by-group/' + selectedGroupId)
//             .then(response => {
//                 listeMatiere.value = [];
//                 matierResponse.value = response.data.matiers;
//                 matierResponse.value.forEach(v => {
//                     listeMatiere.value.push(v.matiere);
//                 })
//                 console.log(listeMatiere.value)
//                 isGroupSelected.value = true;
//             })
//             .catch(error => {
//                 console.log('Error fetching matier:', error);
//             });
//     } else {
//         console.log("Empty");
//     }
// };

const showSeance = (item) => {
        //  console.log("item value :",item.value.group_id)

        axios.get('/get-seance-by-group/' + item.value.group.id )
            .then(response => {
              //  console.log("response data: ", response.data);
                sessionsForm.teacherHasMatier_id = response.data.teacherHasMatier_id;
                if (response.data.seance && response.data.seance.length > 0) {
                    listeSeance.value = response.data.seance;
                   // console.log("list value :" , listeSeance.value);
                } else {
                    listeSeance.value = [];
                }
               // console.log("les Seance: ", listeSeance.value);
               // console.log("teacherHasMatier_id: ", sessionsForm.teacherHasMatier_id);
            })
            .catch(error => {
                console.log('Error fetching seance:', error);
            });
};
watch(listeSeance, () => {
    // console.log("listeSeance changed:", newValue);
});

const isButtonClicked = ref(false);
const addSession = () => {
    // if (sessionsForm.startSeance === '' || sessionsForm.finSeance === '' || sessionsForm.type === '' || sessionsForm.local_id === '') {
    //     toast.add({ severity: 'error', summary: t('toast.error'), detail: t('toast.errorMessage'), group: 'bc', life: 3000 });
    //     return
    // }
    isButtonClicked.value = true;
    router.post('/seance', sessionsForm, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: page => {
            addSessionVisible.value = false;
            resetSessionForm();
            showSeance(selectedGroup);
            toast.add({ severity: 'success', summary: t('toast.success'), detail: t('toast.successMessages'), group: 'bc', life: 3000 });
            isButtonClicked.value = false;
        },
        onError: (error) => {
            console.log('Error', error)
            isButtonClicked.value = false;
        }
    })
}

let itemToDelete;
const areYouSureItem = (item) => {
    itemToDelete = item;
    areYouSure.value = true;
};

let itemToShow;
const listAbsence = async (item) => {
    console.log("item", formatdate(item.created_at));
     router.get(route("seance.show",item.id,formatdate(item.created_at)));
  
};



//Delete Seance Function
const deleteSeance = (item) => {
     isButtonClicked.value = true;
    router.delete(`/seance/${item.id}`, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
            areYouSure.value = false;
            showSeance(selectedGroup);
            toast.add({ severity: "success", summary: t("toast.success"), detail: t("toast.successMessages"), group: "bc", life: 3000 });
             isButtonClicked.value = false;
        },
    });
};

const isButtonDisabled = computed(() => {
    return !selectedGroup.value ;
});


function formatdate(timestamp) {
    const date = new Date(timestamp);

    const year = date.getUTCFullYear();
    const month = String(date.getUTCMonth() + 1).padStart(2, '0'); 
    const day = String(date.getUTCDate()).padStart(2, '0');
    const hours = String(date.getUTCHours()).padStart(2, '0');
    const minutes = String(date.getUTCMinutes()).padStart(2, '0');

    return `${year}/${month}/${day}  ${hours}:${minutes}`;
}

</script>
<template>
    <InsideLayout>
          <Head :title="$t('asidebar.absences')" />
        <div class=" m-4 p-6 bg-white rounded-md">
            <div class="outer-container grid xl:grid-cols-2">
                <div class="ml-5">
                    <Dropdown v-model="selectedGroup" filter :options="props.teacher" optionLabel="group.group"
                        placeholder="Sélectionnez un groupe"  @change="showSeance" 
                        class="w-full h-14 mt-4 rounded-xl border border-gray-200 mb-4 overflow-hidden" />
                        <!-- {{ selectedGroup }} -->
                </div>
            </div>
             <!-- <div class="outer-container grid xl:grid-cols-2">
        <div class="ml-5">
          <Dropdown
            v-model="selectedGroup"
            filter
            :options="props.teacher"
            optionLabel="group.group"
            placeholder="Sélectionnez un groupe"
          
            class="w-full h-14 mt-4 rounded-xl border border-gray-200 mb-4 overflow-hidden"
          />
        </div>
              </div> -->
                <!-- <div class="mr-11">
                    <Dropdown v-model="selectedMatier" filter :options="listeMatiere" optionLabel="name" optionValue="id"
                        @change="showSeance" placeholder="Sélectionnez une matière"
                        class="w-full h-14 mt-4 rounded-xl border border-gray-200 mb-4 ml-6 overflow-hidden" />
                </div> -->
            </div>
              <div class=" m-4 p-6 bg-white rounded-md">
                
            <!-- DATATABLE HERE -->
            <div class="m-4 outer-container" :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
                <Button label="New Séance" @click="addSessionVisible = true" :disabled="isButtonDisabled" class="new-button"
                    raised />
                <Vue3EasyDataTable :headers="headers" :items="listeSeance" :search-field="searchField"
                    :search-value="searchValue" table-class-name="customize-table" border-cell theme-color="#f7071b">
                   
                    <template #header-created_at="header">
                        <div>
                            <p class="text-white text-[1.2rem] py-2" style="text-align: center; width: 100%; margin: auto;">
                              Date de création
                            </p>
                        </div>
                    </template>
                  
                    <template #header-operations="header">
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <p class="text-white text-[1.2rem] py-2">
                                {{ $t("levelTable.operations") }}
                            </p>
                        </div>
                    </template>
                    <!--  -->
                  
                   
                    <template #item-created_at="item">
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <p class="text-[1rem] flex font-medium py-3"
                                :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
                                {{ formatdate(item.created_at) }}
                            </p>
                        </div>
                    </template>
                    
                    <template #item-operations="item" :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
                        <div class="flex justify-center">
                            <button @click="listAbsence(item)">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-presentation-analytics" width="36" height="36"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="#6366F1" fill="none"
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
                            <button @click="areYouSureItem(item)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash"
                                    width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#EF4444"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
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
        </div>
        <!-- Models -->
        <Toast position="bottom-center" group="bc" />
        <!--ADD SEance MODAL-->
        <div class="card flex justify-content-center m-2">
            <Dialog v-model:visible="addSessionVisible" modal header="Add Seance" :style="{ width: '36rem' }"
                :breakpoints="{ '599px': '75vw', '575px': '90vw' }">
                <div :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
                    <hr class="h-px bg-gray-200 border-0 dark:bg-gray-700">
                    <div class="mt-9">
                        <form class="grid" @submit.prevent="addSession">
                            <!-- <div class="grid xl:grid-cols-4">
                                <div>
                                    <label for="date">Start Session</label><br>
                                    <div class="mt-2">
                                        <input v-model="sessionsForm.startSeance" type="datetime-local" class="rounded-md"
                                            style="height: 50px; width: 192px; border: 0.5px solid rgb(54, 53, 53);">
                                    </div>
                                </div>
                                <div>
                                    <label for="date">End Session</label><br>
                                    <div class="mt-2">
                                        <input v-model="sessionsForm.finSeance" type="datetime-local" class="rounded-md"
                                            style="height: 50px; width: 192px; border: 0.5px solid rgb(54, 53, 53);">
                                    </div>
                                </div>
                                <div>
                                    <label for="date">Local</label><br>
                                    <div class="mt-2">
                                        <Dropdown v-model="sessionsForm.local_id" :options="props.local"
                                            optionLabel="locale" placeholder="Select a Locale" optionValue="id"
                                            class="overflow-hidden"
                                            style="height: 50px; width: 192px; border: 1px solid rgb(54, 53, 53);" />
                                    </div>
                                </div>
                                <div>
                                    <label for="date">Type</label><br>
                                    <div class="mt-2">
                                        <Dropdown v-model="sessionsForm.type" :options="typeSeance" optionLabel=""
                                            placeholder="Select a Seance" class="overflow-hidden"
                                            style="height: 50px; width: 192px; border: 0.5px solid rgb(54, 53, 53);" />
                                    </div>
                                </div>
                            </div> -->
                            <div class="flex justify-end mt-5">
                                <button :disabled="isButtonClicked"
                                    class="flex items-center text-lg bg-indigo-500 shadow-lg shadow-indigo-500/50 hover:bg-indigo-400 text-white  p-2 rounded-xl"><svg
                                        width="30px" height="30px" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" stroke="#ffffff">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <circle cx="12" cy="12" r="10" stroke="#ffffff" stroke-width="1.5"></circle>
                                            <path d="M15 12L12 12M12 12L9 12M12 12L12 9M12 12L12 15" stroke="#ffffff"
                                                stroke-width="1.5" stroke-linecap="round"></path>
                                        </g>
                                    </svg><span class="mx-2">{{ $t('buttons.addClass') }}</span></button>
                            </div>
                        </form>

                    </div>
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
                    <button @click="deleteSeance(itemToDelete)" :disabled="isButtonClicked"
                        class="flex items-center text-lg text-white p-2 rounded-xl bg-red-500 shadow-lg shadow-red-500/50 hover:bg-red-400">
                        <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            stroke="">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path d="M10 12V17" stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                                <path d="M14 12V17" stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                                <path d="M4 7H20" stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path d="M6 10V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18V10"
                                    stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z" stroke="#ffffff"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </g>
                        </svg><span class="mx-2">remove seance</span>
                    </button>
                </div>
            </Dialog>
        </div>
    </InsideLayout>
</template>

<style scoped>
.customize-table {
    --easy-table-header-background-color: #818cf8;
}

.p-dropdown-label {
    background-color: rgb(248 250 252);
}

.outer-container {
    margin: 15px 0px 10px 0px;
    box-shadow: 0 4px 8px 0 rgba(220, 217, 217, 0.2);
    border: 1px solid #DEE0E0;
    border-radius: 10px;
}

.inner-container {
    margin: 25px;
    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;
}

.title {
    font-weight: bold;
    font-size: large;
    color: #3F51B5;
}

.button-container {
    width: 240px;
}

.group-button {
    width: 100%;
    height: 56px;
    margin-top: 24px;
    margin-bottom: 8px;
    border: 1px solid #E7E9EB;
    border-radius: 10px;
    text-align: center;
    background-color: #BCC2F4;
    color: rgb(60, 60, 60);
}

.group-button:hover {
    background-color: #3F51B5;
    color: #ffffff;
}

.new-button {
    background-color: #3F51B5;
    height: 42px;
    width: 166px;
    margin-top: 15px;
    color: #ffffff;
    margin-bottom: 13px;
    margin-left:80%;
}

.new-button:hover {
    background-color: rgb(129, 152, 236);
}</style>