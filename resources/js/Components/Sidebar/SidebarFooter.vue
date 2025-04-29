
<script setup>
import { Avatar, Button } from "primevue";
// import { MenuFoldLineLeftIcon, MenuFoldLineRightIcon } from '@/Components/Icons/outline'
import { sidebarState } from '@/Composables'
import {usePage, Link} from "@inertiajs/vue3";
const user = usePage().props.auth.user;
</script>

<template>
    <div class="flex-shrink-0 p-3 sticky">
        <Link
            :href="route('profile.edit')"
            :class="[
                'flex items-center gap-3 self-stretch group select-none cursor-pointer transition-colors py-3 px-7',
                {
                    'text-surface-950 dark:text-white hover:text-primary hover:bg-primary-50 dark:hover:bg-surface-800 dark:hover:text-primary-500': !route().current('profile.edit'),
                    'text-primary bg-primary-50 dark:bg-surface-800 hover:bg-primary-100': route().current('profile.edit'),
                },
            ]"
        >
            <Avatar
                v-if="usePage().props.auth.profile_photo"
                :image="usePage().props.auth.profile_photo"
                shape="circle"
            />
            <Avatar
                v-else
              
                shape="circle"
            />

            <div class="flex flex-col text-sm">
                <div class="flex items-center gap-1 font-semibold">
                    <span class="max-w-28 truncate">{{ user.username }}</span>
                </div>
                <span class="text-xs text-surface-500 max-w-36 truncate">{{ user.email }}</span>
            </div>
            </Link>
    </div>
</template>
