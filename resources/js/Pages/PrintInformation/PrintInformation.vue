<script setup>
import InsideLayout from "../../Layouts/InsideLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import { ref ,computed } from "vue";
import Vue3EasyDataTable from "vue3-easy-data-table";
import "vue3-easy-data-table/dist/style.css";
import TabView from "primevue/tabview";
import TabPanel from "primevue/tabpanel";
import Dropdown from 'primevue/dropdown';
import "primeicons/primeicons.css";
import Calendar from "primevue/calendar";
import { format } from "date-fns";

//Get Data From Controller:
const props = defineProps({
  group: {
    type: Array,
    required: true,
  },
  listseance: {
    type: Array,
    required: true,
  },
  note: {
    type: Array,
    required: true,
  },
});

const selectedGroup = ref();
const selectedDate = ref();
const headers = [
    { text: "Commencer Séance", value: "startSeance", sortable: true },
    { text: "Fin Séance", value: "finSeance", sortable: true, },
    { text: "Type Séance", value: "type", sortable: true, },
    { text: "Local", value: "local_id", sortable: true, },
    { text: "operations", value: "operations" },
];
const getCurrentDate = () => {
  const today = new Date();
  return today.toISOString().split("T")[0];
};
const absenceForm = useForm({
  date:'', 
  //selectedDate:'',
});
//const 
const data =props.group;
const getListMonthAbsenceByGroupId = () => {
   if (selectedDate.value) {
      const formattedDate = format(selectedDate.value, "yyyy-MM-dd");
    console.log("Formatted date:", formattedDate);
    console.log("Selected group ID:", selectedGroup.value.id);

       try {
    axios
      .get("/absencesByMonth/" + selectedGroup.value.id+ "/" + formattedDate, {
        responseType: "blob",
      })
      .then((response) => {
        const blob = new Blob([response.data], { type: "application/pdf" });
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.style.display = "none";
        link.href = url;
        link.setAttribute("download", "list_absence_mois.pdf"); // Set the filename
        document.body.appendChild(link);
        link.click();
      })
      .catch((error) => {
        console.error("Error: ", error);
      });
  } catch (error) {
    console.error("Error generating PDF:", error);
  }
   } else {
    console.error("Selected date is invalid");
  }
 
};

const getListNoteAbsence = () => {
   // console.log("Selected group ID:", selectedGroup.value.id);

       try {
    axios
      .get("/noteByGroupYear/" + selectedGroup.value.id , {
        responseType: "blob",
      })
      .then((response) => {
        const blob = new Blob([response.data], { type: "application/pdf" });
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.style.display = "none";
        link.href = url;
        link.setAttribute("download", "list_notes.pdf"); // Set the filename
        document.body.appendChild(link);
        link.click();
      })
      .catch((error) => {
        console.error("Error: ", error);
      });
  } catch (error) {
    console.error("Error generating PDF:", error);
  }
 
};

const isButtonDisabled = computed(() => {
  return !selectedGroup.value || !selectedDate.value;
});
</script>

<template>
  <Head :title="$t('asidebar.levels')" />
  <InsideLayout>
    <div class="cards-container">
      <div class="card">
        <h5 class="mb-6"> FICHE DE PRESENCE</h5>
        <div class="ml-5">
           <label for="month" class="mt-2 text-gray-500">Group*</label>
          <Dropdown
            v-model="selectedGroup"
            filter
            :options="props.group"
            optionLabel="group"
            placeholder="Sélectionnez un groupe"
            class="w-full h-14 mt-4 rounded-xl border border-gray-200 mb-4 overflow-hidden"
          />
            
              <div class="">
  <label for="month" class="mt-2 text-gray-500">Mois*</label>
  <Calendar v-model="selectedDate" view="month" dateFormat="dd/mm/yy"   class="w-full h-14 mt-4 border" placeholder="Select month" />
</div>
        </div>
        
     <div class="button-container">
  <button 
  
    @click="getListMonthAbsenceByGroupId()" 
    :disabled="isButtonDisabled" 
    class="new-button" 
    raised 
  > Print PDF </button>
</div>
      </div>

     
      
      <div class="card"><h5 class="mb-6"> LISTE DES NOTES</h5>
        <div class="ml-5">
           <label for="month" class="mt-2 text-gray-500">Group*</label>
          <Dropdown
            v-model="selectedGroup"
            filter :options="props.group" optionLabel="group"
            placeholder="Sélectionnez un groupe"
            class="w-full h-14 mt-4 rounded-xl border border-gray-200 mb-4 overflow-hidden"
          />
            
        </div>
        
     <div class="button-container">
  <button 
  
    @click="getListNoteAbsence()" 
    class="new-button" 
    raised 
  > Print PDF </button>
</div>
      </div>

    </div>
  </InsideLayout>
</template>

<style scoped>
.cards-container {
  display: flex;
  gap: 16px; 
  padding: 20px;
}

.card {
  flex: 1; /* Ensures cards are of equal width */
  background-color: white;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  padding: 30px; /* Adjust padding as needed */
  border-radius: 8px; /* Optional: adds rounded corners */
}
.centered-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center; 
}

.group-button {
  width: 150px; /* Increase width to allow fewer buttons per line */
  height: 60px;
  background-color: rgb(191, 182, 182);
  border: none;
  margin: 10px; /* Increase margin for spacing between buttons */
  border-radius: 5px;
  cursor: pointer;
}

.group-button:hover {
  background-color: rgb(169, 169, 169); 
}

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
.button-container {
  display: flex;
  justify-content: center; /* Centers the button horizontally */
  align-items: center; /* Centers the button vertically if parent has height */
  height: 10vh; /* Adjust as needed */
}

.new-button {
  background-color: #ef9d05   !important; /* Button color */
  border-radius: 12px; /* Border radius */
  padding: 10px 20px; /* Padding for better appearance */
  font-size: 16px; /* Font size */
  cursor: pointer; /* Pointer cursor on hover */
  transition: background-color 0.3s ease; /* Smooth transition */
}

.new-button:hover {
  background-color: #f6c069 !important; /* Darker shade on hover */
}

</style>