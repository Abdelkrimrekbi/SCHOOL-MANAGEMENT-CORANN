<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

import SectionBorder from "@/Components/SectionBorder.vue";

import UpdatePasswordForm from "@/Pages/Profile/Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "@/Pages/Profile/Partials/UpdateProfileInformationForm.vue";
import InsideLayout from "@/Layouts/InsideLayout.vue";
</script>

<template>
  <InsideLayout>
    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div v-if="$page.props.jetstream.canUpdateProfileInformation">
          <UpdateProfileInformationForm :user="$page.props.auth.user" />

          <SectionBorder />
        </div>

        <div v-if="$page.props.jetstream.canUpdatePassword">
          <UpdatePasswordForm class="mt-10 sm:mt-0" />

          <SectionBorder />
        </div>
      </div>
    </div>
    <!-- <div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="flex">
            <div class="w-1/2 pr-4">
                <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                    <div>
                        <UpdateProfileInformationForm :user="$page.props.auth.user" />
                        <SectionBorder />
                    </div>
                </div>
            </div>
            <div class="w-1/2 pl-4">
                <div v-if="$page.props.jetstream.canUpdatePassword">
                    <div>
                        <UpdatePasswordForm class="mt-10 sm:mt-0" />
                        <SectionBorder />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
  </InsideLayout>
</template>
