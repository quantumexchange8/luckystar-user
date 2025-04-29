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
        <Head title="Log in" />

        <AuthHeader
            :header="'Welcome back to Lucky Star'"
            :caption="'Please enter your details'"
        />

        <form @submit.prevent="submit" class="w-full">
            <div class="flex flex-col gap-4 w-full self-stretch">
                <div class="flex flex-col gap-1 items-start self-stretch">
                    <InputLabel for="email" value="Email" :invalid="!!form.errors.email"/>
    
                    <InputText
                        id="email"
                        type="text"
                        class="block w-full"
                        v-model="form.email"
                        placeholder="Email"
                        autofocus
                        :invalid="!!form.errors.email"
                    />
    
                    <InputError :message="form.errors.email" />
                </div>
    
                <div class="flex flex-col gap-1 items-start self-stretch">
                    <InputLabel for="password" value="Password" :invalid="!!form.errors.password"/>
    
                    <Password
                        id="password"
                        class="block w-full"
                        v-model="form.password"
                        toggleMask
                        :inputStyle="{'width': '100%'}"
                        :style="{'width': '100%'}"
                        placeholder="Password"
                        :invalid="!!form.errors.password"
                        :feedback="false"
                    />
    
                    <InputError :message="form.errors.password" />
                </div>
    
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" :binary="true"/>
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400"
                            >Remember me
                        </span>
                    </label>

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm text-surface-600 hover:text-primary dark:hover:text-primary-500 focus:outline-none dark:text-surface-400"
                    >
                        Forgot your password?
                    </Link>
                </div>
    
                <div class="flex flex-col gap-1 pt-5 items-center">
                    <Button
                        type="submit"
                        :class="{ 'opacity-25': form.processing }"
                        class="w-full text-center font-semibold dark:text-surface-950 text-white"
                        :disabled="form.processing"
                    >
                        Log in
                    </Button>

                    <Link
                        v-if="canResetPassword"
                        :href="route('register')"
                        class="text-sm text-surface-600 hover:text-primary dark:hover:text-primary-500 focus:outline-none dark:text-surface-400"
                    >
                        Register now
                    </Link>
                </div>
            </div>
        </form>
    </GuestLayout>
</template>
