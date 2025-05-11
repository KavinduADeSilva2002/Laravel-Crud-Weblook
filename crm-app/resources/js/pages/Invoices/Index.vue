<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">Create Invoice</h2>
    </template>

    <div class="max-w-xl mx-auto mt-8 p-6 bg-white shadow rounded">
      <form @submit.prevent="submit">
        <div class="mb-4">
          <label class="block mb-1 font-medium">Customer</label>
          <select v-model="form.customer_id" class="w-full border rounded p-2" required>
            <option disabled value="">Select Customer ID</option>
            <option v-for="customer in customers" :key="customer.id" :value="customer.id">
              {{ customer.id }} - {{ customer.full_name }}
            </option>
          </select>
        </div>

        <div class="mb-4">
          <label class="block mb-1 font-medium">Name</label>
          <input v-model="form.name" type="text" class="w-full border rounded p-2" required />
        </div>

        <div class="mb-4">
          <label class="block mb-1 font-medium">Email</label>
          <input v-model="form.email" type="email" class="w-full border rounded p-2" required />
        </div>

        <div class="mb-4">
          <label class="block mb-1 font-medium">Total Payment</label>
          <input v-model="form.total_payment" type="number" step="0.01" class="w-full border rounded p-2" required />
        </div>

        <div class="mb-4">
          <label class="block mb-1 font-medium">Due Date</label>
          <input v-model="form.due_date" type="date" class="w-full border rounded p-2" required />
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Save Invoice
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
  name: '',
  email: '',
  total_payment: '',
  due_date: '',
})

const submit = () => {
  form.post('/invoices')
}
</script>
