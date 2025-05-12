<script setup>
import {Button, Card, Tag, Message, Image} from "primevue";
import InputLabel from "@/Components/InputLabel.vue";
import {IconAlertSquareRoundedFilled, IconEdit} from "@tabler/icons-vue";
import UploadKyc from "./UploadKyc.vue";

const props = defineProps({
    user: Object,
})
console.log(props.user)
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
</script>

<template>
    <Card class="w-full">
        <template #title>
            <!-- <div class="flex gap-5 items-center justify-between">
                {{ $t('public.kyc_verification') }}
                <Button
                    type="button"
                    icon="IconEdit"
                    severity="secondary"
                    rounded
                    text
                >
                    <template #icon>
                        <IconEdit size="16" stroke-width="1.5" />
                    </template>
                </Button>
            </div> -->
            <div class="flex flex-col">
                <div class="flex items-center justify-between">
                    <div class="flex gap-2 items-center">
                        <span>{{ $t('public.upload_identity_proof') }}</span>
                        <Tag
                            :value="$t(`public.${props.user.kyc_status}`)"
                            :severity="getSeverity(props.user.kyc_status)"
                        />
                    </div>

                      <Button
                        type="button"
                        icon="IconEdit"
                        severity="secondary"
                        rounded
                        text
                    >
                        <template #icon>
                            <IconEdit size="16" stroke-width="1.5" />
                        </template>
                    </Button>
                </div>
                <span class="text-sm text-surface-500">{{ $t('public.upload_identity_proof_caption') }}</span>
            </div>
        </template>

        <template #content>
            <div class="flex flex-col gap-5 items-center w-full">
                <div
                    v-if="props.user.kyc_status === 'rejected'"
                    class="text-sm w-full"
                >
                    <Message closable severity="error">{{ props.user.kyc_approval_description }}</Message>
                </div>

                <div class="flex flex-col gap-2 bg-surface-100 dark:bg-surface-800 p-5 w-full">
                    <span class="text-sm dark:text-white font-semibold">{{ $t('public.take_id_photo') }}</span>
                    <div v-for="(step, index) in 2" :key="index" class="text-sm text-surface-500 dark:text-surface-400">
                        {{ $t(`public.upload_kyc_info_${index+1}`) }}
                    </div>
                </div>

                <div
                    v-if="props.user.kyc_status !== 'verified'"
                    class="w-full"
                >
                    <UploadKyc />
                </div>

                <div v-else class="w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-5 w-full">
                        <div class="flex flex-col gap-1 items-start self-stretch">
                            <InputLabel for="front_identity">{{ $t('public.front_identity' )}}</InputLabel>
                            <div
                                class="flex flex-col gap-3 items-center self-stretch px-5 py-8 rounded-md border-2 border-dashed transition-colors duration-150 bg-surface-50 dark:bg-surface-950 border-surface-300 dark:border-surface-600"
                            >
                                <Image
                                    role="presentation"
                                    alt="front_identity_image"
                                    :src="front_identity_image"
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
                                <Image
                                    role="presentation"
                                    alt="back_identity_image"
                                    :src="back_identity_image"
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
</template>
