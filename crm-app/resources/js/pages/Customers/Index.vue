<template>
  <div class="p-6 max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-2xl font-bold">Customer Management</h2>
      <button @click="openAddForm" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Add Customer
      </button>
    </div>

    <!-- Add/Edit Form -->
    <div v-if="showForm" class="mb-6 bg-gray-100 p-4 rounded">
      <h3 class="text-lg font-semibold mb-2">{{ editingCustomer ? 'Edit Customer' : 'Add New Customer' }}</h3>
      <form @submit.prevent="submitForm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <input v-model="form.full_name" type="text" placeholder="Full Name" class="p-2 border rounded" required />
          <input v-model="form.email" type="email" placeholder="Email" class="p-2 border rounded" required />
        </div>
        <div class="flex gap-2">
          <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            {{ editingCustomer ? 'Update' : 'Save' }}
          </button>
          <button @click="cancelForm" type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            Cancel
          </button>
        </div>
      </form>
    </div>

    <!-- Customer Table -->
    <table class="w-full table-auto border-collapse">
      <thead>
        <tr class="bg-gray-200">
          <th class="border px-4 py-2">Full Name</th>
          <th class="border px-4 py-2">Email</th>
          <th class="border px-4 py-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(customer, index) in displayedCustomers" :key="customer.id" class="hover:bg-gray-100">
          <td class="border px-4 py-2">{{ customer.full_name }}</td>
          <td class="border px-4 py-2">{{ customer.email }}</td>
          <td class="border px-4 py-2">
            <button @click="editCustomer(customer)" class="bg-yellow-500 text-white px-3 py-1 rounded mr-2">
              Update
            </button>
            <button @click="deleteCustomer(customer.id)" class="bg-red-600 text-white px-3 py-1 rounded">
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <p v-if="customers.length === 0" class="mt-4 text-gray-500">No customers yet.</p>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'

const customers = ref(usePage().props.customers)
const showForm = ref(false)
const editingCustomer = ref(null)

const form = ref({
  full_name: '',
  email: '',
})

const apiBaseUrl = '/customers'

const displayedCustomers = computed(() => customers.value.slice(0, 5))

const openAddForm = () => {
  editingCustomer.value = null
  form.value = { full_name: '', email: '' }
  showForm.value = true
}

const submitForm = async () => {
  try {
    if (editingCustomer.value) {
      await axios.put(`${apiBaseUrl}/${editingCustomer.value.id}`, form.value)
    } else {
      await axios.post(apiBaseUrl, form.value)
    }
    window.location.reload()
  } catch (error) {
    console.error("Error saving customer:", error.response?.data || error.message)
  }
}

const editCustomer = (customer) => {
  editingCustomer.value = customer
  form.value = {
    full_name: customer.full_name,
    email: customer.email,
  }
  showForm.value = true
}

const deleteCustomer = async (id) => {
  if (confirm('Are you sure you want to delete this customer?')) {
    try {
      await axios.delete(`${apiBaseUrl}/${id}`)
      window.location.reload()
    } catch (error) {
      console.error('Error deleting customer:', error)
    }
  }
}

const cancelForm = () => {
  showForm.value = false
}
</script>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

export default {
  layout: AuthenticatedLayout,
}
</script>
