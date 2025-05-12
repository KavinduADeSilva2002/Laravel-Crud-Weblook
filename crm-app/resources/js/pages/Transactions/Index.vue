<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">All Transactions</h2>
    </template>

    <div class="max-w-7xl mx-auto mt-8">
      <table class="w-full border-collapse">
        <thead class="bg-gray-200">
          <tr>
            <th class="border px-4 py-2">Invoice #</th>
            <th class="border px-4 py-2">Customer ID</th>
            <th class="border px-4 py-2">Amount</th>
            <th class="border px-4 py-2">Status</th>
            <th class="border px-4 py-2">Date</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="transaction in transactions" :key="transaction.id" class="hover:bg-gray-100">
            <td class="border px-4 py-2">{{ transaction.invoice_number }}</td>
            <td class="border px-4 py-2">{{ transaction.customer_id }}</td>
            <td class="border px-4 py-2">${{ transaction.amount.toFixed(2) }}</td>
            <td class="border px-4 py-2 text-green-700 font-semibold">{{ transaction.status }}</td>
            <td class="border px-4 py-2">{{ new Date(transaction.created_at).toLocaleString() }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { usePage } from '@inertiajs/vue3'

const transactions = usePage().props.transactions
</script>
