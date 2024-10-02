<script setup>
import InsideLayout from "../../Layouts/InsideLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import Dialog from "primevue/dialog";
import { ref } from "vue";
import { useI18n } from "vue-i18n";
import InputNumber from "primevue/inputnumber";
import { useToast } from "primevue/usetoast";
import Dropdown from "primevue/dropdown";
import Toast from "primevue/toast";
import Vue3EasyDataTable from "vue3-easy-data-table";
import "vue3-easy-data-table/dist/style.css";

const { t } = useI18n();
//Get Data From Controller:
const props = defineProps({
  incomes: {
    type: Array,
    required: true,
  },
});

const searchField = ["source", "description", "amount", "income_date"];
const searchValue = ref();

//DataTable items ::
const headers = [
  { text: "source", value: "source", sortable: true },
  { text: "description", value: "description", sortable: true },
  { text: "amount", value: "amount", sortable: true },
  { text: "income_date", value: "income_date", sortable: true },
  { text: "operations", value: "operations" },
  { text: "print", value: "print" },
];
const addIncomeVisible = ref(false);
const toast = useToast();
const showlIncomeVisible = ref(false);
const editIncomeVisible = ref(false);
let itemToShow;
const showlIncome = (item) => {
  itemToShow = item;
  showlIncomeVisible.value = true;
};
let itemToEdit;
const editIncome = (item) => {
  itemToEdit = item.id;
  incomeForm.source = item.source;
  incomeForm.description = item.description;
  incomeForm.amount = item.amount;
  incomeForm.income_date = item.income_date;
  editIncomeVisible.value = true;
};

const incomeForm = useForm({
  source: "",
  description: "",
  amount: "",
  income_date: "",
});
const resertIncomeForm = () => {
  incomeForm.source = "";
  incomeForm.description = "";
  incomeForm.amount = "";
  incomeForm.income_date = "";
};
//Open ADD FILIERE MODAL ::
const openAddIncomeModal = () => {
  incomeForm.reset;
  resertIncomeForm();
  addIncomeVisible.value = true;
};
const isButtonClicked = ref(false);
const addIncome = () => {
  if (
    incomeForm.source === "" ||
    incomeForm.amount === "" ||
    incomeForm.income_date === ""
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
    isButtonClicked.value = true;
    router.post("/incomes", incomeForm, {
      preserveScroll: true,
      preserveState: true,
      onProgress: (page) => { },
      onSuccess: (page) => {
        addIncomeVisible.value = false;
        props.incomes = props.incomes;
        resertIncomeForm();
        incomeForm.reset;
        isButtonClicked.value = false;
      },
    });
    toast.add({
      severity: "success",
      summary: t("toast.success"),
      detail: t("toast.successMessages"),
      group: "bc",
      life: 3000,
    });
  }
};

//Edit Income Function
const editIncomeFun = () => {
  if (
    incomeForm.source === "" ||
    incomeForm.amount === "" ||
    incomeForm.income_date === ""
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
    router.put("/incomes/" + itemToEdit, incomeForm, {
      preserveScroll: true,
      preserveState: true,
      onProgress: (page) => { },
      onSuccess: (page) => {
        addIncomeVisible.value = false;
        props.incomes = props.incomes;
        resertIncomeForm();
        incomeForm.reset;
      },
    });
    editIncomeVisible.value = false;
    toast.add({
      severity: "success",
      summary: t("toast.success"),
      detail: t("toast.successMessages"),
      group: "bc",
      life: 3000,
    });
  }
};

// PDF

const generatePDF = async (item) => {
  try {
    itemToShow = item;
    console.log("value:", itemToShow);
    const id = itemToShow.id;
    const response = await axios.get(`/generate-pdf-revenu/${id}`, {
      responseType: "blob",
    });
    console.log("Response:", response);
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement("a");
    link.href = url;
    link.setAttribute("download", "revenu.pdf");
    document.body.appendChild(link);
    link.click();
  } catch (error) {
    console.error("Error generating PDF:", error);
  }
};


</script>
<template>
  <Head :title="$t('asidebar.incomes')" />
  <InsideLayout>
    <div class="bg-white p-6 m-4 rounded-xl grid xl:grid-cols-4">
      <div class="xl:col-span-2 mx-2">
        <input type="text" v-model="searchValue" :placeholder="$t('search.search')"
          class="h-14 py-6 w-full rounded-xl border border-gray-200" />
      </div>
      <div class="flex justify-start lg:m-2 xl:m-0">
        <button label="Show" @click="openAddIncomeModal"
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
          </svg><span class="mx-2">{{ $t('buttons.addincome') }} </span>
        </button>
      </div>
      <div class="flex mt-2">


        <input type="month" v-model="searchValue" :placeholder="$t('search.search')"
          class="h-14 py-6 w-full rounded-xl border border-gray-200" style="
              height: 50px;
              width: 192px;
              border: 1px solid rgb(54, 53, 53);
            " />
      </div>
    </div>

    <!--Data Table Here-->
    <div class="m-4" :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
      <Vue3EasyDataTable :headers="headers" :items="incomes" :search-field="searchField" :search-value="searchValue"
        border-cell theme-color="#6F32BE" table-class-name="customize-table">
        <!--Control Header-->

        <template #header-source="header">
          <p class="text-white text-[1.2rem] py-2">{{ $t('tableincome.source') }}</p>
        </template>

        <template #header-description="header">
          <p class="text-white text-[1.2rem] py-2">{{ $t('tableincome.description') }}</p>
        </template>

        <template #header-amount="header">
          <p class="text-white text-[1.2rem] py-2">{{ $t('tableincome.amount') }}</p>
        </template>

        <template #header-income_date="header">
          <p class="text-white text-[1.2rem] py-2">{{ $t('tableincome.income_date') }}</p>
        </template>
        <template #header-operations="header">
          <p class="text-white text-[1.2rem] py-2">
            {{ $t("salleTable.operation") }}
          </p>
        </template>
        <template #header-print="header">
          <p class="text-white text-[1.2rem] py-2">
           print
          </p>
        </template>

        <template #item-source="item">
          <p class="text-[1rem] flex font-semibold py-3"
            :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
            {{ item.source }}
          </p>
        </template>
        <template #item-description="item">
          <p class="text-[1rem] flex font-semibold py-3"
            :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
            {{ item.description }}
          </p>
        </template>
        <template #item-income_date="item">
          <p class="text-[1rem] flex font-semibold py-3"
            :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
            {{ item.income_date }}
          </p>
        </template>
        <template #item-amount="item">
          <p class="text-[1rem] flex font-semibold py-3"
            :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
            {{ item.amount }}
          </p>
        </template>
        n
        <template #item-operations="item" :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">
          <div class="flex justify-center">
            <button @click="showlIncome(item)">
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
            <button @click="editIncome(item)" class="mx-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="36" height="36"
                viewBox="0 0 24 24" stroke-width="1.5" stroke="#F59E0B" fill="none" stroke-linecap="round"
                stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                <path d="M16 5l3 3" />
              </svg>
            </button>
          </div>
        </template>
         <template #item-print="item">
                  <div
                    style="
                      display: flex;
                      justify-content: center;
                      align-items: center;
                    "
                  >
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
    </div>

    <!--ADD INcome  MODAL-->
    <Toast position="bottom-center" group="bc" />
    <div class="card flex justify-content-center m-2">
      <Dialog v-model:visible="addIncomeVisible" modal :header="$t('buttons.addincome')" :style="{ width: '50rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
          <div class="m-10">
            <form class="grid" @submit.prevent="addIncome">
              <label for="source" class="mt-2 text-gray-500">{{ $t('tableincome.source') }}*</label>
              <input type="text" class="w-full h-14 rounded-xl border border-gray-200 my-2"
                :placeholder="$t('tableincome.source')" id="source" v-model="incomeForm.source" />

              <label for="amount" class="mt-2 text-gray-500">{{ $t('tableincome.amount') }}*</label>
              <input type="number" class="w-full h-14 rounded-xl border border-gray-200 my-2"
                :placeholder="$t('tableincome.amount')" id="amount" v-model="incomeForm.amount" />

              <label for="income_date" class="mt-2 text-gray-500">{{ $t('tableincome.income_date') }}*</label>
              <input type="date" class="w-full h-14 rounded-xl border border-gray-200 my-2"
                :placeholder="$t('tableincome.income_date')" id="income_date" v-model="incomeForm.income_date" />

              <label for="description" class="mt-2 text-gray-500">{{ $t('tableincome.description') }}</label>
              <textarea type="text" class="w-full rounded-xl border border-gray-200 my-2"
                :placeholder="$t('addevenmentForm.description')" id="description" rows="6"
                v-model="incomeForm.description"></textarea>

              <div class="flex justify-end mt-5">
                <button :disabled="isButtonClicked"
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
                  </svg><span class="mx-2">{{ $t('buttons.addincome') }}</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </Dialog>
    </div>

    <!-- // edit income -->
    <!--EDit LOCALE MODAL-->
    <div class="card flex justify-content-center m-2">
      <Dialog v-model:visible="editIncomeVisible" modal :header="$t('buttons.editincome')" :style="{ width: '50rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
          <div class="m-10">
            <form class="grid" @submit.prevent="editIncomeFun">
              <label for="source" class="mt-2 text-gray-500">{{ $t('tableincome.source') }}*</label>
              <input type="text" class="w-full h-14 rounded-xl border border-gray-200 my-2"
                :placeholder="$t('tableincome.source')" id="source" v-model="incomeForm.source" />

              <label for="amount" class="mt-2 text-gray-500">{{ $t('tableincome.amount') }}*</label>
              <input type="text" class="w-full h-14 rounded-xl border border-gray-200 my-2"
                :placeholder="$t('tableincome.amount')" id="amount" v-model="incomeForm.amount" />
              <label for="income_date" class="mt-2 text-gray-500">{{ $t('tableincome.income_date') }}*</label>
              <input type="date" class="w-full h-14 rounded-xl border border-gray-200 my-2"
                :placeholder="$t('tableincome.income_date')" id="income_date" v-model="incomeForm.income_date" />
              <label for="description" class="mt-2 text-gray-500">{{ $t('tableincome.description') }} </label>
              <textarea type="text" class="w-full rounded-xl border border-gray-200 my-2"
                :placeholder="$t('addevenmentForm.description')" id="description" rows="6"
                v-model="incomeForm.description"></textarea>

              <div class="flex justify-end mt-5">
                <button :disabled="isButtonClicked"
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
                  </svg><span class="mx-2">{{ $t('buttons.editincome') }}</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </Dialog>
    </div>

    <!--SHOW INCOME MODAL-->
    <div class="card flex justify-content-center m-2">
      <Dialog v-model:visible="showlIncomeVisible" :header="$t('buttons.income')" modal :style="{ width: '50rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <label for="source" class="text-x font-semibold text-[#6F32BE]">{{ $t('tableincome.source') }}*</label>
        <input class="w-full h-10 rounded-xl border border-gray-200 my-2" :placeholder="itemToShow.source"
          :disabled="inputDisabled" />

        <label for="amount" class="text-x font-semibold text-[#6F32BE]">{{ $t('tableincome.amount') }}*</label>
        <input type="text" class="w-full h-10 rounded-xl border border-gray-200 my-2" :placeholder="itemToShow.amount"
          :disabled="inputDisabled" />

        <label for="income_date" class="text-x font-semibold text-[#6F32BE]">{{ $t('tableincome.income_date') }}</label>
        <input class="w-full h-10 rounded-xl border border-gray-200 my-2" :placeholder="itemToShow.income_date"
          :disabled="inputDisabled" />

        <label for="description" class="text-x font-semibold text-[#6F32BE]">{{ $t('tableincome.description') }}</label>
        <textarea type="text" class="w-full rounded-xl border border-gray-200 my-2" :placeholder="itemToShow.description"
          id="description" rows="6" :disabled="inputDisabled" v-model="incomeForm.description"></textarea>
      </Dialog>
    </div>
  </InsideLayout>
</template>

<style>
.customize-table {
  --easy-table-header-background-color: #818cf8;
}
</style>