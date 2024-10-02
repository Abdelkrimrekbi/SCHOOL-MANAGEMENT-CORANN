<script setup>
import { ref, defineProps, onMounted, watch } from "vue";
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

const { t } = useI18n();
//Get Data From Controller:
const props = defineProps({
  studentShow: {
    type: Object,
    required: true,
  },
  totalRest: {
    type: Number,
    default: () => 0,
  },
});

//DataTable items ::
const headers = [
   {text: "", value: "check", sortable: false },
  { text: "Date Payment", value: "date_payment", sortable: true },
  { text: "Month", value: "month", sortable: true },
  { text: "Type", value: "type", sortable: true },
  { text: "Amount", value: "amount", sortable: true },
  { text: "Rest", value: "rest", sortable: true },
  // { text: "operations", value: "operations", sortable: true },
];
const searchField = ["Date", "Month"];
const searchValue = ref();
const addPaymentVisible = ref(false);
const toast = useToast();
const type = ["Fees", "Inscription", "Activity"];
const monthArray = ref([
  { month: "september", value: 9, active: false },
  { month: "octobre", value: 10, active: false },
  { month: "november", value: 11, active: false },
  { month: "december", value: 12, active: false },
  { month: "january", value: 1, active: false },
  { month: "february", value: 2, active: false },
  { month: "march", value: 3, active: false },
  { month: "april", value: 4, active: false },
  { month: "may", value: 5, active: false },
  { month: "june", value: 6, active: false },
  { month: "july", value: 7, active: false },
  { month: "august", value: 8, active: false },
]);
let isClick = 0;
const paymentForm = useForm({
  id: ref([]),
  date_payment: "",
  amount: "",
  type: "Fees",
  promotion: "",
  rest: "",
  month: ref([]),
  student_id: "",
  comments: "",
  isChecked: false,
});
const inputDisabled = ref(false);
const resetPaymentForm = () => {
  paymentForm.date_payment = "";
  paymentForm.amount = " ";
  paymentForm.promotion = "";
  paymentForm.rest = "";
  monthArray.value.forEach((month) => {
    month.active = false;
  });
  paymentForm.month = [];
  paymentForm.student_id = "";
  paymentForm.comments = "";
  isClick = 0;
};
// FUNCTION RETURN MONTH PAYMENT
const monthPayment = [];
const monthPaye = [];
const monthNoPaye = [];
const returnMonthPayment = () => {
  props.studentShow.payment.forEach((temp) => {
    if (parseFloat(temp.amount) >= parseFloat(props.studentShow.level.price)) {
      monthPaye.push(temp.month);
    } else {
      monthNoPaye.push(temp.month);
      monthPayment.push({ month: temp.month, amount: temp.amount });
    }
  });
  console.log("Month payament", monthPaye);
  console.log("Month not payament", monthNoPaye);
};
// FUNCTION CLICK TO DETERMINE THE MONTH
const onClick = (month) => {
  let index = -1;
  for (let i = 0; i < paymentForm.month.length; i++) {
    if (paymentForm.month[i].toLowerCase() === month.month) {
      index = i;
      break;
    }
  }
  if (index === -1) {
    paymentForm.month.push(month.month);
    month.active = true;
    isClick++;
  } else {
    paymentForm.month.splice(index, 1);
    month.active = false;
    isClick--;
  }
};

// FUNCTION FOR DETERMINE PAYING OFF PAYMENTS
const payingOff = () => {
  let result = 0;
  let i = 0;
  paymentForm.month.forEach((m) => {
    let monthFound = monthPayment.find((temp) => temp.month === m);
    if (monthFound) {
      result += props.studentShow.level.price - monthFound.amount;
      i++;
    }
  });
  i = isClick - i;
  result += props.studentShow.level.price * i;
  return result.toString();
};

const resetMonth = () => {
  const result = payingOff() - paymentForm.amount;
  return result.toString();
};

// FUNCTION FOR SAVE NEW PAYMENT
let fraiInscription = null;
const addPayment = () => {
  returnMonthPayment();
  if (paymentForm.amount === "") {
    toast.add({
      severity: "error",
      summary: t("toast.error"),
      detail: t("toast.errorMessage"),
      group: "bc",
      life: 3000,
    });
    return;
  }
  if (paymentForm.type === "Fees") {
    const combinedData = {
      date_payment: format(new Date(), "yyyy-MM-dd"),
      type: paymentForm.type,
      amount: paymentForm.amount,
      month: paymentForm.month,
      rest: resetMonth(),
      student_id: props.studentShow.id,
      comments: paymentForm.comments,
      prix_level: props.studentShow.level.price,
    };
    console.log("Voila data: ", JSON.stringify(combinedData, null, 2));
    router.post("/payments", combinedData, {
      headers: { "Content-Type": "application/json" },
      preserveScroll: true,
      preserveState: true,
      onSuccess: (page) => {
        addPaymentVisible.value = false;
        resetPaymentForm();
        monthPayment.length = 0;
        returnMonthPayment();
        toast.add({
          severity: "success",
          summary: t("toast.success"),
          detail: t("toast.successMessages"),
          group: "bc",
          life: 3000,
        });
      },
      onError: (error) => {
        console.error("Error", error);
      },
    });
  } else {
    const combinedData = {
      date_payment: format(new Date(), "yyyy-MM-dd"),
      type: paymentForm.type,
      amount: paymentForm.amount,
      rest: fraiInscription - paymentForm.amount,
      student_id: props.studentShow.id,
      comments: paymentForm.comments,
      prix_level: props.studentShow.level.price,
    };
    console.log("Voila data: ", JSON.stringify(combinedData, null, 2));
    router.post("/payments", combinedData, {
      headers: { "Content-Type": "application/json" },
      preserveScroll: true,
      preserveState: true,
      onSuccess: (page) => {
        addPaymentVisible.value = false;
        resetPaymentForm();
        returnMonthPayment();
        toast.add({
          severity: "success",
          summary: t("toast.success"),
          detail: t("toast.successMessages"),
          group: "bc",
          life: 3000,
        });
      },
      onError: (error) => {
        console.error("Error", error);
      },
    });
  }
};



onMounted(() => {
  returnMonthPayment();

});

 const generatePDFserie  = async () => {
  try {
   
   // const idsArray =Object.values(NBPrint.value);
   const Array =selectedItems.value ;
    console.log("idsArray  =>:",Array);
    console.log("itemToShow.student_id   =>:",props.studentShow.id);
    const id = props.studentShow.id;
    const response = await axios.get(`/generate-pdf-serie/${Array}/${id}`, {
      responseType: "blob",
    });
    console.log("Response:", response);
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement("a");
    link.href = url;
    link.setAttribute("download", "document.pdf");
    document.body.appendChild(link);
    link.click();
  } catch (error) {
    console.error("Error generating PDF:", error);
  }
};



// const insert = (item, event) => {
//   console.log("isChecked: ", event.target.checked);
//   if (event.target.checked && !NBPrint.value.includes(item.id)) {
//     NBPrint.value.push(item.id);
//   } else if (!event.target.checked && NBPrint.value.includes(item.id)) {
//     let index = NBPrint.value.indexOf(item.id);
//     NBPrint.value.splice(index, 1);
//   }
  
//   console.log("IDs array:", NBPrint.value);
  
// };

// const NBPrint = ref([]);


const selectedItems = ref([]);

const isSelected = (id) => selectedItems.value.includes(id);

const toggleRowSelection = (id) => {
  const index = selectedItems.value.indexOf(id);
  if (index === -1) {
    selectedItems.value.push(id);
     console.log("selectitmes1", selectedItems.value);
  } else {
    selectedItems.value.splice(index, 1);
     console.log("selectitmes2", selectedItems.value);
  }
};

const selectAllItems = (event) => {
  if (event.target.checked) {
    selectedItems.value = props.studentShow.payment.map(item => item.id);
    console.log("selectitmes3", selectedItems.value);
  } else {
    selectedItems.value = [];
     console.log("selectitmes4", selectedItems.value);
  }
};
 
</script>
<template>
  <Head title="Payment Student" />
  <InsideLayout>
    <div class="body">
      <TabView class="tabview-custom">
        <TabPanel>
          <template #header style="margin-bottom: 20px">
            <i class="pi pi-user ml-2" style="margin-right: 8px"></i>
            <span class="text-base font-serif">Profil</span>
          </template>
          <div class="grid">
            <div :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
              <div class="grid xl:grid-cols-2">
                <div class="mt-3">
                  <label for="cne" class="mx-2 mt-2 text-gray-500">Nom*</label>
                  <input
                    type="text"
                    class="w-full h-14 rounded-xl border border-gray-200 my-2"
                    :placeholder="props.studentShow.name"
                    :disabled="inputDisabled"
                  />
                </div>
                <div class="xl:ml-4 mt-3 sm:ml-0">
                  <label for="date_birth" class="mx-2 mt-2 text-gray-500"
                    >Date Of Birth*</label
                  >
                  <input
                    type="text"
                    class="w-full h-14 rounded-xl border border-gray-200 my-2"
                    :placeholder="props.studentShow.date_birth"
                    id="date_birth"
                    :disabled="inputDisabled"
                  />
                </div>
                <div class="mt-3">
                  <label for="lieu_birth" class="mx-2 mt-2 text-gray-500"
                    >lieu birth*</label
                  >
                  <input
                    type="text"
                    class="w-full h-14 rounded-xl border border-gray-200 my-2"
                    :placeholder="props.studentShow.lieu_birth"
                    id="lastname"
                    :disabled="inputDisabled"
                  />
                </div>
                <div class="xl:ml-4 sm:ml-0 mt-3">
                  <label for="date_birth" class="mx-2 mt-2 text-gray-500"
                    >Address*</label
                  >
                  <input
                    type="text"
                    class="w-full h-14 rounded-xl border border-gray-200 my-2"
                    :placeholder="props.studentShow.address"
                    id="address"
                    :disabled="inputDisabled"
                  />
                </div>
                <div class="mt-3">
                  <label for="gender" class="mx-2 mt-2 text-gray-500"
                    >phone*</label
                  >
                  <input
                    type="text"
                    class="w-full h-14 rounded-xl border border-gray-200 my-2"
                    :placeholder="props.studentShow.phone"
                    id="phone"
                    :disabled="inputDisabled"
                  />
                </div>
                <div class="xl:ml-4 sm:ml-0 mt-3">
                  <label for="level_id" class="mx-2 mt-2 text-gray-500"
                    >Level*</label
                  >
                  <input
                    type="text"
                    class="w-full h-14 rounded-xl border border-gray-200 my-2"
                    :placeholder="props.studentShow.level.level"
                    id="level_id"
                    :disabled="inputDisabled"
                  />
                </div>
                <div class="mt-3">
                  <label for="class_id" class="mx-2 mt-2 text-gray-500"
                    >Class*</label
                  >
                  <input
                    type="text"
                    class="w-full h-14 rounded-xl border border-gray-200 my-2"
                    :placeholder="props.studentShow.group.group"
                    id="class_id"
                    :disabled="inputDisabled"
                  />
                </div>
                <div class="xl:ml-4 sm:ml-0 mt-3">
                  <label for="level_id" class="mx-2 mt-2 text-gray-500"
                    >Niveau etude*</label
                  >
                  <input
                    type="text"
                    class="w-full h-14 rounded-xl border border-gray-200 my-2"
                    :placeholder="props.studentShow.niveau_etude"
                    id="niveau_etude"
                    :disabled="inputDisabled"
                  />
                </div>
                <div class="mt-3">
                  <label for="class_id" class="mx-2 mt-2 text-gray-500"
                    >name ecole*</label
                  >
                  <input
                    type="text"
                    class="w-full h-14 rounded-xl border border-gray-200 my-2"
                    :placeholder="props.studentShow.name_school"
                    id="class_id"
                    :disabled="inputDisabled"
                  />
                </div>
                <div class="xl:ml-4 sm:ml-0 mt-3">
                  <label for="class_id" class="mx-2 mt-2 text-gray-500"
                    >la situation*</label
                  >
                  <input
                    type="text"
                    class="w-full h-14 rounded-xl border border-gray-200 my-2"
                    :placeholder="props.studentShow.la_situation"
                    id="class_id"
                    :disabled="inputDisabled"
                  />
                </div>
                <!-- <div class="xl:ml-4 sm:ml-0 mt-3">
                  <label for="cin" class="mx-2 mt-2 text-gray-500">CIN*</label>
                  <input type="text" class="w-full h-14 rounded-xl border border-gray-200 my-2"
                    :placeholder="props.studentShow.parent.CIN" id="cin" :disabled="inputDisabled" />
                </div> -->
                <!-- <div class="mt-3">
                  <label for="firstParent" class="mx-2 mt-2 text-gray-500">First Name Parent*</label>
                  <input type="text" class="w-full h-14 rounded-xl border bo-rder-gray-200 my-2"
                    :placeholder="props.studentShow.parent.first_name" id="firstParent" :disabled="inputDisabled" />
                </div>
                <div class="xl:ml-4 sm:ml-0 mt-3">
                  <label for="lastParent" class="mx-2 mt-2 text-gray-500">Last Name Parent*</label>
                  <input type="text" class="w-full h-14 rounded-xl border border-gray-200 my-2"
                    :placeholder="props.studentShow.parent.last_name" id="lastParent" :disabled="inputDisabled" />
                </div>
                <div class="mt-3">
                  <label for="phoneParent" class="mx-2 mt-2 text-gray-500">Phone Parent*</label>
                  <input type="text" class="w-full h-14 rounded-xl border border-gray-200 my-2"
                    :placeholder="props.studentShow.parent.phone" id="phoneParent" :disabled="inputDisabled" />
                </div>
                <div class="xl:ml-4 sm:ml-0 mt-3">
                  <label for="adressParent" class="mx-2 mt-2 text-gray-500">Address Parent*</label>
                  <input type="text" class="w-full h-14 rounded-xl border border-gray-200 my-2"
                    :placeholder="props.studentShow.parent.address" id="adressParent" :disabled="inputDisabled" />
                </div> -->
              </div>
            </div>
          </div>
        </TabPanel>
        <TabPanel>
          <template #header style="margin-bottom: 20px">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="icon icon-tabler icon-tabler-credit-card-pay"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              stroke-width="2"
              stroke="currentColor"
              fill="none"
              stroke-linecap="round"
              stroke-linejoin="round"
              style="margin-right: 8px"
            >
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path
                d="M12 19h-6a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v4.5"
              />
              <path d="M3 10h18" />
              <path d="M16 19h6" />
              <path d="M19 16l3 3l-3 3" />
              <path d="M7.005 15h.005" />
              <path d="M11 15h2" />
            </svg>
            <span class="text-base font-serif">Payment</span>
          </template>
          <div style="margin-top: -20px">
            <div class="bg-white p-2 m-4 rounded-xl">
              <div class="flex justify-between lg:m-2 xl:m-0">
                <button
                  class="flex items-center shadow-lg p-2 rounded-xl text-white"
                  :class="props.totalRest > 0 ? 'bg-red-200' : 'bg-green-300'"
                >
                  {{ props.totalRest }} {{ "DH" }}
                </button>
                <button
                   class="flex items-center text-lg bg-yellow-500 shadow-lg shadow-yellow-500/50 hover:bg-yellow-400 text-white p-2 rounded-xl"
                      @click="generatePDFserie()"
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
                    <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                    <path
                      d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z"
                    /></svg
                  ><span class="mx-2">Print</span>
                </button>
                <button
                  label="Show"
                  @click="addPaymentVisible = true"
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
                    <!-- </svg><span class="mx-2">{{ $t('buttons.addClass') }}</span></button> --></svg
                  ><span class="mx-2">Add Payment</span>
                </button>
              </div>
            </div>

            <!--DATATABLE HERE-->
             <div class="m-4" :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
    <Vue3EasyDataTable
      :headers="headers"
      :items="props.studentShow.payment"
      :search-field="searchField"
      :search-value="searchValue"
      table-class-name="customize-table"
      border-cell
      theme-color="#f7071b"
    >
      <!-- Header -->
      <template #header-check="header">
        <div>
          <p class="text-white text-[1.2rem] py-2" style="text-align: center; width: 80%; margin: auto">
            <input type="checkbox" @change="selectAllItems($event)" />
          </p>
        </div>
      </template>
      <template #header-date_payment="header">
        <div>
          <p class="text-white text-[1.2rem] py-2" style="text-align: center; width: 100%; margin: auto">Date Payment</p>
        </div>
      </template>
      <template #header-month="header">
        <div>
          <p class="text-white text-[1.2rem] py-2" style="text-align: center; width: 100%; margin: auto">Month</p>
        </div>
      </template>
      <template #header-type="header">
        <div>
          <p class="text-white text-[1.2rem] py-2" style="text-align: center; width: 100%; margin: auto">Type</p>
        </div>
      </template>
      <template #header-amount="header">
        <div>
          <p class="text-white text-[1.2rem] py-2" style="text-align: center; width: 100%; margin: auto">Amount</p>
        </div>
      </template>
      <template #header-rest="header">
        <div>
          <p class="text-white text-[1.2rem] py-2" style="text-align: center; width: 100%; margin: auto">Rest</p>
        </div>
      </template>
      
      <!-- Items -->
      <template #item-check="item">
        <div style="display: flex; justify-content: center; align-items: center;">
          <input type="checkbox" :checked="isSelected(item.id)" @change="toggleRowSelection(item.id)" />
        </div>
      </template>
      <template #item-date_payment="item">
        <div style="display: flex; justify-content: center; align-items: center;">
          <p class="text-[1rem] flex font-medium py-3" :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">{{ item.date_payment }}</p>
        </div>
      </template>
      <template #item-month="item">
        <div style="display: flex; justify-content: center; align-items: center;">
          <p class="text-[1rem] flex font-medium py-3" :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">{{ item.month }}</p>
        </div>
      </template>
      <template #item-type="item">
        <div style="display: flex; justify-content: center; align-items: center;">
          <p class="text-[1rem] flex font-medium py-3" :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">{{ item.type }}</p>
        </div>
      </template>
      <template #item-amount="item">
        <div style="display: flex; justify-content: center; align-items: center;">
          <p class="text-[1rem] flex font-medium py-3" :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">{{ item.amount }}</p>
        </div>
      </template>
      <template #item-rest="item">
        <div style="display: flex; justify-content: center; align-items: center;">
          <p class="text-[1rem] flex font-medium py-3" :class="$i18n.locale === 'ar' ? 'justify-start' : 'justify-start'">{{ item.rest }}</p>
        </div>
      </template>
    </Vue3EasyDataTable>
  </div>

            <!-- Toast -->
            <Toast position="bottom-center" group="bc" />
            <!--Add Payment Modals-->
            <div class="card flex justify-content-center">
              <Dialog
                v-model:visible="addPaymentVisible"
                modal
                header="Add Payment"
                :style="{ width: '50vw' }"
              >
                <div :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
                  <form @submit.prevent="addPayment">
                    <div class="xl:ml-4 sm:ml-0 mt-6">
                      <label for="type" class="mt-2 text-gray-500">Type*</label>
                      <Dropdown
                        v-model="paymentForm.type"
                        :options="type"
                        placeholder="Type"
                        class="w-full overflow-hidden"
                      />
                    </div>
                    <!--  -->
                    <div v-if="paymentForm.type === 'Fees'" class="mt-4">
                      <div
                        style="display: flex; flex-wrap: wrap"
                        class="xl:ml-3 sm:ml-0"
                      >
                        <div v-for="month in monthArray" :key="month.value">
                          <input
                            type="button"
                            @click="onClick(month)"
                            class="text-white w-10 h-10 rounded-full mx-2 my-2"
                            :disabled="monthPaye.includes(month.month)"
                            :class="
                              month.active
                                ? 'bg-blue-500'
                                : monthPaye.includes(month.month)
                                ? 'bg-green-400'
                                : monthNoPaye.includes(month.month)
                                ? 'bg-yellow-300'
                                : 'bg-neutral-400'
                            "
                            :value="month.value"
                          />
                        </div>
                      </div>
                      <div class="xl:ml-4 sm:ml-0 mt-2">
                        <label for="price" class="mx-2 mt-2 text-gray-500"
                          >Amount*</label
                        >
                        <input
                          type="text"
                          class="w-full h-14 rounded-xl border border-gray-200 my-2"
                          :placeholder="payingOff()"
                          :disabled="true"
                        />

                        <label for="amount" class="mx-2 mt-2 text-gray-500"
                          >Paying Off*</label
                        >
                        <input
                          type="number"
                          class="w-full h-14 rounded-xl border border-gray-200 my-2"
                          placeholder="Paying Off"
                          v-model="paymentForm.amount"
                          :max="parseFloat(payingOff())"
                          min="0"
                          @input="
                            () => {
                              if (
                                paymentForm.amount > parseFloat(payingOff())
                              ) {
                                paymentForm.amount = parseFloat(payingOff());
                              }
                            }
                          "
                        />
                      </div>
                    </div>
                    <!--  -->
                    <div v-if="paymentForm.type === 'Inscription'" class="mt-4">
                      <div class="xl:ml-2 sm:ml-0 mt-2">
                        <label
                          for="fraisInscription"
                          class="mx-2 mt-2 text-gray-500"
                          >Frais d'inscription</label
                        >
                        <input
                          type="number"
                          class="w-full ml-2 h-14 rounded-xl border border-gray-200 my-2"
                          placeholder="frai"
                          v-model="fraiInscription"
                        />
                      </div>
                      <div class="xl:ml-2 sm:ml-0 mt-2">
                        <label for="amount" class="mx-2 mt-2 text-gray-500"
                          >Amount</label
                        >
                        <input
                          type="number"
                          class="w-full ml-2 h-14 rounded-xl border border-gray-200 my-2"
                          placeholder="amount"
                          v-model="paymentForm.amount"
                        />
                      </div>
                    </div>
                    <!--  -->
                    <div v-if="paymentForm.type === 'Activity'" class="mt-4">
                      <p>Activity</p>
                    </div>
                    <!--  -->
                    <div class="flex justify-center items-center">
                      <button
                        class="text-white bg-indigo-500 w-24 h-10 rounded-md mx-2 my-2"
                      >
                        Ok
                      </button>
                    </div>
                  </form>
                </div>
              </Dialog>
            </div>
          </div>
        </TabPanel>
      </TabView>
    </div>
  </InsideLayout>
</template>

<style>
.customize-table {
  --easy-table-header-background-color: #818cf8;
}

.body {
  margin-left: 2%;
  margin-right: 2%;
  margin-top: 1%;
}

.p-tabview-nav-container {
  margin-bottom: 20px;
}

.p-tabview-nav {
  justify-content: center;
  border-radius: 10px;
  background-color: rgb(255 255 255);
}

.p-tabview-nav-link {
  background-color: rgb(255 255 255);
}

.p-tabview-nav-link:hover {
  border: 1px solid #227093;
  background-color: white;
  color: #227093;
}

.p-tabview-nav-link[aria-selected="true"] {
  color: #818cf8;
}

.p-tabview-panels {
  border-radius: 10px;
  margin-top: 2%;
}

.vue3-easy-data-table__header th .header.direction-left[data-v-19cc4b1b] {
  justify-content: center;
}
</style>