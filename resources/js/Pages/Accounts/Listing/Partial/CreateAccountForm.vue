<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import SelectChipGroup from "@/Components/SelectChipGroup.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import { Button, MultiSelect } from "primevue";

const props = defineProps({
    accountTypes: Array,
    leverageOptions: Array,
});

const emit = defineEmits(['update:visible']);

const selectedAccountType = ref(null);
const selectedLeverages = ref([]);

const form = useForm({
    account_type: '',
    leverage: [],
});

// Filter leverage options based on selected account type
const filteredLeverageOptions = computed(() => {
    if (!selectedAccountType.value) return [];

    return props.leverageOptions
        .filter(option => option.account_type_id === selectedAccountType.value.id)
        .map(option => ({
            ...option,
            label: option.setting_leverage?.label ?? 'Unknown'
        }));
});

// Watchers to sync form state
watch(selectedAccountType, (value) => {
    form.account_type = value?.slug || '';
    selectedLeverages.value = []; // reset leverages on change
});

watch(selectedLeverages, (value) => {
    form.leverage = value.map(item => item.setting_leverage_id); // adjust this if backend expects something else
});
</script>

<template>
    <form class="flex flex-col gap-5 items-center self-stretch">
        <!-- Account Type -->
        <div class="flex flex-col gap-1 items-start self-stretch">
            <InputLabel
                for="account_type"
                :value="$t('public.select_account_type')"
                :invalid="!!form.errors.account_type"
            />
            <SelectChipGroup
                v-model="selectedAccountType"
                :items="accountTypes"
            >
                <template #option="{ item }">
                    {{ item.name }}
                </template>
            </SelectChipGroup>
            <InputError :message="form.errors.account_type" />
        </div>

        <!-- Leverage -->
        <div class="flex flex-col gap-1 items-start self-stretch">
            <InputLabel
                for="leverage"
                :value="$t('public.leverage')"
                :invalid="!!form.errors.leverage"
            />
            <MultiSelect
                v-model="selectedLeverages"
                :options="filteredLeverageOptions"
                optionLabel="label"
                :placeholder="$t('public.select_leverage')"
                :maxSelectedLabels="3"
                class="w-full"
                :invalid="!!form.errors.leverages"
            />
            <InputError :message="form.errors.leverage" />
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 justify-end self-stretch pt-2 w-full">
            <Button
                type="button"
                :label="$t('public.cancel')"
                severity="secondary"
                @click="$emit('update:visible', false)"
                class="flex flex-1 md:flex-none md:w-[120px]"
            />
            <Button
                type="submit"
                :label="$t('public.confirm')"
                :disabled="form.processing"
                class="flex flex-1 md:flex-none md:w-[120px]"
            />
        </div>
    </form>
</template>
