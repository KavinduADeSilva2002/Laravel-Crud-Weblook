<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">Create Proposal</h2>
    </template>

    <div class="max-w-2xl mx-auto mt-8 p-6 bg-white shadow rounded">
      <form @submit.prevent="submit">
        <div class="mb-4">
          <label class="block font-medium mb-1">Customer</label>
          <select v-model="form.customer_id" class="w-full border p-2 rounded" required>
            <option disabled value="">Select a customer</option>
            <option v-for="customer in customers" :key="customer.id" :value="customer.id">
              {{ customer.full_name }} (ID: {{ customer.id }})
            </option>
          </select>
        </div>

        <div class="mb-4">
          <label class="block font-medium mb-1">Title</label>
          <input v-model="form.title" type="text" class="w-full border p-2 rounded" required />
        </div>

        <div class="mb-4">
          <label class="block font-medium mb-1">Description</label>
          <textarea v-model="form.description" class="w-full border p-2 rounded" required></textarea>
        </div>

        <div class="mb-4">
          <label class="block font-medium mb-1">Amount</label>
          <input v-model="form.amount" type="number" step="0.01" class="w-full border p-2 rounded" required />
        </div>

        <div class="mb-4">
          <label class="block font-medium mb-1">Status</label>
          <select v-model="form.status" class="w-full border p-2 rounded" required>
            <option value="Pending">Pending</option>
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>
          </select>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Save Proposal
        </button>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, usePage } from '@inertiajs/vue3'

const customers = usePage().props.customers

const form = useForm({
  customer_id: '',
  title: '',
  description: '',
  amount: '',
  status: 'Pending',
})

const submit = () => {
  form.post('/proposals')
}
</script>
