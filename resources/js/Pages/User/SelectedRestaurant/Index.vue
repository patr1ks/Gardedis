<script setup>
import NoHeroLayout from '../Layouts/NoHeroLayout.vue'
import { ref, onMounted } from 'vue'

// Props
const props = defineProps({
  restaurant: Object,
  layout: Object
})

const currentIndex = ref(0)
const layoutCanvas = ref(null)
const hoveredTableIndex = ref(null)
const selectedTableIndex = ref(null)

const tableImage = new Image()
tableImage.src =
  'data:image/svg+xml;charset=utf-8,' +
  encodeURIComponent(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128"><path fill="black" d="M101.2 47.5H28.9v10.3h8.3v35.1h4.1V57.8h45.4v35.1h4.1V57.8h10.3V47.5zm-95-12.4H0v57.8h6.2V80.5h19.4l1.3 12.4H31V66.1H6.2v-31zm18.5 37.2.6 6.2H6.2v-6.2h18.5zm97.1-37.2v31H97v26.8h4.1l1.3-12.4h19.4v12.4h6.2V35.1h-6.2zm0 43.4h-19.2l.6-6.2h18.5v6.2z"/></svg>`)

const getMousePos = (event) => {
  const rect = layoutCanvas.value.getBoundingClientRect()
  return {
    x: event.clientX - rect.left,
    y: event.clientY - rect.top
  }
}

const drawLayout = () => {
  const ctx = layoutCanvas.value.getContext('2d')
  ctx.clearRect(0, 0, layoutCanvas.value.width, layoutCanvas.value.height)
  ctx.lineWidth = 3
  ctx.lineCap = 'round'
  ctx.lineJoin = 'round'

  // Draw walls
  for (const wall of props.layout.walls || []) {
    ctx.strokeStyle = '#333'
    ctx.beginPath()
    ctx.moveTo(wall.x1, wall.y1)
    ctx.lineTo(wall.x2, wall.y2)
    ctx.stroke()
  }

  // Draw tables (using only text color changes with state)
  for (const [i, table] of (props.layout.tables || []).entries()) {
    const size = 60
    ctx.drawImage(tableImage, table.x - size / 2, table.y - size / 2, size, size)

    // Determine text color based on state
    let textColor = 'black'
    if (selectedTableIndex.value === i) {
      textColor = '#065F46'  // dark green when selected
    } else if (hoveredTableIndex.value === i) {
      textColor = '#00A86B'  // light green when hovered
    }

    ctx.fillStyle = textColor
    ctx.font = '14px sans-serif'
    ctx.textAlign = 'center'
    ctx.fillText(`Table ${i + 1}`, table.x, table.y - size / 2 - 10)
    ctx.fillText(`${table.seats} seats`, table.x, table.y + size / 2 + 15)
  }
}

const handleMouseMove = (event) => {
  const { x, y } = getMousePos(event)
  hoveredTableIndex.value = null

  for (const [i, table] of props.layout.tables.entries()) {
    if (Math.hypot(table.x - x, table.y - y) < 30) {
      hoveredTableIndex.value = i
      break
    }
  }
  drawLayout()
}

const handleClick = (event) => {
  const { x, y } = getMousePos(event)
  for (const [i, table] of props.layout.tables.entries()) {
    if (Math.hypot(table.x - x, table.y - y) < 30) {
      // Toggle selection: if clicked again, deselect
      selectedTableIndex.value = selectedTableIndex.value === i ? null : i
      drawLayout()
      return
    }
  }
}

const nextImage = () => {
  if (currentIndex.value < props.restaurant.restaurant_images.length - 1) {
    currentIndex.value++
  } else {
    currentIndex.value = 0
  }
}

const prevImage = () => {
  if (currentIndex.value > 0) {
    currentIndex.value--
  } else {
    currentIndex.value = props.restaurant.restaurant_images.length - 1
  }
}

onMounted(() => {
  if (!layoutCanvas.value || !props.layout) return

  layoutCanvas.value.addEventListener('mousemove', handleMouseMove)
  layoutCanvas.value.addEventListener('click', handleClick)

  tableImage.onload = drawLayout
  if (tableImage.complete) drawLayout()
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
              <img :src="`/${image.image}`" class="w-full h-full object-contain" alt="Restaurant Image" />
            </div>
          </div>
          <div class="flex justify-center pt-4 space-x-3">
  <button
    @click="prevImage"
    class="p-2 rounded-full border border-gray-300 hover:bg-blue-600 transition"
    aria-label="Previous"
  >
    <svg class="w-5 h-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
  </button>
  <button
    @click="nextImage"
    class="p-2 rounded-full border border-gray-300 hover:bg-blue-600 transition"
    aria-label="Next"
  >
    <svg class="w-5 h-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg>
  </button>
</div>

        </div>

        <!-- Product Info -->
        <div class="mx-auto max-w-7xl px-4 pt-10 pb-16 sm:px-6 lg:px-8 lg:pt-16 lg:pb-24">
          <div class="grid lg:grid-cols-3 gap-x-8">
            <!-- Left Panel -->
            <div class="lg:col-span-2 border-r pr-8">
              <h1 class="text-3xl font-bold text-gray-900">{{ restaurant.title }}</h1>
              <p class="mt-4 text-gray-700">{{ restaurant.description }}</p>
            </div>
            <!-- Right Panel -->
            <div class="mt-6 lg:mt-0">
              <p class="text-3xl text-gray-900">{{ restaurant.price }} €</p>
              <button class="mt-6 w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded">
                Reserve
              </button>
            </div>
          </div>

<!-- Layout Preview -->
<div
  v-if="layout && (layout.tables?.length || layout.walls?.length)"
  class="mt-10 flex flex-col items-center"
>
  <h3 class="text-lg font-semibold mb-4 text-center">Layout Preview</h3>
  <div class="overflow-auto border rounded shadow bg-white">
    <canvas
      ref="layoutCanvas"
      width="900"
      height="500"
      class="block"
    ></canvas>
  </div>
</div>

        </div>
      </div>
    </div>
  </NoHeroLayout>
</template>
