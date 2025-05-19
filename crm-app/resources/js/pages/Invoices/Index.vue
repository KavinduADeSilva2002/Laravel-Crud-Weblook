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
          <select v-model="form.customer_id" @change="updateCustomerDetails" class="w-full border p-2 rounded" required>
            <option value="">Select Customer</option>
            <option v-for="customer in customers" :key="customer.id" :value="customer.id">
              {{ customer.full_name }}
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
            <th class="p-3 text-left">Customer</th>
            <th class="p-3 text-left">Name</th>
            <th class="p-3 text-left">Email</th>
            <th class="p-3 text-left">Amount</th>
            <th class="p-3 text-left">Due Date</th>
            <th class="p-3 text-left">Status</th>
            <th class="p-3 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="invoice in invoices" :key="invoice.id" class="hover:bg-gray-100">
            <td class="p-3">{{ getCustomerName(invoice.customer_id) }}</td>
            <td class="p-3">{{ invoice.name }}</td>
            <td class="p-3">{{ invoice.email }}</td>
            <td class="p-3">${{ invoice.total_payment }}</td>
            <td class="p-3">{{ formatDate(invoice.due_date) }}</td>
            <td class="p-3">
              <span :class="getStatusClass(invoice.status || 'Pending')">
                {{ invoice.status || 'Pending' }}
              </span>
            </td>
            <td class="p-3">
              <div class="flex gap-2">
                <button 
                  @click="editInvoice(invoice)" 
                  class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                  Edit
                </button>
                <button 
                  @click="sendInvoiceEmail(invoice.id)" 
                  class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-sm flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                  Send Email
                </button>
                <button 
                  @click="deleteInvoice(invoice.id)" 
                  class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                  Delete
                </button>
              </div>
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

const updateCustomerDetails = () => {
  const selectedCustomer = customers.find(c => c.id === form.customer_id)
  if (selectedCustomer) {
    form.name = selectedCustomer.full_name
    form.email = selectedCustomer.email
  }
}

const getCustomerName = (customerId) => {
  const customer = customers.find(c => c.id === customerId)
  return customer ? customer.full_name : 'N/A'
}

const submitForm = async () => {
  try {
    if (editingInvoice.value) {
      await axios.put(`/invoices/${editingInvoice.value.id}`, form.data())
    } else {
      await axios.post('/invoices', form.data())
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

const sendInvoiceEmail = async (id) => {
  try {
    await axios.get(`/invoices/send-email/${id}`)
    alert('Invoice email sent successfully!')
  } catch (err) {
    alert('Failed to send email.')
    console.error('Email send error:', err)
  }
}

const cancelEdit = () => {
  editingInvoice.value = null
  form.reset()
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString()
}

const getStatusClass = (status) => {
  const baseClass = 'px-2 py-1 rounded-full text-xs font-semibold'
  const statusClasses = {
    'Pending': 'bg-yellow-100 text-yellow-800',
    'Paid': 'bg-green-100 text-green-800',
    'Overdue': 'bg-red-100 text-red-800'
  }
  return `${baseClass} ${statusClasses[status] || ''}`
}
</script>