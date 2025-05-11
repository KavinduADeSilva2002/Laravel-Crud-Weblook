<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">Invoice Management</h2>
    </template>

    <!-- Invoice Form -->
    <div class="max-w-2xl mx-auto mt-8 p-6 bg-white shadow rounded">
      <form @submit.prevent="submitForm">
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
          <label class="block font-medium mb-1">Name</label>
          <input v-model="form.name" type="text" class="w-full border p-2 rounded" required />
        </div>

        <div class="mb-4">
          <label class="block font-medium mb-1">Email</label>
          <input v-model="form.email" type="email" class="w-full border p-2 rounded" required />
        </div>

        <div class="mb-4">
          <label class="block font-medium mb-1">Total Payment</label>
          <input v-model="form.total_payment" type="number" step="0.01" class="w-full border p-2 rounded" required />
        </div>

        <div class="mb-4">
          <label class="block font-medium mb-1">Due Date</label>
          <input v-model="form.due_date" type="date" class="w-full border p-2 rounded" required />
        </div>

        <div class="flex gap-2">
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            {{ editingInvoice ? 'Update Invoice' : 'Create Invoice' }}
          </button>
          <button v-if="editingInvoice" @click="cancelEdit" type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            Cancel
          </button>
        </div>
      </form>
    </div>

    <!-- Invoice Table -->
    <div class="max-w-5xl mx-auto mt-10">
      <h3 class="text-lg font-semibold mb-4">All Invoices</h3>
      <table class="w-full border-collapse table-auto">
        <thead class="bg-gray-200">
          <tr>
            <th class="border px-4 py-2">Customer ID</th>
            <th class="border px-4 py-2">Name</th>
            <th class="border px-4 py-2">Email</th>
            <th class="border px-4 py-2">Total</th>
            <th class="border px-4 py-2">Due Date</th>
            <th class="border px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="invoice in invoices" :key="invoice.id" class="hover:bg-gray-100">
            <td class="border px-4 py-2">{{ invoice.customer_id }}</td>
            <td class="border px-4 py-2">{{ invoice.name }}</td>
            <td class="border px-4 py-2">{{ invoice.email }}</td>
            <td class="border px-4 py-2">{{ invoice.total_payment }}</td>
            <td class="border px-4 py-2">{{ invoice.due_date }}</td>
            <td class="border px-4 py-2 space-x-2">
              <button @click="editInvoice(invoice)" class="bg-yellow-500 text-white px-3 py-1 rounded">Update</button>
              <button @click="deleteInvoice(invoice.id)" class="bg-red-600 text-white px-3 py-1 rounded">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'
import axios from 'axios'

const customers = usePage().props.customers
const invoices = ref(usePage().props.invoices || [])

const editingInvoice = ref(null)

const form = useForm({
  customer_id: '',
  name: '',
  email: '',
  total_payment: '',
  due_date: ''
})

const submitForm = async () => {
  try {
    if (editingInvoice.value) {
      await axios.put(`/invoices/${editingInvoice.value.id}`, form.data())
    } else {
      await axios.post('/invoices', form)
    }
    window.location.reload()
  } catch (err) {
    console.error('Error saving invoice:', err.response?.data || err)
  }
}

const editInvoice = (invoice) => {
  editingInvoice.value = invoice
  form.customer_id = invoice.customer_id
  form.name = invoice.name
  form.email = invoice.email
  form.total_payment = invoice.total_payment
  form.due_date = invoice.due_date
}

const deleteInvoice = async (id) => {
  if (confirm('Are you sure you want to delete this invoice?')) {
    try {
      await axios.delete(`/invoices/${id}`)
      window.location.reload()
    } catch (err) {
      console.error('Error deleting invoice:', err)
    }
  }
}

const cancelEdit = () => {
  editingInvoice.value = null
  form.reset('customer_id', 'name', 'email', 'total_payment', 'due_date')
}
</script>
