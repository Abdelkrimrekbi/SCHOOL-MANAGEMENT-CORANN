<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Dialog from "primevue/dialog";
import DropdownLink from "@/Components/DropdownLink.vue";
import Dropdown from "@/Components/Dropdown.vue";
import { onMounted } from "vue";
import { useToast } from "vue-toastification";
import "vue-toastification/dist/index.css";

const toast = useToast();

onMounted(() => {
  currentRoute.value = window.location.pathname;
  // loadImage();
});


// const loadImage = () => {
//   axios.get("/api/config").then((res) => {
//     imageSrc.value = "/storage/" + res.data.config.logo;
//    // console.log("imageSrc :", imageSrc.value);
//   }).catch((error) => {
//    // console.error("Error fetching image:", error);
//   });
// };
// const imageSrc = ref('');



import {useImageStore} from './store';

const imageStore = useImageStore();

if (!imageStore.imageLoaded) {
    imageStore.loadImage();
}
// console.log("imageStore insidelayout :",imageStore);



const logoutDialogVisible = ref(false);

const showLogoutConfirmation = (pos) => {
  logoutDialogVisible.value = true;
};


const confirmLogout = () => {
  router.post(route("logout"));
  logoutDialogVisible.value = false;
  toast.success("Logout successful");
};

const cancelLogout = () => {
  toast.warning("Logout canceled");
  logoutDialogVisible.value = false;
};

const page = usePage();
const userName = page.props.auth.user.name;


const currentDate = ref("");
onMounted(() => {
  currentRoute.value = window.location.pathname;
});


const getCurrentDate = () => currentDate.value;

const isPanelMenuOpen = ref(false);

const togglePanelMenu = () => {
  isPanelMenuOpen.value = !isPanelMenuOpen.value;
  // console.log("panel visibility :", isPanelMenuOpen.value);
};

const isPanelMenuOpenDepartements = ref(false);
const togglePanelMenuDepartements = () => {
    isPanelMenuOpenDepartements.value = !isPanelMenuOpenDepartements.value;
};




const currentRoute = ref(window.location.pathname); 

const darkMode = ref(false); 


const toggleDarkMode = (isDarkMode) => {
  darkMode.value = isDarkMode;
}

watch(darkMode, (newValue) => {
 
});


const isPanelMenuOpenUser = ref(false);

const togglePanelMenuUser = () => {
    isPanelMenuOpenUser.value = !isPanelMenuOpenUser.value;
};
</script>

<script>
export default {
  setup() {
    return {
      responsiveSettingsLanguage: ref(false),
    };
  },
  methods: {
    setLocale(locale) {
      router.post("/api/locale", {
        language: locale,
      });
      this.$i18n.locale = locale;
      this.responsiveSettingsLanguage = false;
      localStorage.setItem("language", locale);
    },
  },
};
</script>
<template>

  <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar"
    type="button"
    class="inline-flex items-center p-2 mt-2 ml-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
    <span class="sr-only">Open sidebar</span>
    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
      <path clip-rule="evenodd" fill-rule="evenodd"
        d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
      </path>
    </svg>
  </button>

  <aside id="default-sidebar" :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'"
    :class="$i18n.locale === 'ar' ? 'right-0' : 'ltr'"
    class="fixed top-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0">
    
    <div class="h-full px-3 py-4 overflow-y-auto bg-white pl-4">
      <!-- <div class="my-6 flex justify-center h-24">
        <img :src="imageStore.imageSrc" alt="logo" />
      </div>
      <hr /> -->
      <div class="my-6 flex justify-center h-36">
                <img v-if="imageStore.imageSrc" :src="imageStore.imageSrc" alt="logo"/>
                <div v-else>
                    School Logo
                </div>
            </div>
            <hr/>
      <h1 class="text-gray-600 text-sm my-5">{{ $t("asidebar.menu") }}</h1>
      <ul class="space-y-2 font-medium">
       
        <li>
       <Link href="/dashboard" preserve-scroll preserve-state
          :class="[
            'flex items-center p-2 rounded-lg group',
            currentRoute && currentRoute.includes('/dashboard') ? 'active' : '',
            darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]'
          ]">
      <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
           viewBox="0 0 18 18">
         <path
              d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z" />
          </svg>
      
      <span class="mx-3 font-bold">{{ $t("asidebar.dashboard") }}</span>
    </Link>
        </li>
           <li>
                    <div preserve-scroll
                         preserve-state @click="togglePanelMenuUser"
                         :class="['flex items-center p-2 rounded-lg group', darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]' ]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1.3em" height="1.3em" viewBox="0 0 16 16">
                            <path fill="currentColor"
                                  d="M5 3a3 3 0 1 1 6 0a3 3 0 0 1-6 0m7.001 4h-.553l-3.111 6.316L9.5 7.5L8 6L6.5 7.5l1.163 5.816L4.552 7h-.554c-1.999 0-1.999 1.344-1.999 3v5h12v-5c0-1.656 0-3-1.999-3z"/>
                        </svg>
                        <span class="mx-1 font-bold">Utilisateur</span>
                    </div>
                    <transition>
                        <ul v-if="isPanelMenuOpenUser || (currentRoute && currentRoute.includes('/users')) || (currentRoute && currentRoute.includes('/roles'))">
                            <li>
                                <Link preserve-scroll preserve-state href="/roles" :class="[ 'flex items-center p-2 m-1 ml-5 rounded-lg group',
                                    currentRoute && currentRoute.includes('/roles') ? 'active' : '',
                                    darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]']">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-lock"
                                         width="17"
                                         height="17" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path
                                            d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z"/>
                                        <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0"/>
                                        <path d="M8 11v-4a4 4 0 1 1 8 0v4"/>
                                    </svg>
                                    <span class="mx-3">Roles</span>
                                </Link>
                            </li>
                            <li>
                                <Link preserve-state preserve-scroll href="/users" :class="[ 'flex items-center p-2 m-1 ml-5 rounded-lg group',
                                    currentRoute && currentRoute.includes('/users') ? 'active' : '',
                                    darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]']">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle"
                                         width="14" height="14" viewBox="0 0 24 24" stroke-width="0.5" stroke="#2c3e50"
                                         fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/>
                                    </svg>
                                    <span class="mx-3">List</span>
                                </Link>
                            </li>
                        </ul>
                    </transition>
                </li>

        <li>
          <Link href="/students" preserve-scroll preserve-state :class="[
            'flex items-center p-2 rounded-lg group',
            currentRoute && currentRoute.includes('/students') ? 'active' : '',
            darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]'
          ]">
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
            viewBox="0 0 14 18">
            <path
              d="M7 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9Zm2 1H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
          </svg>
          <span class="mx-3 font-bold">{{ $t("asidebar.students") }}</span>
          </Link>
        </li>
          <li>
          <Link href="/listdatten" preserve-scroll preserve-state :class="[
            'flex items-center p-2 rounded-lg group',
            currentRoute && currentRoute.includes('/listdatten') ? 'active' : '',
            darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]'
          ]">
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
            viewBox="0 0 14 18">
            <path
              d="M7 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9Zm2 1H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
          </svg>
          <span class="mx-3 font-bold">{{ $t("asidebar.Listdatten") }}</span>
          </Link>
        </li>

        <li>
          <Link href="/teachers" preserve-scroll preserve-state  :class="[
            'flex items-center p-2 rounded-lg group',
            currentRoute && currentRoute.includes('/teachers') ? 'active' : '',
            darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]'
          ]">
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
            viewBox="0 0 20 18">
            <path
              d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
          </svg>
          <span class="mx-3 font-bold">{{ $t("asidebar.Teachers") }}</span>
          </Link>
        </li>

        <!-- <li>
          <Link href="/matieres" preserve-scroll preserve-state  :class="[
            'flex items-center p-2 rounded-lg group',
            currentRoute && currentRoute.includes('/matieres') ? 'active' : '',
            darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]'
          ]">
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
            viewBox="0 0 16 20">
            <path
              d="M16 14V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v15a3 3 0 0 0 3 3h12a1 1 0 0 0 0-2h-1v-2a2 2 0 0 0 2-2ZM4 2h2v12H4V2Zm8 16H3a1 1 0 0 1 0-2h9v2Z" />
          </svg>
          <span class="mx-3 font-bold">{{ $t("asidebar.courses") }}</span>
          </Link>
        </li> -->

        <li>
                    <div preserve-scroll preserve-state @click="togglePanelMenuDepartements"
                         :class="['flex items-center p-2 rounded-lg group', darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]' ]">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 18 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 1h12M3 1v16M3 1H2m13 0v16m0-16h1m-1 16H3m12 0h2M3 17H1M6 4h1v1H6V4Zm5 0h1v1h-1V4ZM6 8h1v1H6V8Zm5 0h1v1h-1V8Zm-3 4h2a1 1 0 0 1 1 1v4H7v-4a1 1 0 0 1 1-1Z"/>
                        </svg>
                        <span class="mx-1 font-bold">Departements</span>
                    </div>
                    <transition>
                        <ul v-show="isPanelMenuOpenDepartements  || (currentRoute && currentRoute.includes('/levels')) || (currentRoute && currentRoute.includes('/classes')) || (currentRoute && currentRoute.includes('/matieres'))">
                            <!-- <li>
                                <Link href="/filieres" preserve-scroll preserve-state :class="['flex items-center p-2 m-1 ml-5 rounded-lg group',
                                    currentRoute && currentRoute.includes('/filieres') ? 'active' : '',
                                    darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]']">
                                    <svg class="w-4 h-4 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                         fill="currentColor"
                                         viewBox="0 0 16 22">
                                        <path
                                            d="M15.458 4a3 3 0 1 0-4.478 2.6A2.6 2.6 0 0 1 8.4 9H6.6a4.57 4.57 0 0 0-2.6.814v-3a3 3 0 1 0-2 0v8.368a3 3 0 1 0 2 0V13.6A2.607 2.607 0 0 1 6.6 11h1.8a4.6 4.6 0 0 0 4.548-4.049A3 3 0 0 0 15.458 4Z"/>
                                    </svg>
                                    <span class="mx-3">{{ $t("asidebar.filieres") }}</span>
                                </Link>
                            </li> -->
                            <li>
                                <Link href="/levels" preserve-scroll preserve-state :class="['flex items-center p-2 m-1 ml-5 rounded-lg group',
                                    currentRoute && currentRoute.includes('/levels') ? 'active' : '',
                                    darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]']">
                                    <svg class="w-4 h-4 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                         fill="none"
                                         viewBox="0 0 17 18">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M1 12v5m5-9v9m5-5v5m5-9v9M1 7l5-6 5 6 5-6"/>
                                    </svg>
                                    <span class="mx-3">{{ $t("asidebar.levels") }}</span>
                                </Link>
                            </li>
                            <li>
                                <Link href="/classes" preserve-scroll preserve-state :class="[ 'flex items-center p-2 m-1 ml-5 rounded-lg group',
                                    currentRoute && currentRoute.includes('/classes') ? 'active' : '',
                                      darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]']">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                         fill="currentColor"
                                         viewBox="0 0 20 14">
                                        <path
                                            d="M18 0H2a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2ZM9 6v2H2V6h7Zm2 0h7v2h-7V6Zm-9 4h7v2H2v-2Zm9 2v-2h7v2h-7Z"/>
                                    </svg>
                                    <span class="mx-3">{{ $t("asidebar.classes") }}</span>
                                </Link>
                            </li>
                            <li>
                                <Link href="/matieres" preserve-scroll preserve-state :class="['flex items-center p-2 m-1 ml-5 rounded-lg group',
                                    currentRoute && currentRoute.includes('/matieres') ? 'active' : '',
                                    darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]']">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                         fill="currentColor"
                                         viewBox="0 0 16 20">
                                        <path
                                            d="M16 14V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v15a3 3 0 0 0 3 3h12a1 1 0 0 0 0-2h-1v-2a2 2 0 0 0 2-2ZM4 2h2v12H4V2Zm8 16H3a1 1 0 0 1 0-2h9v2Z"/>
                                    </svg>
                                    <span class="mx-3">{{ $t("asidebar.courses") }}</span>
                                </Link>
                            </li>
                        </ul>
                    </transition>
                </li>

        <!-- <li>
          <Link href="/classes" preserve-scroll preserve-state  :class="[
            'flex items-center p-2 rounded-lg group',
            currentRoute && currentRoute.includes('/classes') ? 'active' : '',
            darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]'
          ]">
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
            viewBox="0 0 20 14">
            <path
              d="M18 0H2a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2ZM9 6v2H2V6h7Zm2 0h7v2h-7V6Zm-9 4h7v2H2v-2Zm9 2v-2h7v2h-7Z" />
          </svg>
          <span class="mx-3 font-bold">{{ $t("asidebar.classes") }}</span>
          </Link>
        </li> -->

        <!-- <li>
          <Link href="/levels" preserve-scroll preserve-state  :class="[
            'flex items-center p-2 rounded-lg group',
            currentRoute && currentRoute.includes('/levels') ? 'active' : '',
            darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]'
          ]">
          <svg class="w-5 h-5 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 17 18">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M1 12v5m5-9v9m5-5v5m5-9v9M1 7l5-6 5 6 5-6" />
          </svg>
          <span class="mx-3 font-bold">{{ $t("asidebar.levels") }}</span>
          </Link>
        </li> -->

        <!-- <li>
          <Link href="/filieres" preserve-scroll preserve-state  :class="[
            'flex items-center p-2 rounded-lg group',
            currentRoute && currentRoute.includes('/filieres') ? 'active' : '',
            darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]'
          ]">
          <svg class="w-5 h-5 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
            viewBox="0 0 16 22">
            <path
              d="M15.458 4a3 3 0 1 0-4.478 2.6A2.6 2.6 0 0 1 8.4 9H6.6a4.57 4.57 0 0 0-2.6.814v-3a3 3 0 1 0-2 0v8.368a3 3 0 1 0 2 0V13.6A2.607 2.607 0 0 1 6.6 11h1.8a4.6 4.6 0 0 0 4.548-4.049A3 3 0 0 0 15.458 4Z" />
          </svg>
          <span class="mx-3 font-bold">{{ $t("asidebar.filieres") }}</span>
          </Link>
        </li> -->

        <!-- <li>
          <Link href="/exams" preserve-scroll preserve-state  :class="[
            'flex items-center p-2 rounded-lg group',
            currentRoute && currentRoute.includes('/exams') ? 'active' : '',
            darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]'
          ]">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" width="28" height="28" viewBox="0 0 24 24"
            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M3 7h10v10h-10z" />
            <path d="M6 10h4" />
            <path d="M8 10v4" />
            <path d="M8.104 17c.47 2.274 2.483 4 4.896 4a5 5 0 0 0 5 -5v-7h-5" />
            <path d="M18 18a4 4 0 0 0 4 -4v-5h-4" />
            <path d="M13.003 8.83a3 3 0 1 0 -1.833 -1.833" />
            <path d="M15.83 8.36a2.5 2.5 0 1 0 .594 -4.117" />
          </svg>
          <span class="mx-3 font-bold">{{ $t("asidebar.exams") }}</span>
          </Link>
        </li> -->

        <li>
          <Link href="/teacherHasMatier" preserve-scroll preserve-state  :class="[
            'flex items-center p-2 rounded-lg group',
            currentRoute && currentRoute.includes('/teacherHasMatier') ? 'active' : '',
            darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]'
          ]">
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
            viewBox="0 0 19 20">
            <path
              d="M18.972.863a.913.913 0 0 0-.041-.207.956.956 0 0 0-.107-.19 1.01 1.01 0 0 0-.065-.116c-.008-.01-.02-.013-.028-.022a1.008 1.008 0 0 0-.174-.137 1.085 1.085 0 0 0-.141-.095 1.051 1.051 0 0 0-.171-.047.985.985 0 0 0-.207-.041C18.025.007 18.014 0 18 0h-3.207a1 1 0 1 0 0 2h.5l-4.552 3.9-3.5-.874a1 1 0 0 0-.867.189l-5 4a1 1 0 0 0 1.25 1.562L7.238 7.09l3.52.88a1 1 0 0 0 .892-.211L17 3.173v1.034a1 1 0 0 0 2 0V1a.9.9 0 0 0-.028-.137ZM13.5 9a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11Zm.24 4.591a3.112 3.112 0 0 1 1.935 1.374 2.036 2.036 0 0 1 .234 1.584 2.255 2.255 0 0 1-1.374 1.469.982.982 0 0 1-1.953.09 2.943 2.943 0 0 1-1.475-.92 1 1 0 0 1 1.536-1.283.953.953 0 0 0 .507.29.778.778 0 0 0 .831-.18 1.108 1.108 0 0 0-.714-.481 3.105 3.105 0 0 1-1.934-1.374 2.042 2.042 0 0 1-.233-1.584 2.264 2.264 0 0 1 1.45-1.493v-.03a1 1 0 0 1 2 0c.517.159.98.457 1.337.862a1.002 1.002 0 1 1-1.524 1.3.962.962 0 0 0-.507-.286.775.775 0 0 0-.829.18 1.113 1.113 0 0 0 .713.482ZM6 20a1 1 0 0 1-1-1v-6a1 1 0 1 1 2 0v6a1 1 0 0 1-1 1Zm-4 0a1 1 0 0 1-1-1v-4a1 1 0 1 1 2 0v4a1 1 0 0 1-1 1Z" />
          </svg>
          <span class="mx-3 font-bold">{{
            $t("asidebar.materialsmanager")
          }}</span>
          </Link>
        </li>

        <!-- <li>
          <Link href="/locales" preserve-scroll preserve-state  :class="[
            'flex items-center p-2 rounded-lg group',
            currentRoute && currentRoute.includes('/locales') ? 'active' : '',
            darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]'
          ]">
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 18">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 1h12M3 1v16M3 1H2m13 0v16m0-16h1m-1 16H3m12 0h2M3 17H1M6 4h1v1H6V4Zm5 0h1v1h-1V4ZM6 8h1v1H6V8Zm5 0h1v1h-1V8Zm-3 4h2a1 1 0 0 1 1 1v4H7v-4a1 1 0 0 1 1-1Z" />
          </svg>
          <span class="mx-3 font-bold">{{ $t("asidebar.salle") }}</span>
          </Link>
        </li> -->

        <li>
          <Link href="/absence" preserve-scroll preserve-state  :class="[
            'flex items-center p-2 rounded-lg group',
            currentRoute && currentRoute.includes('/absence') ? 'active' : '',
            darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]'
          ]">
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
            viewBox="0 0 20 18">
            <path
              d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
          </svg>
          <span class="mx-3 font-bold">Espace Enseignantes</span>
          </Link>
        </li>
        <li>
          <Link href="/printInformation" preserve-scroll preserve-state  :class="[
            'flex items-center p-2 rounded-lg group',
            currentRoute && currentRoute.includes('/printInformation') ? 'active' : '',
            darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]'
          ]">
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
            viewBox="0 0 20 18">
            <path
              d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
          </svg>
          <span class="mx-3 font-bold">Imprimer les informations</span>
          </Link>
        </li>
        <li>
          <div preserve-scroll preserve-state @click="togglePanelMenu"
             :class="[
        'flex items-center p-2 rounded-lg group',
        darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]'
         ]">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
              viewBox="0 0 19 20">
              <path
                d="M18.972.863a.913.913 0 0 0-.041-.207.956.956 0 0 0-.107-.19 1.01 1.01 0 0 0-.065-.116c-.008-.01-.02-.013-.028-.022a1.008 1.008 0 0 0-.174-.137 1.085 1.085 0 0 0-.141-.095 1.051 1.051 0 0 0-.171-.047.985.985 0 0 0-.207-.041C18.025.007 18.014 0 18 0h-3.207a1 1 0 1 0 0 2h.5l-4.552 3.9-3.5-.874a1 1 0 0 0-.867.189l-5 4a1 1 0 0 0 1.25 1.562L7.238 7.09l3.52.88a1 1 0 0 0 .892-.211L17 3.173v1.034a1 1 0 0 0 2 0V1a.9.9 0 0 0-.028-.137ZM13.5 9a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11Zm.24 4.591a3.112 3.112 0 0 1 1.935 1.374 2.036 2.036 0 0 1 .234 1.584 2.255 2.255 0 0 1-1.374 1.469.982.982 0 0 1-1.953.09 2.943 2.943 0 0 1-1.475-.92 1 1 0 0 1 1.536-1.283.953.953 0 0 0 .507.29.778.778 0 0 0 .831-.18 1.108 1.108 0 0 0-.714-.481 3.105 3.105 0 0 1-1.934-1.374 2.042 2.042 0 0 1-.233-1.584 2.264 2.264 0 0 1 1.45-1.493v-.03a1 1 0 0 1 2 0c.517.159.98.457 1.337.862a1.002 1.002 0 1 1-1.524 1.3.962.962 0 0 0-.507-.286.775.775 0 0 0-.829.18 1.113 1.113 0 0 0 .713.482ZM6 20a1 1 0 0 1-1-1v-6a1 1 0 1 1 2 0v6a1 1 0 0 1-1 1Zm-4 0a1 1 0 0 1-1-1v-4a1 1 0 1 1 2 0v4a1 1 0 0 1-1 1Z" />
            </svg>

            <span class="mx-3">Caisse</span>
          </div>
          
          <transition>
            <ul v-show="isPanelMenuOpen ||
              (currentRoute && currentRoute.includes('/incomes')) ||
              (currentRoute && currentRoute.includes('/expenses'))
              ">
              <li>
               <Link preserve-state preserve-scroll href="/incomes" :class="[
            'flex items-center p-2 m-1 ml-5 rounded-lg group',
            currentRoute && currentRoute.includes('/incomes') ? 'active' : '',
            darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]'
              ]">
                 
                <svg aria-hidden="true" width="22px" height="22px" viewBox="0 0 1024 1024" class="icon" version="1.1"
                  xmlns="http://www.w3.org/2000/svg" fill="#000000">
                  <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                  <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                  <g id="SVGRepo_iconCarrier">
                    <path
                      d="M731.39 109.04L511.96 361.72H638.3v306.56c0 74.07-29.42 145.1-81.8 197.48l-11.29 11.29c154.24 0 279.27-125.03 279.27-279.27V361.72h126.34L731.39 109.04z m19.95 179.53v309.19c0 48.23-16.65 92.64-44.5 127.79 3.05-18.77 4.6-37.9 4.6-57.29V288.58h-39.09l59.04-67.98 59.04 67.98h-39.09z"
                      fill="#777779"></path>
                    <path
                      d="M329.1 402.29c-141.38 0-256 114.62-256 256s114.62 256 256 256c141.39 0 256-114.62 256-256s-114.61-256-256-256z m0 438.85c-100.83 0-182.86-82.03-182.86-182.86s82.03-182.86 182.86-182.86 182.86 82.03 182.86 182.86-82.03 182.86-182.86 182.86z"
                      fill="#777779"></path>
                    <path
                      d="M393.81 529.18l-64.59 64.61-64.59-64.61-38.78 38.78L270 612.12h-32.42v54.86h64.21v18.84h-62.16v54.86h62.16v59.91h54.86v-59.91h62.18v-54.86h-62.18v-18.84h64.23v-54.86h-32.44l44.16-44.16zM146.24 292.48h292.57v73.14H146.24zM146.24 182.48h402.29v73.14H146.24z"
                      fill="#777779"></path>
                  </g>
                </svg>
                <span class="mx-1">Revenue</span>
                </Link>
              </li>
              <li>
                <Link preserve-state preserve-scroll href="/expenses" :class="[
            'flex items-center p-2 m-1 ml-5 rounded-lg group',
            currentRoute && currentRoute.includes('/expenses') ? 'active' : '',
            darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]'
              ]">
                
                
               
                <svg width="22px" height="22px" viewBox="0 0 1024 1024" class="icon" version="1.1"
                  xmlns="http://www.w3.org/2000/svg" fill="#000000">
                  <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                  <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                  <g id="SVGRepo_iconCarrier">
                    <path
                      d="M824.52 425.56c0-154.24-125.03-279.27-279.27-279.27l11.29 11.29a279.285 279.285 0 0 1 81.8 197.47v306.56H512l219.43 252.68 219.43-252.68H824.52V425.56z m-93.09 377.17l-59.04-67.98h39.09v-379.7c0-19.38-1.55-38.52-4.6-57.29 27.85 35.15 44.5 79.56 44.5 127.79v309.19h39.09l-59.04 67.99z"
                      fill="#777779"></path>
                    <path
                      d="M329.14 621.71c141.39 0 256-114.62 256-256 0-141.39-114.61-256-256-256-141.38 0-256 114.61-256 256s114.62 256 256 256z m0-438.85c100.83 0 182.86 82.03 182.86 182.86s-82.03 182.86-182.86 182.86c-100.83 0-182.86-82.03-182.86-182.86s82.04-182.86 182.86-182.86z"
                      fill="#777779"></path>
                    <path
                      d="M237.62 319.55v54.86h64.23v18.84h-62.18v54.86h62.18v59.91h54.85v-59.91h62.17v-54.86H356.7v-18.84h64.22v-54.86H388.5l44.15-44.16-38.78-38.78-64.59 64.6-64.59-64.6-38.79 38.78 44.15 44.16zM146.29 658.29h292.57v73.14H146.29zM146.29 768h402.29v73.14H146.29z"
                      fill="#777779"></path>
                  </g>
                </svg>
                <span class="mx-1">Dependence</span>
                </Link>
              </li>
            </ul>
          </transition>
        </li>
      </ul>
      <div class="mt-10">
        <h1 class="text-gray-600 text-sm mb-5 mx-2">
          {{ $t("asidebar.other") }}
        </h1>
      </div>
      <div class="ml-3">
        <ul class="space-y-2 font-medium">
          <li>
            <Link href="/user/profile" preserve-scroll preserve-state :class="[
            'flex items-center p-2 rounded-lg group',
            currentRoute && currentRoute.includes('/user/profile') ? 'active' : '',
            darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]'
          ]">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
              viewBox="0 0 20 19">
              <path
                d="M7.324 9.917A2.479 2.479 0 0 1 7.99 7.7l.71-.71a2.484 2.484 0 0 1 2.222-.688 4.538 4.538 0 1 0-3.6 3.615h.002ZM7.99 18.3a2.5 2.5 0 0 1-.6-2.564A2.5 2.5 0 0 1 6 13.5v-1c.005-.544.19-1.072.526-1.5H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h7.687l-.697-.7ZM19.5 12h-1.12a4.441 4.441 0 0 0-.579-1.387l.8-.795a.5.5 0 0 0 0-.707l-.707-.707a.5.5 0 0 0-.707 0l-.795.8A4.443 4.443 0 0 0 15 8.62V7.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.12c-.492.113-.96.309-1.387.579l-.795-.795a.5.5 0 0 0-.707 0l-.707.707a.5.5 0 0 0 0 .707l.8.8c-.272.424-.47.891-.584 1.382H8.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1.12c.113.492.309.96.579 1.387l-.795.795a.5.5 0 0 0 0 .707l.707.707a.5.5 0 0 0 .707 0l.8-.8c.424.272.892.47 1.382.584v1.12a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1.12c.492-.113.96-.309 1.387-.579l.795.8a.5.5 0 0 0 .707 0l.707-.707a.5.5 0 0 0 0-.707l-.8-.795c.273-.427.47-.898.584-1.392h1.12a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5ZM14 15.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5Z" />
            </svg>
            <span class="mx-3">{{ $t("asidebar.settings") }}</span>
            </Link>
          </li>

          <li>
            <Link href="/payments" preserve-scroll preserve-state :class="[
            'flex items-center p-2 rounded-lg group',
            currentRoute && currentRoute.includes('/payments') ? 'active' : '',
            darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]'
          ]">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 11 20">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M1.75 15.363a4.954 4.954 0 0 0 2.638 1.574c2.345.572 4.653-.434 5.155-2.247.502-1.813-1.313-3.79-3.657-4.364-2.344-.574-4.16-2.551-3.658-4.364.502-1.813 2.81-2.818 5.155-2.246A4.97 4.97 0 0 1 10 5.264M6 17.097v1.82m0-17.5v2.138" />
            </svg>
            <span class="mx-3">{{ $t("asidebar.payment") }}</span>
            </Link>
          </li>

          <li>
            <Link preserve-state preserve-scroll href="/configurations" :class="[
            'flex items-center p-2 rounded-lg group',
            currentRoute && currentRoute.includes('/configurations') ? 'active' : '',
            darkMode ? 'text-gray-300 hover:text-white hover:bg-[#6366F1]' : 'text-gray-500 hover:text-white hover:bg-[#6366F1]'
          ]">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings-automation" width="24"
              height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
              stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
              <path
                d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z">
              </path>
              <path d="M10 9v6l5 -3z"></path>
            </svg>
            <span class="mx-3">{{ $t("asidebar.configuration") }}</span>
            </Link>
          </li>
        </ul>
      </div>
    </div>
  </aside>

  <div class="relative" :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
    <div class="p-2 rounded-xl mx-2">
      <div class="flex justify-end items-center">
       
        <div class="ml-3 relative">
          <Dropdown :align="$i18n.locale === 'ar' ? 'left' : 'right'" width="48">
            <template #trigger>
              <button type="button"
                class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                <svg width="35px" height="35px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                  <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                  <g id="SVGRepo_iconCarrier">
                    <path
                      d="M20.58 19.37L17.59 11.01C17.38 10.46 16.91 10.12 16.37 10.12C15.83 10.12 15.37 10.46 15.14 11.03L12.16 19.37C12.02 19.76 12.22 20.19 12.61 20.33C13 20.47 13.43 20.27 13.57 19.88L14.19 18.15H18.54L19.16 19.88C19.27 20.19 19.56 20.38 19.87 20.38C19.95 20.38 20.04 20.37 20.12 20.34C20.51 20.2 20.71 19.77 20.57 19.38L20.58 19.37ZM14.74 16.64L16.38 12.05L18.02 16.64H14.74ZM12.19 7.85C9.92999 11.42 7.89 13.58 5.41 15.02C5.29 15.09 5.16 15.12 5.04 15.12C4.78 15.12 4.53 14.99 4.39 14.75C4.18 14.39 4.3 13.93 4.66 13.73C6.75999 12.51 8.48 10.76 10.41 7.86H4.12C3.71 7.86 3.37 7.52 3.37 7.11C3.37 6.7 3.71 6.36 4.12 6.36H7.87V4.38C7.87 3.97 8.21 3.63 8.62 3.63C9.02999 3.63 9.37 3.97 9.37 4.38V6.36H13.12C13.53 6.36 13.87 6.7 13.87 7.11C13.87 7.52 13.53 7.86 13.12 7.86H12.18L12.19 7.85ZM12.23 15.12C12.1 15.12 11.97 15.09 11.85 15.02C11.2 14.64 10.57 14.22 9.97999 13.78C9.64999 13.53 9.58 13.06 9.83 12.73C10.08 12.4 10.55 12.33 10.88 12.58C11.42 12.99 12.01 13.37 12.61 13.72C12.97 13.93 13.09 14.39 12.88 14.75C12.74 14.99 12.49 15.12 12.23 15.12Z"
                      fill="#000000"></path>
                  </g>
                </svg>
              </button>
            </template>

            <template #content>
              <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                <li>
                  <button @click="setLocale('ar')" class="flex items-center px-4 py-2">
                    <div
                      class="flex items-center p-1 text-gray-500 hover:text-white rounded-lg dark:text-white hover:bg-[#A6ACB2] group">
                      <svg height="30px" width="30px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve"
                        fill="#000000">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                          <path style="fill: #ff4b55"
                            d="M473.655,423.724H38.345C17.167,423.724,0,406.557,0,385.379V126.621 c0-21.177,17.167-38.345,38.345-38.345h435.31c21.177,0,38.345,17.167,38.345,38.345V385.38 C512,406.557,494.833,423.724,473.655,423.724z">
                          </path>
                          <path style="fill: #5a8250"
                            d="M297.951,277.961l67.433-49.478h-83.509L256,148.845l-25.875,79.638h-83.509l67.433,49.478 l-25.675,79.022l67.56-48.289l0.066,0.049l0.066-0.049l67.56,48.289L297.951,277.961z M311.478,246.138l-19.273,14.142 l-4.595-14.142H311.478z M234.784,271.277l8.169-25.14h26.094l8.169,25.14L256,286.845L234.784,271.277z M256,205.983l7.31,22.5 h-14.622L256,205.983z M200.522,246.138h23.868l-4.595,14.142L200.522,246.138z M221.737,311.431l7.302-22.472l11.911,8.739 L221.737,311.431z M282.962,288.959l7.302,22.472l-19.212-13.732L282.962,288.959z">
                          </path>
                        </g>
                      </svg><span class="mx-2">{{ $t("languages.arabic") }}</span>
                    </div>
                  </button>
                </li>

                <li>
                  <button @click="setLocale('en')" class="flex items-center px-4 py-2">
                    <div
                      class="flex items-center p-1 text-gray-500 hover:text-white rounded-lg dark:text-white hover:bg-[#A6ACB2] group">
                      <svg height="30px" width="30px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve"
                        fill="#000000">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                          <path style="fill: #41479b"
                            d="M473.655,88.276H38.345C17.167,88.276,0,105.443,0,126.621V385.38 c0,21.177,17.167,38.345,38.345,38.345h435.31c21.177,0,38.345-17.167,38.345-38.345V126.621 C512,105.443,494.833,88.276,473.655,88.276z">
                          </path>
                          <path style="fill: #f5f5f5"
                            d="M511.469,120.282c-3.022-18.159-18.797-32.007-37.814-32.007h-9.977l-163.54,107.147V88.276h-88.276 v107.147L48.322,88.276h-9.977c-19.017,0-34.792,13.847-37.814,32.007l139.778,91.58H0v88.276h140.309L0.531,391.717 c3.022,18.159,18.797,32.007,37.814,32.007h9.977l163.54-107.147v107.147h88.276V316.577l163.54,107.147h9.977 c19.017,0,34.792-13.847,37.814-32.007l-139.778-91.58H512v-88.276H371.691L511.469,120.282z">
                          </path>
                          <g>
                            <polygon style="fill: #ff4b55"
                              points="282.483,88.276 229.517,88.276 229.517,229.517 0,229.517 0,282.483 229.517,282.483 229.517,423.724 282.483,423.724 282.483,282.483 512,282.483 512,229.517 282.483,229.517 ">
                            </polygon>
                            <path style="fill: #ff4b55"
                              d="M24.793,421.252l186.583-121.114h-32.428L9.224,410.31 C13.377,415.157,18.714,418.955,24.793,421.252z">
                            </path>
                            <path style="fill: #ff4b55"
                              d="M346.388,300.138H313.96l180.716,117.305c5.057-3.321,9.277-7.807,12.287-13.075L346.388,300.138z">
                            </path>
                            <path style="fill: #ff4b55"
                              d="M4.049,109.475l157.73,102.387h32.428L15.475,95.842C10.676,99.414,6.749,104.084,4.049,109.475z">
                            </path>
                            <path style="fill: #ff4b55"
                              d="M332.566,211.862l170.035-110.375c-4.199-4.831-9.578-8.607-15.699-10.86L300.138,211.862H332.566z">
                            </path>
                          </g>
                        </g>
                      </svg><span class="mx-2">{{ $t("languages.english") }}</span>
                    </div>
                  </button>
                </li>

                <li>
                  <button @click="setLocale('fr')" class="flex items-center px-4 py-2">
                    <div
                      class="flex items-center p-1 text-gray-500 hover:text-white rounded-lg dark:text-white hover:bg-[#A6ACB2] group">
                      <svg width="30px" height="30px" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
                        class="iconify iconify--twemoji" preserveAspectRatio="xMidYMid meet" fill="#000000">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                          <path fill="#ED2939" d="M36 27a4 4 0 0 1-4 4h-8V5h8a4 4 0 0 1 4 4v18z"></path>
                          <path fill="#002495" d="M4 5a4 4 0 0 0-4 4v18a4 4 0 0 0 4 4h8V5H4z"></path>
                          <path fill="#EEE" d="M12 5h12v26H12z"></path>
                        </g>
                      </svg>
                      <span class="mx-2">{{ $t("languages.french") }}</span>
                    </div>
                  </button>
                </li>
              </ul>
            </template>
          </Dropdown>
        </div>

        <!-- Dropdown menu -->
        <div class="ml-3 relative">
          <Dropdown :align="$i18n.locale === 'ar' ? 'left' : 'right'" width="48">
            <template #trigger>
              <button v-if="$page.props.jetstream.managesProfilePhotos"
                class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url"
                  :alt="$page.props.auth.user.name" />
              </button>

              <span v-else class="inline-flex rounded-md">
                <button type="button"
                  class="inline-flex items-center font-bold text-xl border border-transparent leading-4 rounded-md text-black focus:outline-none active:bg-gray-50 transition ease-in-out duration-150">
                  <svg width="35px" height="35px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                      <circle cx="12" cy="6" r="4" stroke="#000000" stroke-width="1.5"></circle>
                      <path
                        d="M15 20.6151C14.0907 20.8619 13.0736 21 12 21C8.13401 21 5 19.2091 5 17C5 14.7909 8.13401 13 12 13C15.866 13 19 14.7909 19 17C19 17.3453 18.9234 17.6804 18.7795 18"
                        stroke="#000000" stroke-width="1.5" stroke-linecap="round"></path>
                    </g>
                  </svg>
                </button>
              </span>
            </template>

            <template #content>
              <!-- Account Management -->
              <div class="block px-2 py-2 text-xs text-gray-400">
                Manage Account
              </div>

              <DropdownLink :href="route('profile.show')">
                <div
                  class="flex items-center p-1 text-gray-500 hover:text-white rounded-lg dark:text-white hover:bg-[#A6ACB2] group">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-circle" width="20"
                    height="20" viewBox="0 0 24 24" stroke-width="2" stroke="#6C6E71" fill="none" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                    <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                    <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                  </svg>
                  <span class="mx-2">Profile</span>
                </div>
              </DropdownLink>

              <DropdownLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')">
                <div
                  class="flex items-center p-1 text-gray-500 hover:text-white rounded-lg dark:text-white hover:bg-[#A6ACB2] group">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-api-app" width="20"
                    height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#6C6E71" fill="none" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 15h-6.5a2.5 2.5 0 1 1 0 -5h.5" />
                    <path d="M15 12v6.5a2.5 2.5 0 1 1 -5 0v-.5" />
                    <path d="M12 9h6.5a2.5 2.5 0 1 1 0 5h-.5" />
                    <path d="M9 12v-6.5a2.5 2.5 0 0 1 5 0v.5" />
                  </svg>
                  <span class="mx-2"> API Tokens</span>
                </div>
              </DropdownLink>

              <div class="border-t border-gray-200" />
              <!-- Authentication -->
              <div>
                <div class="flex justify-center">
                  <div class="w-3/4">
                        <button label="Open Dialog" @click="showLogoutConfirmation('top')" severity="secondary" style="min-width: 10rem" >
                      <div
                        class="flex items-center p-1 m-1 text-gray-500 hover:text-white rounded-lg dark:text-white hover:bg-[#A6ACB2] group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-logout" width="20"
                          height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#6C6E71" fill="none"
                          stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                          <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                          <path d="M9 12h12l-3 -3" />
                          <path d="M18 15l3 -3" />
                        </svg>
                        <span class="mx-2">Log Out</span>
                      </div>
                    </button>
                  </div>
                </div>

                  

                
               <Dialog v-model:visible="logoutDialogVisible" header="Edit Profile" :style="{ width: '25rem' }" position="top" :modal="true" :draggable="false">
           
             <template #header>
                    <div
                      class="inline-flex align-items-center justify-content-center gap-2"
                    >
                      <span class="font-bold white-space-nowrap"
                        > {{ $t("logout.logoutconfirmation") }}</span
                      >
                    </div>
                  </template>
                 
                  <p class="text-1xl my-3 text-center">
                    {{ $t("logout.logout") }}
                  </p>
                 <template #footer>
  <div class="flex justify-end">
    <button label="No" icon="pi pi-times" @click="cancelLogout"
      class="flex items-center text-lg text-white p-2 rounded-xl bg-gray-400 shadow-lg shadow-gray-400/50 hover:bg-gray-300 mr-2">
      <span class="mx-2">No</span>
    </button>
        <button label="Yes" icon="pi pi-check" @click="confirmLogout"
      class="flex items-center text-lg text-white p-2 rounded-xl bg-red-500 shadow-lg shadow-red-500/30 hover:bg-red-300">
      <span class="mx-2">Yes</span>
    </button>
         </div>
           </template>
        </Dialog>

              </div>
            </template>
          </Dropdown>
        </div>
      </div>
    </div>

    <div class="border-t border-gray-200" />

    <div :class="$i18n.locale === 'ar' ? 'sm:mr-64' : 'sm:ml-64'">
      <main>
        <slot />
      </main>
    </div>
  </div>
</template>

<style scoped>
.active {
  background: linear-gradient(to right, #5851db, #667eea);
  background-color: #667eea;
  color: white;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>









