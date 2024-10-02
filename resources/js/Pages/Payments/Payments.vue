<script setup>
import { ref } from 'vue';
import InsideLayout from '../../Layouts/InsideLayout.vue'
import Dialog from 'primevue/dialog';
import { useI18n } from 'vue-i18n';
import Calendar from 'primevue/calendar';
import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import { useToast } from 'primevue/usetoast';
import { useForm } from '@inertiajs/vue3';

const { t } = useI18n()
//Get Data From Controller: 
const props = defineProps({
    payments: {
        type: Array,
        required: true
    },
    students: {
        type: Array,
        required: true
    },
    levels: {
        type: Array,
        required: true
    },
    classes: {
        type: Array,
        required: true
    }
})
//DataTable items :: 
const headers = [
    { text: "CNE", value: "CNE", sortable: true },
    { text: "First Name", value: "first_name", sortable: true, },
    { text: "Last Name", value: "last_name", sortable: true, },
    { text: "Parent", value: "parent", sortable: true, },
    // { text: "operations", value: "operations" },
];
const searchField = ["CNE", "first_name", "last_name"];
const searchValue = ref();
const addPaymentVisible = ref(false);
const inputDisabled = true;
const toast = useToast();

const paymentForm = useForm({
    id: null,
    date_payment: '',
    amount: '',
    type: '',
    promotion: '',
    rest: '',
    month: '',
    student_id: '',
    comments: ''
})
const studentForm = useForm({
    id: null,
    CNE: '',
    first_name: '',
    last_name: '',
    date_birth: '',
    gender: '',
    parent_id: '',
    level_id: '',
    class_id: '',
    profile_image: '',
    price: '',
})
const resertpaymentForm = () => {
    paymentForm.date_payment = "";
    paymentForm.amount = "";
    paymentForm.type = "";
    paymentForm.promotion = "";
    paymentForm.rest = "";
    paymentForm.month = "";
    paymentForm.student_id = "";
    paymentForm.comments = ""
}

const studentNotPAyement = () => {
    axios.get('/students/not-payments')
}

</script>
<template>
    <Head :title="$t('title.students')" />
    <InsideLayout>
        <div class="bg-white p-6 m-4 rounded-xl grid xl:grid-cols-4">
            <div class="xl:col-span-2 mx-2">
                <input type="text" v-model="searchValue" :placeholder="$t('search.search')"
                    class="h-14 py-6 w-full rounded-xl border border-gray-200 " />
            </div>
        </div>
        <!--DATATABLE HERE-->
        <div class="m-4" :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
            <Vue3EasyDataTable :headers="headers" :items="payments" :search-field="searchField" :search-value="searchValue"
                border-cell theme-color="#6F32BE" table-class-name="customize-table">
                <template #header-CNE="header">
                    <div style="display: flex; justify-content: center; align-items: center;">
                        <p class="text-white text-[1.2rem] py-2">CNE</p>
                    </div>
                </template>
                <template #header-first_name="header">
                    <div style="display: flex; justify-content: center; align-items: center;">
                        <p class="text-white text-[1.2rem] py-2">First Name</p>
                    </div>
                </template>
                <template #header-last_name="header">
                    <div style="display: flex; justify-content: center; align-items: center;">
                        <p class="text-white text-[1.2rem] py-2">Last Name</p>
                    </div>
                </template>
                <template #header-parent="header">
                    <div style="display: flex; justify-content: center; align-items: center;">
                        <p class="text-white text-[1.2rem] py-2"> Parent</p>
                    </div>
                </template>

                <template #item-CNE="item">
                    <div style="display: flex; justify-content: center; align-items: center;">
                        <p class="text-[1rem] flex font-medium py-3"
                            :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">{{ item.CNE }}</p>
                    </div>
                </template>
                <template #item-first_name="item">
                    <div style="display: flex; justify-content: center; align-items: center;">
                        <p class="text-[1rem] flex font-medium  py-3"
                            :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">{{ item.first_name }}</p>
                    </div>
                </template>
                <template #item-last_name="item">
                    <div style="display: flex; justify-content: center; align-items: center;">
                        <p class="text-[1rem] flex font-medium  py-3"
                            :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">{{ item.last_name }}</p>
                    </div>
                </template>
                <template #item-operations="item" :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
                    <div style="display: flex; justify-content: center; align-items: center;">
                        <button @click="showlStudent(item)">
                            <div style="margin: 0 auto;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="35"
                                    height="35" viewBox="0 0 24 24" stroke-width="1.5" stroke="#6366F1" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path
                                        d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                </svg>
                            </div>
                        </button>
                        <button @click="editStudent(item)" class="mx-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="36"
                                height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#F59E0B" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                <path d="M16 5l3 3" />
                            </svg>
                        </button>
                        <button @click="areYouSureItem(item)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="36"
                                height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#EF4444" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
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

        <!-- Toast -->
        <Toast position="bottom-center" group="bc" />
        
    </InsideLayout>
</template>
<style>
.customize-table {
  --easy-table-header-background-color: #818cf8;
}
</style>
