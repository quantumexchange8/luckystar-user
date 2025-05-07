<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Button, Checkbox, Password, InputText } from 'primevue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthHeader from '@/Components/Auth/AuthHeader.vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head :title="$t('public.login')" />

        <AuthHeader
            :header="$t('public.welcome_to_luckystar')"
            :caption="$t('public.enter_your_details')"
        />

        <form @submit.prevent="submit" class="w-full">
            <div class="flex flex-col gap-5 w-full self-stretch">
                <div class="flex flex-col gap-1 items-start self-stretch">
                    <InputLabel
                        for="email"
                        :value="$t('public.email')"
                        :invalid="!!form.errors.email"
                    />

                    <InputText
                        id="email"
                        type="text"
                        class="block w-full"
                        v-model="form.email"
                        :placeholder="$t('public.enter_your_email')"
                        autofocus
                        :invalid="!!form.errors.email"
                    />

                    <InputError :message="form.errors.email" />
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
                        :feedback="false"
                    />

                    <InputError :message="form.errors.password" />
                </div>

            </div>

            <div class="flex flex-col gap-6 mt-6 w-full self-stretch">
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" :binary="true"/>
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400"
                            >{{ $t('public.remember_me') }}
                        </span>
                    </label>

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm text-surface-600 hover:text-primary dark:hover:text-primary-500 focus:outline-none dark:text-surface-400"
                    >
                        {{ $t('public.forgot_your_password') }}
                    </Link>
                </div>

                <div class="flex flex-col items-center">
                    <Button
                        type="submit"
                        :class="['w-full', { 'opacity-25': form.processing }]"
                        :disabled="form.processing"
                        :label="$t('public.login')"
                    />

                    <label
                        v-if="canResetPassword"
                        class="flex items-center justify-center w-full self-stretch"
                    >
                        <span class="text-sm text-surface-600 dark:text-surface-400">{{ $t('public.dont_have_account') }}</span>
                        <Button
                            variant="link"
                            :label="$t('public.register_now')"
                            as="a"
                            :href="route('register')"
                        />
                    </label>
                </div>
            </div>
        </form>
    </GuestLayout>
</template>
