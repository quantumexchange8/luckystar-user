<script setup>
import { Card, Tag, Button } from 'primevue';
import Transfer from '../Partial/Transfer.vue';
import IndividualAction from './IndividualAction.vue';
</script>

<template>
    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 w-full gap-3 md:gap-5">
        <Card class="w-full">
            <template #content>
                <div class="flex gap-3 items-center self-stretch relative">
                   <div
                        class="absolute -left-2 w-2.5 rounded-full h-full"
                        :style="{'backgroundColor': '#FDBA74'}"
                    ></div>
                    <div class="flex flex-col gap-5 w-full self-stretch pl-4">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex flex-col items-center self-stretch">
                                <div class="flex items-center gap-2 self-stretch">
                                    <div class="self-stretch flex items-center gap-4 truncate text-sm text-surface-600 dark:text-surface-500">
                                        <span class="font-semibold text-lg">
                                            #12345
                                        </span>
                                        <Tag 
                                            severity="warn"
                                            value="Premium Account"
                                        />
                                    </div>
                                </div>
                                <div class="flex flex-row gap-2 items-center self-stretch text-xs">
                                    <span class="text-surface-500">{{ $t('public.name') }}:</span>
                                    <span class="font-medium">{{ 'test' }}</span>
                                </div>
                            </div>
                            <IndividualAction />
                        </div>
                
                        <div class="grid grid-cols-2 gap-2 w-full">
                            <div class="flex flex-row gap-1 items-center self-stretch text-xs">
                                <span class="text-surface-500 w-20">{{ $t('public.balance') }}:</span>
                                <span class="font-medium">$123</span>
                            </div>
    
                            <div class="flex flex-row gap-1 items-center self-stretch text-xs">
                                <span class="text-surface-500 w-20">{{ $t('public.equity') }}:</span>
                                <span class="font-medium">$456</span>
                            </div>
    
                            <div class="flex flex-row gap-1 items-center self-stretch text-xs">
                                <span class="text-surface-500 w-20">{{ $t('public.pamm') }}:</span>
                                <span class="font-medium">-</span>
                            </div>
    
                            <div class="flex flex-row gap-1 items-center self-stretch text-xs">
                                <span class="text-surface-500 w-20">{{ $t('public.mature_in') }}:</span>
                                <span class="font-medium">-</span>
                            </div>
                        </div>
                     
                        <div class="flex items-center gap-3 w-full">
                            <Button
                                type="button"
                                severity="secondary"
                                outlined
                                class="w-full"
                                size="small"
                            >
                                {{ $t('public.deposit') }}
                            </Button>
    
                            <Transfer 
                                
                           />
                        </div>
                    </div>
                </div>
            </template>
        </Card>
    </div>
</template>