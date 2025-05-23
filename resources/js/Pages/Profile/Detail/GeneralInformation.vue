<script setup>
import {Card, Button, Dialog, InputText, DatePicker, Message} from "primevue";
import {IconEdit, IconAlertSquareRoundedFilled} from "@tabler/icons-vue";
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import SelectChipGroup from "@/Components/SelectChipGroup.vue";
import dayjs from "dayjs";

const props = defineProps({
    user: Object,
});

const visible = ref(false);

const openDialog = () => {
    visible.value = true;
};

const genders = [
    'male',
    'female',
]

const form = useForm({
    identity_number: props.user.identity_number,
    gender: props.user.gender,
    dob: props.user.dob,
});

const submitForm = () => {
    form.post(route('profile.updateGeneralInformation'), {
        onSuccess: () => {
            closeDialog();
        }
    })
}

const closeDialog = () => {
    visible.value = false;
}
</script>

<template>
    <Card class="w-full">
        <template #title>
            <div class="flex gap-5 items-center justify-between">
                {{ $t('public.general_information') }}
                <Button
                    type="button"
                    icon="IconEdit"
                    severity="secondary"
                    rounded
                    text
                    @click="openDialog"
                >
                    <template #icon>
                        <IconEdit size="16" stroke-width="1.5" />
                    </template>
                </Button>
            </div>
        </template>

        <template #content>
            <div class="flex flex-col gap-3 items-center">
                <div class="grid grid-cols-2 w-full">
                    <div class="text-surface-500">
                        {{ $t('public.ic_passport_no') }}
                    </div>
                    <div v-if="form.identity_number" class="text-sm dark:text-white">
                        {{ form.identity_number }}
                    </div>
                    <div v-else class="flex items-center text-red-500 gap-1 text-xs">
                        <IconAlertSquareRoundedFilled size="12" stroke-width="1.5" />
                        {{ $t('public.missing_field') }}
                    </div>
                </div>
                <div class="grid grid-cols-2 w-full">
                    <div class="text-surface-500">
                        {{ $t('public.gender') }}
                    </div>
                    <div v-if="form.gender">
                        {{ $t(`public.${form.gender}`) }}
                    </div>
                    <div v-else class="flex items-center text-red-500 gap-1 text-xs">
                        <IconAlertSquareRoundedFilled size="12" stroke-width="1.5" />
                        {{ $t('public.missing_field') }}
                    </div>
                </div>
                <div class="grid grid-cols-2 w-full">
                    <div class="text-surface-500">
                        {{ $t('public.date_of_birth') }}
                    </div>
                    <div v-if="form.dob">
                        {{ dayjs(form.dob).format('YYYY/MM/DD') }}
                    </div>
                    <div v-else class="flex items-center text-red-500 gap-1 text-xs">
                        <IconAlertSquareRoundedFilled size="12" stroke-width="1.5" />
                        {{ $t('public.missing_field') }}
                    </div>
                </div>
            </div>
        </template>
    </Card>

    <Dialog
        v-model:visible="visible"
        modal
        :header="$t('public.general_information')"
        class="dialog-xs md:dialog-sm"
    >
        <form class="flex flex-col gap-8 items-center self-stretch">
            <div class="flex flex-col gap-5 items-center self-stretch">
                <div class="flex flex-col gap-1 items-start self-stretch">
                    <InputLabel
                        for="identity_number"
                        :value="$t('public.ic_passport_no')"
                        :invalid="!!form.errors.identity_number"
                    />
                    <InputText
                        id="identity_number"
                        type="text"
                        class="block w-full"
                        v-model="form.identity_number"
                        :placeholder="$t('public.enter_ic_passport_no')"
                        :invalid="!!form.errors.identity_number"
                    />
                    <InputError :message="form.errors.identity_number" />
                    <Message size="small" severity="secondary" variant="simple">
                        <span class="text-xs font-normal">{{ $t('public.ic_passport_no_help_text') }}</span>
                    </Message>
                </div>
                <div class="flex flex-col gap-1 items-start self-stretch">
                    <InputLabel
                        for="gender"
                        :value="$t('public.gender')"
                        :invalid="!!form.errors.gender"
                    />
                    <SelectChipGroup
                        :items="genders"
                        v-model="form.gender"
                    />
                    <InputError :message="form.errors.gender" />
                </div>
                <div class="flex flex-col gap-1 items-start self-stretch">
                    <InputLabel
                        for="dob"
                        :value="$t('public.date_of_birth')"
                        :invalid="!!form.errors.dob"
                    />
                    <DatePicker
                        v-model="form.dob"
                        dateFormat="yy/mm/dd"
                        placeholder="YYYY/MM/DD"
                        class="w-full"
                    />
                    <InputError :message="form.errors.dob" />
                </div>
            </div>

            <div class="flex gap-3 justify-end self-stretch w-full">
                <Button
                    type="button"
                    :label="$t('public.cancel')"
                    severity="secondary"
                    @click="closeDialog"
                    class="w-full md:w-fit"
                />
                <Button
                    type="submit"
                    :label="$t('public.save')"
                    :disabled="form.processing"
                    class="w-full md:w-fit"
                    @click.prevent="submitForm"
                />
            </div>
        </form>
    </Dialog>
</template>
