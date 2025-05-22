<script setup>
import { IconCalendarCheck, IconCalendarCancel, IconUserStar } from '@tabler/icons-vue';
import SelectChipGroup from '@/Components/SelectChipGroup.vue';
import { Card, Button, Tag, Skeleton } from 'primevue';
import { ref, watch } from 'vue';

const props = defineProps({
    subscribedStrategyCount: Number,
});

const subscribedStrategy = ref([]);
const isLoading = ref(false);
const selectedSubStrategy = ref(null);
const emit = defineEmits(['update:strategy']);

const getSubscribedStrategy = async () => {
    isLoading.value = true;

    try {
        const response = await axios.get('/investment/get_subscribed_strategy');
        subscribedStrategy.value = response.data.subscribedStrategy || [];
      
        if (subscribedStrategy.value.length > 0) {
            selectedSubStrategy.value = subscribedStrategy.value[0];
          
            emit('update:strategy', selectedSubStrategy.value);
        }
    } catch (error) {
        console.error('Error getting subscribedStrategy:', error);
    } finally {
        isLoading.value = false;
    }
};

getSubscribedStrategy();

watch(selectedSubStrategy, (newVal) => {
    if (newVal) {
        emit('update:strategy', newVal);
    }
});

const dummy = ref([
    {
        master_name: 'STR0000001'
    },
      {
        master_name: 'STR0000001'
    },
      {
        master_name: 'STR0000001'
    },
      {
        master_name: 'STR0000001'
    },
       {
        master_name: 'STR0000001'
    },
      {
        master_name: 'STR0000001'
    },
       {
        master_name: 'STR0000001'
    },
       {
        master_name: 'STR0000001'
    },
      {
        master_name: 'STR0000001'
    },
])

</script>

<template>
    <div v-if="isLoading" class="w-full flex flex-row gap-3">
        <Skeleton
            v-for="index in 2"
            :key="index"
            class="flex-shrink-0"
            height="2.5rem"
            width="10rem"
            borderRadius="2rem"
        />
    </div>

    <SelectChipGroup
        v-else
        v-model="selectedSubStrategy"
        :items="subscribedStrategy"
        value-key="id"
        class="flex-shrink-0 w-full sm:max-w-[300px]"
    >
        <template #option="{ item }">
            {{ item.master_name }}
        </template>
    </SelectChipGroup>
    <!-- <div
        v-else
        class="flex gap-3 overflow-x-auto sm:max-w-[300px]"
        style="min-width: 300px;"
    >
        <SelectChipGroup
            v-model="selectedSubStrategy"
            :items="subscribedStrategy"
            value-key="id"
            class="flex-shrink-0 min-w-[300px]"
        >
            <template #option="{ item }">
                {{ item.master_name }}
            </template>
        </SelectChipGroup>
    </div> -->
</template>
