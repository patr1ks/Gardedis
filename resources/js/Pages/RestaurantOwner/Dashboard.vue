<script setup>
import RestaurantLayout from './Components/RestaurantLayout.vue'
import axios from 'axios'
import { ref, onMounted } from 'vue'

import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  ArcElement,
  CategoryScale,
  LinearScale
} from 'chart.js'
import { Bar, Pie } from 'vue-chartjs'

ChartJS.register(Title, Tooltip, Legend, BarElement, ArcElement, CategoryScale, LinearScale)

const stats = ref({
  restaurant_name: '',
  is_published: false,
  total_menu_items: 0,
  total_events: 0,
  total_payments: 0,
  has_layout: false,
  weekly_revenue: {},
  reservation_statuses: {}
})

const barChartData = ref(null)
const pieChartData = ref(null)

const chartOptions = {
  responsive: true,
  plugins: {
    legend: {
      labels: {
        color: '#ccc'
      }
    }
  },
  scales: {
    x: {
      ticks: { color: '#ccc' }
    },
    y: {
      ticks: { color: '#ccc' }
    }
  }
}

onMounted(async () => {
  try {
    const response = await axios.get('/restaurant-owner/dashboard/data')
    stats.value = response.data

    // Weekly Revenue Chart
    barChartData.value = {
      labels: Object.keys(stats.value.weekly_revenue).map(week => `Week ${week}`),
      datasets: [
        {
          label: 'Weekly Revenue (€)',
          data: Object.values(stats.value.weekly_revenue),
          backgroundColor: '#3b82f6'
        }
      ]
    }

    // Reservation Status Pie Chart
    pieChartData.value = {
      labels: Object.keys(stats.value.reservation_statuses),
      datasets: [
        {
          label: 'Reservation Statuses',
          data: Object.values(stats.value.reservation_statuses),
          backgroundColor: ['#10b981', '#f59e0b', '#ef4444']
        }
      ]
    }
  } catch (error) {
    console.error('Failed to load dashboard data', error)
  }
})
</script>

<template>
  <RestaurantLayout>
    <div class="p-6 space-y-6">
      <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
        Welcome, {{ stats.restaurant_name || 'Restaurant Owner' }}
      </h1>

      <!-- Summary Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow">
          <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Published</h3>
          <p class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ stats.is_published == 0 ? 'Yes' : 'No' }}
          </p>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow">
          <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Menu Items</h3>
          <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total_menu_items }}</p>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow">
          <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Events</h3>
          <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total_events }}</p>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow">
          <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Revenue</h3>
          <p class="text-2xl font-bold text-gray-900 dark:text-white">€{{ stats.total_payments }}</p>
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow">
          <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Layout Created</h3>
          <p class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ stats.has_layout ? 'Yes' : 'No' }}
          </p>
        </div>
      </div>

      <!-- Charts -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow">
          <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Weekly Revenue</h2>
          <Bar v-if="barChartData" :data="barChartData" :options="chartOptions" />
        </div>
        <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow">
          <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Reservation Statuses</h2>
          <Pie v-if="pieChartData" :data="pieChartData" :options="chartOptions" />
        </div>
      </div>
    </div>
  </RestaurantLayout>
</template>