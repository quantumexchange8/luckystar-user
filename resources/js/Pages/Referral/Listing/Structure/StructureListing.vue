<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {onMounted, ref, watch} from "vue";
import { OrganizationChart, InputText, InputNumber, Button, Chip, Divider, Tag, Message } from "primevue";
import panzoom from '@panzoom/panzoom';
import {
    IconPlus,
    IconMinus,
    IconFocus2,
    IconMouse,
    IconLoader,
    IconChevronDown
} from "@tabler/icons-vue";
import {generalFormat} from "@/Composables/format.js";
import debounce from "lodash/debounce.js";

const props = defineProps({
    root: Object,
})

const {formatAmount} = generalFormat();
const panzoomContainer = ref();
const panzoomInstance = ref();
const zoom = ref(100);
const root = ref(props.root);

onMounted(() => {
    setInitialCollapsedKeys(root.value);

    if (panzoomContainer.value) {
        const instance = panzoom(panzoomContainer.value, {
            maxZoom: 5,
            minZoom: 0.5,
            bounds: true,
            boundsPadding: 0.1,
            canvas: true,
            startY: 20
        });
        panzoomInstance.value = instance;

        const parent = panzoomContainer.value.parentElement;

        parent.addEventListener(
            'wheel',
            (e) => {
                if (e.ctrlKey || e.shiftKey) {
                    e.preventDefault();
                    instance.zoomWithWheel(e);
                    zoom.value = Math.round(instance.getScale() * 100);
                }
            },
            { passive: false }
        );
    }
});

// Update panzoom when zoom input changes
watch(zoom, (newVal) => {
    if (panzoomInstance.value) {
        const scale = newVal / 100;
        panzoomInstance.value.zoom(scale);
    }
});

const resetZoom = () => {
    if (panzoomInstance.value) {
        panzoomInstance.value.reset();
        zoom.value = 100;
    }
};

// Assuming `root` is an object with a `children` property (like your structure)
const collapsedKeys = ref({});

function setInitialCollapsedKeys(node) {
    if (node.children && node.children.length) {
        let hasLoadingChild = node.children.some(child => child.label === 'loading');
        if (hasLoadingChild) {
            collapsedKeys.value[node.key] = true; // Collapse the node if it has a "loading" child
        }

        // Recursively process descendants
        node.children.forEach(child => {
            if (child.children && child.children.length) {
                setInitialCollapsedKeys(child);
            }
        });
    }
}

const loadingKeys = ref({});

function onNodeExpand(event) {
    const node = event;

    if (loadingKeys.value[node.key]) {
        console.log('Already loading:', loadingKeys.value[node.key]);
        return; // already loading
    }

    if (node.children && node.children[0]?.label === 'loading') {
        // Keep it collapsed while loading
        collapsedKeys.value[node.key] = true;

        loadChildren(node);
    }
}

function onNodeCollapsed(event) {
    const node = event.node;
    console.log('Collapsed:', node);

    // Mark as collapsed in collapsedKeys (this is handled by v-model by default, you might not need this)
    collapsedKeys.value[node.key] = true;
}

// Load children function (same as before)
async function loadChildren(node) {
    loadingKeys.value[node.key] = true;
    showLoadingMessage('loading_data', 'info');

    try {
        const { data } = await axios.get(`/referral/structure/getStructureData?child_id=${node.key}`);
        const targetNode = findNodeByKey(root.value, node.key);
        if (targetNode) {
            targetNode.children = data.data;
        }

        // Expand after loading
        delete collapsedKeys.value[node.key];
        showLoadingMessage('successfully_loaded_data', 'success');
        setInitialCollapsedKeys(root.value);

    } catch (error) {
        console.error('Failed to load children:', error);
    } finally {
        loadingKeys.value[node.key] = false;
    }
}

function findNodeByKey(root, key) {
    // Recursive search for node by key
    if (root.key === key) {
        return root;
    }

    if (root.children) {
        for (let child of root.children) {
            const found = findNodeByKey(child, key);
            if (found) return found;
        }
    }
    return null;
}

const showMessage = ref(false);
const message = ref('');
const type = ref('info')

function showLoadingMessage(text, severity) {
    message.value = text;
    showMessage.value = true;
    type.value = severity;

    setTimeout(() => {
        showMessage.value = false;
    }, 3000);
}

const selection = ref({});

watch(selection, (newValue, oldValue) => {
    console.log(selection.value)
});

const search = ref('');

const searchUser = async (keyword) => {
    try {
        if (keyword) {
            const response = await axios.get(`/referral/structure/getStructureData?search=${keyword}`);
            if (response.data.success) {
                const result = response.data.data;

                if (result.data.directs_count > 0) {
                    result.children = [
                        {
                            key: 'loading-' + result.key,
                            label: 'loading'
                        }
                    ];
                }

                collapsedKeys.value = {};

                root.value = result;
                setInitialCollapsedKeys(root.value);
            } else {
                root.value = props.root;
            }
        }
    } catch (e) {
        console.error('Search failed:', e);
        root.value = props.root;
    }
};

watch(search, debounce(function() {
    searchUser(search.value);
}, 300));
</script>

<template>
    <AuthenticatedLayout :title="$t('public.structure')">
        <div class="flex flex-col gap-5 items-center self-stretch">
            <div class="flex items-center gap-5 justify-between self-stretch">
                <InputText
                    id="identity_number"
                    v-model="search"
                    type="text"
                    class="block w-full md:w-60"
                    :placeholder="$t('public.search_keyword')"
                />

                <div class="flex items-center self-stretch gap-3">
                    <Button
                        type="button"
                        severity="secondary"
                        class="min-w-12"
                        @click="resetZoom"
                    >
                        <template #icon>
                            <IconFocus2 size="20" stroke-width="1.5" />
                        </template>
                    </Button>
                    <InputNumber
                        v-model="zoom"
                        inputId="zoom-control"
                        :min="50"
                        :max="500"
                        :step="10"
                        showButtons
                        buttonLayout="horizontal"
                        suffix="%"
                        input-class="w-20"
                    >
                        <template #incrementbuttonicon>
                            <IconPlus size="14" stroke-width="1.5" />
                        </template>
                        <template #decrementbuttonicon>
                            <IconMinus size="14" stroke-width="1.5" />
                        </template>
                    </InputNumber>
                </div>
            </div>

            <div class="border border-surface-300 dark:border-surface-600 rounded-xl bg-surface-100 dark:bg-surface-900 bg-[radial-gradient(#d4d4d8_2px,transparent_1px)] dark:bg-[radial-gradient(#3f3f46_2px,transparent_1px)] [background-size:52px_52px] overflow-hidden w-full h-[600px] relative">
                <div class="absolute top-5 right-5 z-50">
                    <Message
                        v-if="showMessage"
                        :severity="type"
                        :life="3000"
                        class="w-fit"
                    >
                        {{ $t(`public.${message}`) }}
                    </Message>
                </div>
                <div ref="panzoomContainer" class="w-full">
                    <OrganizationChart
                        v-model:selectionKeys="selection"
                        v-model:collapsedKeys="collapsedKeys"
                        :value="root"
                        collapsible
                        selectionMode="single"
                        @node-expand="onNodeExpand"
                        @node-collapsed="onNodeCollapsed"
                    >
                        <template #person="slotProps">
                            <div class="flex flex-col items-center self-stretch">
                                <div class="flex items-center gap-2 justify-between w-full">
                                    <div class="flex flex-col items-start">
                                        <span class="font-medium">{{ slotProps.node.data.username }}</span>
                                        <Tag
                                            severity="info"
                                            :value="slotProps.node.data.rank"
                                        />
                                    </div>
                                    <Tag severity="warn">
                                        <div class="flex flex-col self-stretch">
                                            <span class="text-sm font-semibold">{{ formatAmount(slotProps.node.data.total_clients, 0, '') }}</span>
                                            <span class="text-xs font-normal">{{ $t('public.clients') }}</span>
                                        </div>
                                    </Tag>
                                </div>
                                <Divider />
                                <div class="flex flex-col gap-1 items-center">
                                    <div class="grid grid-cols-2 w-full">
                                        <div class="text-left text-xs text-surface-500">
                                            {{ $t('public.active_capital') }}
                                        </div>
                                        <div class="flex justify-end font-medium">
                                            {{ formatAmount(slotProps.node.data.active_capital) }}
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 w-full">
                                        <div class="text-left text-xs text-surface-500">
                                            {{ $t('public.group_active_capital') }}
                                        </div>
                                        <div class="flex justify-end font-medium">
                                            {{ formatAmount(slotProps.node.data.group_active_capital) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template #toggleicon>
                            <div v-if="type === 'info' && showMessage" class="animate-spin">
                                <IconLoader size="16" stroke-width="1.5" />
                            </div>
                            <IconChevronDown
                                v-else
                                size="16"
                                stroke-width="1.5"
                            />
                        </template>
                        <template #default="slotProps">
                            <span>{{ 'loading' }}</span>
                        </template>
                    </OrganizationChart>
                </div>
            </div>

            <div class="flex flex-col gap-1 items-start self-stretch">
                <span class="font-semibold text-sm dark:text-white">{{ $t('public.zoom') }}</span>
                <div class="flex gap-1 items-center text-xs dark:text-white">
                    <Chip>
                        <kbd class="text-xxs">Shift</kbd>
                    </Chip> +
                    <Chip>
                        <template #icon>
                            <IconMouse size="14" stroke-width="1.5" />
                        </template>
                    </Chip>
                    {{ $t('public.scroll') }}
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
