<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { usePage } from '@inertiajs/vue3'

const { customerCount } = usePage().props
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
          <div class="bg-white shadow rounded-lg p-6">
            <div class="text-sm font-medium text-gray-500">Total Customers</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">{{ customerCount }}</div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
