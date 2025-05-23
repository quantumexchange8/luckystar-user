<script setup>
import {Button, Dialog, Avatar, InputText, Select, Message} from "primevue";
import {IconEdit} from "@tabler/icons-vue";
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import {generalFormat} from "@/Composables/format.js";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import {useLangObserver} from "@/Composables/localeObserver.js";

const props = defineProps({
    user: Object
});

const visible = ref(false);
const {formatNameLabel} = generalFormat();
const {locale} = useLangObserver();

const openDialog = () => {
    visible.value = true;
};

const form = useForm({
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    email: props.user.email,
    username: props.user.username,
    dial_code: '',
    phone: props.user.phone,
    country_id: '',
    address: props.user.address,
    profile_action: '',
    profile_photo: null,
});

const selectedPhoneCode = ref();
const selectedCountry = ref();

// country
const countries = ref([]);
const loadingCountries = ref(false);
const getCountries = async () => {
    loadingCountries.value = true;
    try {
        const response = await axios.get('/get_countries');
        countries.value = response.data.countries;

        selectedCountry.value = countries.value.find(
            (country) => country.id === props.user.country_id
        ) || null;

        selectedPhoneCode.value = countries.value.find(
            (country) => country.phone_code === props.user.dial_code
        ) || null;
    } catch (error) {
        console.error('Error fetching selectedCountry:', error);
    } finally {
        loadingCountries.value = false;
    }
}

getCountries();

const removeProfilePhoto = () => {
    selectedProfilePhoto.value = null;
    form.profile_photo = null;
    form.profile_action = 'remove';
};

const selectedProfilePhoto = ref(props.user.profile_photo);
const handleUploadProfilePhoto = (event) => {
    const profilePhotoInput = event.target;
    const file = profilePhotoInput.files[0];

    if (file) {
        // Display the selected image
        const reader = new FileReader();
        reader.onload = () => {
            selectedProfilePhoto.value = reader.result;
        };
        reader.readAsDataURL(file);
        form.profile_photo = event.target.files[0];
    } else {
        selectedProfilePhoto.value = null;
    }
};

const submitForm = () => {
    form.country_id = selectedCountry.value?.id;
    form.dial_code = selectedPhoneCode.value.phone_code;
    form.post(route('profile.updateProfileInformation'), {
        onSuccess: () => {
            closeDialog();
            form.reset();
        }
    })
}

const closeDialog = () => {
    visible.value = false;
}
</script>

<template>
    <div class="absolute top-4 right-4">
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

    <Dialog
        v-model:visible="visible"
        modal
        :header="$t('public.profile_information')"
        class="dialog-xs md:dialog-md"
    >
        <form class="flex flex-col gap-8 items-center self-stretch">
            <div class="flex items-end gap-5 self-stretch">
                <Avatar
                    v-if="selectedProfilePhoto"
                    :image="selectedProfilePhoto"
                    class="w-40 h-40"
                />
                <Avatar
                    v-else
                    :label="formatNameLabel(user.full_name)"
                    class="w-40 h-40"
                    size="large"
                />
                <input
                    ref="profilePhotoInput"
                    id="profile_photo_input"
                    type="file"
                    class="hidden"
                    accept="image/*"
                    @change="handleUploadProfilePhoto"
                />
                <div class="flex flex-col md:flex-row justify-end items-end gap-5 self-stretch">
                    <Button
                        type="button"
                        severity="secondary"
                        outlined
                        :label="$t('public.change')"
                        @click.prevent="$refs.profilePhotoInput.click()"
                    />
                    <Button
                        type="button"
                        severity="danger"
                        outlined
                        :label="$t('public.remove')"
                        :disabled="!selectedProfilePhoto || form.processing"
                        @click.prevent="removeProfilePhoto"
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full">
                <div class="flex flex-col gap-1 items-start self-stretch">
                    <InputLabel
                        for="first_name"
                        :value="$t('public.first_name')"
                        :invalid="!!form.errors.first_name"
                    />

                    <InputText
                        id="first_name"
                        type="text"
                        class="block w-full"
                        v-model="form.first_name"
                        :placeholder="$t('public.enter_first_name')"
                        :invalid="!!form.errors.first_name"
                    />

                    <InputError :message="form.errors.first_name" />
                </div>

                <div class="flex flex-col gap-1 items-start self-stretch">
                    <InputLabel
                        for="last_name"
                        :value="$t('public.last_name')"
                        :invalid="!!form.errors.last_name"
                    />

                    <InputText
                        id="last_name"
                        type="text"
                        class="block w-full"
                        v-model="form.last_name"
                        :placeholder="$t('public.enter_last_name')"
                        :invalid="!!form.errors.last_name"
                    />

                    <InputError :message="form.errors.last_name" />
                </div>

                <div class="flex flex-col gap-1 items-start self-stretch">
                    <InputLabel
                        for="phone"
                        :value="$t('public.phone_number')"
                        :invalid="!!form.errors.phone"
                    />

                    <div class="flex gap-2 items-center self-stretch relative">
                        <Select
                            class="w-[150px]"
                            v-model="selectedPhoneCode"
                            :options="countries"
                            :loading="loadingCountries"
                            optionLabel="name"
                            placeholder="60"
                            :invalid="!!form.errors.dial_code"
                            filter
                            :filterFields="['name', 'iso2', 'phone_code']"
                        >
                            <template #value="slotProps">
                                <div v-if="slotProps.value" class="flex items-center">
                                    <div>{{ slotProps.value.phone_code }}</div>
                                </div>
                                <span v-else class="text-surface-400 dark:text-surface-700">{{ slotProps.placeholder }}</span>
                            </template>
                            <template #option="slotProps">
                                <div class="flex items-center gap-1">
                                    <img
                                        v-if="slotProps.option.iso2"
                                        :src="`https://flagcdn.com/w40/${slotProps.option.iso2.toLowerCase()}.png`"
                                        :alt="slotProps.option.iso2"
                                        width="18"
                                        height="12"
                                    />
                                    <div>{{ slotProps.option.phone_code }}</div>
                                </div>
                            </template>
                        </Select>

                        <InputText
                            id="phone"
                            type="text"
                            class="block w-full"
                            v-model="form.phone"
                            placeholder="1234567"
                            :invalid="!!form.errors.phone"
                        />
                    </div>
                    <InputError :message="form.errors.phone"/>
                </div>

                <div class="flex flex-col gap-1 items-start self-stretch">
                    <InputLabel
                        for="country"
                        :value="$t('public.nationality')"
                        :invalid="!!form.errors.country_id"
                    />
                    <Select
                        v-model="selectedCountry"
                        :options="countries"
                        :loading="loadingCountries"
                        optionLabel="name"
                        :placeholder="$t('public.select_nationality')"
                        class="w-full"
                        :invalid="!!form.errors.country_id"
                        filter
                        :filter-fields="['name', 'iso2']"
                    >
                        <template #value="slotProps">
                            <div v-if="slotProps.value" class="flex items-center">
                                <div class="leading-tight w-full">{{ JSON.parse(slotProps.value.translations)[locale] || slotProps.value.name }}</div>
                            </div>
                            <span v-else class="text-surface-400 dark:text-surface-700">{{ slotProps.placeholder }}</span>
                        </template>
                        <template #option="slotProps">
                            <div class="flex items-center gap-1">
                                <img
                                    v-if="slotProps.option.iso2"
                                    :src="`https://flagcdn.com/w40/${slotProps.option.iso2.toLowerCase()}.png`"
                                    :alt="slotProps.option.iso2"
                                    width="18"
                                    height="12"
                                />
                                <div class="max-w-[200px] truncate">{{ JSON.parse(slotProps.option.translations)[locale] || slotProps.option.name }}</div>
                            </div>
                        </template>
                    </Select>
                    <InputError :message="form.errors.country_id" />
                </div>

                <div class="flex flex-col gap-1 items-start self-stretch md:col-span-2">
                    <InputLabel
                        for="email"
                        :value="$t('public.email')"
                        :invalid="!!form.errors.email"
                    />
                    <InputText
                        id="email"
                        type="email"
                        class="block w-full"
                        v-model="form.email"
                        :placeholder="$t('public.enter_your_email')"
                        :invalid="!!form.errors.email"
                        disabled
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="flex flex-col gap-1 items-start self-stretch md:col-span-2">
                    <InputLabel
                        for="address"
                        :value="$t('public.address')"
                        :invalid="!!form.errors.address"
                    />
                    <InputText
                        id="address"
                        type="text"
                        class="block w-full"
                        v-model="form.address"
                        :placeholder="$t('public.enter_address')"
                        :invalid="!!form.errors.address"
                    />
                    <InputError :message="form.errors.address" />
                    <Message size="small" severity="secondary" variant="simple">
                        <span class="text-xs font-normal">{{ $t('public.address_help_text') }}</span>
                    </Message>
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
