<script setup>
import InsideLayout from '../../Layouts/InsideLayout.vue';
import {router, useForm} from "@inertiajs/vue3";
import {ref, watch} from "vue";
import Card from 'primevue/card';
import Button from 'primevue/button';
import Toast from "primevue/toast";
import {useToast} from "primevue/usetoast";
import Dialog from "primevue/dialog";
import Checkbox from 'primevue/checkbox';
import {useI18n} from "vue-i18n";

const props = defineProps({
    roles: {
        type: Array,
        required: true
    },
    permissions: {
        type: Array,
        required: true
    }
});

const rolesForm = useForm({
    name: "",
    permissions: []
});
const {t} = useI18n();
const toast = useToast();
const addRoleVisible = ref(false);
const areYouSureDelete = ref(false);
let itemToDelete;

const editRole = (item) => {
    rolesForm.name = item.name;
    rolesForm.permissions = item.permissions.map(permission => permission.id);
    addRoleVisible.value = true;
}

const areYouSureItem = (item) => {
    itemToDelete = item;
    areYouSureDelete.value = true;
};

/** Method to select all permissions **/
// const selectAllPermissions = () => {
//     if (rolesForm.permissions.includes('selectAll')) {

//         rolesForm.permissions = props.permissions.map(permission => permission.id);
//         console.log(" rolesForm.permissions", rolesForm.permissions);
//     }
//     else {
//         rolesForm.permissions = [];
//          console.log(" rolesForm.permissions", rolesForm.permissions);

//     }
// };
const selectAllPermissions = () => {
    const isSelectAllChecked = rolesForm.permissions.includes('selectAll');
    if (isSelectAllChecked) {
        // Add all permissions to the rolesForm.permissions array
        rolesForm.permissions = props.permissions.map(permission => permission.id);
         console.log(" rolesForm.permissions", rolesForm.permissions);
    } else {
        // Remove all permissions from the rolesForm.permissions array
        rolesForm.permissions = [];
         console.log(" rolesForm.permissions", rolesForm.permissions);
    }
};

const saveRole = () => {
    router.post('/roles', rolesForm, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (response) => {
            addRoleVisible.value = false;
            rolesForm.reset();
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
};

/** Delete Roles Function **/
const deleteRole = (item) => {
    router.delete(`/roles/${item.id}`, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
            areYouSureDelete.value = false;
            toast.add({
                severity: "success",
                summary: t("toast.success"),
                detail: t("toast.successMessages"),
                group: "bc",
                life: 3000,
            });
        },
        onError: (error) => {
            console.log("Error: ", error);
        }
    });
};

watch(addRoleVisible, (newValue) => {
    if (!newValue) {
        rolesForm.reset();
    }
});
</script>
<template>
    <InsideLayout>
        <div class="bg-white p-6 m-4 rounded-xl grid xl:grid-cols-4">
            <div class="xl:col-span-3 mx-2">
                <input type="text" :placeholder="$t('search.search')"
                       class="h-14 py-6 w-full rounded-xl border border-gray-200"/>
            </div>
            <div class="flex justify-start lg:m-2 xl:m-0">
                <button label="Show" @click="addRoleVisible = true"
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
                    <span class="mx-2">{{ $t("buttons.addRole") }}</span>
                </button>
            </div>
        </div>
        <div class="bg-white p-6 m-4 rounded-xl grid xl:grid-cols-4">
            <div v-for="item in roles">
                <Card style="margin: 9px">
                    <template #title>{{ item.name }}</template>
                    <template #content>
                        Total {{ item.name === 'admin' ? item.users.length - 1 : item.users.length }} utilisateurs
                    </template>
                    <template #footer>
                        <div class="">
                            <Button @click="editRole(item)" label="Modifier" severity="secondary" outlined
                                    class="w-20 text-start" style="color: #8176f2; font-weight: 300; font-family: 'Figtree';"/>
                            <Button @click="areYouSureItem(item)" label="Supprimer" class="w-40 text-start"
                                    style="color: #ea5455; font-weight: 300; font-family: 'Figtree';"/>
                        </div>
                    </template>
                </Card>
            </div>
        </div>
        <!-- Toast -->
        <Toast position="bottom-center" group="bc"/>
        <!--Add Role Modals-->
        <div class="card flex justify-content-center">
        <Dialog v-model:visible="addRoleVisible" modal header="Ajouter Nouvelle Rôle" :style="{ width: '75rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
    <hr/>
    <p class="mt-2 text-center">Définir les autorisations de rôle</p>
    <form @submit.prevent="saveRole">
        <p class="mt-4">Nom de Rôle</p>
        <input v-model="rolesForm.name" type="text" class="custom-input w-full rounded-md mt-1"
               style="border: solid #dbdade 1px;" placeholder="Entre Nom de Rôle" required>
        <div style="margin-top: 8%">
            <div style="display: flex; justify-content: space-between">
                <p class="font-medium">Accès administrateur</p>
                <div class="flex align-items-center">
                    <Checkbox v-model="rolesForm.permissions" name="selectAll" value="selectAll"
                              class="mr-2" @change="selectAllPermissions"/>
                              
                    <label for="ingredient1" class="ml-2">Tout Sélectionner</label>
                </div>
            </div>
            <hr class="mt-2" style="justify-content: center"/>
            <div class="mt-5 card-container">
                <div v-for="(item, index) in permissions" :key="item.id">
                    <div style="box-shadow: rgba(0, 0, 0, 0.05) 0px 5px 15px 0px; border: solid 1px; border-radius: 8px; padding: 24px; display: flex; justify-content: center; margin: 9px">
                        <Checkbox v-model="rolesForm.permissions" :name="item.name" :value="item.id"
                                  class="mr-2"/>
                        {{ item.name.length > 22 ? item.name.substring(0, 20) + "..." : item.name }}
                    </div>
                </div>
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
                    <button @click="deleteRole(itemToDelete)"
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
                        <span class="mx-2">Supprimer le Rôle</span>
                    </button>
                </div>
            </Dialog>
        </div>
    </InsideLayout>
</template>

<style>
.p-card .p-card-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0rem;
}

.p-card .p-card-content {
    padding: 0.25rem 0;
}

.p-card .p-card-footer {
    padding: 0.25rem 0 0 0;
}

.custom-input:focus {
    outline: none;
    border-color: #281aba;
    box-shadow: 0 0 5px rgba(3, 128, 229, 0.5);
}
.p-button-label {
    font-weight: 00;
}
.card-container {
    display: grid;
    gap: 1rem; /* Adjust the gap between cards */
    grid-template-columns: repeat(4, 1fr);
}

/* For screens smaller than 1200px (e.g., tablets) */
@media (max-width: 1199px) {
    .card-container {
        grid-template-columns: repeat(3, 1fr); /* 3 cards per row */
    }
}

/* For screens smaller than 992px (e.g., small tablets) */
@media (max-width: 992px) {
    .card-container {
        grid-template-columns: repeat(2, 1fr); /* 2 cards per row */
    }
}

/* For screens smaller than 768px (e.g., mobile devices) */
@media (max-width: 768px) {
    .card-container {
        grid-template-columns: 1fr; /* 1 card per row */
    }
}
</style>
