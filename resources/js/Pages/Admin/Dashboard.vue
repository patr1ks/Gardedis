<script setup>
import AdminLayout from './Components/AdminLayout.vue'
import axios from 'axios'
import { ref, onMounted } from 'vue'
import { Bar, Pie } from 'vue-chartjs'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
  ArcElement
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement)

const metrics = ref({
  total_users: 0,
  total_restaurants: 0,
  total_revenue: 0,
  recent_users: [],
  revenue_per_week: {},
  user_roles: {}
})

const chartOptions = {
  responsive: true,
  plugins: {
    legend: {
      labels: { color: '#ccc' }
    }
  },
  scales: {
    x: { ticks: { color: '#ccc' } },
    y: { ticks: { color: '#ccc' } }
  }
}

const barChartData = ref(null)
const pieChartData = ref(null)

onMounted(async () => {
  try {
    const response = await axios.get('/admin/dashboard/data')
    metrics.value = response.data

    barChartData.value = {
      labels: Object.keys(metrics.value.revenue_per_week),
      datasets: [
        {
          label: 'Revenue (€)',
          data: Object.values(metrics.value.revenue_per_week),
          backgroundColor: '#3b82f6'
        }
      ]
    }

    pieChartData.value = {
      labels: Object.keys(metrics.value.user_roles),
      datasets: [
        {
          label: 'User Roles',
          data: Object.values(metrics.value.user_roles),
          backgroundColor: ['#2563eb', '#10b981', '#f59e0b']
        }
      ]
    }
  } catch (error) {
    console.error('Failed to load dashboard data', error)
  }
})
</script>

<template>
  <AdminLayout>
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</h3>
        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ metrics.total_users }}</p>
      </div>
      <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Restaurants</h3>
        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ metrics.total_restaurants }}</p>
      </div>
      <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Revenue</h3>
        <p class="text-3xl font-bold text-gray-900 dark:text-white">€{{ metrics.total_revenue }}</p>
      </div>
      <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">New Signups</h3>
        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ metrics.recent_users.length }}</p>
      </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
      <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Weekly Revenue</h2>
        <Bar v-if="barChartData" :data="barChartData" :options="chartOptions" />
      </div>
      <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">User Roles</h2>
        <Pie v-if="pieChartData" :data="pieChartData" :options="chartOptions" />
      </div>
    </div>

    <!-- Recent Users -->
    <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
      <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Recent Registrations</h2>
      <ul class="space-y-2">
        <li
          v-for="(user, index) in metrics.recent_users"
          :key="index"
          class="text-gray-700 dark:text-gray-300"
        >
          {{ user.email }} — Joined {{ new Date(user.created_at).toLocaleDateString() }}
        </li>
      </ul>
    </div>
  </AdminLayout>
</template>