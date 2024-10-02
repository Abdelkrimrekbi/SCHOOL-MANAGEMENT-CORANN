<script setup>
import InsideLayout from '../../Layouts/InsideLayout.vue';
import Button from "primevue/button";
import Dropdown from 'primevue/dropdown';
import Toast from "primevue/toast";
import Dialog from "primevue/dialog";
import {useI18n} from "vue-i18n";
import {useToast} from "primevue/usetoast";
import {onMounted, ref} from "vue";
import {router, useForm} from "@inertiajs/vue3";
import Vue3EasyDataTable from 'vue3-easy-data-table';
import "vue3-easy-data-table/dist/style.css";

const props = defineProps({
    roles: {
        type: Array,
        required: true
    },
    users: {
        type: Array,
        required: true
    }
});
const {t} = useI18n();
const toast = useToast();
const addUsersVisible = ref(false);
const editUsersVisible = ref(false);
const areYouSureDelete = ref(false);
const headers = [
    {text: "Nom et Prénome", value: "nom", sortable: true},
    {text: "Email", value: "email", sortable: true},
    {text: "Role", value: "role", sortable: true},
    {text: "Actions", value: "actions"},
    {text: "Accéder", value: "accéder"},
];
const searchField = ["nom", "role", "email", "statut"];
const searchValue = ref();
const usersForm = useForm({
    id: "",
    name: "",
    email: "",
    password: "",
    role_id: "",
    role: "",
    active: ref(false),
});
const editUser = (item) => {
    usersForm.id = item.id;
    usersForm.name = item.name;
    usersForm.email = item.email;
    usersForm.role = item.role;
    editUsersVisible.value = true;
}
let itemToDelete;
const areYouSureItem = (item) => {
    itemToDelete = item;
    areYouSureDelete.value = true;
};

const saveUsers = () => {
    const combineData = {
        name: usersForm.name,
        email: usersForm.email,
        password: usersForm.password,
        role: usersForm.role.name,
        role_id: usersForm.role.id,
    }
    router.post('/users', combineData, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (response) => {
            if (response.props.flash.message === '422') {
                toast.add({
                    severity: "error",
                    summary: t("toast.error"),
                    detail: 'L\'utilisateur avec cet e-mail existe déjà.',
                    group: "bc",
                    life: 3000
                });
                return;
            }
            addUsersVisible.value = false;
            usersForm.reset();
            toast.add({
                severity: "success",
                summary: t("toast.success"),
                detail: response.props.flash.message,
                group: "bc",
                life: 3000
            });
        },
        onError: (error) => {
            console.log("Error: ", error)
        }
    })
}

const updateUsers = () => {
    const combineData = {
        role: usersForm.role.name,
        role_id: usersForm.role.id,
    }
    console.log("combineData :",combineData);
    router.put('/users/' + usersForm.id, combineData, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (response) => {
            editUsersVisible.value = false;
            toast.add({
                severity: "success",
                summary: t("toast.success"),
                detail: response.props.flash.message,
                group: "bc",
                life: 3000
            });
        },
        onError: (error) => {
            console.log("Error: ", error);
        }
    })
}
const updateActiveUsers = (item) => {
    console.log("item: ", item)
    const activeValue = item.active;
    console.log( "active",activeValue);
     console.log("id ",item.id);
    router.get(`/update-Active-Users/${item.id}` + '/' + activeValue , '', {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (response) => {
            toast.add({
                severity: "success",
                summary: t("toast.success"),
                detail: response.props.flash.message,
                group: "bc",
                life: 3000
            });
        },
        onError: (error) => {
            console.log("Error: ", error);
        }
    })
}

/** Delete Roles Function **/
const deleteUsers = (item) => {
    router.delete(`/users/${item.id}`, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (response) => {
            areYouSureDelete.value = false;
            toast.add({
                severity: "success",
                summary: t("toast.success"),
                detail: response.props.flash.message,
                group: "bc",
                life: 3000,
            });
        },
        onError: (error) => {
            console.log("Error: ", error);
        }
    });
};


</script>
<template>
    <InsideLayout>
        <div class="bg-white p-6 m-4 rounded-xl grid xl:grid-cols-4">
            <div class="xl:col-span-3 mx-2">
                <input type="text" v-model="searchValue" @input="searchStudents" :placeholder="$t('search.search')"
                       class="h-14 py-6 w-full rounded-xl border border-gray-200"/>
            </div>
            <div class="flex justify-start lg:m-2 xl:m-0">
                <button label="Show" @click="addUsersVisible = true"
                        class="flex items-center text-lg bg-indigo-500 shadow-lg shadow-indigo-500/50 hover:bg-indigo-400 text-white p-2 rounded-xl ml-20">
                    <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                         stroke="#ffffff">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <circle cx="12" cy="12" r="10" stroke="#ffffff" stroke-width="1.5"></circle>
                            <path d="M15 12L12 12M12 12L9 12M12 12L12 9M12 12L12 15" stroke="#ffffff" stroke-width="1.5"
                                  stroke-linecap="round"></path>
                        </g>
                    </svg>
                    <span class="mx-2">{{ $t("buttons.addUsers") }}</span>
                </button>
            </div>
        </div>
        <div class="bg-white p-6 m-4 rounded-xl">

            <div class="m-4" :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">

                <Vue3EasyDataTable :headers="headers" :items="users" :search-field="searchField"
                                   :search-value="searchValue"  table-responsive
                                   table-class-name="customize-table" border-cell theme-color="#6F32BE">
                    <!--Control Header-->
                    <template #header-nom="header">
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <p class="text-white text-[1.2rem] py-2">{{ $t('usersTable.name') }}</p>
                        </div>
                    </template>
                    <template #header-email="header">
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <p class="text-white text-[1.2rem] py-2">{{ $t('usersTable.email') }}</p>
                        </div>
                    </template>
                    <template #header-statut="header">
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <p class="text-white text-[1.2rem] py-2">{{ $t('usersTable.status') }}</p>
                        </div>
                    </template>
                    <template #header-role="header">
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <p class="text-white text-[1.2rem] py-2">{{ $t('usersTable.role') }}</p>
                        </div>
                    </template>
                    <template #header-actions="header">
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <p class="text-white text-[1.2rem] py-2">{{ $t('usersTable.actions') }}</p>
                        </div>
                    </template>
                    <template #header-accéder="header">
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <p class="text-white text-[1.2rem] py-2">{{ $t('usersTable.accéder') }}</p>
                        </div>
                    </template>
                    <!-- Control Item -->
                    <template #item-nom="item">
                        <div style="display: flex; justify-content: center; align-items: center">
                            <p class="text-[1rem] flex font-medium py-3"
                               :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
                                {{ item.name }}
                            </p>
                        </div>
                    </template>
                    <template #item-email="item">
                        <div style="display: flex; justify-content: center; align-items: center">
                            <p class="text-[1rem] flex font-medium py-3"
                               :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
                                {{ item.email }}
                            </p>
                        </div>
                    </template>
                    <template #item-statut="item">
                        <div style="display: flex; justify-content: center; align-items: center">
                            <p class="text-[1rem] flex font-medium py-3"
                               :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
                                {{ item.status }}
                            </p>
                        </div>
                    </template>
                    <template #item-role="item">
                        <div style="display: flex; justify-content: center; align-items: center">
                            <p class="text-[1rem] flex font-medium py-3"
                               :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
                                {{ item.role }}
                            </p>
                        </div>
                    </template>
                    <template #item-actions="item" :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
                        <div style="display: flex; justify-content: center; align-items: center">
                            <button @click="editUser(item)" class="mx-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit"
                                     width="36"
                                     height="36"
                                     viewBox="0 0 24 24" stroke-width="1.5" stroke="#F59E0B" fill="none"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                    <path d="M16 5l3 3"/>
                                </svg>
                            </button>

                            <button @click="areYouSureItem(item)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash"
                                     width="36" height="36"
                                     viewBox="0 0 24 24" stroke-width="1.5" stroke="#EF4444" fill="none"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M4 7l16 0"/>
                                    <path d="M10 11l0 6"/>
                                    <path d="M14 11l0 6"/>
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                    <template #item-accéder="item" :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
                        <div style="display: flex; justify-content: center; align-items: center">
                            <input
                                type="button"
                                @click="updateActiveUsers(item)"
                                :value="item.active === '1' ? 'Accéder' : 'Not Accéder'"
                                :class="{ 'bg-green-200 border-green-500 border-2': item.active === '1',
                                        'bg-red-200 border-red-500 border-2': item.active !== '1'}"
                                style="width: 100px; height: 30px; border-radius: 8px; --text-color: red; font-size: 0.9rem"
                            />
                        </div>
                    </template>
                </Vue3EasyDataTable>
            </div>
        </div>
        <!-- Toast -->
        <Toast position="bottom-center" group="bc"/>
        <!--Add User Modals-->
        <div class="card flex justify-content-center">
            <Dialog v-model:visible="addUsersVisible" modal header="Ajouter Nouvelle Utilisateur"
                    :style="{ width: '54rem' }"
                    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
                <hr/>
                <form @submit.prevent="saveUsers">
                    <div class="grid xl:grid-cols-2">
                        <div>
                            <p class="mt-4 text-gray-800"> CNE </p>
                            <input v-model="usersForm.password" type="text" class="custom-input w-full rounded-md mt-1"
                                   style="border: solid #dbdade 1px; width: 392px"
                                   placeholder="Entre CNE" required>
                        </div>
                        <div class="xl:ml-4 mt-6 xl:mt-0 sm:ml-0">
                            <p class="mt-4 text-gray-800"> Nom et Prénom </p>
                            <input v-model="usersForm.name" type="text" class="custom-input w-full rounded-md mt-1"
                                   style="border: solid #dbdade 1px;"
                                   placeholder="Entre Nom et Prénome" required>
                        </div>
                        <div>
                            <p class="mt-4 text-gray-800"> Email </p>
                            <input v-model="usersForm.email" type="email" class="custom-input w-full rounded-md mt-1"
                                   style="border: solid #dbdade 1px; width: 392px"
                                   placeholder="Entre Email" required>
                        </div>
                        <div class="xl:ml-4 mt-6 xl:mt-0 sm:ml-0">
                            <p class="mt-4 text-gray-800"> Sélectionner un rôle </p>
                            <Dropdown v-model="usersForm.role" :options="roles" optionLabel="name" required
                                      placeholder="Sélectionner un rôle"
                                      class="w-full rounded-md border border-gray-200 my-1 overflow-hidden"
                                      style="height: 42px"/>
                        </div>
                    </div>
                    <div class="flex justify-end mt-5">
                        <button type="submit"
                                class="flex items-center text-lg text-white p-2 rounded-xl hover:bg-[#9464D1] bg-[#6F32BE] hover:shadow-lg">
                            <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg"
                                 stroke="#ffffff">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <circle cx="12" cy="12" r="10" stroke="#ffffff" stroke-width="1.5"></circle>
                                    <path d="M15 12L12 12M12 12L9 12M12 12L12 9M12 12L12 15" stroke="#ffffff"
                                          stroke-width="1.5"
                                          stroke-linecap="round"></path>
                                </g>
                            </svg>
                            <span class="mx-2">Confirme</span>
                        </button>
                    </div>
                </form>
            </Dialog>
        </div>
        <!-- Model Update Role The Users       -->
        <div class="card flex justify-content-center">
            <Dialog v-model:visible="editUsersVisible" modal header="Modifier Le Role Utilisateur" :style="{ width: '35rem' }"
                    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
                <hr/>
                <form @submit.prevent="updateUsers">
                    <div>
                        <p class="mt-4 text-gray-800"> Sélectionner un rôle </p>
                        <Dropdown v-model="usersForm.role" :options="roles" optionLabel="name" required
                                  :placeholder="usersForm.role"
                                  class="w-full rounded-md border border-gray-200 my-1 overflow-hidden"
                                  style="height: 42px"/>
                    </div>
                    <div class="flex justify-end mt-5">
                        <button type="submit"
                                class="flex items-center text-lg text-white p-2 rounded-xl hover:bg-[#9464D1] bg-[#6F32BE] hover:shadow-lg">
                            <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg"
                                 stroke="#ffffff">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <circle cx="12" cy="12" r="10" stroke="#ffffff" stroke-width="1.5"></circle>
                                    <path d="M15 12L12 12M12 12L9 12M12 12L12 9M12 12L12 15" stroke="#ffffff"
                                          stroke-width="1.5"
                                          stroke-linecap="round"></path>
                                </g>
                            </svg>
                            <span class="mx-2">Confirme</span>
                        </button>
                    </div>
                </form>
            </Dialog>
        </div>
        <!--ARE YOU SURE ??-->
        <div class="card flex justify-content-center m-2">
            <Dialog v-model:visible="areYouSureDelete" modal header="Supprimer le Role" :style="{ width: '30rem' }"
                    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
                <div class="flex justify-center">
                    <svg width="100px" height="100px" viewBox="0 0 1024 1024" class="icon" version="1.1"
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
                <p class="text-2xl my-3 text-center">{{ $t("delete.sure") }}</p>

                <div class="flex justify-end mt-2">
                    <button @click="deleteUsers(itemToDelete)"
                            class="flex items-center text-lg text-white p-2 rounded-xl bg-red-400 shadow-lg shadow-red-500/50 hover:bg-red-400">
                        <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none"
                             xmlns="http://www.w3.org/2000/svg" stroke="">
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
                                      stroke="#ffffff"
                                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z"
                                      stroke="#ffffff"
                                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </g>
                        </svg>
                        <span class="mx-2">Supprimer l'utilisateur</span>
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

.vue3-easy-data-table__header th .header.direction-left[data-v-19cc4b1b] {
    justify-content: center;
}

.p-dropdown-label.p-inputtext {
    background-color: #ffffff;
    height: 42px;
    padding: 7px;
}
.header.direction-left {
    justify-content: center;
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
.flex {
  display: flex;
}

.flex-grow {
  flex: 1;
}

</style>
