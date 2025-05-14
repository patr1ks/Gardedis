<template>
  <div class="relative w-full h-full flex flex-col items-start p-4 space-y-3">
    <!-- Toolbar -->
    <div class="flex flex-wrap items-center gap-2">
      <button :class="mode === 'table' ? activeBtn : defaultBtn" @click="mode = 'table'">Add Table</button>
      <button :class="mode === 'wall' ? activeBtn : defaultBtn" @click="mode = 'wall'">Draw Wall</button>
      <button :class="mode === 'delete' ? activeBtn : defaultBtn" @click="mode = 'delete'">Delete</button>
      <button :class="defaultBtn" @click="clearLayout">Clear Layout</button>
      <button :class="defaultBtn" @click="saveLayout">Save Layout</button>
    </div>

    <!-- Presets -->
    <div class="flex items-center gap-3">
      <span class="font-semibold">Presets:</span>
      <button :class="defaultBtn" @click="loadPreset('square')">Square</button>
      <button :class="defaultBtn" @click="loadPreset('lshape')">L-Shape</button>
    </div>

    <!-- Seat Editor Panel -->
    <div v-if="editingTable" class="absolute top-4 right-6 z-20">
      <div class="bg-white border border-gray-400 rounded shadow-md p-4">
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
          <button class="px-3 py-1 text-sm border rounded bg-blue-700 text-white hover:bg-blue-800" @click="saveSeatCount">Save</button>
          <button class="px-3 py-1 text-sm border rounded bg-gray-100 hover:bg-gray-200" @click="editingTable = null">Close</button>
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
import { router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

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
tableImage.src =
  'data:image/svg+xml;charset=utf-8,' +
  encodeURIComponent(`<svg version="1.1" id="real_x5F_estate_1_" xmlns="http://www.w3.org/2000/svg" x="0" y="0" viewBox="0 0 128 128" xml:space="preserve"><style>.st0{display:none}.st1{display:inline}.st2{fill:#0f0f0f}</style><g id="table_x5F_chair_1_"><path class="st2" d="M101.2 47.5H28.9v10.3h8.3v35.1h4.1V57.8h45.4v35.1h4.1V57.8h10.3V47.5zm-95-12.4H0v57.8h6.2V80.5h19.4l1.3 12.4H31V66.1H6.2v-31zm18.5 37.2.6 6.2H6.2v-6.2h18.5zm97.1-37.2v31H97v26.8h4.1l1.3-12.4h19.4v12.4h6.2V35.1h-6.2zm0 43.4h-19.2l.6-6.2h18.5v6.2z" id="icon_14_"/></g></svg>`);

const defaultBtn = 'px-4 py-1 border rounded text-sm bg-blue-600 text-white hover:bg-blue-700'
const activeBtn = 'px-4 py-1 border rounded text-sm bg-blue-700 text-white border-blue-800'

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
    const snapAngles = [
      0,
      Math.PI / 4,
      Math.PI / 2,
      (3 * Math.PI) / 4,
      Math.PI,
      -(Math.PI / 4),
      -(Math.PI / 2),
      -(3 * Math.PI) / 4
    ]

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

const saveLayout = async () => {
  try {
    await router.post('/restaurant-owner/layout/store', {
      layout: {
        tables: tables.value,
        walls: walls.value,
      }
    }, {
      onSuccess: (page) => {
        Swal.fire({
          toast: true,
          icon: 'success',
          position: 'top-end',
          showConfirmButton: false,
          timer: 2000,
          title: page.props.flash?.success || 'Layout saved successfully!',
        });
      },
      onError: () => {
        Swal.fire({
          toast: true,
          icon: 'error',
          position: 'top-end',
          showConfirmButton: false,
          timer: 2000,
          title: 'Failed to save layout!',
        });
      },
    });
  } catch {
    Swal.fire({
      toast: true,
      icon: 'error',
      position: 'top-end',
      showConfirmButton: false,
      timer: 2000,
      title: 'Unexpected error during save!',
    });
  }
};

const loadLayout = async () => {
  const res = await fetch('/restaurant-owner/layout-json', {
    headers: { Accept: 'application/json' },
    credentials: 'include'
  });
  const json = await res.json();
  if (json) {
    tables.value = json.tables || []
    walls.value = json.walls || []
    drawScene()
  }
}

const loadPreset = (type) => {
  walls.value = []
  if (type === 'square') {
    const size = 500
    const offsetX = (canvasWidth - size) / 2
    const offsetY = (canvasHeight - size) / 2
    walls.value = [
      { x1: offsetX, y1: offsetY, x2: offsetX + size, y2: offsetY },
      { x1: offsetX + size, y1: offsetY, x2: offsetX + size, y2: offsetY + size },
      { x1: offsetX + size, y1: offsetY + size, x2: offsetX, y2: offsetY + size },
      { x1: offsetX, y1: offsetY + size, x2: offsetX, y2: offsetY }
    ]
  }
  if (type === 'lshape') {
    const offsetX = (canvasWidth - 500) / 2
    const offsetY = (canvasHeight - 400) / 2
    walls.value = [
      { x1: offsetX, y1: offsetY, x2: offsetX + 500, y2: offsetY },
      { x1: offsetX + 500, y1: offsetY, x2: offsetX + 500, y2: offsetY + 200 },
      { x1: offsetX + 500, y1: offsetY + 200, x2: offsetX + 250, y2: offsetY + 200 },
      { x1: offsetX + 250, y1: offsetY + 200, x2: offsetX + 250, y2: offsetY + 400 },
      { x1: offsetX + 250, y1: offsetY + 400, x2: offsetX, y2: offsetY + 400 },
      { x1: offsetX, y1: offsetY + 400, x2: offsetX, y2: offsetY }
    ]
  }
  drawScene()
}

onMounted(() => {
  ctx.value = canvas.value.getContext('2d')
  tableImage.onload = () => {
    loadLayout()
  }
})
watch([tables, walls], drawScene)
</script>
