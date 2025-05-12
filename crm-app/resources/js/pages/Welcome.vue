<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  laravelVersion: String,
  phpVersion: String,
});
</script>

<template>
  <Head title="Welcome" />
  <div class="min-h-screen bg-white dark:bg-gray-900 flex flex-col items-center justify-center text-gray-800 dark:text-gray-100">
    <div class="text-center">
      <h1 class="text-4xl font-bold mb-4 text-blue-700 dark:text-blue-400">Welcome to the CRM System</h1>
      <p class="text-lg mb-8 max-w-xl mx-auto">
        This is a customer relationship management tool built using Laravel {{ laravelVersion }} and PHP {{ phpVersion }}.
        Easily manage customers, proposals, invoices, and transactions.
      </p>

      <div class="flex justify-center space-x-4">
        <Link
          v-if="canLogin"
          :href="route('login')"
          class="px-6 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition"
        >
          Login
        </Link>
        <Link
          v-if="canRegister"
          :href="route('register')"
          class="px-6 py-2 bg-gray-300 text-black font-semibold rounded hover:bg-gray-400 transition dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600"
        >
          Register
        </Link>
      </div>
    </div>

    <footer class="mt-10 text-sm text-gray-500 dark:text-gray-400">
      Laravel v{{ laravelVersion }} · PHP v{{ phpVersion }}
    </footer>
  </div>
</template>
