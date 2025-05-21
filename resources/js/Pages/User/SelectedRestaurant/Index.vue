<script setup>
import NoHeroLayout from '../Layouts/NoHeroLayout.vue'
import { ref, onMounted, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import axios from 'axios'
import { defineProps, watch } from 'vue'

const props = defineProps({
  restaurant: Object,
  layout: Object
})

const currentIndex = ref(0)
const layoutCanvas = ref(null)
const hoveredTableIndex = ref(null)
const selectedTableIndex = ref(null)

const layoutCanvasWidth = 800
const layoutCanvasHeight = 600
const scale = 1

// New date logic with Riga timezone
const rigaNow = new Date().toLocaleString('en-CA', {
  timeZone: 'Europe/Riga',
  year: 'numeric',
  month: '2-digit',
  day: '2-digit',
})
const today = rigaNow.split(',')[0]
const selectedDate = ref(today)


const selectedTime = ref('')
const timeSlots = []
for (let hour = 9; hour < 22; hour++) {
  const start = `${hour.toString().padStart(2, '0')}:00`
  const end = `${(hour + 1).toString().padStart(2, '0')}:00`
  timeSlots.push(`${start} - ${end}`)
}

const selectedTableLabel = computed(() => {
  return selectedTableIndex.value !== null
    ? `Selected table: ${selectedTableIndex.value + 1}`
    : 'No table selected'
})

const timeSlotStates = computed(() => {
  const states = {}
  for (const time of timeSlots) {
    states[time] = reservedTimes.value.includes(time) ? 'reserved' : 'available'
  }
  return states
})

const reserveTable = async () => {
  if (selectedTableIndex.value === null || !selectedTime.value || !selectedDate.value) {
    Swal.fire({
      icon: 'warning',
      title: 'Please select a table, date and time.',
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 2000,
    })
    return
  }

  const selectedSeats = props.layout.tables[selectedTableIndex.value]?.seats || 1

  try {
    const response = await axios.post('/reservations/store', {
      restaurant_id: props.restaurant.id,
      table_number: selectedTableIndex.value + 1,
      date: selectedDate.value,
      time: selectedTime.value,
      price: props.restaurant.price,
      seats: selectedSeats, // ✅ added
    })

    const reservationId = response.data.reservation_id
    window.location.href = `/reservations/pay/${reservationId}`

  } catch (error) {
    if (error.response && error.response.status === 422) {
      console.error('Validation errors:', error.response.data.errors)

      const messages = Object.values(error.response.data.errors)
        .flat()
        .join('\n')

      Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        text: messages,
      })
    } else {
      console.error(error)
      Swal.fire({
        toast: true,
        icon: 'error',
        position: 'top-end',
        title: 'Reservation failed!',
      })
    }
  }
}

const tableImage = new Image()
tableImage.src =
  'data:image/svg+xml;charset=utf-8,' +
  encodeURIComponent(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128"><path fill="black" d="M101.2 47.5H28.9v10.3h8.3v35.1h4.1V57.8h45.4v35.1h4.1V57.8h10.3V47.5zm-95-12.4H0v57.8h6.2V80.5h19.4l1.3 12.4H31V66.1H6.2v-31zm18.5 37.2.6 6.2H6.2v-6.2h18.5zm97.1-37.2v31H97v26.8h4.1l1.3-12.4h19.4v12.4h6.2V35.1h-6.2zm0 43.4h-19.2l.6-6.2h18.5v6.2z"/></svg>`)

const getMousePos = (event) => {
  const rect = layoutCanvas.value.getBoundingClientRect()
  return {
    x: (event.clientX - rect.left) / scale,
    y: (event.clientY - rect.top) / scale
  }
}

const drawLayout = () => {
  const ctx = layoutCanvas.value.getContext('2d')
  ctx.clearRect(0, 0, layoutCanvas.value.width, layoutCanvas.value.height)
  ctx.save()
  ctx.scale(scale, scale)

  ctx.lineWidth = 3
  ctx.lineCap = 'round'
  ctx.lineJoin = 'round'

  for (const wall of props.layout.walls || []) {
    ctx.strokeStyle = '#333'
    ctx.beginPath()
    ctx.moveTo(wall.x1, wall.y1)
    ctx.lineTo(wall.x2, wall.y2)
    ctx.stroke()
  }

  for (const [i, table] of (props.layout.tables || []).entries()) {
    const size = 60
    ctx.drawImage(tableImage, table.x - size / 2, table.y - size / 2, size, size)

    let textColor = 'black'
    if (selectedTableIndex.value === i) textColor = '#065F46'
    else if (hoveredTableIndex.value === i) textColor = '#00A86B'

    ctx.fillStyle = textColor
    ctx.font = '14px sans-serif'
    ctx.textAlign = 'center'
    ctx.fillText(`Table ${i + 1}`, table.x, table.y - size / 2 - 10)
    ctx.fillText(`${table.seats} seats`, table.x, table.y + size / 2 + 15)
  }

  ctx.restore()
}

const handleMouseMove = (event) => {
  const { x, y } = getMousePos(event)
  hoveredTableIndex.value = null
  let hovering = false

  for (const [i, table] of props.layout.tables.entries()) {
    if (Math.hypot(table.x - x, table.y - y) < 30) {
      hoveredTableIndex.value = i
      hovering = true
      break
    }
  }

  layoutCanvas.value.style.cursor = hovering ? 'pointer' : 'default'
  drawLayout()
}

const handleClick = (event) => {
  const { x, y } = getMousePos(event)
  for (const [i, table] of props.layout.tables.entries()) {
    if (Math.hypot(table.x - x, table.y - y) < 30) {
      selectedTableIndex.value = selectedTableIndex.value === i ? null : i
      drawLayout()
      return
    }
  }
}

const nextImage = () => {
  currentIndex.value = (currentIndex.value + 1) % props.restaurant.restaurant_images.length
}

const prevImage = () => {
  currentIndex.value = (currentIndex.value - 1 + props.restaurant.restaurant_images.length) % props.restaurant.restaurant_images.length
}

onMounted(() => {
  if (!layoutCanvas.value || !props.layout) return

  layoutCanvas.value.addEventListener('mousemove', handleMouseMove)
  layoutCanvas.value.addEventListener('click', handleClick)

  tableImage.onload = drawLayout
  if (tableImage.complete) drawLayout()
})

const reservedTimes = ref([])

watch([selectedTableIndex, selectedDate], async () => {
  if (selectedTableIndex.value !== null && selectedDate.value) {
    try {
      const response = await axios.post('/api/table-reservations', {
        restaurant_id: props.restaurant.id,
        table_number: selectedTableIndex.value + 1,
        date: selectedDate.value
      })
      reservedTimes.value = response.data
    } catch (error) {
      reservedTimes.value = []
      console.error('Failed to load reserved times', error)
    }
  } else {
    reservedTimes.value = []
  }
})
</script>

<template>
  <NoHeroLayout>
    <div class="bg-white">
      <div class="pt-6">
<!-- Image Gallery -->
<div v-if="restaurant.restaurant_images.length" class="w-full text-center">
  <div class="relative h-56 md:h-96">
    <div
      v-for="(image, index) in restaurant.restaurant_images"
      :key="index"
      :class="[ 
        'absolute inset-0 transition-opacity duration-700', 
        index === currentIndex ? 'opacity-100 z-10' : 'opacity-0 z-0' 
      ]"
    >
      <el-image
        style="width: 100%; height: 100%; object-fit: contain"
        :src="`/${image.image}`"
        :preview-src-list="restaurant.restaurant_images.map(img => `/${img.image}`)"
        :initial-index="index"
        fit="contain"
      />
    </div>
  </div>

  <!-- Prev/Next Buttons -->
  <div class="flex justify-center pt-4 space-x-4">
    <button
      @click="prevImage"
      class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 text-gray-600 hover:bg-blue-600 hover:text-white transition"
      aria-label="Previous"
    >
      &lt;
    </button>
    <button
      @click="nextImage"
      class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 text-gray-600 hover:bg-blue-600 hover:text-white transition"
      aria-label="Next"
    >
      &gt;
    </button>
  </div>
</div>


        <!-- Restaurant Info -->
        <div class="mx-auto max-w-7xl px-4 pt-10 pb-16 sm:px-6 lg:px-8 lg:pt-16 lg:pb-24">
          <div class="grid lg:grid-cols-3 gap-x-8">
            <!-- Left: Info and Canvas -->
            <div class="lg:col-span-2 border-r pr-8">
              <h1 class="text-3xl font-bold text-gray-900">{{ restaurant.title }}</h1>
              <p class="mt-4 text-gray-700">{{ restaurant.description }}</p>

              <div v-if="layout && (layout.tables?.length || layout.walls?.length)" class="mt-10">
                <h3 class="text-lg font-semibold mb-4 text-center">Layout Preview</h3>
                <div class="w-full border rounded shadow bg-white overflow-x-auto">
                  <canvas
                    ref="layoutCanvas"
                    :width="layoutCanvasWidth"
                    :height="layoutCanvasHeight"
                    class="block max-w-full"
                  ></canvas>
                </div>
              </div>
                          <!-- Menu Section -->
            <div v-if="restaurant.menus?.length" class="mt-12">
              <h3 class="text-lg font-semibold mb-4 text-center">Menu</h3>
              <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div
                  v-for="(item, index) in restaurant.menus"
                  :key="index"
                  class="border rounded-lg p-4 shadow hover:shadow-md transition"
                >
                  <div class="flex justify-between items-start mb-1">
                    <h4 class="text-lg font-bold text-gray-800">{{ item.name }}</h4>
                    <span v-if="item.isSpecial" class="text-sm text-red-600 font-semibold">Special offer</span>
                  </div>
                  <p class="text-sm text-gray-600 mb-2">{{ item.description }}</p>
                  <p class="text-base font-semibold text-indigo-600">{{ item.price }} €</p>
                </div>
              </div>
            </div>
            </div>

            <!-- Right: Time and Reserve -->
            <div class="lg:col-span-1 mt-6">
              <p class="text-3xl text-gray-900">{{ restaurant.price }} €</p>

              <div class="mt-4">
                <p class="text-sm font-medium text-gray-700 mb-2">{{ selectedTableLabel }}</p>
                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Choose Date</label>
                <input
                  type="date"
                  id="date"
                  v-model="selectedDate"
                  :min="today"
                  :disabled="selectedTableIndex === null"
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed"
                />
              </div>

              <div class="mt-4">
                <label for="timeSelect" class="block text-sm font-medium text-gray-700 mb-2">Choose Time</label>
                <select
                  id="timeSelect"
                  v-model="selectedTime"
                  :disabled="selectedTableIndex === null"
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed"
                >
                  <option value="" disabled>Select time</option>
                  <option v-for="time in timeSlots" :key="time" :value="time" :class="{'text-green-600': timeSlotStates[time] === 'available','text-red-600': timeSlotStates[time] === 'reserved'}":disabled="timeSlotStates[time] === 'reserved'">
                    {{ time }}
                  </option>
                </select>
              </div>

              <button
                @click="reserveTable"
                :disabled="selectedTableIndex === null || !selectedTime"
                class="mt-6 w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded disabled:bg-gray-300 disabled:cursor-not-allowed"
              >
                Reserve
              </button>
            </div>

          </div>
        </div>
      </div>
    </div>
  </NoHeroLayout>
</template>