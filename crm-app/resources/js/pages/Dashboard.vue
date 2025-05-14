<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { usePage } from '@inertiajs/vue3'

// Get all counts from the controller
const { customerCount, proposalCount, invoiceCount, transactionCount } = usePage().props
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

        <!-- Title and Welcome Message -->
        <div class="mb-8 text-center">
          <h1 class="text-3xl font-bold text-gray-800">Weblook CRM Application</h1>
          <p class="mt-2 text-lg text-gray-600">
            You have successfully logged in to the Weblook CRM Application.
          </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <!-- Customer Count Card -->
          <div class="bg-blue-500 text-white shadow rounded-lg p-6">
            <div class="text-sm font-medium">Total Customers</div>
            <div class="mt-2 text-3xl font-bold">{{ customerCount }}</div>
          </div>

          <!-- Proposal Count Card -->
          <div class="bg-amber-600 text-white shadow rounded-lg p-6">
            <div class="text-sm font-medium">Total Proposals</div>
            <div class="mt-2 text-3xl font-bold">{{ proposalCount }}</div>
          </div>

          <!-- Invoice Count Card -->
          <div class="bg-lime-700 text-white shadow rounded-lg p-6">
            <div class="text-sm font-medium">Total Invoices</div>
            <div class="mt-2 text-3xl font-bold">{{ invoiceCount }}</div>
          </div>

          <!-- Transaction Count Card -->
          <div class="bg-purple-600 text-white shadow rounded-lg p-6">
            <div class="text-sm font-medium">Transactions</div>
            <div class="mt-2 text-3xl font-bold">{{ transactionCount }}</div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
