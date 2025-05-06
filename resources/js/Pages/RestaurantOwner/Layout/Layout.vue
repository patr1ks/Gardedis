<template>
  <div class="layout-planner">
    <div class="toolbar">
      <button :class="{ active: mode === 'table' }" @click="mode = 'table'">🪑 Add Table</button>
      <button :class="{ active: mode === 'wall' }" @click="mode = 'wall'">🧱 Draw Wall</button>
      <button :class="{ active: mode === 'delete' }" @click="mode = 'delete'">🗑️ Delete</button>
      <button @click="clearLayout">🧹 Clear Layout</button>
    </div>

    <div class="presets">
      <span>Presets:</span>
      <button @click="loadPreset('square')">⬛ Square</button>
      <button @click="loadPreset('lshape')">📐 L-Shape</button>
    </div>

    <canvas
      ref="canvas"
      :width="canvasWidth"
      :height="canvasHeight"
      @click="handleClick"
      @mousemove="handleMouseMove"
      class="canvas"
    />
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'

const canvas = ref(null)
const canvasWidth = 900
const canvasHeight = 600
const ctx = ref(null)

const mode = ref('table')
const tables = ref([])
const walls = ref([])
const isDrawingWall = ref(false)
const tempWallStart = ref(null)
const tempWallEnd = ref(null)
const hoveredItem = ref(null)

const tableImage = new Image()
tableImage.src = new URL('../../../images/table.png', import.meta.url).href

const drawScene = () => {
  if (!ctx.value) return

  ctx.value.clearRect(0, 0, canvasWidth, canvasHeight)
  ctx.value.lineCap = 'round'
  ctx.value.lineJoin = 'round'

  // Draw walls
  walls.value.forEach((w, index) => {
    ctx.value.strokeStyle =
      hoveredItem.value?.type === 'wall' && hoveredItem.value?.index === index ? 'red' : '#333'
    ctx.value.lineWidth = 4
    ctx.value.beginPath()
    ctx.value.moveTo(w.x1, w.y1)
    ctx.value.lineTo(w.x2, w.y2)
    ctx.value.stroke()
  })

  // Live wall preview
  if (isDrawingWall.value && tempWallStart.value && tempWallEnd.value) {
    ctx.value.strokeStyle = '#888'
    ctx.value.setLineDash([6, 4])
    ctx.value.beginPath()
    ctx.value.moveTo(tempWallStart.value.x, tempWallStart.value.y)
    ctx.value.lineTo(tempWallEnd.value.x, tempWallEnd.value.y)
    ctx.value.stroke()
    ctx.value.setLineDash([])
  }

  // Draw tables
  tables.value.forEach((t, index) => {
    const size = 60
    const isHovered = hoveredItem.value?.type === 'table' && hoveredItem.value?.index === index

    ctx.value.drawImage(tableImage, t.x - size / 2, t.y - size / 2, size, size)

    if (isHovered) {
      ctx.value.strokeStyle = 'red'
      ctx.value.lineWidth = 2
      ctx.value.strokeRect(t.x - size / 2, t.y - size / 2, size, size)
    }
  })
}

const getMousePos = (event) => {
  const rect = canvas.value.getBoundingClientRect()
  return {
    x: event.clientX - rect.left,
    y: event.clientY - rect.top
  }
}

const isPointOnWall = (x, y, wall) => {
  const { x1, y1, x2, y2 } = wall
  const distToLine =
    Math.abs((y2 - y1) * x - (x2 - x1) * y + x2 * y1 - y2 * x1) / Math.hypot(y2 - y1, x2 - x1)
  return (
    distToLine < 6 &&
    Math.min(x1, x2) - 6 <= x &&
    x <= Math.max(x1, x2) + 6 &&
    Math.min(y1, y2) - 6 <= y &&
    y <= Math.max(y1, y2) + 6
  )
}

const handleClick = (event) => {
  const { x, y } = getMousePos(event)

  if (mode.value === 'table') {
    tables.value.push({ x, y, seats: 4 })
  } else if (mode.value === 'wall') {
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
  } else if (mode.value === 'delete') {
    if (hoveredItem.value?.type === 'table') {
      tables.value.splice(hoveredItem.value.index, 1)
    } else if (hoveredItem.value?.type === 'wall') {
      walls.value.splice(hoveredItem.value.index, 1)
    }
  }

  drawScene()
}

const handleMouseMove = (event) => {
  const { x, y } = getMousePos(event)
  hoveredItem.value = null

  if (mode.value === 'delete') {
    for (let i = 0; i < tables.value.length; i++) {
      const t = tables.value[i]
      const dx = x - t.x
      const dy = y - t.y
      if (Math.sqrt(dx * dx + dy * dy) < 30) {
        hoveredItem.value = { type: 'table', index: i }
        break
      }
    }

    if (!hoveredItem.value) {
      for (let i = 0; i < walls.value.length; i++) {
        if (isPointOnWall(x, y, walls.value[i])) {
          hoveredItem.value = { type: 'wall', index: i }
          break
        }
      }
    }
  }

  if (isDrawingWall.value && tempWallStart.value) {
    const dx = x - tempWallStart.value.x
    const dy = y - tempWallStart.value.y
    const angle = Math.atan2(dy, dx)
    const snapAngles = [0, Math.PI / 4, Math.PI / 2, (3 * Math.PI) / 4, Math.PI,
                        -(Math.PI / 4), -(Math.PI / 2), -(3 * Math.PI) / 4]

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
    const snappedX = tempWallStart.value.x + Math.cos(closest) * length
    const snappedY = tempWallStart.value.y + Math.sin(closest) * length
    tempWallEnd.value = { x: snappedX, y: snappedY }
  }

  drawScene()
}

const loadPreset = (type) => {
  walls.value = []

  if (type === 'square') {
    const size = 400
    const offset = 100
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
      { x1: 600, y1: 100, x2: 600, y2: 400 },
      { x1: 600, y1: 400, x2: 300, y2: 400 },
      { x1: 300, y1: 400, x2: 300, y2: 250 },
      { x1: 300, y1: 250, x2: 100, y2: 250 },
      { x1: 100, y1: 250, x2: 100, y2: 100 }
    ]
  }

  drawScene()
}

const clearLayout = () => {
  tables.value = []
  walls.value = []
  isDrawingWall.value = false
  tempWallStart.value = null
  tempWallEnd.value = null
  hoveredItem.value = null
  drawScene()
}

onMounted(() => {
  ctx.value = canvas.value.getContext('2d')
  tableImage.onload = () => drawScene()
})

watch([tables, walls], drawScene)
</script>

<style scoped>
.layout-planner {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.toolbar {
  margin-bottom: 12px;
  flex-wrap: wrap;
  display: flex;
}

button {
  padding: 8px 14px;
  margin-right: 10px;
  margin-bottom: 6px;
  background-color: #f4f4f4;
  border: 1px solid #aaa;
  border-radius: 6px;
  font-size: 14px;
  cursor: pointer;
  transition: 0.2s;
}

button:hover {
  background-color: #e0e0e0;
}

button.active {
  background-color: #4caf50;
  color: white;
  border-color: #4caf50;
}

.presets {
  margin-bottom: 12px;
  display: flex;
  align-items: center;
  gap: 8px;
}
.presets span {
  font-weight: bold;
}
.presets button {
  padding: 6px 10px;
  font-size: 13px;
  background-color: #eee;
  border: 1px solid #aaa;
  border-radius: 4px;
  cursor: pointer;
}
.presets button:hover {
  background-color: #ddd;
}

.canvas {
  border: 1px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}
</style>
