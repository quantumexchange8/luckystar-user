<script setup>
import { IconChevronRight, IconClipboardData, IconDotsVertical } from '@tabler/icons-vue';
import { Button, TieredMenu } from 'primevue';
import { ref, h } from 'vue';

const menu = ref();

const items = ref([
    {
        label: 'trade_report',
        icon: h(IconClipboardData),
        command: () => {
              window.location.href = ``;
        },
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
            <IconDotsVertical size="20" stroke-width="1.5"/>
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
                   class="text-surface-400 dark:text-surface-500"
                >
                    <component
                        :is="item.icon"
                        size="20"
                        stroke-width="1.5"
                    />
                </div>

                <span
                    class="font-medium"
                >
                    {{ $t(`public.${item.label}`) }}
                </span>

                <span v-if="hasSubmenu" class="ml-auto">
                    <IconChevronRight size="20" stroke-width="1.5" />
                </span>
            </div>
        </template>
    </TieredMenu>
</template>