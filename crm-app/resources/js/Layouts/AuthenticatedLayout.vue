<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
  <div class="min-h-screen bg-white text-black">
    <!-- Navigation Bar -->
    <nav class="bg-white border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <!-- Left side: Logo and Nav links -->
          <div class="flex">
            <!-- Logo -->
            <div class="shrink-0 flex items-center">
              <Link :href="route('dashboard')">
                <ApplicationLogo class="block h-9 w-auto fill-current text-black" />
              </Link>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
              <NavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</NavLink>
              <NavLink :href="route('customers.index')" :active="route().current('customers.*')">Customers</NavLink>
              <NavLink :href="route('proposals.index')" :active="route().current('proposals.*')">Proposals</NavLink>
              <NavLink :href="route('invoices.index')" :active="route().current('invoices.*')">Invoices</NavLink>
              <NavLink :href="route('transactions.index')" :active="route().current('transactions.*')">Transactions</NavLink>
            </div>
          </div>

          <!-- Right side: User dropdown -->
          <div class="hidden sm:flex sm:items-center sm:ms-6">
            <div class="ms-3 relative">
              <Dropdown align="right" width="48">
                <template #trigger>
                  <span class="inline-flex rounded-md">
                    <button
                      type="button"
                      class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-black bg-white hover:text-black focus:outline-none transition ease-in-out duration-150"
                    >
                      {{ $page.props.auth.user.name }}
                      <svg class="ms-2 -me-0.5 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path
                          fill-rule="evenodd"
                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                          clip-rule="evenodd"
                        />
                      </svg>
                    </button>
                  </span>
                </template>

                <template #content>
                  <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                  <DropdownLink :href="route('logout')" method="post" as="button"> Log Out </DropdownLink>
                </template>
              </Dropdown>
            </div>
          </div>

          <!-- Hamburger button -->
          <div class="-me-2 flex items-center sm:hidden">
            <button
              @click="showingNavigationDropdown = !showingNavigationDropdown"
              class="inline-flex items-center justify-center p-2 rounded-md text-black hover:text-black hover:bg-gray-100 focus:outline-none transition"
            >
              <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path
                  :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                />
                <path
                  :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Navigation Menu -->
      <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
          <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</ResponsiveNavLink>
          <ResponsiveNavLink :href="route('customers.index')" :active="route().current('customers.*')">Customers</ResponsiveNavLink>
          <ResponsiveNavLink :href="route('proposals.index')" :active="route().current('proposals.*')">Proposals</ResponsiveNavLink>
          <ResponsiveNavLink :href="route('invoices.index')" :active="route().current('invoices.*')">Invoices</ResponsiveNavLink>
          <ResponsiveNavLink :href="route('transactions.index')" :active="route().current('transactions.*')">Transactions</ResponsiveNavLink>
        </div>

        <!-- Mobile Settings -->
        <div class="pt-4 pb-1 border-t border-gray-200">
          <div class="px-4">
            <div class="font-medium text-base text-black">{{ $page.props.auth.user.name }}</div>
            <div class="font-medium text-sm text-gray-700">{{ $page.props.auth.user.email }}</div>
          </div>

          <div class="mt-3 space-y-1">
            <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
            <ResponsiveNavLink method="post" as="button" :href="route('logout')">Log Out</ResponsiveNavLink>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Page Content -->
    <main>
      <slot />
    </main>
  </div>
</template>
