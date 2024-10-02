<script setup>
import {useI18n} from 'vue-i18n';
import InsideLayout from '../../Layouts/InsideLayout.vue'
import {router, useForm} from '@inertiajs/vue3';
import {useToast} from 'primevue/usetoast';
import Toast from 'primevue/toast';
import {onMounted, ref, watch} from "vue";

const {t} = useI18n()

const props = defineProps({
    configuration: {
        type: Object,
        default: () => ({}),
    },
})
const toast = useToast();

const configForm = useForm({
    id: props.configuration?.id || null,
    name: props.configuration?.name || '',
    phone: props.configuration?.phone || '',
    local: props.configuration?.local || '',
    site: props.configuration?.site || '',
    email: props.configuration?.email || '',
    RC: props.configuration?.RC || '',
    IF: props.configuration?.IF || '',
    CNSS: props.configuration?.CNSS || '',
    patente: props.configuration?.patente || '',
    ICE: props.configuration?.ICE || '',
    RIB: props.configuration?.RIB || '',
    promotion: props.configuration?.promotion || '',
    logo: props.configuration?.logo || ''
})

/** Fonction pour gérer le téléchargement de l'image **/
function handleLogoUpload(event) {
    const file = event.target.files[0];
    configForm.logo = file;
}

const addConfig = () => {
     if (configForm.name === ""  || configForm.promotion === "" || configForm.logo === "") {
        toast.add({ severity: 'error', summary: t('toast.error'), detail: t('toast.errorMessage'), group: 'bc', life: 3000 });
        return
    }
    else {
    const formData = new FormData();
    formData.append('logo', configForm.logo);
        router.post('/configurations', configForm, {
            preserveState: true,
            onSuccess: page => {
                toast.add({
                    severity: 'success',
                    summary: t('toast.success'),
                    detail: t('toast.successMessages'),
                    group: 'bc',
                    life: 3000
                });
            },
            onError: page => {
                console.log("error", console.error())
            }
        });

    }
}

const getImageUrl = (logo) => {
    if (!logo) {
        return '';
    }
    return `/storage/${logo}`;
}

</script>
<template>
    <Head :title="$t('title.students')"/>
    <InsideLayout>
        <!-- Toast -->
        <Toast position="bottom-center" group="bc"/>
        <div class="bg-white p-6 m-4 rounded-xl">
            <form class="grid" @submit.prevent="addConfig">
                <div style=" display: flex; justify-content: center; align-items: center;">
                    <img :src="getImageUrl(configForm.logo)"
                         class="bg-green-50 w-32 h-32 m-3 rounded-full"
                         alt="Logo"/>
                </div>
                <div class="grid xl:grid-cols-2">
                    <div class="mt-3">
                        <label for="name" class=" mt-2 text-gray-500">Name</label>
                        <input type="text" class="w-full h-10 rounded-xl " placeholder="Name"
                               :placeholder="$t('addconfigForm.cne')" id="name" v-model="configForm.name"/>
                    </div>
                    <div class="xl:ml-4 mt-3 sm:ml-0">
                        <label for="promotion" class=" mt-2 text-gray-500">Promotion*</label>
                        <input type="text" class="w-full h-10 rounded-xl" placeholder="Promotion" required
                               :placeholder="$t('addconfigForm.cne')" id="promotion" v-model="configForm.promotion"/>
                    </div>
                    <div class="mt-3">
                        <label for="phone" class=" mt-2 text-gray-500">Phone</label>
                        <input type="text" class="w-full h-10 rounded-xl " placeholder="Phone"
                               :placeholder="$t('addconfigForm.cne')" id="phone" v-model="configForm.phone"/>
                    </div>
                    <div class="xl:ml-4 sm:ml-0 mt-3">
                        <label for="email" class=" mt-2 text-gray-500">Email</label>
                        <input type="text" class="w-full h-10 rounded-xl " placeholder="Email"
                               :placeholder="$t('addconfigForm.cne')" id="email" v-model="configForm.email"/>
                    </div>
                    <div class="mt-3">
                        <label for="site" class=" mt-2 text-gray-500">Site Wib</label>
                        <input type="text" class="w-full h-10 rounded-xl " placeholder="Site Web"
                               :placeholder="$t('addconfigForm.cne')" id="site" v-model="configForm.site"/>
                    </div>
                    <div class="xl:ml-4 sm:ml-0 mt-3">
                        <label for="local" class=" mt-2 text-gray-500">Address</label>
                        <input type="text" class="w-full h-10 rounded-xl " placeholder="Address"
                               :placeholder="$t('addconfigForm.cne')" id="cne" v-model="configForm.local"/>
                    </div>
                    <div class="mt-3">
                        <label for="rc" class=" mt-2 text-gray-500">R.C</label>
                        <input type="text" class="w-full h-10 rounded-xl " placeholder="R.C"
                               :placeholder="$t('addconfigForm.cne')" id="rc" v-model="configForm.RC"/>
                    </div>
                    <div class="xl:ml-4 sm:ml-0 mt-3">
                        <label for="if" class=" mt-2 text-gray-500">I.F</label>
                        <input type="text" class="w-full h-10 rounded-xl " placeholder="I.F"
                               :placeholder="$t('addconfigForm.cne')" id="if" v-model="configForm.IF"/>
                    </div>
                    <div class="mt-3">
                        <label for="cnss" class=" mt-2 text-gray-500">CNSS</label>
                        <input type="text" class="w-full h-10 rounded-xl " placeholder="CNSS"
                               :placeholder="$t('addconfigForm.cne')" id="cnss" v-model="configForm.CNSS"/>
                    </div>
                    <div class="xl:ml-4 sm:ml-0 mt-3">
                        <label for="patente" class=" mt-2 text-gray-500">Patente</label>
                        <input type="text" class="w-full h-10 rounded-xl " placeholder="Patente"
                               :placeholder="$t('addconfigForm.cne')" id="patente" v-model="configForm.patente"/>
                    </div>
                    <div class="mt-3">
                        <label for="ice" class=" mt-2 text-gray-500">I.C.E</label>
                        <input type="text" class="w-full h-10 rounded-xl " placeholder="I.C.E"
                               :placeholder="$t('addconfigForm.cne')" id="ice" v-model="configForm.ICE"/>
                    </div>
                    <div class="xl:ml-4 sm:ml-0 mt-3">
                        <label for="rib" class=" mt-2 text-gray-500">RIB</label>
                        <input type="text" class="w-full h-10 rounded-xl " placeholder="RIB"
                               :placeholder="$t('addconfigForm.cne')" id="rib" v-model="configForm.RIB"/>
                    </div>
                    <div class="sm:ml-0 mt-3">
                        <label for="profile_image" class="mt-2 mt-2 text-gray-500">Profile Image*</label>
                        <div class="border border-gray-500 w-full h-12 rounded-xl">
                            <input type="file" id="profile_image" accept="image/*" @change="handleLogoUpload"
                                   class="mt-2 ml-2 text-gray-500 w-full  overflow-hidden"/>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-2">
                    <button
                            class="flex items-center text-lg text-white p-2 rounded-xl hover:bg-[#9464D1] bg-[#6F32BE] hover:shadow-lg"><span
                            class="mx-2">Confirm</span></button>
                </div>
            </form>
        </div>
    </InsideLayout>
</template>
