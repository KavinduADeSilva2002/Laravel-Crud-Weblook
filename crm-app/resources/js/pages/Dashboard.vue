<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { usePage } from '@inertiajs/vue3'

const { customerCount, invoiceCount } = usePage().props
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Customer Count Card -->
          <div class="bg-blue-500 text-white shadow rounded-lg p-6">
            <div class="text-sm font-medium">Total Customers</div>
            <div class="mt-2 text-3xl font-bold">{{ customerCount }}</div>
          </div>

          <!-- Invoice Count Card -->
          <div class="bg-lime-400 text-white shadow rounded-lg p-6">
            <div class="text-sm font-medium">Total Invoices</div>
            <div class="mt-2 text-3xl font-bold">{{ invoiceCount }}</div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
