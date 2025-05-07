<script setup>
import { Card, Skeleton } from 'primevue';
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 w-full gap-3 md:gap-5">
        <Card>
            <template #content>
               
            </template>
        </Card>

        <Card>
            <template #content>
              
            </template>
        </Card>

        <Card class="col-span-1 md:col-span-2 lg:col-span-1">
            <template #content>
               
            </template>
        </Card>
    </div>
</template>