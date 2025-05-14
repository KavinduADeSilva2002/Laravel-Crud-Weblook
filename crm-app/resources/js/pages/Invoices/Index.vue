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
                <button @click="editInvoice(invoice)" class="text-blue-600 hover:text-blue-800">
                  Edit
                </button>
                <button @click="sendInvoiceEmail(invoice.id)" class="text-green-600 hover:text-green-800">
                  Send Email
                </button>
                <button @click="deleteInvoice(invoice.id)" class="text-red-600 hover:text-red-800">
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
