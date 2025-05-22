<script setup>
import { Button, Tag, RadioButton, useToast } from 'primevue';
import {IconAlertSquareRounded, IconFileCheck, IconPhotoPlus} from "@tabler/icons-vue";
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { trans } from 'laravel-vue-i18n';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    document_type: 'utility_bill',
    residency_proof: null,
});

const toast = useToast();

// Front Identity
const residencyFile = ref(null);
const isDraggingResidency = ref(false);

const dragOverResidency = () => {
    isDraggingResidency.value = true;
};

const dragLeaveResidency = () => {
    isDraggingResidency.value = false;
};

const handleDropResidency = (event) => {
    isDraggingResidency.value = false;
    form.errors.residency_proof = null;
    const droppedFiles = event.dataTransfer.files;
    if (droppedFiles.length > 0) {
        validateFile(droppedFiles[0]);
    }
};

const handleResidencyFileSelect = (event) => {
    form.errors.residency_proof = null;
    const selectedFile = event.target.files[0];
    validateFile(selectedFile);
};

const validateFile = (fileInput) => {
    if (fileInput.type.startsWith("application/pdf")) {
        residencyFile.value = fileInput;
        form.residency_proof = residencyFile.value;
    } else {
        toast.add({
            severity: 'error',
            summary: trans('public.error'),
            detail: trans('public.toast_file_type_error'),
            life: 5000,
        });
    }
};

const emit = defineEmits(['update:residency_status']);

const submitForm = () => {
    form.post(route('profile.uploadResidencyProof'), {
        onSuccess: () => {
            residencyFile.value = null;
            emit('update:residency_status', {
                residency_status: 'pending'
            });
        },
        preserveScroll: true,
        preserveState: true,
    })
}
</script>

<template>
     <div class="flex flex-col gap-5 w-full">
         <div class="flex flex-col gap-1 items-start self-stretch">
             <InputLabel
                 for="proof_type"
                 :value="$t('public.choose_document_type')"
             />
             <div class="flex items-center gap-5">
                 <div class="flex items-center gap-2 text-sm">
                     <RadioButton
                         v-model="form.document_type"
                         inputId="utility_bill"
                         name="utility_bill"
                         value="utility_bill"
                     />
                     <label for="utility_bill">{{ $t('public.utility_bill') }}</label>
                 </div>

                 <div class="flex items-center gap-2 text-sm">
                     <RadioButton
                         v-model="form.document_type"
                         inputId="bank_statement"
                         name="bank_statement"
                         value="bank_statement"
                     />
                     <label for="bank_statement">{{ $t('public.bank_statement') }}</label>
                 </div>

                 <div class="flex items-center gap-2 text-sm">
                     <RadioButton
                         v-model="form.document_type"
                         inputId="residency_certificate"
                         name="residency_certificate"
                         value="residency_certificate"
                     />
                     <label for="residency_certificate">{{ $t('public.residency_certificate') }}</label>
                 </div>
             </div>
         </div>

         <div class="flex flex-col gap-1 items-start self-stretch">
            <InputLabel for="residency_proof">{{ $t(`public.${form.document_type}` )}}</InputLabel>
             <div
                 :class="[
                    'flex flex-col gap-3 items-center self-stretch px-5 py-8 rounded-md border-2 border-dashed transition-colors duration-150',
                    {
                        'border-blue-500 dark:text-blue-400 bg-blue-200/50 dark:bg-blue-800/10': isDraggingResidency,
                        'bg-surface-50 dark:bg-surface-950 border-surface-300 dark:border-surface-600': !isDraggingResidency,
                    }
                ]"
                @dragover.prevent="dragOverResidency"
                @dragleave.prevent="dragLeaveResidency"
                @drop.prevent="handleDropResidency"
             >
                 <div
                     v-if="form.errors.residency_proof"
                     class="rounded-lg w-12 h-12 shrink-0 grow-0 border border-red-300 dark:border-red-600 flex items-center justify-center text-red-500 dark:text-red-400"
                 >
                     <IconAlertSquareRounded size="28" stroke-width="1.5" />
                 </div>
                 <div
                     v-else-if="residencyFile"
                     class="rounded-lg w-12 h-12 shrink-0 grow-0 border border-green-300 dark:border-green-600 flex items-center justify-center text-green-500 dark:text-green-400"
                 >
                     <IconFileCheck size="28" stroke-width="1.5" />
                 </div>
                 <div
                     v-else
                     :class="[
                        'rounded-lg w-12 h-12 shrink-0 grow-0 border border-surface-300 dark:border-surface-600 flex items-center justify-center',
                        {
                            'text-blue-500 dark:text-blue-400': isDraggingResidency,
                            'text-surface-600 dark:text-surface-400': !isDraggingResidency,
                        }
                    ]"
                 >
                     <IconPhotoPlus size="28" stroke-width="1.5" />
                 </div>
                 <div
                     v-if="residencyFile"
                     class="flex flex-col items-center justify-center self-stretch"
                 >
                     <span class="text-xs text-surface-600 dark:text-surface-400">{{ residencyFile.name }}</span>
                     <label
                         for="fileInputResidency"
                         class="text-xs text-blue-500 cursor-pointer underline select-none hover:text-blue-600"
                     >
                         {{ $t('public.replace_file') }}
                     </label>
                 </div>
                 <div v-else class="flex flex-col items-center text-surface-500 dark:text-surface-400 text-xs text-center">
                     {{ $t('public.drag_and_drop') }} <label for="fileInputResidency" class="text-blue-500 cursor-pointer underline select-none hover:text-blue-600">{{ $t('public.choose_file') }}</label>
                 </div>
                 <input type="file" id="fileInputResidency" class="hidden" @change="handleResidencyFileSelect" accept="application/pdf" />
                 <InputError :message="form.errors.residency_proof" class="text-center" />
                 <div class="flex items-center gap-2 self-stretch justify-center w-full">
                     <Tag severity="secondary">
                         <span class="dark:text-surface-500">PDF</span>
                     </Tag>
                 </div>
             </div>
             <div class="text-xs text-right w-full text-surface-500 dark:text-surface-400">
                 {{ $t('public.max_size') }}: 8MB
             </div>
         </div>

         <div class="flex items-center justify-end">
             <Button
                 size="small"
                 class="w-auto px-8"
                 @click="submitForm"
                 :label="$t('public.save')"
                 :disabled="form.processing"
             />
         </div>
    </div>
</template>
