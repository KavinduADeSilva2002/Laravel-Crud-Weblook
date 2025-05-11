<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">Proposal Management</h2>
    </template>

    <!-- Form -->
    <div v-if="showForm" class="max-w-2xl mx-auto mt-8 p-6 bg-white shadow rounded">
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
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>

        <div class="flex gap-2">
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            {{ editingProposal ? 'Update Proposal' : 'Create Proposal' }}
          </button>
          <button v-if="editingProposal" @click="cancelEdit" type="button" class="bg-gray-500 text-white px-4 py-2 rounded">
            Cancel
          </button>
        </div>
      </form>
    </div>

    <!-- Table -->
    <div class="max-w-6xl mx-auto mt-10">
      <h3 class="text-lg font-semibold mb-4">All Proposals</h3>
      <table class="w-full border-collapse table-auto">
        <thead class="bg-gray-200">
          <tr>
            <th class="border px-4 py-2">Customer</th>
            <th class="border px-4 py-2">Title</th>
            <th class="border px-4 py-2">Description</th>
            <th class="border px-4 py-2">Amount</th>
            <th class="border px-4 py-2">Status</th>
            <th class="border px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="proposal in proposals" :key="proposal.id" class="hover:bg-gray-100">
            <td class="border px-4 py-2">{{ proposal.customer_id }}</td>
            <td class="border px-4 py-2">{{ proposal.title }}</td>
            <td class="border px-4 py-2">{{ proposal.description }}</td>
            <td class="border px-4 py-2">{{ proposal.amount }}</td>
            <td class="border px-4 py-2 capitalize">{{ proposal.status }}</td>
            <td class="border px-4 py-2 space-x-2">
              <button @click="editProposal(proposal)" class="bg-yellow-500 text-white px-3 py-1 rounded">Update</button>
              <button @click="deleteProposal(proposal.id)" class="bg-red-600 text-white px-3 py-1 rounded">Delete</button>
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

// Props from server
const customers = usePage().props.customers
const proposals = ref(usePage().props.proposals || [])

// Form & state
const editingProposal = ref(null)
const showForm = ref(true)

const form = useForm({
  customer_id: '',
  title: '',
  description: '',
  amount: '',
  status: 'pending',
})

const submitForm = async () => {
  try {
    if (editingProposal.value) {
      await axios.put(`/proposals/${editingProposal.value.id}`, form.data())
    } else {
      await axios.post('/proposals', form)
    }
    form.reset('customer_id', 'title', 'description', 'amount', 'status')
    editingProposal.value = null
    showForm.value = true
    window.location.reload()
  } catch (err) {
    console.error('Error saving proposal:', err)
  }
}

const editProposal = (proposal) => {
  editingProposal.value = proposal
  form.customer_id = proposal.customer_id
  form.title = proposal.title
  form.description = proposal.description
  form.amount = proposal.amount
  form.status = proposal.status
  showForm.value = true
}

const deleteProposal = async (id) => {
  if (confirm('Are you sure you want to delete this proposal?')) {
    try {
      await axios.delete(`/proposals/${id}`)
      window.location.reload()
    } catch (err) {
      console.error('Error deleting proposal:', err)
    }
  }
}

const cancelEdit = () => {
  editingProposal.value = null
  form.reset('customer_id', 'title', 'description', 'amount', 'status')
}
</script>
