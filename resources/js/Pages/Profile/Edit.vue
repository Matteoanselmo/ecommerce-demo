<script setup>
import { Head } from '@inertiajs/vue3';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />
    <v-container>
        <div class="text-h2">
            Profilo
        </div>
        <v-row>
            <v-col cols="12">
                <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                            class="max-w-xl"
                        />
            </v-col>
            <v-col cols="12">
                <UpdatePasswordForm class="max-w-xl" />
            </v-col>
            <v-col cols="12">
                <DeleteUserForm class="max-w-xl" />
            </v-col>
        </v-row>
    </v-container>


            <!-- <v-container class="py-12">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
                <v-container class="max-w-7xl mx-auto space-y-6">
                    <v-card class="p-4">
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                            class="max-w-xl"
                        />
                    </v-card>

                    <v-card class="p-4">
                        <UpdatePasswordForm class="max-w-xl" />
                    </v-card>

                    <v-card class="p-4">
                        <DeleteUserForm class="max-w-xl" />
                    </v-card>
                </v-container>
            </v-container> -->

</template>
