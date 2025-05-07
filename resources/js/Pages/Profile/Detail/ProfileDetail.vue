<script setup>
import GeneralInformation from "@/Pages/Profile/Detail/Partials/GeneralInformation.vue";
import TradingBackground from "@/Pages/Profile/Detail/Partials/TradingBackground.vue";
import JobInformation from "@/Pages/Profile/Detail/Partials/JobInformation.vue";
import BeneficiaryInformation from "@/Pages/Profile/Detail/Partials/BeneficiaryInformation.vue";
import KycVerification from "@/Pages/Profile/Detail/Partials/KycVerification.vue";

defineProps({
    user: Object,
})
</script>

<template>
    <div class="flex flex-col md:flex-row gap-3 md:gap-5 items-start self-stretch w-full">
        <div class="flex flex-col gap-3 md:gap-5 items-center self-stretch w-full">
            <!-- General Information -->
            <GeneralInformation
                :user="user"
            />

            <!-- General Information -->
            <JobInformation
                :user="user"
            />

            <!-- Trading Background -->
            <TradingBackground
                :user="user"
            />
        </div>

        <div class="flex flex-col gap-3 md:gap-5 items-center self-stretch w-full">
            <!-- Trading Background -->
            <BeneficiaryInformation
                :user="user"
            />

            <!-- KYC Verification -->
            <KycVerification
                :user="user"
            />
        </div>
    </div>
</template>
