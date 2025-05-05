<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import SelectChipGroup from "@/Components/SelectChipGroup.vue";
import InputError from "@/Components/InputError.vue";
import {useForm} from "@inertiajs/vue3";
import {ref, watch} from "vue";

const props = defineProps({
    accountTypes: Array
})

const selectedAccountType = ref(null);

watch(selectedAccountType, (value) => {
    console.log(value);
})

const form = useForm({
    account_type: '',
});
</script>

<template>
    <form class="flex flex-col gap-5 items-center self-stretch">
        <div class="flex flex-col gap-1 items-start self-stretch">
            <InputLabel
                for="account_type"
                :value="$t('public.select_account_type')"
                :invalid="!!form.errors.account_type"
            />
            <SelectChipGroup
                v-model="selectedAccountType"
                :items="accountTypes"
                value-key="slug"
            >
                <template #option="{ item }">
                    {{ item.name }}
                </template>
            </SelectChipGroup>
            <InputError :message="form.errors.account_type" />
        </div>
    </form>
</template>
