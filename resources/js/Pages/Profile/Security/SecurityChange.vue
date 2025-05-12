<script setup>
import { Card, Password, InputOtp, Button } from 'primevue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    old_password: '',
    new_password: '',
    new_password_confirmation: '',

    old_security_pin: '',
    new_security_pin: '',
});
</script>

<template>
    <div class="w-full">
         <div class="flex flex-col md:flex-row md:items-start gap-5 w-full">
            <Card class="w-full">
                 <template #title>
                    <div class="flex gap-5 items-center justify-between">
                        {{ $t('public.change_password') }}
                    </div>
                </template>
                <template #content>
                    <form>
                        <div class="flex flex-col gap-5">
                            <div class="flex flex-col items-start gap-1 self-stretch">
                                <InputLabel
                                    for="account_id"
                                    :value="$t('public.old_password')"
                                    :invalid="form.errors.old_password"
                                />

                                <Password 
                                    id="old_password"
                                    class="block w-full"
                                    v-model="form.old_password"
                                    toggleMask
                                    :inputStyle="{'width': '100%'}"
                                    :style="{'width': '100%'}"
                                    placeholder="••••••••"
                                    :invalid="!!form.errors.old_password"
                                />
                                
                                <InputError :message="form.errors.old_password" />
                            </div>

                            <div class="flex flex-col items-start gap-1 self-stretch">
                                <InputLabel
                                    for="account_id"
                                    :value="$t('public.new_password')"
                                    :invalid="form.errors.new_password"
                                />

                                <Password 
                                    id="new_password"
                                    class="block w-full"
                                    v-model="form.new_password"
                                    toggleMask
                                    :inputStyle="{'width': '100%'}"
                                    :style="{'width': '100%'}"
                                    placeholder="••••••••"
                                    :invalid="!!form.errors.new_password"
                                />
                                
                                <InputError :message="form.errors.new_password" />
                            </div>

                            <div class="flex flex-col items-start gap-1 self-stretch">
                                <InputLabel
                                    for="account_id"
                                    :value="$t('public.new_password_confirmation')"
                                    :invalid="form.errors.new_password_confirmation"
                                />

                                <Password 
                                    id="new_password_confirmation"
                                    class="block w-full"
                                    v-model="form.new_password_confirmation"
                                    toggleMask
                                    :inputStyle="{'width': '100%'}"
                                    :style="{'width': '100%'}"
                                    placeholder="••••••••"
                                    :invalid="!!form.errors.new_password_confirmation"
                                />
                                
                                <InputError :message="form.errors.new_password_confirmation" />
                            </div>
                        </div>

                        <div class="flex justify-end items-center pt-10 md:pt-7 gap-3 md:gap-4 self-stretch">
                            <Button type="submit" :label="$t('public.save')" :disabled="form.processing" class="w-auto px-8"></Button>
                        </div>
                    </form>
                </template>
            </Card>

            <Card class="w-full">
                 <template #title>
                    <div class="flex gap-5 items-center justify-between">
                        {{ $t('public.change_security_pin') }}
                    </div>
                </template>

                <template #content>
                    <form>
                        <div class="flex flex-col gap-5">
                            <div class="flex flex-col items-start gap-1 self-stretch">
                                <InputLabel
                                    for="account_id"
                                    :value="$t('public.old_security_pin')"
                                    :invalid="form.errors.old_security_pin"
                                />

                                <InputOtp 
                                    v-model="form.old_security_pin" 
                                    class="w-full" 
                                    integerOnly
                                    :length="6"
                                />
                                
                                <InputError :message="form.errors.old_security_pin" />
                            </div>

                            <div class="flex flex-col items-start gap-1 self-stretch">
                                <InputLabel
                                    for="account_id"
                                    :value="$t('public.new_security_pin')"
                                    :invalid="form.errors.new_security_pin"
                                />

                                <InputOtp 
                                    v-model="form.new_security_pin" 
                                    class="w-full" 
                                    integerOnly 
                                    :length="6"
                                />
                                
                                <InputError :message="form.errors.new_security_pin" />
                            </div>
                        </div>

                        <div class="flex justify-end items-center pt-10 md:pt-7 gap-3 md:gap-4 self-stretch">
                            <Button type="submit" :label="$t('public.save')" :disabled="form.processing" class="w-auto px-8"></Button>
                        </div>
                    </form>
                </template>
            </Card>
        </div>
    </div>
</template>