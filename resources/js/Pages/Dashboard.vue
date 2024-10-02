<script setup>
import InsideLayout from "@/Layouts/InsideLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted, computed } from "vue";
import { useI18n } from "vue-i18n";
import useAuthStore from "@/store/authStore.js";
//import VueApexCharts from 'vue-apexcharts';
const authStore = useAuthStore();

const props = defineProps({
  teachersCount: {
    type: Number,
  },
  studentsCount: {
    type: Number,
  },
  parentCount: {
    type: Number,
  },
  pyments: {
    type: Array,
  },
  paymentsByMonth: {
    type: Number,
  },
  ByMonthExpense: {
    type: Number,
  },
  totalIncomeAndPaymentAmount: {
    type: Number,
  },
  expenses: {
    type: Number,
  },
});

const series = ref([
  { name: " Revenu", data: props.pyments?.map((e) => e.total) },
]);

const colors = ["#6f32be"];

const chartOptions = ref({
  chart: {
    height: 400,
    type: "bar",
    events: {
      click: function (chart, w, e) {},
    },
    title: {
      text: "Your Chart Title",
      align: "center",
      style: {
        fontSize: "16px",
        color: "#333",
      },
    },
  },
  title: {
    text: "Revenu par mois",
    align: "center",
    style: {
      fontSize: "16px",
      color: "#000304",
    },
  },
  dataLabels: {
    enabled: false,
  },
  legend: {
    show: false,
  },
  xaxis: {
    categories: props.pyments?.map((e) => e.month),
    labels: {
      style: {
        colors: colors,
        fontSize: "12px",
      },
    },
  },
  plotOptions: {
    bar: {
      columnWidth: "40px",
      //distributed: true,
      dataLabels: {
        position: "top",
      },
    },
  },
});

onMounted(() => {
  // console.log(props.pyments);
});

// chart circle
const customChartData = ref([
  (
    (props.ByMonthExpense / (props.paymentsByMonth + props.ByMonthINcome)) *
    100
  ).toFixed(2),
]);
// console.log("value :", props.ByMonthExpense / props.paymentsByMonth);

const chartOptions3 = {
  chart: {
    height: 0,
    type: "radialBar",
    title: "Your Chart Title",
  },
  plotOptions: {
    radialBar: {
      hollow: {
        size: "60%",
      },
    },
  },
  labels: ["Dépenses"],
};

const customChartData2 = ref([40]);

const chartOptions4 = {
  chart: {
    height: 0,
    type: "radialBar",
  },
  plotOptions: {
    radialBar: {
      hollow: {
        size: "60%",
      },
      colors: ["#FF0000"], // Set your desired color (red in this case)
    },
  },
  labels: [""],
};
</script>

<template>
  <Head :title="$t('title.dashboard')" />

  <InsideLayout>
    <div class="grid md:grid-cols-2 xl:grid-cols-4 m-4">
      <div class="flex items-center p-1 rounded-lg">
        <div class="bg-white rounded-xl m-4 p-4 grid grid-cols-3">
          <div>
            <svg
              fill="#6f32be"
              width="54px"
              height="54px"
              viewBox="0 0 256 256"
              id="Flat"
              xmlns="http://www.w3.org/2000/svg"
            >
              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
              <g
                id="SVGRepo_tracerCarrier"
                stroke-linecap="round"
                stroke-linejoin="round"
              ></g>
              <g id="SVGRepo_iconCarrier">
                <path
                  d="M226.52979,56.41016l-96-32a8.00672,8.00672,0,0,0-5.05958,0L29.6239,56.35889l-.00976.00341-.14393.04786c-.02819.00927-.053.02465-.08105.03442a7.91407,7.91407,0,0,0-1.01074.42871c-.03748.019-.07642.03516-.11353.05469a7.97333,7.97333,0,0,0-.93139.58325c-.06543.04688-.129.09522-.19288.144a8.08459,8.08459,0,0,0-.81872.71119c-.0238.02416-.04443.05053-.06787.0747a8.0222,8.0222,0,0,0-.661.783c-.04163.05567-.08472.10986-.12476.16675a8.00177,8.00177,0,0,0-.56714.92993c-.02588.04981-.04809.10083-.073.15112a7.97024,7.97024,0,0,0-.40515.97608c-.01062.03149-.0238.06128-.03405.093a7.95058,7.95058,0,0,0-.26282,1.08544c-.01331.07666-.02405.15308-.035.23A8.02888,8.02888,0,0,0,24,64v80a8,8,0,0,0,16,0V75.09985L73.58521,86.29492a63.9717,63.9717,0,0,0,20.42944,87.89746,95.88087,95.88087,0,0,0-46.48389,37.4375,7.9997,7.9997,0,1,0,13.40235,8.73828,80.023,80.023,0,0,1,134.1333,0,7.99969,7.99969,0,1,0,13.40234-8.73828,95.87941,95.87941,0,0,0-46.4834-37.43725,63.972,63.972,0,0,0,20.42944-87.89771l44.115-14.70508a8.0005,8.0005,0,0,0,0-15.17968ZM128,168A47.99154,47.99154,0,0,1,89.34875,91.54932l36.12146,12.04052a8.00672,8.00672,0,0,0,5.05958,0l36.12146-12.04052A47.99154,47.99154,0,0,1,128,168Z"
                ></path>
              </g>
            </svg>
          </div>

          <div class="col-span-2">
            <p class="text-xl font-bold text-[#6f32be]">
              {{ $t("title.students") }}
            </p>
            <p class="text-xl font-bold text-gray-600">{{ studentsCount }}</p>
          </div>
        </div>
      </div>

      <div class="flex items-center p-1 rounded-lg">
        <div class="bg-white rounded-xl m-4 p-4 grid grid-cols-3">
          <div>
            <svg
              width="54px"
              height="54px"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
              <g
                id="SVGRepo_tracerCarrier"
                stroke-linecap="round"
                stroke-linejoin="round"
              ></g>
              <g id="SVGRepo_iconCarrier">
                <path
                  d="M16.8311 15.6402C17.5011 15.2002 18.3811 15.6802 18.3811 16.4802V17.7702C18.3811 19.0402 17.3911 20.4002 16.2011 20.8002L13.0111 21.8602C12.4511 22.0502 11.5411 22.0502 10.9911 21.8602L7.80109 20.8002C6.60109 20.4002 5.62109 19.0402 5.62109 17.7702V16.4702C5.62109 15.6802 6.50109 15.2002 7.16109 15.6302L9.22109 16.9702C10.0111 17.5002 11.0111 17.7602 12.0111 17.7602C13.0111 17.7602 14.0111 17.5002 14.8011 16.9702L16.8311 15.6402Z"
                  fill="#6f32be"
                ></path>
                <path
                  d="M19.9795 6.45859L13.9895 2.52859C12.9095 1.81859 11.1295 1.81859 10.0495 2.52859L4.02953 6.45859C2.09953 7.70859 2.09953 10.5386 4.02953 11.7986L5.62953 12.8386L10.0495 15.7186C11.1295 16.4286 12.9095 16.4286 13.9895 15.7186L18.3795 12.8386L19.7495 11.9386V14.9986C19.7495 15.4086 20.0895 15.7486 20.4995 15.7486C20.9095 15.7486 21.2495 15.4086 21.2495 14.9986V10.0786C21.6495 8.78859 21.2395 7.28859 19.9795 6.45859Z"
                  fill="#6f32be"
                ></path>
              </g>
            </svg>
          </div>
          <div class="col-span-2">
            <p class="text-xl font-bold text-[#6f32be]">
              {{ $t("title.teachers") }}
            </p>
            <p class="text-xl font-bold text-gray-600">{{ teachersCount }}</p>
          </div>
        </div>
      </div>

      <div class="flex items-center p-1 rounded-lg">
        <div class="bg-white rounded-xl m-4 p-4 grid grid-cols-3">
          <div>
            <svg
              fill="#6A11C9"
              height="60px"
              width="60px"
              version="1.1"
              id="Capa_1"
              xmlns="http://www.w3.org/2000/svg"
              xmlns:xlink="http://www.w3.org/1999/xlink"
              viewBox="-14.71 -14.71 519.71 519.71"
              xml:space="preserve"
              stroke="#6c05da"
            >
              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
              <g
                id="SVGRepo_tracerCarrier"
                stroke-linecap="round"
                stroke-linejoin="round"
              ></g>
              <g id="SVGRepo_iconCarrier">
                <g>
                  <path
                    d="M197.994,40.144l68.2-39.4c3.6-2.1,8,0.5,8,4.6v21.6h42.2c46.9,0,85.1,38.2,85.1,85.1h-35.6c0-27.3-22.2-49.5-49.5-49.5 h-42.2v21.6c0,4.1-4.4,6.7-8,4.6l-68.2-39.4C194.494,47.344,194.494,42.144,197.994,40.144z M291.794,440.944l-68.2-39.4 c-3.6-2.1-8,0.5-8,4.6v21.6h-42.2c-27.3,0-49.5-22.2-49.5-49.5h-35.6c0,46.9,38.2,85.1,85.1,85.1h42.2v21.6c0,4.1,4.4,6.7,8,4.6 l68.2-39.4C295.294,448.144,295.294,442.944,291.794,440.944z M346.194,224.244c-10.6,0-19.3,8.6-19.3,19.3 c0,10.6,8.6,19.3,19.3,19.3c10.6,0,19.3-8.6,19.3-19.3C365.494,232.844,356.794,224.244,346.194,224.244z M143.994,224.244 c-10.6,0-19.3,8.6-19.3,19.3c0,10.6,8.6,19.3,19.3,19.3c10.6,0,19.3-8.6,19.3-19.3 C163.294,232.844,154.594,224.244,143.994,224.244z M428.194,161.944v163.2c0,17.5-14.2,31.7-31.7,31.7h-302.7 c-17.5,0-31.7-14.2-31.7-31.7v-163.2c0-17.5,14.2-31.7,31.7-31.7h302.7C413.894,130.144,428.194,144.444,428.194,161.944z M401.094,196.244c-2.5,0.6-5.2,1-7.9,1c-17.7,0-32.1-14.4-32.1-32.1c0-2.7,0.3-5.3,1-7.8h-235.8c0.4,1.9,0.6,3.9,0.6,5.9 c0,17.7-14.4,32.1-32.1,32.1c-1.9,0-3.8-0.2-5.6-0.5v97.5c1.8-0.3,3.7-0.5,5.6-0.5c17.7,0,32.1,14.4,32.1,32.1c0,2-0.2,4-0.6,5.9 h234.9c-0.2-1.3-0.3-2.6-0.3-4c0-17.7,14.4-32.1,32.1-32.1c2.7,0,5.4,0.4,7.9,1v-98.5H401.094z M314.594,243.544 c0,38.4-31.1,69.5-69.5,69.5s-69.5-31.1-69.5-69.5s31.1-69.5,69.5-69.5C283.494,173.944,314.594,205.144,314.594,243.544z M238.594,225.444c0-2.5,0.6-4.5,1.8-6.1s3.1-2.4,5.7-2.4c2.5,0,4.6,1,6.1,2.9c1.2,1.6,1.9,3.7,2.2,6.6c0.1,1.3,1.3,2.2,2.6,2.2 l13-0.2c1.4,0,2.6-1.2,2.5-2.6c-0.3-5.7-2.2-10.5-5.5-14.3c-3.4-4-8-6.6-13.7-7.7v-9.8c0-1.2-1-2.2-2.2-2.2h-7.9 c-1.2,0-2.2,1-2.2,2.2v9.5c-6,0.9-10.9,3.1-14.7,6.7c-4.1,4-6.2,9-6.2,15.1c0,6.8,1.9,11.9,5.8,15.4c3.9,3.5,10.1,6.9,18.7,10.4 c3.5,1.5,6,3.1,7.3,4.6c1.4,1.6,2.1,3.8,2.1,6.7c0,2.5-0.7,4.5-2,6s-3.3,2.3-5.9,2.3c-3.1,0-5.6-1-7.6-3c-1.6-1.6-2.6-4.1-2.8-7.4 c-0.1-1.3-1.3-2.3-2.6-2.2l-13,0.2c-1.4,0-2.6,1.2-2.5,2.6c0.3,6.9,2.6,12.2,6.7,16c4.5,4.1,10.1,6.5,16.7,7.3v8.9 c0,1.2,1,2.2,2.2,2.2h7.9c1.2,0,2.2-1,2.2-2.2v-9.2c5.4-1,9.7-3.1,13.1-6.4c4-3.8,6-8.9,6-15.2c0-6.6-2-11.7-5.9-15.3 c-4-3.6-10.1-7.1-18.5-10.7c-3.6-1.6-6.1-3.2-7.4-4.7C239.194,229.944,238.594,227.944,238.594,225.444z"
                  ></path>
                </g>
              </g>
            </svg>
          </div>
          <div class="col-span-2">
            <p class="text-xl font-bold text-[#6f32be]">
              {{ $t("title.expenses") }}
            </p>
            <p class="text-xl font-bold text-gray-600 text-center">
              {{ ByMonthExpense }} DH
            </p>
          </div>
        </div>
      </div>

      <div class="flex items-center p-1 rounded-lg">
        <div class="bg-white rounded-xl m-4 p-4 grid grid-cols-3">
          <div>
            <svg
              width="54px"
              height="54px"
              viewBox="-0.5 0 25 25"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
              <g
                id="SVGRepo_tracerCarrier"
                stroke-linecap="round"
                stroke-linejoin="round"
              ></g>
              <g id="SVGRepo_iconCarrier">
                <path
                  d="M12.7003 17.1099V18.22C12.7003 18.308 12.6829 18.395 12.6492 18.4763C12.6156 18.5576 12.5662 18.6316 12.504 18.6938C12.4418 18.7561 12.3679 18.8052 12.2867 18.8389C12.2054 18.8725 12.1182 18.8899 12.0302 18.8899C11.9423 18.8899 11.8551 18.8725 11.7738 18.8389C11.6925 18.8052 11.6187 18.7561 11.5565 18.6938C11.4943 18.6316 11.4449 18.5576 11.4113 18.4763C11.3776 18.395 11.3602 18.308 11.3602 18.22V17.0801C10.9165 17.0072 10.4917 16.8468 10.1106 16.6082C9.72943 16.3695 9.39958 16.0573 9.14023 15.6899C9.04577 15.57 8.99311 15.4226 8.99023 15.27C8.99148 15.1842 9.00997 15.0995 9.04459 15.021C9.0792 14.9425 9.12927 14.8718 9.19177 14.813C9.25428 14.7542 9.32794 14.7087 9.40842 14.679C9.4889 14.6492 9.57455 14.6359 9.66025 14.6399C9.74504 14.6401 9.82883 14.6582 9.90631 14.6926C9.98379 14.7271 10.0532 14.7773 10.1102 14.8401C10.4326 15.2576 10.8657 15.5763 11.3602 15.76V13.21C10.0302 12.69 9.36023 11.9099 9.36023 10.8999C9.38027 10.3592 9.5928 9.84343 9.9595 9.44556C10.3262 9.04769 10.8229 8.79397 11.3602 8.72998V7.62988C11.3602 7.5419 11.3776 7.45482 11.4113 7.37354C11.4449 7.29225 11.4943 7.21847 11.5565 7.15625C11.6187 7.09403 11.6925 7.04466 11.7738 7.01099C11.8551 6.97732 11.9423 6.95996 12.0302 6.95996C12.1182 6.95996 12.2054 6.97732 12.2867 7.01099C12.3679 7.04466 12.4418 7.09403 12.504 7.15625C12.5662 7.21847 12.6156 7.29225 12.6492 7.37354C12.6829 7.45482 12.7003 7.5419 12.7003 7.62988V8.71997C13.0724 8.77828 13.4289 8.91103 13.7485 9.11035C14.0681 9.30967 14.3442 9.57137 14.5602 9.87988C14.6555 9.99235 14.7117 10.1329 14.7202 10.28C14.7229 10.3662 14.7084 10.4519 14.6776 10.5325C14.6467 10.613 14.6002 10.6867 14.5406 10.749C14.481 10.8114 14.4096 10.8613 14.3306 10.8958C14.2516 10.9303 14.1665 10.9487 14.0802 10.95C13.99 10.9475 13.9013 10.9257 13.8202 10.886C13.7391 10.8463 13.6675 10.7897 13.6102 10.72C13.3718 10.4221 13.0575 10.1942 12.7003 10.0601V12.3101L12.9503 12.4099C14.2203 12.9099 15.0103 13.63 15.0103 14.77C14.9954 15.3808 14.7481 15.9629 14.3189 16.3977C13.8897 16.8325 13.3108 17.0871 12.7003 17.1099ZM11.3602 11.73V10.0999C11.1988 10.1584 11.0599 10.2662 10.963 10.408C10.8662 10.5497 10.8162 10.7183 10.8203 10.8899C10.8173 11.0676 10.8669 11.2424 10.963 11.3918C11.0591 11.5413 11.1973 11.6589 11.3602 11.73ZM13.5502 14.8C13.5502 14.32 13.2203 14.03 12.7003 13.8V15.8C12.9387 15.7639 13.1561 15.6427 13.3123 15.459C13.4685 15.2752 13.553 15.0412 13.5502 14.8Z"
                  fill="#6f32be"
                ></path>
                <path
                  d="M18 3.96997H6C4.93913 3.96997 3.92172 4.39146 3.17157 5.1416C2.42142 5.89175 2 6.9091 2 7.96997V17.97C2 19.0308 2.42142 20.0482 3.17157 20.7983C3.92172 21.5485 4.93913 21.97 6 21.97H18C19.0609 21.97 20.0783 21.5485 20.8284 20.7983C21.5786 20.0482 22 19.0308 22 17.97V7.96997C22 6.9091 21.5786 5.89175 20.8284 5.1416C20.0783 4.39146 19.0609 3.96997 18 3.96997Z"
                  stroke="#6f32be"
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                ></path>
              </g>
            </svg>
          </div>
          <div class="col-span-2">
            <p class="text-xl font-bold text-[#6f32be]">
              {{ $t("title.earning") }}
            </p>
            <p class="text-xl font-bold text-gray-600">
              {{ totalIncomeAndPaymentAmount }} DH
            </p>
          </div>
        </div>
      </div>
    </div>

    <div></div>

    <!--**********************-->
    <div class="m-4 grid xl:grid-cols-3">
      <div
        class="bg-white rounded-xl m-2 p-4 justify-center items-center col-span-2"
      >
        <div>
          <div id="dashboard">
            <!-- Your other dashboard components go here -->
            <div id="chart-container">
              <apexchart
                :type="chartOptions.chart.type"
                :height="chartOptions.chart.height"
                :options="chartOptions"
                :series="series"
              ></apexchart>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl m-4 p-4">
        <div>
          <div class="text-center">
            <h2 style="font-size: 16px; margin-bottom: 20px">
              Le pourcentage des dépenses par rapport au revenu mensuel
            </h2>
            <apexchart
              type="radialBar"
              height="340"
              :options="chartOptions3"
              :series="customChartData"
            ></apexchart>
          </div>
          <div></div>

          <!-- <div>
            <apexchart
              type="radialBar"
              height="200"
              :options="chartOptions4"
              :series="customChartData2"
            ></apexchart>
          </div> -->
        </div>
      </div>
    </div>
  </InsideLayout>
</template>


