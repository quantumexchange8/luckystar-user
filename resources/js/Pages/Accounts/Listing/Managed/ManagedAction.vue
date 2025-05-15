<script setup>
import { IconChevronRight, IconCreditCardPay, IconDatabaseMinus, IconDots, IconHistory, IconTrash } from '@tabler/icons-vue';
import { Button, TieredMenu, Dialog, useConfirm } from 'primevue';
import { ref, h } from 'vue';
import Withdrawal from '../Partial/Withdrawal.vue';
import AccountReport from '../Partial/AccountReport.vue';
import Leverage from '../Partial/Leverage.vue';
import {trans} from "laravel-vue-i18n";

const confirm = useConfirm();

const requireConfirmation = (action_type) => {
    const messages = {
        delete_item: {
            group: 'headless-error',
            header: trans('public.delete_account'),
            text: trans('public.delete_account_caption'),
            cancelButton: trans('public.cancel'),
            acceptButton: trans('public.confirm'),
        },
        revoke_pamm: {
            group: 'headless-error',
            header: trans('public.revoke_pamm'),
            text: trans('public.revoke_pamm_caption'),
            cancelButton: trans('public.cancel'),
            acceptButton: trans('public.confirm'),
        },
    };

    const { group, header, text, dynamicText, suffix, actionType, cancelButton, acceptButton, action } = messages[action_type];

    confirm.require({
        group,
        header,
        actionType,
        text,
        dynamicText,
        suffix,
        cancelButton,
        acceptButton,
        accept: action
    });
};

const handleRevoke = () => {
    requireConfirmation('revoke_pamm')
}

const handleDelete = () => {
    requireConfirmation('delete_item')
}

const menu = ref();
const visible = ref(false);

const dialogType = ref();

const items = ref([
    {
        label: 'withdrawal',
        icon: h(IconCreditCardPay),
        command: () => {
            visible.value = true;
            dialogType.value = 'withdrawal'
        },
    },
    {
        label: 'revoke_pamm',
        icon: h(IconDatabaseMinus),
        command: () => {
            handleRevoke();
        }
    },
    {
        label: 'account_report',
        icon: h(IconHistory),
        command: () => {
            visible.value = true;
            dialogType.value = 'account_report'
        },
    },
    {
        separator: true
    },
    {
        label: 'delete',
        icon: h(IconTrash),
        command: () => {
            handleDelete();
        }
    },

]);

const toggle = (event) => {
    menu.value.toggle(event);
};
</script>

<template>
    <Button
        severity="secondary"
        size="small"
        type="button"
        rounded
        text
        @click="toggle"
        aria-haspopup="true"
        aria-controls="overlay_tmenu"
    >
        <template #icon>
            <IconDots size="20" stroke-width="1.5"/>
        </template>
    </Button>

    <TieredMenu
        ref="menu"
        id="overlay_tmenu"
        :model="items" popup
    >
        <template #item="{item, props, hasSubmenu}">
            <div
                class="flex items-center gap-3 self-stretch"
                v-bind="props.action"
            >
                <div
                    :class="{
                        'text-surface-400 dark:text-surface-500': item.label !== 'delete',
                        'text-red-500': item.label === 'delete',
                    }"
                >
                    <component
                        :is="item.icon"
                        size="20"
                        stroke-width="1.5"
                    />
                </div>

                <span
                    class="font-medium"
                    :class="{'text-red-500': item.label === 'delete'}"
                >{{ $t(`public.${item.label}`) }}</span>
                <span v-if="hasSubmenu" class="ml-auto">
                    <IconChevronRight size="20" stroke-width="1.5" />
                </span>
            </div>
        </template>
    </TieredMenu>

    <Dialog
        v-model:visible="visible"
        modal
        class="dialog-xs md:dialog-sm"
        :class="[
            { 'md:dialog-md': dialogType === 'account_report' }
        ]"
    >
        <template #header>
            <div class="flex items-center gap-4">
                <div class="text-xl font-bold">
                    {{$t(`public.${dialogType}`)}}
                </div>
            </div>
        </template>

        <template v-if="dialogType === 'withdrawal'">
            <Withdrawal 
                @update:visible="visible = false"
            />
        </template>

        <template v-if="dialogType === 'revoke_pamm'">
            <Leverage 
                @update:visible="visible = false"
            />
        </template>

        <template v-if="dialogType === 'account_report'">
            <AccountReport />
        </template>
    </Dialog>
</template>