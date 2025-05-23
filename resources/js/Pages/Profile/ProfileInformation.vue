<script setup>
import {Image, Divider, Tag} from "primevue";
import {
    IconPhone,
    IconGlobe,
    IconMail,
    IconCalendar,
    IconHome, IconAlertSquareRoundedFilled,
} from "@tabler/icons-vue"
import {generalFormat} from "@/Composables/format.js";
import dayjs from "dayjs";
import UpdateProfileInformation from "@/Pages/Profile/UpdateProfileInformation.vue";

const props = defineProps({
    user: Object,
});

const {formatNameLabel} = generalFormat();
</script>

<template>
    <div class="flex flex-col md:flex-row rounded-xl bg-surface-0 dark:bg-surface-900 text-surface-700 dark:text-surface-0 border dark:border-surface-600 shadow-toast w-full">
        <div class="flex self-stretch w-full md:max-w-60 md:h-60">
            <Image
                v-if="user.profile_photo"
                :src="user.profile_photo"
                alt="Image"
                image-class="w-full h-full rounded-t-xl md:rounded-tl-xl md:rounded-tr-none md:rounded-bl-xl object-cover"
                preview
            />
            <div
                v-else
                class="w-full h-full rounded-t-xl md:rounded-tl-xl md:rounded-tr-none md:rounded-bl-xl object-cover text-3xl bg-surface-300 dark:bg-surface-700 flex items-center justify-center"
            >
                {{ formatNameLabel(user.full_name) }}
            </div>
        </div>
        <div class="flex flex-col justify-between self-stretch gap-2 p-4 md:p-6 w-full relative">
            <UpdateProfileInformation
                :user="user"
            />

            <div class="flex gap-5 items-start self-stretch">
                <div class="flex flex-col gap-1 items-start self-stretch">
                    <div class="text-xl font-bold">
                        {{ user.full_name }}
                    </div>
                    <div class="flex items-center">
                        <div class="text-sm text-surface-600">
                            {{ user.username }}
                        </div>
                        <Divider layout="vertical" />
                        <div class="text-sm text-surface-600">
                            {{ user.id_number ?? 'LS000001' }}
                        </div>
                        <Divider layout="vertical" />
                        <div class="text-sm text-surface-600">
                            {{ $t(`public.${user.rank.rank_name}`) ?? 'Member' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 w-full">
                <div class="flex gap-3 items-center">
                    <IconPhone size="16" stroke-width="1.5" />
                    <div class="text-sm dark:text-white">
                        {{ user.dial_code }} {{ user.phone }}
                    </div>
                </div>
                <div class="flex gap-3 items-center">
                    <IconGlobe size="16" stroke-width="1.5" />
                    <div v-if="user.country" class="text-sm dark:text-white">
                        {{ user.country.name }}
                    </div>
                    <div v-else class="flex items-center text-red-500 gap-1 text-xs">
                        <IconAlertSquareRoundedFilled size="12" stroke-width="1.5" />
                        {{ $t('public.missing_field') }}
                    </div>
                </div>
                <div class="flex gap-3 items-center">
                    <IconMail size="16" stroke-width="1.5" />
                    <div class="text-sm dark:text-white">
                        {{ user.email }}
                    </div>
                </div>
                <div class="flex gap-3 items-center">
                    <IconCalendar size="16" stroke-width="1.5" />
                    <div class="text-sm dark:text-white">
                        {{ dayjs(user.created_at).format('YYYY/MM/DD') }}
                    </div>
                </div>
                <div class="flex gap-3 items-center col-span-2">
                    <IconHome size="16" stroke-width="1.5" />
                    <div v-if="user.address" class="text-sm dark:text-white">
                        {{ user.address }}
                    </div>
                    <div v-else class="flex items-center text-red-500 gap-1 text-xs">
                        <IconAlertSquareRoundedFilled size="12" stroke-width="1.5" />
                        {{ $t('public.missing_field') }}
                    </div>
                </div>
            </div>

            <div class="flex gap-3 items-center">
                <Tag
                    :severity="user.profile_status === 'incomplete' ? 'warn' : 'info'"
                    :value="$t(`public.profile_${user.profile_status}`)"
                />
                <Tag
                    :severity="user.kyc_status === 'verified' ? 'success' : 'danger'"
                    :value="$t(`public.kyc_${user.kyc_status}`)"
                />
            </div>
        </div>
    </div>
</template>
