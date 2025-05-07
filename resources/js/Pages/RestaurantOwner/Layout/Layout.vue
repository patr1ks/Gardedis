<template>
  <div class="relative w-full h-full flex flex-col items-start p-4 space-y-3">
    <!-- Toolbar -->
    <div class="flex flex-wrap items-center gap-2">
      <button :class="mode === 'table' ? activeBtn : defaultBtn" @click="mode = 'table'">🪑 Add Table</button>
      <button :class="mode === 'wall' ? activeBtn : defaultBtn" @click="mode = 'wall'">🧱 Draw Wall</button>
      <button :class="mode === 'delete' ? activeBtn : defaultBtn" @click="mode = 'delete'">🗑️ Delete</button>
      <button :class="defaultBtn" @click="clearLayout">🧹 Clear Layout</button>
    </div>

    <!-- Presets -->
    <div class="flex items-center gap-3">
      <span class="font-semibold">Presets:</span>
      <button :class="defaultBtn" @click="loadPreset('square')">⬛ Square</button>
      <button :class="defaultBtn" @click="loadPreset('lshape')">📐 L-Shape</button>
    </div>

    <!-- Seat Editor Panel above canvas -->
    <div
      v-if="editingTable"
      class="mb-4 w-full flex justify-end pr-10"
    >
      <div class="bg-white border border-gray-400 rounded shadow-md p-4 z-10">
        <h2 class="font-bold mb-2">Edit Table #{{ editingIndex + 1 }}</h2>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Seats:
          <input
            type="number"
            v-model.number="seatInput"
            min="1"
            max="20"
            class="ml-2 w-16 border rounded px-2 py-1"
          />
        </label>
        <div class="flex gap-2 mt-2">
          <button
            class="px-3 py-1 text-sm border rounded bg-green-600 text-white hover:bg-green-700"
            @click="saveSeatCount"
          >
            Save
          </button>
          <button
            class="px-3 py-1 text-sm border rounded bg-gray-100 hover:bg-gray-200"
            @click="editingTable = null"
          >
            Close
          </button>
        </div>
      </div>
    </div>

    <!-- Canvas -->
    <canvas
      ref="canvas"
      :width="canvasWidth"
      :height="canvasHeight"
      @mousedown="handleMouseDown"
      @mousemove="handleMouseMove"
      @mouseup="handleMouseUp"
      class="border border-gray-300 rounded shadow"
    />
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'

const canvas = ref(null)
const ctx = ref(null)
const canvasWidth = 900
const canvasHeight = 600

const mode = ref('table')
const tables = ref([])
const walls = ref([])
const isDrawingWall = ref(false)
const tempWallStart = ref(null)
const tempWallEnd = ref(null)

const hoveredItem = ref(null)
const draggingTable = ref(null)
const offset = ref({ x: 0, y: 0 })
const editingTable = ref(null)
const editingIndex = ref(null)
const seatInput = ref(4)
let dragStartTime = 0

const tableImage = new Image()
tableImage.src = new URL('../../../images/table.png', import.meta.url).href

const defaultBtn = 'px-4 py-1 border rounded text-sm bg-gray-100 hover:bg-gray-200'
const activeBtn = 'px-4 py-1 border rounded text-sm bg-green-600 text-white border-green-600'

const drawScene = () => {
  if (!ctx.value) return
  ctx.value.clearRect(0, 0, canvasWidth, canvasHeight)
  ctx.value.lineCap = 'round'
  ctx.value.lineJoin = 'round'

  walls.value.forEach((w, index) => {
    const highlight = mode.value === 'delete' && hoveredItem.value?.type === 'wall' && hoveredItem.value.index === index
    ctx.value.strokeStyle = highlight ? 'red' : '#333'
    ctx.value.lineWidth = 4
    ctx.value.beginPath()
    ctx.value.moveTo(w.x1, w.y1)
    ctx.value.lineTo(w.x2, w.y2)
    ctx.value.stroke()
  })

  if (isDrawingWall.value && tempWallStart.value && tempWallEnd.value) {
    ctx.value.strokeStyle = '#888'
    ctx.value.setLineDash([6, 4])
    ctx.value.beginPath()
    ctx.value.moveTo(tempWallStart.value.x, tempWallStart.value.y)
    ctx.value.lineTo(tempWallEnd.value.x, tempWallEnd.value.y)
    ctx.value.stroke()
    ctx.value.setLineDash([])
  }

  tables.value.forEach((t, index) => {
    const size = 60
    const isHovered = mode.value === 'delete' && hoveredItem.value?.type === 'table' && hoveredItem.value.index === index

    ctx.value.drawImage(tableImage, t.x - size / 2, t.y - size / 2, size, size)

    if (isHovered) {
      ctx.value.strokeStyle = 'red'
      ctx.value.lineWidth = 2
      ctx.value.strokeRect(t.x - size / 2, t.y - size / 2, size, size)
    }

    ctx.value.fillStyle = 'black'
    ctx.value.font = '14px sans-serif'
    ctx.value.textAlign = 'center'
    ctx.value.fillText(`Table ${index + 1}`, t.x, t.y - size / 2 - 10)
    ctx.value.fillText(`${t.seats} seats`, t.x, t.y + size / 2 + 15)
  })
}

const getMousePos = (e) => {
  const rect = canvas.value.getBoundingClientRect()
  return { x: e.clientX - rect.left, y: e.clientY - rect.top }
}

const isPointOnWall = (x, y, wall) => {
  const { x1, y1, x2, y2 } = wall
  const dist =
    Math.abs((y2 - y1) * x - (x2 - x1) * y + x2 * y1 - y2 * x1) / Math.hypot(y2 - y1, x2 - x1)
  return dist < 6 &&
    Math.min(x1, x2) - 6 <= x &&
    x <= Math.max(x1, x2) + 6 &&
    Math.min(y1, y2) - 6 <= y &&
    y <= Math.max(y1, y2) + 6
}

const handleMouseDown = (e) => {
  const { x, y } = getMousePos(e)
  dragStartTime = performance.now()

  if (mode.value === 'delete') {
    for (let i = 0; i < tables.value.length; i++) {
      if (Math.hypot(x - tables.value[i].x, y - tables.value[i].y) < 30) {
        tables.value.splice(i, 1)
        drawScene()
        return
      }
    }
    for (let i = 0; i < walls.value.length; i++) {
      if (isPointOnWall(x, y, walls.value[i])) {
        walls.value.splice(i, 1)
        drawScene()
        return
      }
    }
  }

  if (mode.value === 'table') {
    for (let i = 0; i < tables.value.length; i++) {
      const t = tables.value[i]
      if (Math.hypot(x - t.x, y - t.y) < 30) {
        draggingTable.value = t
        offset.value = { x: x - t.x, y: y - t.y }
        return
      }
    }
    tables.value.push({ x, y, seats: 4 })
    drawScene()
  }

  if (mode.value === 'wall') {
    if (!isDrawingWall.value) {
      tempWallStart.value = { x, y }
      isDrawingWall.value = true
    } else {
      walls.value.push({
        x1: tempWallStart.value.x,
        y1: tempWallStart.value.y,
        x2: tempWallEnd.value.x,
        y2: tempWallEnd.value.y
      })
      tempWallStart.value = null
      tempWallEnd.value = null
      isDrawingWall.value = false
    }
    drawScene()
  }
}

const handleMouseUp = () => {
  if (draggingTable.value) {
    const duration = performance.now() - dragStartTime
    const clicked = duration < 200
    const index = tables.value.findIndex(t => t === draggingTable.value)
    if (clicked) {
      editingTable.value = draggingTable.value
      editingIndex.value = index
      seatInput.value = editingTable.value.seats
    }
    draggingTable.value = null
  }
}

const handleMouseMove = (e) => {
  const { x, y } = getMousePos(e)

  if (draggingTable.value) {
    draggingTable.value.x = x - offset.value.x
    draggingTable.value.y = y - offset.value.y
    drawScene()
    return
  }

  hoveredItem.value = null

  if (mode.value === 'delete') {
    for (let i = 0; i < tables.value.length; i++) {
      const t = tables.value[i]
      if (Math.hypot(x - t.x, y - t.y) < 30) {
        hoveredItem.value = { type: 'table', index: i }
        drawScene()
        return
      }
    }
    for (let i = 0; i < walls.value.length; i++) {
      if (isPointOnWall(x, y, walls.value[i])) {
        hoveredItem.value = { type: 'wall', index: i }
        drawScene()
        return
      }
    }
  }

  if (isDrawingWall.value && tempWallStart.value) {
    const dx = x - tempWallStart.value.x
    const dy = y - tempWallStart.value.y
    const angle = Math.atan2(dy, dx)
    const snapAngles = [0, Math.PI / 2, Math.PI, -Math.PI / 2]

    let closest = snapAngles[0]
    let minDiff = Math.abs(angle - closest)
    for (const a of snapAngles) {
      const diff = Math.abs(angle - a)
      if (diff < minDiff) {
        minDiff = diff
        closest = a
      }
    }

    const length = Math.hypot(dx, dy)
    tempWallEnd.value = {
      x: tempWallStart.value.x + Math.cos(closest) * length,
      y: tempWallStart.value.y + Math.sin(closest) * length
    }
    drawScene()
  }
}

const clearLayout = () => {
  tables.value = []
  walls.value = []
  isDrawingWall.value = false
  tempWallStart.value = null
  tempWallEnd.value = null
  editingTable.value = null
  drawScene()
}

const saveSeatCount = () => {
  if (editingTable.value) {
    editingTable.value.seats = seatInput.value
    editingTable.value = null
    editingIndex.value = null
    drawScene()
  }
}

const loadPreset = (type) => {
  walls.value = []
  if (type === 'square') {
    const size = 400, offset = 100
    walls.value = [
      { x1: offset, y1: offset, x2: offset + size, y2: offset },
      { x1: offset + size, y1: offset, x2: offset + size, y2: offset + size },
      { x1: offset + size, y1: offset + size, x2: offset, y2: offset + size },
      { x1: offset, y1: offset + size, x2: offset, y2: offset }
    ]
  }
  if (type === 'lshape') {
    walls.value = [
      { x1: 100, y1: 100, x2: 600, y2: 100 },
      { x1: 600, y1: 100, x2: 600, y2: 300 },
      { x1: 600, y1: 300, x2: 300, y2: 300 },
      { x1: 300, y1: 300, x2: 300, y2: 500 },
      { x1: 300, y1: 500, x2: 100, y2: 500 },
      { x1: 100, y1: 500, x2: 100, y2: 100 }
    ]
  }
  drawScene()
}

onMounted(() => {
  ctx.value = canvas.value.getContext('2d')
  tableImage.onload = () => drawScene()
})
watch([tables, walls], drawScene)
</script>
