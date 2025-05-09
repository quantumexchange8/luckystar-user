<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Button, Password, InputText, Select } from 'primevue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthHeader from '@/Components/Auth/AuthHeader.vue';

const selectedPhoneCode = ref();

// country
const countries = ref([]);
const loadingCountries = ref(false);
const getCountries = async () => {
    loadingCountries.value = true;
    try {
        const response = await axios.get('/get_countries');
        countries.value = response.data.countries;
    } catch (error) {
        console.error('Error fetching selectedCountry:', error);
    } finally {
        loadingCountries.value = false;
    }
}

getCountries();

const form = useForm({
    username: '',
    first_name: '',
    last_name: '',
    email: '',
    dial_code: '',
    phone: '',
    phone_number: '',
    password: '',
    password_confirmation: '',
    referral_code: '',
});

const submit = () => {
    form.dial_code = selectedPhoneCode.value;
    if(selectedPhoneCode.value){
        form.phone_number = selectedPhoneCode.value.phone_code + form.phone;
    }

    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <AuthHeader
            :header="$t('public.register_an_account')"
            :caption="$t('public.enter_your_details')"
        />

        <form @submit.prevent="submit" class="w-full">
            <div class="flex flex-col gap-5 w-full self-stretch">
                <div class="flex flex-col gap-1 items-start self-stretch">
                    <InputLabel
                        for="username"
                        :value="$t('public.username')"
                        :invalid="!!form.errors.username"
                    />

                    <InputText
                        id="username"
                        type="text"
                        class="block w-full"
                        v-model="form.username"
                        autofocus
                        :placeholder="$t('public.enter_username')"
                        :invalid="!!form.errors.username"
                    />

                    <InputError :message="form.errors.username" />
                </div>

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
                    />

                    <InputError :message="form.errors.email" />
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
                                <span v-else class="text-surface-400 dark:text-surface-500">{{ slotProps.placeholder }}</span>
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
                        for="password"
                        :value="$t('public.password')"
                        :invalid="!!form.errors.password"
                    />

                    <Password
                        id="password"
                        class="block w-full"
                        v-model="form.password"
                        toggleMask
                        :inputStyle="{'width': '100%'}"
                        :style="{'width': '100%'}"
                        placeholder="••••••••"
                        :invalid="!!form.errors.password"
                    />

                    <InputError :message="form.errors.password" />
                </div>

                <div class="flex flex-col gap-1 items-start self-stretch">
                    <InputLabel
                        for="password_confirmation"
                        :value="$t('public.confirm_password')"
                        :invalid="!!form.errors.password"
                    />

                    <Password
                        id="password_confirmation"
                        class="block w-full"
                        v-model="form.password_confirmation"
                        toggleMask
                        :inputStyle="{'width': '100%'}"
                        :style="{'width': '100%'}"
                        placeholder="••••••••"
                        :invalid="!!form.errors.password"
                    />
                </div>

                <div class="flex flex-col gap-1 items-start self-stretch">
                    <InputLabel
                        for="referral_code"
                        :invalid="!!form.errors.referral_code"
                    >
                        {{ $t('public.referral_code') }}
                    </InputLabel>

                    <InputText
                        id="referral_code"
                        type="text"
                        class="block w-full"
                        v-model="form.referral_code"
                        :placeholder="$t('public.enter_referral_code_if_you_have_one')"
                        :invalid="!!form.errors.referral_code"
                    />

                    <InputError :message="form.errors.referral_code" />
                </div>
            </div>

            <div class="flex flex-col mt-6 w-full self-stretch">
                <div class="flex flex-col items-center">
                    <Button
                        type="submit"
                        :class="['w-full', { 'opacity-25': form.processing }]"
                        :disabled="form.processing"
                        :label="$t('public.register')"
                    />

                    <label class="flex items-center justify-center w-full self-stretch">
                        <span class="text-sm text-surface-600 dark:text-surface-400">{{ $t('public.already_have_account') }}</span>
                        <Button
                            variant="link"
                            :label="$t('public.login')"
                            as="a"
                            :href="route('login')"
                        />
                    </label>
                </div>
            </div>
        </form>
    </GuestLayout>
</template>
