<script setup>
import { IconChevronRight, IconCreditCardPay, IconDots, IconHistory, IconScale, IconTrash } from '@tabler/icons-vue';
import { Button, TieredMenu, Dialog, useConfirm } from 'primevue';
import { ref, h } from 'vue';
import Withdrawal from '../Partial/Withdrawal.vue';
import AccountReport from '../Partial/AccountReport.vue';
import Leverage from '../Partial/Leverage.vue';

const confirm = useConfirm();

const requireConfirmation = (action_type) => {
    const messages = {
        delete_item: {
            group: 'headless-error',
            header: 'public.delete_account',
            text: 'public.delete_account_caption',
            cancelButton: 'public.cancel',
            acceptButton: 'Yes, delete it',
           
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

const handleStatus = () => {
    requireConfirmation('delete_item')
}

const menu = ref();
const visible = ref(false);

const dialogType = ref();

const items = ref([
    {
        label: 'Withdrawal',
        icon: h(IconCreditCardPay),
        command: () => {
            visible.value = true;
            dialogType.value = 'Withdrawal'
        },
    },
    {
        label: 'Change Leverage',
        icon: h(IconScale),
        command: () => {
            visible.value = true;
            dialogType.value = 'Leverage'
        },
    },
    {
        label: 'Account Report',
        icon: h(IconHistory),
        command: () => {
            visible.value = true;
            dialogType.value = 'Account Report'
        },
    },
    {
        separator: true
    },
    {
        label: 'delete',
        icon: h(IconTrash),
        command: () => {
            handleStatus();
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
        class="!p-2"
        @click="toggle"
        aria-haspopup="true"
        aria-controls="overlay_tmenu"
    >
        <IconDots size="20" stroke-width="1.5"/>
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
                >{{ item.label }}</span>
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
            { 'md:dialog-md': dialogType === 'Account Report' }
        ]"
    >
        <template #header>
            <div class="flex items-center gap-4">
                <div class="text-xl font-bold">
                    {{ dialogType }}
                </div>
            </div>
        </template>

        <template v-if="dialogType === 'Withdrawal'">
            <Withdrawal 
                @update:visible="visible = false"
            />
        </template>

        <template v-if="dialogType === 'Leverage'">
            <Leverage 
                @update:visible="visible = false"
            />
        </template>

        <template v-if="dialogType === 'Account Report'">
            <AccountReport />
        </template>
    </Dialog>
</template>