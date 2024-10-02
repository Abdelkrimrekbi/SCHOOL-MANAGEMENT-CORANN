<script setup>
import { router, useForm } from '@inertiajs/vue3';
import InsideLayout from '../../Layouts/InsideLayout.vue'
import Dropdown from 'primevue/dropdown';
import { ref } from "vue";
import axios from 'axios';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';
import { useI18n } from 'vue-i18n';
const { t } = useI18n()

const props = defineProps({
    teacherHasMatier: {
        type: Array,
        required: true
    },
    configuration: {
        type: Object,
        required: true
    },
    teachers: {
        type: Array,
        required: true
    },
    levels: {
        type: Array,
        required: true
    }
});
const toast = useToast();
const selectedLevel = ref();
const getClassByLevel = ref();
const getMatierByLevel = ref();
const getMatierTeacher = ref([]);
const selectedTeachers = ref([]);
const teacherHasMatierForm = useForm({
    teacher_id: '',
    group_id: '',
    matier_id: ''
})
const resetTeacherHasMatierForm = () => {
    selectedLevel.value = null,
    getClassByLevel.value = null
}

// FONCTION RETURN LES CLASS ET MATIÈRE PAR LEVEL
const onLevelChange = () => {
    if (selectedLevel.value) {
        const levelId = selectedLevel.value;
        axios.get('/api/get-classes-by-level/' + levelId)
            .then(response => {
                getClassByLevel.value = response.data.classes;
                getMatierByLevel.value = response.data.matiers;
            })
            .catch(error => {
                console.error('Error fetching classes:', error);
            });
    } else {
        console.log("Empty");
    }
}

// FONCTION RETURN MATIÈRE ET TEACHER A LA TABLE teacherHasMatierForm PAR CLASS
const onClassChange = async () => {
    if (teacherHasMatierForm.group_id) {
        await axios.get('/api/get-matierTeacher-by-class/' + teacherHasMatierForm.group_id)
            .then(response => {
                getMatierTeacher.value = response.data.matierTeacher;
            })
            .catch(error => {
                console.error('Error fetching classes:', error);
            })
    }
    else {
        console.log("Empty")
    }
}

// FONCTION RETURN NAME TEACHER PAR ID TABLE TEACHER
const getTeacherName = (matierId) => {
    const teacher = getMatierTeacher.value.find(item => item.matier_id === matierId);
    if (teacher) {
        const nameTeacher = props.teachers.find(item => item.id === teacher.teacher_id);
        if (nameTeacher) {
            return nameTeacher.name;
        } else {
            console.error("Teacher not found in props.teachers");
            return "";
        }
    } else {
        console.error("Teacher not found for matierId: ", matierId);
        return "";
    }
}

const isButtonClicked = ref(false);
const addTeacherHasMatier = async () => {
    if (teacherHasMatierForm.group_id === '') {
        toast.add({ severity: 'error', summary: t('toast.success'), detail: "S'il Te Plaît Sélectionner Group", group: 'bc', life: 3000 });
        return;
    }
    else {
        const matierHasTeacher = getMatierByLevel.value.map(matier => {
            const teacherId = selectedTeachers.value[matier.id];
            return {
                matier_id: matier.id,
                teacher_id: teacherId !== undefined ? teacherId : null,
                group_id: teacherHasMatierForm.group_id
            };
        });
        isButtonClicked.value = true;
        router.post('/teacherHasMatier', matierHasTeacher, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: page => {
                toast.add({ severity: 'success', summary: t('toast.success'), detail: t('toast.successMessages'), group: 'bc', life: 3000 });
                isButtonClicked.value = false;
            },
            onError: error => {
                console.log("Error", error);
                isButtonClicked.value = false;
            }
        })
    }
}

</script>
<template>
    <InsideLayout>
        <!-- Toast -->
        <Toast position="bottom-center" group="bc" />
        <!--  -->
        <div class="grid bg-white p-6 m-4 rounded-xl">
            <form @submit.prevent="addTeacherHasMatier">
                <div class="outer-container grid xl:grid-cols-2">
                    <div class="ml-5">
                        <Dropdown v-model="selectedLevel" filter @change="onLevelChange" :options="levels"
                            optionLabel="level" optionValue="id" placeholder="Level"
                            class="w-full h-14 mt-4 rounded-xl border border-gray-200 mb-4 overflow-hidden" />
                    </div>
                    <div class="mr-11">
                        <Dropdown v-model="teacherHasMatierForm.group_id" filter @change="onClassChange"
                            :options="getClassByLevel" optionLabel="group" optionValue="id" placeholder="Class"
                            class="w-full h-14 mt-4 rounded-xl border border-gray-200 mb-4 ml-6 overflow-hidden" />
                    </div>

                </div>
                <div class="outer-container">
                    <div class="mb-4 ml-2 mr-5">
                        <div v-if="getMatierByLevel">
                            <div class="grid grid-cols-4">
                                <div v-for="matier in getMatierByLevel" :key="matier.id">
                                    <div class="ajoute-container">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mt-5"
                                            style="stroke: rgb(103, 103, 233)">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                                        </svg>
                                        <div class="center-text">
                                            <p class="mt-2 mb-5 font-semibold">{{ matier.name.length > 33 ? matier.name.substring(0, 33) + "..." : matier.name }}</p>
                                        </div>
                                        <Dropdown class="w-full ml-2 mr-2" v-model="selectedTeachers[matier.id]" filter
                                            :options="props.teachers" optionLabel="name" optionValue="id"
                                            :placeholder="getTeacherName(matier.id)" />
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-center items-center">
                                <button :disabled="isButtonClicked" class="text-white bg-indigo-500 w-24 h-10 rounded-md mx-2 my-2">Ok</button>
                            </div>
                        </div>
                        <div v-else class="inner-container">
                            <p>No matiers available</p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </InsideLayout>
</template>

<style>
.outer-container {
    margin: 15px 0px 10px 0px;
    box-shadow: 0 4px 8px 0 rgba(220, 217, 217, 0.2);
    border: 1px solid #DEE0E0;
    border-radius: 10px;
}

.ajoute-container {
    margin: 15px 0px 10px 10px;
    box-shadow: 0 4px 8px 0 rgba(220, 217, 217, 0.2);
    border: 1px solid #DEE0E0;
    border-radius: 10px;
    background-color: #bec3ef;
    flex-direction: column;
    align-items: center;
    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;
}

.inner-container {
    margin: 25px;
    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;
}

.center-text {
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}
</style>