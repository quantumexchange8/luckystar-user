<script setup>
import {Button, Card, Tag, Message, Image, ProgressSpinner} from "primevue";
import InputLabel from "@/Components/InputLabel.vue";
import {IconEdit, IconFileCheck} from "@tabler/icons-vue";
import UploadKyc from "./UploadKyc.vue";
import UploadResidency from "./UploadResidency.vue";
import {ref, watchEffect} from "vue";
import {usePage} from "@inertiajs/vue3";

const props = defineProps({
    user: Object,
});

const user_identity_status = ref(props.user.identity_status);
const user_residency_status = ref(props.user.residency_status);
const user_proof_type = ref(props.user.proof_type);

const getSeverity = (status) => {
    switch (status) {
        case 'verified':
            return 'success';

        case 'unverified':
            return 'danger';

        case 'rejected':
            return 'danger';

        case 'pending':
            return 'warn';
    }
};

const identityFiles = ref([]);
const loadingIdentityFiles = ref(false);

const getKycFiles = async () => {
    loadingIdentityFiles.value = true;
    try {
        const response = await axios.get(route('profile.getKycFiles'));
        identityFiles.value = response.data;
    } catch (error) {
        console.error('Error fetching leverages:', error);
    } finally {
        loadingIdentityFiles.value = false;
    }
}

const updateIdentityStatus = (data) => {
    user_identity_status.value = data.identity_status;
    user_proof_type.value = data.proof_type;
    getKycFiles();
}

const updateResidencyStatus = (data) => {
    user_residency_status.value = data.residency_status;
    getKycFiles();
}
</script>

<template>
    <Card class="w-full">
        <template #title>
            <div class="flex flex-col">
                <div class="flex items-center justify-between">
                    <div class="flex gap-2 items-center">
                        <span>{{ $t('public.upload_identity_proof') }}</span>
                        <Tag
                            :value="$t(`public.${user_identity_status}`)"
                            :severity="getSeverity(user_identity_status)"
                        />
                    </div>
                </div>
            </div>
        </template>

        <template #content>
            <div class="flex flex-col gap-5 items-center w-full pt-2">
                <div
                    v-if="user_identity_status === 'rejected'"
                    class="text-sm w-full"
                >
                    <Message closable severity="error">{{ user.kyc_approval_description }}</Message>
                </div>

                <div
                    v-if="['rejected', 'unverified'].includes(user_identity_status)"
                    class="w-full"
                >
                    <UploadKyc
                        @update:identity_status="updateIdentityStatus"
                    />
                </div>

                <div v-else class="w-full">
                    <div
                        v-if="user_proof_type === 'passport'"
                        class="flex flex-col gap-1 items-start self-stretch"
                    >
                        <InputLabel for="passport">{{ $t('public.passport' )}}</InputLabel>
                        <div
                            class="flex flex-col gap-3 items-center self-stretch px-5 py-8 rounded-md border-2 border-dashed transition-colors duration-150 bg-surface-50 dark:bg-surface-950 border-surface-300 dark:border-surface-600"
                        >
                            <div v-if="loadingIdentityFiles" class="w-full flex items-center justify-center h-40">
                                <ProgressSpinner />
                            </div>
                            <Image
                                v-else
                                role="presentation"
                                alt="passport_identity_image"
                                :src="user.passport_identity || identityFiles['passport_identity']"
                                preview
                                imageClass="w-full object-contain h-40"
                            />
                        </div>
                    </div>
                    <div
                        v-else
                        class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-5 w-full"
                    >
                        <div class="flex flex-col gap-1 items-start self-stretch">
                            <InputLabel for="front_identity">{{ $t('public.front_identity' )}}</InputLabel>
                            <div
                                class="flex flex-col gap-3 items-center self-stretch px-5 py-8 rounded-md border-2 border-dashed transition-colors duration-150 bg-surface-50 dark:bg-surface-950 border-surface-300 dark:border-surface-600"
                            >
                                <div v-if="loadingIdentityFiles" class="w-full flex items-center justify-center h-40">
                                    <ProgressSpinner />
                                </div>
                                <Image
                                    v-else
                                    role="presentation"
                                    alt="front_identity_image"
                                    :src="user.front_identity || identityFiles['front_identity']"
                                    preview
                                    imageClass="w-full object-contain h-40"
                                />
                            </div>
                        </div>
                        <div class="flex flex-col gap-1 items-start self-stretch">
                            <InputLabel for="front_identity">{{ $t('public.back_identity' )}}</InputLabel>
                            <div
                                class="flex flex-col gap-3 items-center self-stretch px-5 py-8 rounded-md border-2 border-dashed transition-colors duration-150 bg-surface-50 dark:bg-surface-950 border-surface-300 dark:border-surface-600"
                            >
                                <div v-if="loadingIdentityFiles" class="w-full flex items-center justify-center h-40">
                                    <ProgressSpinner />
                                </div>
                                <Image
                                    v-else
                                    role="presentation"
                                    alt="back_identity_image"
                                    :src="user.back_identity || identityFiles['back_identity']"
                                    preview
                                    imageClass="w-full object-contain h-40"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </Card>

    <!-- Proof of residency -->
    <Card class="w-full">
        <template #title>
            <div class="flex flex-col">
                <div class="flex items-center justify-between">
                    <div class="flex gap-2 items-center">
                        <span>{{ $t('public.upload_residency_proof') }}</span>
                        <Tag
                            :value="$t(`public.${user_residency_status}`)"
                            :severity="getSeverity(user_residency_status)"
                        />
                    </div>
                </div>
            </div>
        </template>

        <template #content>
            <div class="flex flex-col gap-5 items-center w-full">
                <div
                    v-if="user_residency_status === 'rejected'"
                    class="text-sm w-full"
                >
                    <Message closable severity="error">{{ props.user.kyc_approval_description }}</Message>
                </div>

                <div
                    v-if="['rejected', 'unverified'].includes(user_residency_status)"
                    class="w-full"
                >
                    <UploadResidency
                        @update:residency_status="updateResidencyStatus"
                    />
                </div>

                <div v-else class="w-full">
                    <div class="flex flex-col gap-1 items-start self-stretch">
                        <InputLabel for="residency_proof">{{ $t('public.residency_proof')}}</InputLabel>
                        <div
                            class="flex flex-col justify-center gap-3 items-center self-stretch px-5 py-8 rounded-md border-2 border-dashed transition-colors duration-150 bg-surface-50 dark:bg-surface-950 border-surface-300 dark:border-surface-600"
                        >
                            <div
                                class="rounded-lg w-12 h-12 shrink-0 grow-0 border border-green-300 dark:border-green-600 flex items-center justify-center text-green-500 dark:text-green-400"
                            >
                                <IconFileCheck size="28" stroke-width="1.5" />
                            </div>
                            <div
                                class="flex flex-col items-center justify-center self-stretch"
                            >
                                {{ $t('public.toast_upload_residency_success') }}
                            </div>
                            <div class="w-full flex items-center justify-center">
                                <Button
                                    severity="secondary"
                                    as="a"
                                    :label="$t('public.view_file')"
                                    :href="user.residency_proof || identityFiles['residency_proof']"
                                    target="_blank"
                                    rel="noopener"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </Card>
</template>
