<template>
  <div class="min-h-screen bg-gray-100 py-6 flex flex-col justify-center sm:py-12">
    <div class="relative py-3 sm:max-w-xl sm:mx-auto">
      <div class="relative px-4 py-10 bg-white mx-8 md:mx-0 shadow rounded-3xl sm:p-10">
        <div class="max-w-md mx-auto">
          <div class="divide-y divide-gray-200">
            <div class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
              <div class="flex justify-center">
                <div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
                  <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
              </div>
              <div class="text-center">
                <h2 class="text-2xl font-bold text-green-600">Payment Successful!</h2>
                <p class="mt-2 text-gray-600">Thank you for your payment</p>
              </div>
              <div class="bg-gray-50 p-4 rounded-lg mt-6">
                <div class="grid grid-cols-2 gap-4">
                  <div class="text-sm text-gray-600">Invoice ID:</div>
                  <div class="text-sm font-medium">#{{ invoice.id }}</div>
                  
                  <div class="text-sm text-gray-600">Amount Paid:</div>
                  <div class="text-sm font-medium">${{ formatAmount(invoice.total_payment) }}</div>
                  
                  <div class="text-sm text-gray-600">Status:</div>
                  <div class="text-sm font-medium text-green-600">{{ invoice.status }}</div>
                  
                  <div class="text-sm text-gray-600">Date:</div>
                  <div class="text-sm font-medium">{{ formatDate(transaction.created_at) }}</div>
                </div>
              </div>
              <div class="pt-6 text-center">
                <Link :href="route('dashboard')" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                  Return to Dashboard
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { defineProps } from 'vue'

const props = defineProps({
  invoice: Object,
  transaction: Object
})

const formatAmount = (amount) => {
  return Number(amount).toFixed(2)
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>