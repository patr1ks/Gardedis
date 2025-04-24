<script setup>
import { ref } from 'vue'

const stageWidth = 800
const stageHeight = 600

const tables = ref([])
const walls = ref([])

const mode = ref('table')
const tableIdCounter = ref(1)

const addTable = (e) => {
  const stage = e.target.getStage()
  const pointer = stage.getPointerPosition()

  tables.value.push({
    id: 'table-' + tableIdCounter.value++,
    x: pointer.x,
    y: pointer.y,
    radius: 30,
    fill: 'green',
    seats: 4,
    reserved: false,
  })
}

const drawingWall = ref(false)

const drawWall = (e) => {
  const stage = e.target.getStage()
  const pointer = stage.getPointerPosition()

  if (!drawingWall.value) {
    // First click – start a new wall segment
    walls.value.push({
      points: [pointer.x, pointer.y, pointer.x, pointer.y]
    })
    drawingWall.value = true
  } else {
    // Second click – finish the wall
    const lastWall = walls.value[walls.value.length - 1]
    lastWall.points[2] = pointer.x
    lastWall.points[3] = pointer.y
    drawingWall.value = false
  }
}

const handleClick = (e) => {
  if (mode.value === 'table') addTable(e)
  else if (mode.value === 'wall') drawWall(e)
}
</script>

<template>
  <div class="mb-4">
    <button @click="mode = 'table'" class="mr-2">🪑 Add Table</button>
    <button @click="mode = 'wall'">🧱 Draw Wall</button>
  </div>

  <v-stage :width="stageWidth" :height="stageHeight" @click="handleClick">
    <v-layer>
      <v-line
        v-for="(wall, index) in walls"
        :key="index"
        :points="wall.points"
        stroke="black"
        :strokeWidth="4"
      />
      <v-circle
        v-for="table in tables"
        :key="table.id"
        :x="table.x"
        :y="table.y"
        :radius="table.radius"
        :fill="table.reserved ? 'red' : 'green'"
        @mouseover="e => e.target.getStage().container().style.cursor = 'pointer'"
        @mouseout="e => e.target.getStage().container().style.cursor = 'default'"
      />
      <v-text
        v-for="table in tables"
        :key="'label-' + table.id"
        :x="table.x - 15"
        :y="table.y - 10"
        :text="`${table.seats} seats`"
        :fontSize="12"
        fill="black"
      />
    </v-layer>
  </v-stage>
</template>