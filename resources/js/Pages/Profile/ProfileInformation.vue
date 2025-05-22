<script setup>
import {Image, Divider, Tag, Avatar} from "primevue";
import {
    IconPhone,
    IconGlobe,
    IconMail,
    IconCalendar,
    IconHome,
} from "@tabler/icons-vue"
import {generalFormat} from "@/Composables/format.js";

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
                class="w-full h-full rounded-t-xl md:rounded-tl-xl md:rounded-tr-none md:rounded-bl-xl object-cover text-3xl bg-surface-300 flex items-center justify-center"
            >
                {{ formatNameLabel(user.full_name) }}
            </div>
        </div>
        <div class="flex flex-col justify-between self-stretch gap-2 p-4 md:p-6 w-full">
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
                            {{ user.rank.rank_name ?? 'Member' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 w-full">
                <div class="flex gap-3 items-center">
                    <IconPhone size="16" stroke-width="1.5" />
                    <div class="text-sm dark:text-white">
                        {{ user.phone_number }}
                    </div>
                </div>
                <div class="flex gap-3 items-center">
                    <IconGlobe size="16" stroke-width="1.5" />
                    <div class="text-sm dark:text-white">
                        {{ user.country?.name || '-' }}
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
                        {{ user.dob ?? '-' }}
                    </div>
                </div>
                <div class="flex gap-3 items-center col-span-2">
                    <IconHome size="16" stroke-width="1.5" />
                    <div class="text-sm dark:text-white">
                        {{ user.address ?? '-' }}
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
