<script setup>
import GeneralInformation from "@/Pages/Profile/Detail/GeneralInformation.vue";
import TradingBackground from "@/Pages/Profile/Detail/TradingBackground.vue";
import JobInformation from "@/Pages/Profile/Detail/JobInformation.vue";
import BeneficiaryInformation from "@/Pages/Profile/Detail/BeneficiaryInformation.vue";
import KycVerification from "@/Pages/Profile/Detail/KycVerification.vue";

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

            <!-- Trading Background -->
            <BeneficiaryInformation
                :user="user"
            />
        </div>

        <div class="flex flex-col gap-3 md:gap-5 items-center self-stretch w-full">
            <!-- KYC Verification -->
            <KycVerification
                :user="user"
            />
        </div>
    </div>
</template>
