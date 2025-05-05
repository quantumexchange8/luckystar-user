<script setup>
import { Card, Tag, Button } from 'primevue';
import IndividualAction from './IndividualAction.vue';
import Transfer from '../Partial/Transfer.vue';
</script>

<template>
    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 w-full gap-3 md:gap-5">
        <Card class="w-full">
            <template #content>
                <div class="flex flex-col gap-5 w-full self-stretch">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 self-stretch">
                            <div class="self-stretch flex items-center gap-4 truncate text-sm text-surface-600 dark:text-surface-500">
                                <span class="font-bold text-lg">
                                    #12345
                                </span>
                                <Tag 
                                    severity="success"
                                    value="Standard Account"
                                />
                            </div>
    
                        </div>
                        <IndividualAction />
                    </div>
            
                    <div class="grid grid-cols-2 gap-2 w-full">
                        <div class="flex flex-row gap-1 items-center self-stretch">
                            <span class="w-20">Balance:</span>
                            <span class="font-medium">$123</span>
                        </div>

                        <div class="flex flex-row gap-1 items-center self-stretch">
                            <span class="w-20">Equity:</span>
                            <span class="font-medium">$456</span>
                        </div>

                        <div class="flex flex-row gap-1 items-center self-stretch">
                            <span class="w-20">Credit:</span>
                            <span class="font-medium">$78</span>
                        </div>

                        <div class="flex flex-row gap-1 items-center self-stretch">
                            <span class="w-20">Leverage:</span>
                            <span class="font-medium">1:1</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 w-full">
                        <Button
                            class="w-full dark:text-white text-surface bg-surface-0 dark:bg-surface-800 border-surface-300 dark:border-surface-700 enabled:hover:bg-white dark:hover:bg-surface-800 enabled:hover:border-surface-500 dark:hover:border-surface-600"
                        >
                            Deposit
                        </Button>

                       <Transfer 
                            
                       />                        
                    </div>
                </div>
            </template>
        </Card>

        <Card class="w-full">
            <template #content>
                <div class="flex flex-col gap-5 w-full self-stretch">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 self-stretch">
                            <div class="self-stretch flex items-center gap-4 truncate text-sm text-surface-600 dark:text-surface-500">
                                <span class="font-bold text-lg">
                                    #12345
                                </span>
                                <Tag 
                                    severity="success"
                                    value="Standard Account"
                                />
                            </div>
    
                        </div>
                        <IndividualAction />
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 w-full">
                        <div class="flex flex-row gap-1 items-center self-stretch">
                            <span class="w-20">Balance:</span>
                            <span class="font-medium">$123</span>
                        </div>

                        <div class="flex flex-row gap-1 items-center self-stretch">
                            <span class="w-20">Equity:</span>
                            <span class="font-medium">$456</span>
                        </div>

                        <div class="flex flex-row gap-1 items-center self-stretch">
                            <span class="w-20">Credit:</span>
                            <span class="font-medium">$78</span>
                        </div>

                        <div class="flex flex-row gap-1 items-center self-stretch">
                            <span class="w-20">Leverage:</span>
                            <span class="font-medium">1:1</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 w-full">
                        <Button
                            class="w-full dark:text-white text-surface bg-surface-0 dark:bg-surface-800 border-surface-300 dark:border-surface-700 enabled:hover:bg-white dark:hover:bg-surface-800 enabled:hover:border-surface-500 dark:hover:border-surface-600"
                        >
                            Deposit
                        </Button>

                       <Transfer 
                            
                       />
                    </div>
                </div>
            </template>
        </Card>
    </div>
</template>